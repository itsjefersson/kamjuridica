import { ref } from 'vue'
import settingsService from '@/api/settings'

// Singleton de configuración del sitio: se descarga una sola vez y se reutiliza.
const settings = ref(null)
let request = null

async function fetchSettings() {
  if (settings.value) return settings.value

  // Evita peticiones duplicadas cuando varios componentes piden la configuración.
  if (!request) {
    request = settingsService
      .getAll()
      .then((data) => {
        settings.value = data
        return data
      })
      .finally(() => {
        request = null
      })
  }

  return request
}

export function useSettings() {
  return { settings, fetchSettings }
}
