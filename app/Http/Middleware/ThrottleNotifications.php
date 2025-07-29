<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleNotifications
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $key = 'notifications:' . $request->user()->id;

        // Batasi 30 request per menit
        if ($this->limiter->tooManyAttempts($key, 30)) {
            return response()->json([
                'message' => 'Terlalu banyak permintaan. Silakan coba lagi nanti.'
            ], 429);
        }

        $this->limiter->hit($key, 60);

        return $next($request);
    }
} 