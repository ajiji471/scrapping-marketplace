<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import {
    LayoutDashboard,
    Package,
    Calculator,
    User,
    LogOut,
    Settings,
    ChevronDown,
} from 'lucide-vue-next'
import { cn } from '@lib/utils'

// shadcn-vue components
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar'
import { Button } from '@/Components/ui/button'
import Sidebar from '@/Components/Sidebar.vue'

const route = useRoute()
const router = useRouter()



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

// Mock user data
const user = {
    name: 'Admin User',
    email: 'admin@spkmarketplace.com',
    avatar: '',
    initials: 'AU',
}

const handleLogout = () => {
    // Implementasi logout
    // contoh: authStore.logout()
    router.push('/login')
}

const goToProfile = () => {
    router.push('/profile')
}

const goToSettings = () => {
    router.push('/settings')
}
</script>

<template>
    <div class="min-h-screen bg-background">

<!-- sidebar -->
<sidebar />

        <!-- Main Content -->
        <div class="pl-64">
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b bg-background/95 px-8 backdrop-blur">
                <h2 class="text-lg font-semibold">{{ pageTitle }}</h2>

                <!-- Auth Menu -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="ghost" class="relative h-9 gap-2 px-2">
                            <Avatar class="h-7 w-7">
                                <AvatarImage :src="user.avatar" :alt="user.name" />
                                <AvatarFallback class="text-xs bg-primary text-primary-foreground">
                                    {{ user.initials }}
                                </AvatarFallback>
                            </Avatar>
                            <span class="text-sm font-medium hidden sm:inline-block">
                                {{ user.name }}
                            </span>
                            <ChevronDown class="h-4 w-4 text-muted-foreground" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="w-56" align="end">
                        <DropdownMenuLabel>
                            <div class="flex flex-col space-y-0.5">
                                <span class="text-sm font-medium">{{ user.name }}</span>
                                <span class="text-xs text-muted-foreground">{{ user.email }}</span>
                            </div>
                        </DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem @click="goToProfile">
                            <User class="mr-2 h-4 w-4" />
                            <span>Profil</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="goToSettings">
                            <Settings class="mr-2 h-4 w-4" />
                            <span>Pengaturan</span>
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem @click="handleLogout" class="text-destructive focus:text-destructive">
                            <LogOut class="mr-2 h-4 w-4" />
                            <span>Keluar</span>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </header>
            <main class="p-8">
                <router-view />
            </main>
        </div>
    </div>
</template>