<template>
  <div class="login-wrapper">
    <div class="login-card">

      <div class="text-center mb-4">
        <div class="login-logo">❤️</div>
        <h2 class="fw-bold mt-3">VolunTrack</h2>
        <p class="text-muted">Accede para gestionar actividades y voluntarios</p>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input
              type="email"
              class="form-control"
              :class="{ 'is-invalid': !!error }"
              v-model="email"
              placeholder="coordinador@email.com"
              @keyup.enter="handleLogin"
          />
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label">Contraseña</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input
              :type="showPassword ? 'text' : 'password'"
              class="form-control"
              :class="{ 'is-invalid': !!error }"
              v-model="password"
              placeholder="••••••••"
              @keyup.enter="handleLogin"
          />
          <button class="input-group-text" type="button" @click="showPassword = !showPassword">
            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
          </button>
          <div class="invalid-feedback">{{ error }}</div>
        </div>
      </div>

      <button class="btn btn-primary w-100 btn-lg" @click="handleLogin" :disabled="loading">
        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
        <i v-else class="fas fa-right-to-bracket me-2"></i>
        {{ loading ? 'Accediendo...' : 'Entrar' }}
      </button>

      <div class="text-center mt-4">
        <RouterLink to="/" class="text-muted small">
          <i class="fas fa-arrow-left me-1"></i>Volver al inicio
        </RouterLink>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { login } from '../services/auth'

const router = useRouter()

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)
const showPassword = ref(false)

function handleLogin() {
  if (!email.value || !password.value) {
    error.value = 'Rellena todos los campos'
    return
  }

  loading.value = true
  error.value = ''

  login(email.value, password.value)
      .then(data => {
        localStorage.setItem('token', data.token)
        const payload = JSON.parse(atob(data.token.split('.')[1]))
        localStorage.setItem('coordinatorName', payload.name ?? payload.username)
        router.push('/intranet/actividades')
      })
      .catch(err => {
        error.value = err.message
      })
      .finally(() => {
        loading.value = false
      })
}
</script>

<style scoped>
.login-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, #fff8f5 0%, #f0f4ff 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}
.login-card {
  background: white;
  border-radius: 24px;
  padding: 3rem;
  width: 100%;
  max-width: 440px;
  box-shadow: 0 16px 48px rgba(232, 93, 38, 0.10);
}
.login-logo {
  font-size: 3rem;
}
</style>