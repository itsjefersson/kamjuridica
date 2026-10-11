<script setup>
import PublicNavbar from '@/components/public/PublicNavbar.vue'
import PublicFooter from '@/components/public/PublicFooter.vue'
</script>

<template>
  <div class="d-flex flex-column min-vh-100">
    <PublicNavbar />
    <main class="flex-grow-1">
      <RouterView />
    </main>
    <PublicFooter />
  </div>
</template>
