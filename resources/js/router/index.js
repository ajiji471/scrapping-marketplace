import { createRouter, createWebHistory } from 'vue-router'
import DashboardLayout from '../Layouts/DashboardLayout.vue'
import Login from '../Pages/Auth/Login.vue'

const routes = [
    {
        path: '/',
        component: DashboardLayout,
        children: [
            {
                path: '',
                name: 'dashboard',
                component: () => import('../views/Dashboard.vue')  // path relatif
            },
            {
                path: 'products',
                name: 'products',
                component: () => import('../views/Products.vue')
            },
            {
                path: 'products/:id',
                name: 'product-detail',
                component: () => import('../views/ProductDetail.vue')
            },
            {
                path: 'spk',
                name: 'spk',
                component: () => import('../views/SpkCalc.vue')
            },
        ]
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router