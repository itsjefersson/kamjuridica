<script setup>
import { computed } from 'vue'

const props = defineProps({
  article: {
    type: Object,
    required: true,
  },
})

const imageUrl = computed(() =>
  props.article.featured_image ? `/storage/${props.article.featured_image}` : '',
)

// Imagen genérica (justicia) para artículos sin imagen destacada.
const fallbackImage =
  'https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt=' +
  encodeURIComponent(
    'Scales of justice and law books on a wooden desk, navy blue and gold tones, elegant, professional, photorealistic',
  ) +
  '&image_size=landscape_16_9'

const formattedDate = computed(() => {
  if (!props.article.published_at) return ''
  return new Date(props.article.published_at).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
})
</script>

<template>
  <RouterLink
    class="article-card text-reset"
    :to="{ name: 'article-detail', params: { slug: article.slug } }"
  >
    <div class="thumb">
      <img v-if="imageUrl" :src="imageUrl" :alt="article.title" loading="lazy" />
      <img v-else :src="fallbackImage" :alt="article.title" loading="lazy" />
    </div>
    <div class="body">
      <span v-if="formattedDate" class="date mb-2">{{ formattedDate }}</span>
      <h3 class="h6 mb-2">{{ article.title }}</h3>
      <p v-if="article.excerpt" class="mb-3 text-muted small">{{ article.excerpt }}</p>
      <span class="read-more">Leer más <span class="arrow">→</span></span>
    </div>
  </RouterLink>
</template>
