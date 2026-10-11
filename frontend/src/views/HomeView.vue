<script setup>
import { computed, onMounted, ref } from 'vue'
import { useSettings } from '@/composables/useSettings'
import { useSeo } from '@/composables/useSeo'
import articlesService from '@/api/articles'
import HeroSection from '@/components/public/HeroSection.vue'
import ServiceCard from '@/components/public/ServiceCard.vue'
import ArticleCard from '@/components/public/ArticleCard.vue'
import ContactSection from '@/components/public/ContactSection.vue'
import SocialSection from '@/components/public/SocialSection.vue'

const { settings, fetchSettings } = useSettings()

useSeo(() => ({
  title: '',
  description: settings.value?.tagline || '',
  image: settings.value?.hero_image_url || '',
}))

const latestArticles = ref([])

const services = computed(() => settings.value?.services || [])
const about = computed(() => settings.value?.about || '')
const photo = computed(() => settings.value?.photo_url || '')
const lawyerName = computed(() => settings.value?.lawyer_name || '')

const initials = computed(() =>
  lawyerName.value
    .split(' ')
    .filter(Boolean)
    .map((word) => word[0])
    .slice(0, 2)
    .join('')
    .toUpperCase(),
)

onMounted(async () => {
  fetchSettings()
  try {
    latestArticles.value = (await articlesService.listPublished()).slice(0, 3)
  } catch {
    latestArticles.value = []
  }
})
</script>

<template>
  <div>
    <HeroSection />

    <!-- Servicios -->
    <section class="section section-alt">
      <div class="container">
        <div v-reveal>
          <p class="section-eyebrow">Servicios</p>
          <h2 class="section-title">Áreas de práctica</h2>
        </div>
        <div class="row g-4">
          <div
            v-for="(service, i) in services.slice(0, 3)"
            :key="service.title"
            class="col-md-4"
            v-reveal="i * 120"
          >
            <ServiceCard :service="service" />
          </div>
        </div>
        <div class="text-center mt-4" v-reveal>
          <RouterLink class="btn btn-outline-navy" :to="{ name: 'services' }">
            Ver todos los servicios
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- Sobre mí -->
    <section class="section">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-5" v-reveal>
            <img v-if="photo" :src="photo" :alt="lawyerName" class="img-fluid rounded" loading="lazy" />
            <div v-else class="hero-avatar-placeholder">{{ initials }}</div>
          </div>
          <div class="col-lg-7" v-reveal="150">
            <p class="section-eyebrow">Sobre mí</p>
            <h2 class="section-title">{{ lawyerName }}</h2>
            <p class="text-muted">{{ about }}</p>
            <RouterLink class="btn btn-outline-navy" :to="{ name: 'about' }">
              Conoce más
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Últimos artículos -->
    <section v-if="latestArticles.length" class="section section-alt">
      <div class="container">
        <div v-reveal>
          <p class="section-eyebrow">Blog</p>
          <h2 class="section-title">Últimos artículos</h2>
        </div>
        <div class="row g-4">
          <div
            v-for="(article, i) in latestArticles"
            :key="article.id"
            class="col-md-4"
            v-reveal="i * 120"
          >
            <ArticleCard :article="article" />
          </div>
        </div>
        <div class="text-center mt-4" v-reveal>
          <RouterLink class="btn btn-outline-navy" :to="{ name: 'articles' }">
            Ver todos los artículos
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- Contacto -->
    <section class="section">
      <div class="container" v-reveal>
        <ContactSection />
      </div>
    </section>

    <!-- Redes sociales -->
    <SocialSection />
  </div>
</template>
