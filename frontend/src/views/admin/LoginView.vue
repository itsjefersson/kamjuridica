<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { login } = useAuth()

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

async function onSubmit() {
  error.value = ''
  loading.value = true
  try {
    await login(email.value, password.value)
    router.push({ name: 'dashboard' })
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al iniciar sesión.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <main class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-6 col-lg-4">
        <h1 class="mb-4">Iniciar sesión</h1>

        <div v-if="error" class="alert alert-danger">{{ error }}</div>

        <form @submit.prevent="onSubmit">
          <div class="mb-3">
            <label for="email" class="form-label">Correo</label>
            <input
              id="email"
              v-model="email"
              type="email"
              class="form-control"
              required
              autocomplete="email"
            />
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input
              id="password"
              v-model="password"
              type="password"
              class="form-control"
              required
              autocomplete="current-password"
            />
          </div>

          <button type="submit" class="btn btn-primary w-100" :disabled="loading">
            {{ loading ? 'Entrando...' : 'Entrar' }}
          </button>
        </form>
      </div>
    </div>
  </main>
</template>
