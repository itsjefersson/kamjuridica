<script setup>
import { onMounted, ref } from 'vue'
import { useSeo } from '@/composables/useSeo'
import articlesService from '@/api/articles'
import ArticleCard from '@/components/public/ArticleCard.vue'

useSeo(() => ({
  title: 'Artículos',
  description: 'Artículos y novedades jurídicas de KAM Jurídica.',
}))

const articles = ref([])
const loading = ref(true)
const error = ref('')

onMounted(async () => {
  try {
    articles.value = await articlesService.listPublished()
  } catch {
    error.value = 'No se pudieron cargar los artículos.'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <section class="section">
      <div class="container">
        <div v-reveal>
          <p class="section-eyebrow">Blog</p>
          <h1 class="section-title">Artículos</h1>
        </div>

        <div v-if="loading" class="text-muted">Cargando...</div>
        <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
        <div v-else-if="articles.length === 0" class="text-muted">
          Aún no hay artículos publicados.
        </div>
        <div v-else class="row g-4">
          <div
            v-for="(article, i) in articles"
            :key="article.id"
            class="col-md-6 col-lg-4"
            v-reveal="i * 100"
          >
            <ArticleCard :article="article" />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
