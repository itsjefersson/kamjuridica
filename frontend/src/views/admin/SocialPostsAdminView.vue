<script setup>
import { onMounted, reactive, ref } from 'vue'
import socialPostsService from '@/api/socialPosts'

const posts = ref([])
const loading = ref(true)
const error = ref('')
const saving = ref(false)

const platforms = [
  { value: 'instagram', label: 'Instagram' },
  { value: 'facebook', label: 'Facebook' },
  { value: 'tiktok', label: 'TikTok' },
]

const form = reactive({
  id: null,
  platform: 'instagram',
  url: '',
  title: '',
  active: true,
})

function resetForm() {
  form.id = null
  form.platform = 'instagram'
  form.url = ''
  form.title = ''
  form.active = true
}

async function load() {
  loading.value = true
  error.value = ''
  try {
    posts.value = await socialPostsService.list()
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al cargar las publicaciones.'
  } finally {
    loading.value = false
  }
}

function onEdit(post) {
  form.id = post.id
  form.platform = post.platform
  form.url = post.url
  form.title = post.title || ''
  form.active = !!post.active
}

async function onSubmit() {
  saving.value = true
  error.value = ''
  try {
    const payload = { ...form }
    if (payload.id) {
      await socialPostsService.update(payload.id, payload)
    } else {
      await socialPostsService.create(payload)
    }
    resetForm()
    await load()
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al guardar la publicación.'
  } finally {
    saving.value = false
  }
}

async function onDelete(post) {
  if (!confirm(`¿Eliminar la publicación de ${post.platform}?`)) return
  try {
    await socialPostsService.remove(post.id)
    await load()
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al eliminar.'
  }
}

onMounted(load)
</script>

<template>
  <div>
    <h1 class="mb-4">Redes sociales</h1>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="mb-3">{{ form.id ? 'Editar publicación' : 'Nueva publicación' }}</h5>
        <form @submit.prevent="onSubmit">
          <div class="row g-3 align-items-end">
            <div class="col-md-3">
              <label for="sp-platform" class="form-label">Plataforma</label>
              <select id="sp-platform" v-model="form.platform" class="form-select">
                <option v-for="p in platforms" :key="p.value" :value="p.value">
                  {{ p.label }}
                </option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="sp-url" class="form-label">URL</label>
              <input
                id="sp-url"
                v-model="form.url"
                type="url"
                class="form-control"
                placeholder="https://..."
                required
              />
            </div>
            <div class="col-md-3">
              <label for="sp-title" class="form-label">Título (opcional)</label>
              <input id="sp-title" v-model="form.title" type="text" class="form-control" />
            </div>
            <div class="col-md-2 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1" :disabled="saving">
                {{ saving ? 'Guardando...' : 'Guardar' }}
              </button>
              <button
                v-if="form.id"
                type="button"
                class="btn btn-outline-secondary"
                @click="resetForm"
              >
                Cancelar
              </button>
            </div>
          </div>
          <div class="form-check mt-3">
            <input id="sp-active" v-model="form.active" type="checkbox" class="form-check-input" />
            <label for="sp-active" class="form-check-label">Visible en el sitio público</label>
          </div>
        </form>
      </div>
    </div>

    <div v-if="loading" class="text-muted">Cargando...</div>

    <div v-else-if="posts.length === 0" class="text-muted">
      No hay publicaciones todavía.
    </div>

    <div v-else class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>Plataforma</th>
            <th>Título</th>
            <th>URL</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in posts" :key="post.id">
            <td class="text-capitalize">{{ post.platform }}</td>
            <td>{{ post.title || '—' }}</td>
            <td>
              <a :href="post.url" target="_blank" rel="noopener">{{ post.url }}</a>
            </td>
            <td>
              <span class="badge" :class="post.active ? 'bg-success' : 'bg-secondary'">
                {{ post.active ? 'Visible' : 'Oculto' }}
              </span>
            </td>
            <td class="text-end">
              <button type="button" class="btn btn-sm btn-outline-primary me-1" @click="onEdit(post)">
                Editar
              </button>
              <button type="button" class="btn btn-sm btn-outline-danger" @click="onDelete(post)">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
