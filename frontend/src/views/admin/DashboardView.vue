<script setup>
import { useAuth } from '@/composables/useAuth'

const { user } = useAuth()
</script>

<template>
  <div>
    <h1>Panel administrativo</h1>
    <p>Bienvenida, <strong>{{ user?.name }}</strong>.</p>
    <p class="text-muted">
      Desde aquí puedes gestionar los artículos del sitio.
    </p>
  </div>
</template>
