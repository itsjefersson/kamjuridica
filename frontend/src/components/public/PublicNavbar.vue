<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { useSettings } from '@/composables/useSettings'

const { settings, fetchSettings } = useSettings()

const scrolled = ref(false)

function onScroll() {
  scrolled.value = window.scrollY > 8
}

onMounted(() => {
  fetchSettings()
  window.addEventListener('scroll', onScroll, { passive: true })
  onScroll()
})

onBeforeUnmount(() => window.removeEventListener('scroll', onScroll))

const brand = computed(() => {
  const name = settings.value?.site_name || 'KAM Jurídica'
  const [first, ...rest] = name.split(' ')
  return { first, rest: rest.join(' ') }
})

const whatsappLink = computed(() => {
  const number = settings.value?.whatsapp
  return number ? `https://wa.me/${number}` : '#'
})

const links = [
  { name: 'home', label: 'Inicio' },
  { name: 'services', label: 'Servicios' },
  { name: 'about', label: 'Sobre mí' },
  { name: 'articles', label: 'Artículos' },
  { name: 'contact', label: 'Contacto' },
]
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-kam sticky-top" :class="{ scrolled }">
    <div class="container">
      <RouterLink class="navbar-brand brand" :to="{ name: 'home' }">
        <span class="brand-gold">{{ brand.first }}</span>{{ brand.rest ? ` ${brand.rest}` : '' }}
      </RouterLink>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#publicNav"
        aria-controls="publicNav"
        aria-expanded="false"
        aria-label="Abrir menú"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="publicNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li v-for="link in links" :key="link.name" class="nav-item">
            <RouterLink class="nav-link" :to="{ name: link.name }">{{ link.label }}</RouterLink>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-whatsapp btn-sm" :href="whatsappLink" target="_blank" rel="noopener">
              WhatsApp
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
