<script setup>
import { computed, onMounted } from 'vue'
import { useSettings } from '@/composables/useSettings'
import { useSeo } from '@/composables/useSeo'

const { settings, fetchSettings } = useSettings()

const name = computed(() => settings.value?.lawyer_name || '')
const title = computed(() => settings.value?.lawyer_title || '')
const about = computed(() => settings.value?.about || '')
const photo = computed(() => settings.value?.photo_url || '')

useSeo(() => ({
  title: name.value,
  description: about.value,
  image: photo.value,
}))

const initials = computed(() =>
  name.value
    .split(' ')
    .filter(Boolean)
    .map((word) => word[0])
    .slice(0, 2)
    .join('')
    .toUpperCase(),
)

onMounted(fetchSettings)
</script>

<template>
  <div>
    <section class="section">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-4" v-reveal>
            <img
              v-if="photo"
              :src="photo"
              :alt="name"
              class="img-fluid rounded"
              loading="lazy"
            />
            <div v-else class="hero-avatar-placeholder">{{ initials }}</div>
          </div>
          <div class="col-lg-8" v-reveal="150">
            <p class="section-eyebrow">Sobre mí</p>
            <h1 class="section-title">{{ name }}</h1>
            <p v-if="title" class="text-muted fw-semibold">{{ title }}</p>
            <p class="text-muted">{{ about }}</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
