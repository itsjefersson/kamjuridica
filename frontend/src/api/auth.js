import apiClient from './client'

export default {
  async login(email, password) {
    const { data } = await apiClient.post('/login', { email, password })
    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))
    return data.user
  },

  async logout() {
    try {
      await apiClient.post('/logout')
    } finally {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  },

  async me() {
    const { data } = await apiClient.get('/admin/me')
    return data.user
  },

  getUser() {
    const raw = localStorage.getItem('user')
    return raw ? JSON.parse(raw) : null
  },

  isAuthenticated() {
    return !!localStorage.getItem('token')
  },
}
