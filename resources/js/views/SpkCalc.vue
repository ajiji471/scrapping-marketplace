<!-- resources/js/views/SpkCalc.vue -->
<template>
    <div class="space-y-6">
        <!-- Configuration -->
        <Card>
            <CardHeader>
                <CardTitle>Konfigurasi SPK</CardTitle>
            </CardHeader>
            <CardContent class="space-y-6">
                <div class="flex gap-4 flex-wrap">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium">Metode:</label>
                        <Select v-model="method">
                            <option value="saw">SAW</option>
                            <option value="wp">Weighted Product</option>
                            <option value="both">Keduanya</option>
                        </Select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium">Kategori:</label>
                        <Select v-model="categoryFilter">
                            <option value="">Semua</option>
                            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                        </Select>
                    </div>
                    <Button @click="calculate">Hitung</Button>
                </div>

                <div class="border-t pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium">Bobot Kriteria</h4>
                        <Badge :variant="Math.abs(totalWeight - 1) < 0.01 ? 'success' : 'destructive'">
                            Total: {{ totalWeight.toFixed(2) }}
                        </Badge>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="c in criteria" :key="c.key" class="space-y-2">
                            <div class="flex justify-between">
                                <label class="text-sm">{{ c.label }}</label>
                                <span class="text-xs text-muted-foreground">{{ c.type }}</span>
                            </div>
                            <Slider v-model="weights[c.key]" />
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- SAW Results -->
        <Card v-if="results.saw">
            <CardHeader class="flex flex-row items-center justify-between">
                <CardTitle>Hasil SAW</CardTitle>
                <Badge variant="secondary">{{ results.saw.length }} produk</Badge>
            </CardHeader>
            <CardContent>
                <Table>
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="h-12 px-4 text-left font-medium w-16">Rank</th>
                            <th class="h-12 px-4 text-left font-medium">Produk</th>
                            <th class="h-12 px-4 text-left font-medium">Score</th>
                            <th class="h-12 px-4 text-left font-medium">Margin</th>
                            <th class="h-12 px-4 text-left font-medium">Terjual</th>
                            <th class="h-12 px-4 text-left font-medium">Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in results.saw" :key="item.product_id" class="border-b hover:bg-muted/50">
                            <td class="p-4">
                                <span :class="cn(
                                    'inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold',
                                    item.rank === 1 ? 'bg-yellow-100 text-yellow-700' :
                                    item.rank === 2 ? 'bg-gray-200 text-gray-700' :
                                    item.rank === 3 ? 'bg-orange-100 text-orange-700' :
                                    'bg-muted text-muted-foreground'
                                )">
                                    {{ item.rank }}
                                </span>
                            </td>
                            <td class="p-4 font-medium">{{ item.product?.name }}</td>
                            <td class="p-4 font-mono">{{ item.score.toFixed(4) }}</td>
                            <td class="p-4">{{ item.details?.margin_percent?.toFixed(1) }}%</td>
                            <td class="p-4">{{ Math.round(item.details?.sold_count || 0) }}</td>
                            <td class="p-4">{{ item.details?.rating?.toFixed(2) }}</td>
                        </tr>
                    </tbody>
                </Table>
            </CardContent>
        </Card>

        <!-- WP Results -->
        <Card v-if="results.wp">
            <CardHeader class="flex flex-row items-center justify-between">
                <CardTitle>Hasil Weighted Product</CardTitle>
                <Badge variant="secondary">{{ results.wp.length }} produk</Badge>
            </CardHeader>
            <CardContent>
                <Table>
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="h-12 px-4 text-left font-medium w-16">Rank</th>
                            <th class="h-12 px-4 text-left font-medium">Produk</th>
                            <th class="h-12 px-4 text-left font-medium">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in results.wp" :key="item.product_id" class="border-b hover:bg-muted/50">
                            <td class="p-4 font-bold">{{ item.rank }}</td>
                            <td class="p-4 font-medium">{{ item.product?.name }}</td>
                            <td class="p-4 font-mono">{{ item.score.toFixed(6) }}</td>
                        </tr>
                    </tbody>
                </Table>
            </CardContent>
        </Card>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { spkApi, productApi } from '@api/client'
import { cn } from '@lib/utils'
import Card from '@components/ui/Card.vue'
import CardHeader from '@components/ui/CardHeader.vue'
import CardTitle from '@components/ui/CardTitle.vue'
import CardContent from '@components/ui/CardContent.vue'
import Button from '@components/ui/Button.vue'
import Badge from '@components/ui/Badge.vue'
import Select from '@components/ui/Select.vue'
import Slider from '@components/ui/Slider.vue'
import Table from '@components/ui/Table.vue'

const method = ref('saw')
const categoryFilter = ref('')
const categories = ref([])
const criteria = ref([])
const weights = reactive({})
const results = ref({ saw: null, wp: null })

const totalWeight = computed(() => 
    Object.values(weights).reduce((a, b) => a + (b || 0), 0)
)

async function loadCriteria() {
    try {
        const res = await spkApi.getCriteria()
        criteria.value = res.criteria
        res.criteria.forEach(c => {
            weights[c.key] = c.default_weight
        })
    } catch (e) {
        console.error(e)
    }
}

async function loadCategories() {
    try {
        const res = await productApi.getAll({ per_page: 1000 })
        categories.value = [...new Set((res.data || []).map(p => p.category))].filter(Boolean)
    } catch (e) {
        console.error(e)
    }
}

async function calculate() {
    if (Math.abs(totalWeight.value - 1) > 0.01) {
        alert('Total bobot harus = 1.00')
        return
    }

    try {
        const payload = {
            method: method.value,
            weights: { ...weights },
        }
        if (categoryFilter.value) payload.category = categoryFilter.value
        
        const res = await spkApi.calculate(payload)
        results.value = res.data
    } catch (e) {
        alert('Gagal menghitung SPK: ' + e.message)
    }
}

onMounted(() => {
    loadCriteria()
    loadCategories()
})
</script>