<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useSettings } from '@/composables/useSettings'
import contactService from '@/api/contact'

const { settings, fetchSettings } = useSettings()

onMounted(fetchSettings)

const form = reactive({
  name: '',
  email: '',
  phone: '',
  message: '',
})

const sending = ref(false)
const sent = ref(false)
const error = ref('')

const whatsappLink = computed(() => {
  const number = settings.value?.whatsapp
  return number ? `https://wa.me/${number}` : '#'
})

async function onSubmit() {
  sending.value = true
  error.value = ''
  sent.value = false

  try {
    await contactService.send(form)
    sent.value = true
    form.name = ''
    form.email = ''
    form.phone = ''
    form.message = ''
  } catch (e) {
    error.value = e.response?.data?.message || 'No se pudo enviar el mensaje. Inténtalo de nuevo.'
  } finally {
    sending.value = false
  }
}
</script>

<template>
  <div class="row g-5">
    <div class="col-lg-5">
      <h2 class="section-title">Contacto</h2>
      <p class="text-muted mb-4">
        Escríbeme y resolveré tus dudas con total confidencialidad.
      </p>

      <ul class="list-unstyled d-flex flex-column gap-3">
        <li v-if="settings?.phone" class="d-flex align-items-center gap-3">
          <span class="contact-icon">📞</span>
          <span>{{ settings.phone }}</span>
        </li>
        <li v-if="settings?.email" class="d-flex align-items-center gap-3">
          <span class="contact-icon">✉️</span>
          <span>{{ settings.email }}</span>
        </li>
        <li v-if="settings?.address" class="d-flex align-items-center gap-3">
          <span class="contact-icon">📍</span>
          <span>{{ settings.address }}</span>
        </li>
        <li v-if="settings?.schedule" class="d-flex align-items-center gap-3">
          <span class="contact-icon">🕒</span>
          <span>{{ settings.schedule }}</span>
        </li>
      </ul>

      <a
        class="btn btn-whatsapp mt-4"
        :href="whatsappLink"
        target="_blank"
        rel="noopener"
      >
        Escribir por WhatsApp
      </a>
    </div>

    <div class="col-lg-7">
      <div v-if="sent" class="alert alert-success">
        Mensaje enviado. Te responderé lo antes posible.
      </div>
      <div v-if="error" class="alert alert-danger">{{ error }}</div>

      <form @submit.prevent="onSubmit">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="contact-name" class="form-label">Nombre</label>
            <input
              id="contact-name"
              v-model="form.name"
              type="text"
              class="form-control"
              required
            />
          </div>
          <div class="col-md-6">
            <label for="contact-email" class="form-label">Correo</label>
            <input
              id="contact-email"
              v-model="form.email"
              type="email"
              class="form-control"
              required
            />
          </div>
          <div class="col-md-6">
            <label for="contact-phone" class="form-label">Teléfono (opcional)</label>
            <input
              id="contact-phone"
              v-model="form.phone"
              type="tel"
              class="form-control"
            />
          </div>
          <div class="col-12">
            <label for="contact-message" class="form-label">Mensaje</label>
            <textarea
              id="contact-message"
              v-model="form.message"
              class="form-control"
              rows="5"
              required
            ></textarea>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-gold" :disabled="sending">
              {{ sending ? 'Enviando...' : 'Enviar mensaje' }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
