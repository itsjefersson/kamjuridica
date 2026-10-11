<script setup>
import { computed, onMounted, ref } from 'vue'
import socialPostsService from '@/api/socialPosts'

const posts = ref([])
const loading = ref(true)

const platformMeta = {
  instagram: {
    label: 'Instagram',
    gradient: 'linear-gradient(135deg, #f58529, #dd2a7b, #8134af)',
  },
  facebook: {
    label: 'Facebook',
    gradient: 'linear-gradient(135deg, #1877f2, #0e5fd8)',
  },
  tiktok: {
    label: 'TikTok',
    gradient: 'linear-gradient(135deg, #25f4ee, #000000)',
  },
}

const list = computed(() =>
  posts.value
    .filter((p) => p.url)
    .map((p) => ({
      ...p,
      meta: platformMeta[p.platform] || { label: p.platform, gradient: 'var(--grad-navy)' },
    })),
)

async function load() {
  loading.value = true
  try {
    posts.value = await socialPostsService.listActive()
  } catch {
    posts.value = []
  } finally {
    loading.value = false
  }
}

onMounted(load)
</script>

<template>
  <section v-if="list.length || loading" class="section social-section">
    <div class="container">
      <div v-reveal>
        <p class="section-eyebrow">Sígueme</p>
        <h2 class="section-title">Conecta en redes sociales</h2>
      </div>

      <div v-if="loading" class="text-muted">Cargando...</div>

      <div v-else class="row g-4">
        <div
          v-for="(post, i) in list"
          :key="post.id"
          class="col-md-4"
          v-reveal="i * 120"
        >
          <a
            class="social-card"
            :href="post.url"
            target="_blank"
            rel="noopener"
          >
            <span class="social-card-icon" :style="{ background: post.meta.gradient }">
              <svg
                v-if="post.platform === 'instagram'"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
              </svg>
              <svg
                v-else-if="post.platform === 'facebook'"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z" />
              </svg>
              <svg
                v-else-if="post.platform === 'tiktok'"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.04-.1z" />
              </svg>
              <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="16" />
                <line x1="8" y1="12" x2="16" y2="12" />
              </svg>
            </span>
            <span class="social-card-body">
              <span class="social-card-platform">{{ post.meta.label }}</span>
              <span class="social-card-title">{{ post.title || 'Sígueme' }}</span>
            </span>
            <span class="social-card-arrow">→</span>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>
