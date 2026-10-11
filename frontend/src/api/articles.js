import apiClient from './client'

export default {
  // ---- Público ----
  async listPublished() {
    const { data } = await apiClient.get('/articles')
    return data.data
  },

  async getBySlug(slug) {
    const { data } = await apiClient.get(`/articles/${slug}`)
    return data.data
  },

  // ---- Administración ----
  async list() {
    const { data } = await apiClient.get('/admin/articles')
    return data.data
  },

  async get(id) {
    const { data } = await apiClient.get(`/admin/articles/${id}`)
    return data.data
  },

  async create(payload) {
    const { data } = await apiClient.post('/admin/articles', payload)
    return data.data
  },

  async update(id, payload) {
    const { data } = await apiClient.put(`/admin/articles/${id}`, payload)
    return data.data
  },

  async remove(id) {
    await apiClient.delete(`/admin/articles/${id}`)
  },

  async uploadImage(file) {
    const form = new FormData()
    form.append('image', file)
    const { data } = await apiClient.post('/admin/upload', form, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    return data
  },
}
