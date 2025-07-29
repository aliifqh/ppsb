<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Activity;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Domain yang diizinkan untuk login
     */
    protected $allowedDomain = 'almukmin.sch.id';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Redirect ke Google untuk autentikasi
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with([
                'hd' => $this->allowedDomain,
                'prompt' => 'select_account consent',
                'access_type' => 'offline',
                'include_granted_scopes' => 'true'
            ])
            ->redirect();
    }

    /**
     * Handle callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Validasi format email
            if (!filter_var($googleUser->email, FILTER_VALIDATE_EMAIL)) {
                \Log::warning('Invalid email format: ' . $googleUser->email);
                return redirect()->route('login')
                    ->with('error', 'Format email tidak valid.');
            }

            // Validasi domain email dengan lebih ketat
            $email = $googleUser->email;
            $emailParts = explode('@', $email);

            if (count($emailParts) !== 2) {
                \Log::warning('Invalid email format (no @ symbol): ' . $email);
                return redirect()->route('login')
                    ->with('error', 'Format email tidak valid.');
            }

            $domain = $emailParts[1];

            if ($domain !== $this->allowedDomain) {
                \Log::warning('Login attempt with unauthorized domain: ' . $domain . ' from IP: ' . request()->ip());
                return redirect()->route('login')
                    ->with('error', 'Maaf, hanya email dari domain ' . $this->allowedDomain . ' yang diizinkan untuk login.');
            }

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                try {
                    // Buat user baru
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'google_avatar' => $googleUser->avatar,
                        'password' => Hash::make(Str::random(24)),
                    ]);

                    \Log::info('New user created: ' . $user->email);

                    // Cek apakah roles sudah ada
                    if (!\Spatie\Permission\Models\Role::count()) {
                        \Log::info('Running RoleSeeder as no roles exist');
                        \Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
                    }

                    // Assign role default
                    $user->assignRole('ustadz');
                    \Log::info('Assigned default role to new user: ' . $user->email);

                    // Jika user pertama, jadikan super admin
                    if (User::count() === 1) {
                        $user->syncRoles(['super admin']);
                        \Log::info('First user assigned super admin role: ' . $user->email);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error creating new user: ' . $e->getMessage());
                    \Log::error('Stack trace: ' . $e->getTraceAsString());
                    return redirect()->route('login')
                        ->with('error', 'Terjadi kesalahan saat membuat akun baru. Silakan hubungi administrator.');
                }
            } else {
                // Update google_avatar jika berubah
                if ($user->google_avatar !== $googleUser->avatar) {
                    try {
                        $user->update([
                            'google_avatar' => $googleUser->avatar
                        ]);
                        \Log::info('Updated avatar for user: ' . $user->email);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to update avatar for user: ' . $user->email . ' - ' . $e->getMessage());
                        // Lanjutkan proses login meskipun update avatar gagal
                    }
                }
            }

            // Pastikan user punya role (jaga-jaga jika user lama)
            if ($user->roles()->count() === 0) {
                try {
                    $user->assignRole('ustadz');
                    \Log::info('Assigned default role to existing user: ' . $user->email);
                } catch (\Exception $e) {
                    \Log::error('Error assigning default role: ' . $e->getMessage());
                    \Log::error('Stack trace: ' . $e->getTraceAsString());
                    // Lanjutkan login meskipun gagal memberikan role
                }
            }

            Auth::login($user);
            \Log::info('User logged in successfully: ' . $user->email);

            // Log aktivitas login
            Activity::log(
                $user->id,
                'login',
                'telah masuk ke sistem',
                null,
                ['ip' => request()->ip()]
            );

            return redirect()->intended('/admin');
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode(request()->all()));
            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.');
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Log aktivitas logout
        if (Auth::check()) {
            Activity::log(
                Auth::id(),
                'logout',
                'telah keluar dari sistem',
                null,
                ['ip' => request()->ip()]
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Handle login manual dengan email dan password
     */
    public function login(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ], [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Kata sandi wajib diisi',
                'password.min' => 'Kata sandi minimal 6 karakter',
            ]);

            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember', false);

            // Cek apakah user ada dan validasi domain
            $user = User::where('email', $credentials['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau kata sandi salah'
                ], 401);
            }

            // Validasi domain email (hanya untuk user yang belum punya google_id)
            if (!$user->google_id) {
                $emailParts = explode('@', $user->email);
                if (count($emailParts) === 2 && $emailParts[1] !== $this->allowedDomain) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maaf, hanya email dari domain ' . $this->allowedDomain . ' yang diizinkan untuk login.'
                    ], 403);
                }
            }

            // Coba login
            if (Auth::attempt($credentials, $remember)) {
                // Log aktivitas login
                Activity::log(
                    Auth::id(),
                    'login',
                    'telah masuk ke sistem',
                    null,
                    ['ip' => request()->ip(), 'method' => 'manual']
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil!',
                    'redirect' => '/admin/dashboard'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi salah'
            ], 401);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Manual login error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.'
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = $avatar->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $avatarPath;
        }
        $user->update($data);
        return response()->json(['message' => 'Profil berhasil diupdate', 'user' => $user]);
    }
}
