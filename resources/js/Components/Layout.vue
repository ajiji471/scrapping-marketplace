<!-- resources/js/components/Layout.vue -->
<template>
    <div class="min-h-screen bg-background">
        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 z-40 h-screen w-64 border-r bg-card">
            <div class="flex h-16 items-center border-b px-6">
                <h1 class="text-lg font-bold">SPK Marketplace</h1>
            </div>
            <nav class="space-y-1 p-4">
                <router-link
                    v-for="item in navigation"
                    :key="item.name"
                    :to="item.to"
                    :class="cn(
                        'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                        isActive(item.to) 
                            ? 'bg-primary text-primary-foreground' 
                            : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground'
                    )"
                >
                    <component :is="item.icon" class="h-4 w-4" />
                    {{ item.name }}
                </router-link>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="pl-64">
            <header class="sticky top-0 z-30 flex h-16 items-center border-b bg-background/95 px-8 backdrop-blur">
                <h2 class="text-lg font-semibold">{{ pageTitle }}</h2>
            </header>
            <main class="p-8">
                <router-view />
            </main>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { LayoutDashboard, Package, Calculator } from 'lucide-vue-next'
import { cn } from '@lib/utils'

const route = useRoute()

const navigation = [
    { name: 'Dashboard', to: '/', icon: LayoutDashboard },
    { name: 'Produk', to: '/products', icon: Package },
    { name: 'SPK', to: '/spk', icon: Calculator },
]

const isActive = (path) => {
    if (path === '/') return route.path === '/'
    return route.path.startsWith(path)
}

const pageTitle = computed(() => {
    const titles = {
        'dashboard': 'Dashboard',
        'products': 'Daftar Produk',
        'product-detail': 'Detail Produk',
        'spk': 'Perhitungan SPK',
    }
    return titles[route.name] || 'SPK Marketplace'
})
</script>