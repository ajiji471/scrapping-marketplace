// resources/js/api/client.js
import axios from 'axios'

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }
})

// Add CSRF token
const token = document.querySelector('meta[name="csrf-token"]')
if (token) {
    api.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

export const productApi = {
    getAll: (params = {}) => api.get('/products', { params }).then(r => r.data),
    getById: (id) => api.get(`/products/${id}`).then(r => r.data),
    create: (data) => api.post('/products', data).then(r => r.data),
    update: (id, data) => api.put(`/products/${id}`, data).then(r => r.data),
    delete: (id) => api.delete(`/products/${id}`).then(r => r.data),
    updatePrice: (id, data) => api.patch(`/products/${id}/price`, data).then(r => r.data),
}

export const spkApi = {
    calculate: (data) => api.post('/spk/calculate', data).then(r => r.data),
    getCriteria: () => api.get('/spk/criteria').then(r => r.data),
}

export default api