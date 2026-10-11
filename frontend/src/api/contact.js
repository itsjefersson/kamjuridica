import apiClient from './client'

export default {
  async send(payload) {
    const { data } = await apiClient.post('/contact', payload)
    return data
  },
}
