<script setup>
import { computed, onMounted } from 'vue'
import { useSettings } from '@/composables/useSettings'
import { useSeo } from '@/composables/useSeo'
import ServiceCard from '@/components/public/ServiceCard.vue'

const { settings, fetchSettings } = useSettings()

useSeo(() => ({
  title: 'Servicios',
  description: 'Áreas de práctica y servicios jurídicos ofrecidos por KAM Jurídica.',
}))

const services = computed(() => settings.value?.services || [])

onMounted(fetchSettings)
</script>

<template>
  <div>
    <section class="section">
      <div class="container">
        <div v-reveal>
          <p class="section-eyebrow">Servicios</p>
          <h1 class="section-title">Áreas de práctica</h1>
          <p class="text-muted mb-4">
            Acompañamiento jurídico integral en las siguientes áreas.
          </p>
        </div>
        <div class="row g-4">
          <div
            v-for="(service, i) in services"
            :key="service.title"
            class="col-md-6 col-lg-4"
            v-reveal="i * 100"
          >
            <ServiceCard :service="service" />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
