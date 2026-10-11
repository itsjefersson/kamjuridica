<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useSeo } from '@/composables/useSeo'
import articlesService from '@/api/articles'
import DOMPurify from 'dompurify'

const route = useRoute()

const article = ref(null)
const loading = ref(true)
const error = ref('')

const safeContent = computed(() => DOMPurify.sanitize(article.value?.content || ''))
const imageUrl = computed(() =>
  article.value?.featured_image ? `/storage/${article.value.featured_image}` : '',
)
const embedUrl = computed(() => youtubeEmbed(article.value?.youtube_url))
const formattedDate = computed(() => {
  if (!article.value?.published_at) return ''
  return new Date(article.value.published_at).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
})

useSeo(() => ({
  title: article.value?.title || '',
  description: article.value?.excerpt || '',
  image: imageUrl.value ? `${window.location.origin}${imageUrl.value}` : '',
}))

function youtubeEmbed(url) {
  if (!url) return ''
  const patterns = [
    /youtube\.com\/watch\?v=([\w-]+)/,
    /youtu\.be\/([\w-]+)/,
    /youtube\.com\/embed\/([\w-]+)/,
  ]
  for (const pattern of patterns) {
    const match = url.match(pattern)
    if (match) return `https://www.youtube.com/embed/${match[1]}`
  }
  return ''
}

onMounted(async () => {
  try {
    article.value = await articlesService.getBySlug(route.params.slug)
  } catch {
    error.value = 'Artículo no encontrado.'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <section class="section">
      <div class="container" style="max-width: 820px">
        <div v-if="loading" class="text-muted">Cargando...</div>

        <div v-else-if="error" class="alert alert-danger">{{ error }}</div>

        <article v-else-if="article">
          <p class="section-eyebrow">Blog</p>
          <h1 class="section-title">{{ article.title }}</h1>
          <p v-if="formattedDate" class="text-muted">{{ formattedDate }}</p>

          <img
            v-if="imageUrl"
            :src="imageUrl"
            :alt="article.title"
            class="img-fluid rounded mb-4"
          />

          <div v-html="safeContent" class="article-content"></div>

          <div v-if="embedUrl" class="ratio ratio-16x9 mt-4">
            <iframe
              :src="embedUrl"
              title="Video"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
          </div>

          <div class="mt-4">
            <RouterLink :to="{ name: 'articles' }">← Volver a artículos</RouterLink>
          </div>
        </article>
      </div>
    </section>
  </div>
</template>
