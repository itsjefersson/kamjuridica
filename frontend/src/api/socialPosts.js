import apiClient from './client'

export default {
  // ---- Público ----
  async listActive() {
    const { data } = await apiClient.get('/social-posts')
    return data.data
  },

  // ---- Administración ----
  async list() {
    const { data } = await apiClient.get('/admin/social-posts')
    return data.data
  },

  async create(payload) {
    const { data } = await apiClient.post('/admin/social-posts', payload)
    return data.data
  },

  async update(id, payload) {
    const { data } = await apiClient.put(`/admin/social-posts/${id}`, payload)
    return data.data
  },

  async remove(id) {
    await apiClient.delete(`/admin/social-posts/${id}`)
  },
}
