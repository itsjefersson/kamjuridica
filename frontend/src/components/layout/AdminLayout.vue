<script setup>
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { logout } = useAuth()

async function onLogout() {
  await logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
      <div class="container">
        <RouterLink class="navbar-brand" :to="{ name: 'dashboard' }">KAMJuridica</RouterLink>

        <div class="navbar-nav me-auto">
          <RouterLink class="nav-link" :to="{ name: 'dashboard' }">Panel</RouterLink>
          <RouterLink class="nav-link" :to="{ name: 'admin-articles' }">Artículos</RouterLink>
          <RouterLink class="nav-link" :to="{ name: 'admin-social-posts' }">Redes sociales</RouterLink>
          <RouterLink class="nav-link" :to="{ name: 'admin-settings' }">Configuración</RouterLink>
        </div>

        <button type="button" class="btn btn-outline-light btn-sm" @click="onLogout">
          Cerrar sesión
        </button>
      </div>
    </nav>

    <main class="container">
      <RouterView />
    </main>
  </div>
</template>
