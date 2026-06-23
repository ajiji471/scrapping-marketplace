// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import Layout from '@components/Layout.vue'

const routes = [
    {
        path: '/',
        component: Layout,
        children: [
            {
                path: '',
                name: 'dashboard',
                component: () => import('@views/Dashboard.vue')
            },
            {
                path: 'products',
                name: 'products',
                component: () => import('@views/Products.vue')
            },
            {
                path: 'products/:id',
                name: 'product-detail',
                component: () => import('@views/ProductDetail.vue')
            },
            {
                path: 'spk',
                name: 'spk',
                component: () => import('@views/SpkCalc.vue')
            },
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router