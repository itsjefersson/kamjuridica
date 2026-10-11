<script setup>
import { computed } from 'vue'

const props = defineProps({
  service: {
    type: Object,
    required: true,
  },
})

// Íconos simples (trazos Feather) para cada servicio.
const iconPaths = {
  briefcase:
    '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
  family:
    '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
  contract:
    '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line>',
  shield:
    '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
  chart:
    '<line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line>',
  health:
    '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>',
}

const iconMarkup = computed(() => iconPaths[props.service.icon] || iconPaths.shield)
</script>

<template>
  <div class="service-card">
    <div class="icon">
      <svg
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        v-html="iconMarkup"
      ></svg>
    </div>
    <h3 class="h5">{{ service.title }}</h3>
    <p class="mb-0 text-muted">{{ service.description }}</p>
  </div>
</template>
