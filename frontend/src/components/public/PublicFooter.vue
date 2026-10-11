<script setup>
import { computed, onMounted } from 'vue'
import { useSettings } from '@/composables/useSettings'

const { settings, fetchSettings } = useSettings()

onMounted(fetchSettings)

const siteName = computed(() => settings.value?.site_name || 'KAM Jurídica')
const about = computed(() => settings.value?.about || '')

const socials = computed(() => [
  { label: 'Instagram', url: settings.value?.instagram },
  { label: 'Facebook', url: settings.value?.facebook },
  { label: 'TikTok', url: settings.value?.tiktok },
].filter((s) => s.url))

const year = new Date().getFullYear()
</script>

<template>
  <footer class="footer-kam">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-4">
          <h5>{{ siteName }}</h5>
          <p class="mb-0">{{ about }}</p>
        </div>

        <div class="col-lg-3">
          <h5>Navegación</h5>
          <ul class="list-unstyled">
            <li><RouterLink :to="{ name: 'home' }">Inicio</RouterLink></li>
            <li><RouterLink :to="{ name: 'services' }">Servicios</RouterLink></li>
            <li><RouterLink :to="{ name: 'about' }">Sobre mí</RouterLink></li>
            <li><RouterLink :to="{ name: 'articles' }">Artículos</RouterLink></li>
            <li><RouterLink :to="{ name: 'contact' }">Contacto</RouterLink></li>
          </ul>
        </div>

        <div class="col-lg-3">
          <h5>Contacto</h5>
          <ul class="list-unstyled">
            <li v-if="settings?.phone">{{ settings.phone }}</li>
            <li v-if="settings?.email">{{ settings.email }}</li>
            <li v-if="settings?.address">{{ settings.address }}</li>
          </ul>
        </div>

        <div class="col-lg-2">
          <h5>Redes</h5>
          <div>
            <a
              v-for="s in socials"
              :key="s.label"
              class="social-link mb-1"
              :href="s.url"
              target="_blank"
              rel="noopener"
            >
              {{ s.label }}
            </a>
          </div>
        </div>
      </div>

      <hr class="border-secondary mt-4" />
      <p class="text-center mb-0 small">
        © {{ year }} {{ siteName }}. Todos los derechos reservados.
      </p>
    </div>
  </footer>
</template>
