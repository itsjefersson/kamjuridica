import axios from 'axios'

// Instancia centralizada de Axios.
// En desarrollo, las peticiones a /api se redirigen al backend por el proxy de Vite.
// En producción, frontend y API comparten origen (servidos por Laravel).
const apiClient = axios.create({
  baseURL: '/api',
  headers: {
    Accept: 'application/json',
  },
})

// Adjunta el token de Sanctum a cada petición, si existe.
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Si una petición devuelve 401 (token inválido/vencido), se limpia la sesión.
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
    return Promise.reject(error)
  },
)

export default apiClient
