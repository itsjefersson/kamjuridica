import { watchEffect } from 'vue'

const SITE_NAME = 'KAM Jurídica'

// Cache de elementos <meta> ya creados, para no duplicarlos.
const metaCache = new Map()

function ensureMeta(attr, key) {
  const cacheKey = `${attr}:${key}`
  if (metaCache.has(cacheKey)) return metaCache.get(cacheKey)

  const selector = attr === 'property' ? `meta[property="${key}"]` : `meta[name="${key}"]`
  let el = document.head.querySelector(selector)

  if (!el) {
    el = document.createElement('meta')
    el.setAttribute(attr, key)
    document.head.appendChild(el)
  }

  metaCache.set(cacheKey, el)
  return el
}

function setContent(el, value) {
  el.setAttribute('content', value || '')
}

/**
 * Actualiza el título de la pestaña y las meta etiquetas (description + Open Graph).
 *
 * `getMeta` debe devolver un objeto { title, description, image, url }.
 * Al ser reactivo (watchEffect), se recalcula automáticamente si esos datos
 * provienen de refs/computed (p. ej. settings o un artículo cargado de forma asíncrona).
 */
export function useSeo(getMeta) {
  watchEffect(() => {
    const meta = getMeta() || {}
    const title = meta.title || ''
    const description = meta.description || ''

    document.title = title ? `${title} — ${SITE_NAME}` : SITE_NAME

    setContent(ensureMeta('name', 'description'), description)
    setContent(ensureMeta('property', 'og:title'), title || SITE_NAME)
    setContent(ensureMeta('property', 'og:description'), description)
    setContent(ensureMeta('property', 'og:type'), 'website')
    setContent(ensureMeta('property', 'og:image'), meta.image)
    setContent(ensureMeta('property', 'og:url'), meta.url || window.location.href)
    setContent(ensureMeta('name', 'twitter:card'), 'summary_large_image')
  })
}
