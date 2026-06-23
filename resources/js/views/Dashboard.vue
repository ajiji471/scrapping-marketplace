<!-- resources/js/views/Dashboard.vue -->
<template>
    <div class="space-y-6">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <Card v-for="stat in stats" :key="stat.label">
                <CardHeader class="pb-2">
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        {{ stat.label }}
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ stat.value }}</div>
                </CardContent>
            </Card>
        </div>

        <!-- Top Margin -->
        <Card>
            <CardHeader>
                <CardTitle>Top 5 Margin Tertinggi</CardTitle>
            </CardHeader>
            <CardContent>
                <Table>
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="h-12 px-4 text-left font-medium">Produk</th>
                            <th class="h-12 px-4 text-left font-medium">Harga China</th>
                            <th class="h-12 px-4 text-left font-medium">Harga Indo</th>
                            <th class="h-12 px-4 text-left font-medium">Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in topProducts" :key="p.id" class="border-b hover:bg-muted/50">
                            <td class="p-4 font-medium">{{ p.name }}</td>
                            <td class="p-4">¥{{ p.price_china_cny }}</td>
                            <td class="p-4">Rp{{ formatNumber(p.price_indonesia_idr) }}</td>
                            <td class="p-4">
                                <Badge :variant="p.margin_percent > 30 ? 'success' : 'default'">
                                    {{ p.margin_percent }}%
                                </Badge>
                            </td>
                        </tr>
                    </tbody>
                </Table>
            </CardContent>
        </Card>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { productApi } from '@api/client'
import Card from '@components/ui/card/Card.vue'
import CardHeader from '@components/ui/card/CardHeader.vue'
import CardTitle from '@components/ui/card/CardTitle.vue'
import CardContent from '@components/ui/card/CardContent.vue'
import Badge from '@components/ui/Badge.vue'
import Table from '@components/ui/Table.vue'

const stats = ref([
    { label: 'Total Produk', value: '0' },
    { label: 'Rata-rata Margin', value: '0%' },
    { label: 'Produk >30% Margin', value: '0' },
    { label: 'Kategori', value: '0' },
])

const topProducts = ref([])

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num)

onMounted(async () => {
    try {
        const res = await productApi.getAll({ per_page: 100 })
        const products = res.data || []
        
        stats.value[0].value = products.length
        stats.value[1].value = (products.reduce((a, b) => a + (b.margin_percent || 0), 0) / products.length).toFixed(1) + '%'
        stats.value[2].value = products.filter(p => p.margin_percent > 30).length
        stats.value[3].value = [...new Set(products.map(p => p.category))].filter(Boolean).length
        
        topProducts.value = [...products]
            .sort((a, b) => b.margin_percent - a.margin_percent)
            .slice(0, 5)
    } catch (e) {
        console.error(e)
    }
})
</script>