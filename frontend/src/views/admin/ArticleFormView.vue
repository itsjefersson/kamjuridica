<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Ckeditor } from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import '@ckeditor/ckeditor5-build-classic/build/translations/es.js'
import articlesService from '@/api/articles'

const route = useRoute()
const router = useRouter()

const isEdit = !!route.params.id

const form = ref({
  title: '',
  excerpt: '',
  content: '',
  featured_image: '',
  youtube_url: '',
  status: 'draft',
})

const saving = ref(false)
const uploading = ref(false)
const error = ref('')

async function loadArticle() {
  if (!isEdit) return
  try {
    const article = await articlesService.get(route.params.id)
    form.value = {
      title: article.title,
      excerpt: article.excerpt || '',
      content: article.content || '',
      featured_image: article.featured_image || '',
      youtube_url: article.youtube_url || '',
      status: article.status,
    }
  } catch (e) {
    error.value = 'Error al cargar el artículo.'
  }
}

async function onImageSelected(event) {
  const file = event.target.files[0]
  if (!file) return

  uploading.value = true
  error.value = ''
  try {
    const result = await articlesService.uploadImage(file)
    form.value.featured_image = result.path
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al subir la imagen.'
  } finally {
    uploading.value = false
  }
}

async function onSubmit() {
  saving.value = true
  error.value = ''
  try {
    if (isEdit) {
      await articlesService.update(route.params.id, form.value)
    } else {
      await articlesService.create(form.value)
    }
    router.push({ name: 'admin-articles' })
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al guardar el artículo.'
  } finally {
    saving.value = false
  }
}

onMounted(loadArticle)
</script>

<template>
  <div>
    <h1 class="mb-4">{{ isEdit ? 'Editar artículo' : 'Nuevo artículo' }}</h1>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <form @submit.prevent="onSubmit">
      <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input id="title" v-model="form.title" type="text" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="excerpt" class="form-label">Resumen</label>
        <textarea id="excerpt" v-model="form.excerpt" class="form-control" rows="2"></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Contenido</label>
        <Ckeditor
          v-model="form.content"
          :editor="ClassicEditor"
          :config="{ language: 'es', licenseKey: 'GPL' }"
        />
      </div>

      <div class="mb-3">
        <label class="form-label">Imagen destacada</label>
        <input type="file" class="form-control" accept="image/*" @change="onImageSelected" />
        <div v-if="uploading" class="text-muted small mt-1">Subiendo imagen...</div>
        <img
          v-if="form.featured_image"
          :src="`/storage/${form.featured_image}`"
          class="img-thumbnail mt-2"
          style="max-width: 200px"
          alt="Imagen destacada"
        />
      </div>

      <div class="mb-3">
        <label for="youtube" class="form-label">URL de YouTube (opcional)</label>
        <input id="youtube" v-model="form.youtube_url" type="url" class="form-control" />
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Estado</label>
        <select id="status" v-model="form.status" class="form-select">
          <option value="draft">Borrador</option>
          <option value="published">Publicado</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary" :disabled="saving || uploading">
        {{ saving ? 'Guardando...' : 'Guardar' }}
      </button>
      <button
        type="button"
        class="btn btn-outline-secondary ms-2"
        @click="router.push({ name: 'articles' })"
      >
        Cancelar
      </button>
    </form>
  </div>
</template>
