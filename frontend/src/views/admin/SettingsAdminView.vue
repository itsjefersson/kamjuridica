<script setup>
import { onMounted, reactive, ref } from 'vue'
import settingsService from '@/api/settings'

const loading = ref(true)
const saving = ref(false)
const error = ref('')
const saved = ref(false)

const form = reactive({
  site_name: '',
  lawyer_name: '',
  lawyer_title: '',
  tagline: '',
  about: '',
  phone: '',
  whatsapp: '',
  email: '',
  address: '',
  schedule: '',
  instagram: '',
  facebook: '',
  tiktok: '',
  photo_url: '',
  hero_image_url: '',
})

async function load() {
  loading.value = true
  error.value = ''
  try {
    const data = await settingsService.getAdmin()
    Object.keys(form).forEach((key) => {
      form[key] = data[key] ?? ''
    })
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al cargar la configuración.'
  } finally {
    loading.value = false
  }
}

async function onSubmit() {
  saving.value = true
  error.value = ''
  saved.value = false
  try {
    await settingsService.update({ ...form })
    saved.value = true
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al guardar la configuración.'
  } finally {
    saving.value = false
  }
}

onMounted(load)
</script>

<template>
  <div>
    <h1 class="mb-4">Configuración del sitio</h1>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-if="saved" class="alert alert-success">Configuración guardada correctamente.</div>

    <div v-if="loading" class="text-muted">Cargando...</div>

    <form v-else @submit.prevent="onSubmit">
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-3">Información general</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="s-site_name" class="form-label">Nombre del sitio</label>
              <input id="s-site_name" v-model="form.site_name" type="text" class="form-control" />
            </div>
            <div class="col-md-6">
              <label for="s-lawyer_name" class="form-label">Nombre de la abogada</label>
              <input id="s-lawyer_name" v-model="form.lawyer_name" type="text" class="form-control" />
            </div>
            <div class="col-md-6">
              <label for="s-lawyer_title" class="form-label">Título / cargo</label>
              <input id="s-lawyer_title" v-model="form.lawyer_title" type="text" class="form-control" />
            </div>
            <div class="col-md-6">
              <label for="s-tagline" class="form-label">Eslogan</label>
              <input id="s-tagline" v-model="form.tagline" type="text" class="form-control" />
            </div>
            <div class="col-12">
              <label for="s-about" class="form-label">Sobre mí (descripción)</label>
              <textarea id="s-about" v-model="form.about" class="form-control" rows="4"></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-3">Contacto</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="s-phone" class="form-label">Teléfono</label>
              <input id="s-phone" v-model="form.phone" type="text" class="form-control" />
            </div>
            <div class="col-md-6">
              <label for="s-whatsapp" class="form-label">WhatsApp (solo números, con código de país)</label>
              <input id="s-whatsapp" v-model="form.whatsapp" type="text" class="form-control" />
            </div>
            <div class="col-md-6">
              <label for="s-email" class="form-label">Correo</label>
              <input id="s-email" v-model="form.email" type="email" class="form-control" />
            </div>
            <div class="col-md-6">
              <label for="s-address" class="form-label">Dirección</label>
              <input id="s-address" v-model="form.address" type="text" class="form-control" />
            </div>
            <div class="col-12">
              <label for="s-schedule" class="form-label">Horario de atención</label>
              <input id="s-schedule" v-model="form.schedule" type="text" class="form-control" />
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-3">Redes sociales</h5>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="s-instagram" class="form-label">Instagram</label>
              <input id="s-instagram" v-model="form.instagram" type="url" class="form-control" />
            </div>
            <div class="col-md-4">
              <label for="s-facebook" class="form-label">Facebook</label>
              <input id="s-facebook" v-model="form.facebook" type="url" class="form-control" />
            </div>
            <div class="col-md-4">
              <label for="s-tiktok" class="form-label">TikTok</label>
              <input id="s-tiktok" v-model="form.tiktok" type="url" class="form-control" />
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-3">Imágenes</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="s-photo_url" class="form-label">Foto de perfil (URL)</label>
              <input id="s-photo_url" v-model="form.photo_url" type="url" class="form-control" />
            </div>
            <div class="col-md-6">
              <label for="s-hero_image_url" class="form-label">Imagen principal / hero (URL)</label>
              <input id="s-hero_image_url" v-model="form.hero_image_url" type="url" class="form-control" />
            </div>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" :disabled="saving">
        {{ saving ? 'Guardando...' : 'Guardar cambios' }}
      </button>
    </form>
  </div>
</template>
