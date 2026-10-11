<script setup>
import { useSeo } from '@/composables/useSeo'
import ContactSection from '@/components/public/ContactSection.vue'

useSeo(() => ({
  title: 'Contacto',
  description: 'Contacta a KAM Jurídica por WhatsApp, correo o formulario.',
}))
</script>

<template>
  <div>
    <section class="section">
      <div class="container">
        <div v-reveal>
          <p class="section-eyebrow">Contacto</p>
          <h1 class="section-title mb-4">Hablemos</h1>
        </div>
        <div v-reveal="120">
          <ContactSection />
        </div>
      </div>
    </section>
  </div>
</template>
