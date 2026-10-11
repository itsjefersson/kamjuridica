<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import articlesService from '@/api/articles'

const router = useRouter()
const articles = ref([])
const loading = ref(true)
const error = ref('')

async function load() {
  loading.value = true
  error.value = ''
  try {
    articles.value = await articlesService.list()
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al cargar los artículos.'
  } finally {
    loading.value = false
  }
}

async function onDelete(article) {
  if (!confirm(`¿Eliminar el artículo "${article.title}"?`)) return
  try {
    await articlesService.remove(article.id)
    await load()
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al eliminar.'
  }
}

onMounted(load)
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Artículos</h1>
      <button type="button" class="btn btn-primary" @click="router.push({ name: 'admin-article-create' })">
        Nuevo artículo
      </button>
    </div>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-if="loading" class="text-muted">Cargando...</div>

    <div v-else-if="articles.length === 0" class="text-muted">
      No hay artículos todavía.
    </div>

    <div v-else class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>Título</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="article in articles" :key="article.id">
            <td>{{ article.title }}</td>
            <td>
              <span
                class="badge"
                :class="article.status === 'published' ? 'bg-success' : 'bg-secondary'"
              >
                {{ article.status === 'published' ? 'Publicado' : 'Borrador' }}
              </span>
            </td>
            <td>
              {{ article.published_at ? new Date(article.published_at).toLocaleDateString('es-ES') : '—' }}
            </td>
            <td class="text-end">
              <button
                type="button"
                class="btn btn-sm btn-outline-primary me-1"
                @click="router.push({ name: 'admin-article-edit', params: { id: article.id } })"
              >
                Editar
              </button>
              <button type="button" class="btn btn-sm btn-outline-danger" @click="onDelete(article)">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
