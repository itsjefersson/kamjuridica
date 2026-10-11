import apiClient from './client'

export default {
  async getAll() {
    const { data } = await apiClient.get('/settings')
    return data.data
  },

  // ---- Administración ----
  async getAdmin() {
    const { data } = await apiClient.get('/admin/settings')
    return data.data
  },

  async update(payload) {
    const { data } = await apiClient.put('/admin/settings', payload)
    return data.data
  },
}
