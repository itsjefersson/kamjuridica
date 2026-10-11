<script setup>
import { computed, onMounted } from 'vue'
import { useSettings } from '@/composables/useSettings'

const { settings, fetchSettings } = useSettings()

onMounted(fetchSettings)

const name = computed(() => settings.value?.lawyer_name || 'Abogada')
const title = computed(() => settings.value?.lawyer_title || '')
const tagline = computed(() => settings.value?.tagline || '')
const heroImage = computed(() => settings.value?.hero_image_url || '')

const whatsappLink = computed(() => {
  const number = settings.value?.whatsapp
  const text = encodeURIComponent('Hola, me gustaría solicitar una consulta jurídica.')
  return number ? `https://wa.me/${number}?text=${text}` : '#'
})

// Divide el nombre para resaltar con dorado la última palabra.
const nameParts = computed(() => {
  const words = name.value.split(' ').filter(Boolean)
  if (words.length <= 1) return { first: name.value, accent: '' }
  const accent = words.pop()
  return { first: words.join(' '), accent }
})

const heroBackground = computed(() =>
  heroImage.value ? { backgroundImage: `url(${heroImage.value})` } : {},
)
</script>

<template>
  <section class="hero" :style="heroBackground">
    <div class="container">
      <div class="hero-content" v-reveal>
        <p class="section-eyebrow hero-eyebrow mb-3">{{ title }}</p>
        <h1>
          {{ nameParts.first }}
          <span v-if="nameParts.accent" class="accent">{{ nameParts.accent }}</span>
        </h1>
        <p class="hero-tagline mb-4">{{ tagline }}</p>
        <div class="d-flex flex-wrap gap-2 mb-4">
          <a class="btn btn-gold" :href="whatsappLink" target="_blank" rel="noopener">
            Contactar por WhatsApp
          </a>
          <RouterLink class="btn btn-outline-light" :to="{ name: 'contact' }">
            Solicitar consulta
          </RouterLink>
        </div>
        <div class="hero-trust">
          <span><span class="tick">✓</span> Confiable</span>
          <span><span class="tick">✓</span> Cercana</span>
          <span><span class="tick">✓</span> A tu medida</span>
        </div>
      </div>
    </div>
  </section>
</template>
