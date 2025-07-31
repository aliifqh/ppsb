<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-emerald-100 animate-bgFade">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 flex flex-col items-center animate-fadeIn">
      <img src="/img/LOGO-PPIN-NGRUKI.png" alt="Logo" class="w-40 md:w-56 mb-6 drop-shadow-lg transition-transform duration-300 hover:scale-105" />

      <!-- Alert Error -->
      <div v-if="error" class="w-full mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg animate-fadeIn">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
          </svg>
          {{ error }}
        </div>
      </div>

      <!-- Alert Success -->
      <div v-if="success" class="w-full mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg animate-fadeIn">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          {{ success }}
        </div>
      </div>

      <div class="w-full mb-4">
        <button
          @click="loginWithGoogle"
          :disabled="loading"
          class="w-full flex items-center justify-center border border-gray-300 rounded-lg py-2 mb-4 hover:bg-gray-50 transition group animate-fadeIn delay-200 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <svg v-if="!loading" class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200" viewBox="0 0 24 24">
            <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          <div v-else class="w-5 h-5 mr-2 border-2 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
          <span class="font-medium text-gray-700">{{ loading ? 'Memproses...' : 'Masuk dengan Google' }}</span>
        </button>

        <div class="flex items-center my-4 animate-fadeIn delay-300">
          <div class="flex-grow h-px bg-gray-200"></div>
          <span class="mx-3 text-gray-400 text-sm">ATAU</span>
          <div class="flex-grow h-px bg-gray-200"></div>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-4 animate-fadeIn delay-400">
          <div>
            <label class="block text-gray-700 text-sm mb-1">Email</label>
            <input
              v-model="form.email"
              type="email"
              :class="[
                'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all duration-200 focus:scale-[1.03]',
                errors.email ? 'border-red-500 focus:ring-red-400' : 'border-gray-300'
              ]"
              placeholder="Masukkan alamat email Anda"
              required
            />
            <span v-if="errors.email" class="text-red-500 text-xs mt-1 block">{{ errors.email }}</span>
          </div>

          <div>
            <label class="block text-gray-700 text-sm mb-1">Kata Sandi</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                :class="[
                  'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all duration-200 focus:scale-[1.03]',
                  'pr-14',
                  errors.password ? 'border-red-500 focus:ring-red-400' : 'border-gray-300'
                ]"
                placeholder="Masukkan kata sandi Anda"
                required
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 transition cursor-pointer"
                tabindex="-1"
                aria-label="Tampilkan/sembunyikan password"
              >
                <svg v-if="showPassword" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
                <svg v-else class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
              </button>
            </div>
            <span v-if="errors.password" class="text-red-500 text-xs mt-1 block">{{ errors.password }}</span>
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center text-sm">
              <input
                v-model="form.remember"
                type="checkbox"
                class="form-checkbox rounded text-emerald-500 mr-2 transition-all duration-200 focus:ring-emerald-400"
              />
              Ingat saya
            </label>
            <a href="#" class="text-sm text-emerald-600 hover:underline font-medium transition-colors duration-200">Lupa sandi?</a>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
          >
            <div v-if="loading" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>
            {{ loading ? 'Memproses...' : 'Masuk' }}
          </button>
        </form>

        <div class="mt-6 text-center text-gray-500 text-sm animate-fadeIn delay-500">
          Belum punya akun? <a href="#" class="text-emerald-600 hover:underline font-medium transition-colors duration-200">Daftar</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

// Reactive data
const loading = ref(false)
const error = ref('')
const success = ref('')
const showPassword = ref(false)

const form = reactive({
  email: '',
  password: '',
  remember: false
})

const errors = reactive({
  email: '',
  password: ''
})

// Methods
const clearErrors = () => {
  error.value = ''
  success.value = ''
  errors.email = ''
  errors.password = ''
}

const validateForm = () => {
  clearErrors()
  let isValid = true

  if (!form.email) {
    errors.email = 'Email wajib diisi'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Format email tidak valid'
    isValid = false
  }

  if (!form.password) {
    errors.password = 'Kata sandi wajib diisi'
    isValid = false
  } else if (form.password.length < 6) {
    errors.password = 'Kata sandi minimal 6 karakter'
    isValid = false
  }

  return isValid
}

const loginWithGoogle = async () => {
  try {
    loading.value = true
    clearErrors()

    // Redirect ke Google OAuth
    window.location.href = '/auth/google'
  } catch (err) {
    console.error('Google login error:', err)
    error.value = 'Terjadi kesalahan saat login dengan Google'
  } finally {
    loading.value = false
  }
}

const handleLogin = async () => {
  if (!validateForm()) return

  try {
    loading.value = true
    clearErrors()

    const response = await axios.post('/login', {
      email: form.email,
      password: form.password,
      remember: form.remember
    })

    if (response.data.success) {
      success.value = 'Login berhasil! Mengalihkan...'

      // Redirect ke admin dashboard
      setTimeout(() => {
        window.location.href = '/admin/dashboard'
      }, 1000)
    }
  } catch (err) {
    console.error('Login error:', err)

    if (err.response?.data?.errors) {
      // Validation errors
      const validationErrors = err.response.data.errors
      if (validationErrors.email) {
        errors.email = validationErrors.email[0]
      }
      if (validationErrors.password) {
        errors.password = validationErrors.password[0]
      }
    } else if (err.response?.data?.message) {
      // Server error message
      error.value = err.response.data.message
    } else {
      // Generic error
      error.value = 'Terjadi kesalahan saat login. Silakan coba lagi.'
    }
  } finally {
    loading.value = false
  }
}

// Check for flash messages on mount
onMounted(() => {
  // Check URL parameters for error/success messages
  const urlParams = new URLSearchParams(window.location.search)
  const errorMsg = urlParams.get('error')
  const successMsg = urlParams.get('success')

  if (errorMsg) {
    error.value = decodeURIComponent(errorMsg)
  }

  if (successMsg) {
    success.value = decodeURIComponent(successMsg)
  }
})
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: none; }
}
@keyframes bgFade {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.animate-fadeIn {
  animation: fadeIn 0.7s cubic-bezier(0.4,0,0.2,1) both;
}
.animate-bgFade {
  background-size: 400% 400%;
  animation: bgFade 12s ease-in-out infinite;
}
</style>
