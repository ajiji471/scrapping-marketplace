<!-- resources/js/views/SpkCalc.vue -->
<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { spkApi, productApi } from '@api/client'
import { cn } from '@lib/utils'
import Card from '@components/ui/card/Card.vue'
import CardHeader from '@components/ui/card/CardHeader.vue'
import CardTitle from '@components/ui/card/CardTitle.vue'
import CardContent from '@components/ui/card/CardContent.vue'
import Button from '@components/ui/button/Button.vue'
import Badge from '@components/ui/Badge.vue'
import Select from '@components/ui/Select.vue'
import Slider from '@components/ui/Slider.vue'
import DataTable from '@components/ui/DataTable.vue'

const method = ref('saw')
const categoryFilter = ref('')
const categories = ref([])
const criteria = ref([])
const weights = reactive({})
const results = ref({ saw: null, wp: null })
const isLoading = ref(false)

const totalWeight = computed(() => 
    Object.values(weights).reduce((a, b) => a + (b || 0), 0)
)

const sawColumns = [
    { 
        key: 'rank', 
        label: 'Rank', 
        width: 'w-16',
        align: 'center'
    },
    { key: 'product_name', label: 'Produk' },
    { 
        key: 'score', 
        label: 'Score',
        formatter: (val) => val.toFixed(4)
    },
    { 
        key: 'margin_percent', 
        label: 'Margin',
        formatter: (val) => `${val?.toFixed(1) || 0}%`
    },
    { 
        key: 'sold_count', 
        label: 'Terjual',
        formatter: (val) => Math.round(val || 0).toLocaleString('id-ID')
    },
    { 
        key: 'rating', 
        label: 'Rating',
        formatter: (val) => val?.toFixed(2) || 0
    },
]

const wpColumns = [
    { 
        key: 'rank', 
        label: 'Rank', 
        width: 'w-16',
        align: 'center'
    },
    { key: 'product_name', label: 'Produk' },
    { 
        key: 'score', 
        label: 'Score',
        formatter: (val) => val.toFixed(6)
    },
    { 
        key: 'margin_percent', 
        label: 'Margin',
        formatter: (val) => `${val?.toFixed(1) || 0}%`
    },
]

async function loadCriteria() {
    try {
        const res = await spkApi.getCriteria()
        criteria.value = res.criteria || []
        Object.keys(weights).forEach(k => delete weights[k])
        res.criteria.forEach(c => {
            weights[c.key] = c.default_weight
        })
    } catch (e) {
        console.error('Failed to load criteria:', e)
    }
}

async function loadCategories() {
    try {
        const res = await productApi.getAll({ per_page: 1000 })
        categories.value = [...new Set((res.data || []).map(p => p.category))].filter(Boolean)
    } catch (e) {
        console.error('Failed to load categories:', e)
    }
}

async function calculate() {
    if (Math.abs(totalWeight.value - 1) > 0.01) {
        alert('Total bobot harus = 1.00 (saat ini: ' + totalWeight.value.toFixed(2) + ')')
        return
    }

    isLoading.value = true
    results.value = { saw: null, wp: null }

    try {
        const payload = {
            method: method.value,
            weights: { ...weights },
        }
        if (categoryFilter.value) payload.category = categoryFilter.value
        
        const res = await spkApi.calculate(payload)
        const responseData = res.data || res
        
        console.log('SPK Response:', responseData)
        
        if (responseData.saw) {
            results.value.saw = responseData.saw.map(item => ({
                ...item,
                product_name: item.product?.name || 'Produk #' + item.product_id,
                margin_percent: item.details?.margin_percent,
                sold_count: item.details?.sold_count,
                rating: item.details?.rating,
            }))
        }
        
        if (responseData.wp) {
            results.value.wp = responseData.wp.map(item => ({
                ...item,
                product_name: item.product?.name || 'Produk #' + item.product_id,
                margin_percent: item.details?.margin_percent,
            }))
        }
    } catch (e) {
        console.error('SPK Calculation error:', e)
        alert('Gagal menghitung SPK: ' + (e.response?.data?.message || e.message))
    } finally {
        isLoading.value = false
    }
}

function resetWeights() {
    criteria.value.forEach(c => {
        weights[c.key] = c.default_weight
    })
}

function getRankClass(rank) {
    return cn(
        'inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold',
        rank === 1 ? 'bg-yellow-100 text-yellow-700' :
        rank === 2 ? 'bg-gray-200 text-gray-700' :
        rank === 3 ? 'bg-orange-100 text-orange-700' :
        'bg-muted text-muted-foreground'
    )
}

onMounted(() => {
    loadCriteria()
    loadCategories()
})
</script>

<template>
    <div class="space-y-6">
        <!-- Configuration -->
        <Card>
            <CardHeader>
                <CardTitle>Konfigurasi SPK</CardTitle>
            </CardHeader>
            <CardContent class="space-y-6">
                <div class="flex gap-4 flex-wrap items-end">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium">Metode:</label>
                        <Select v-model="method" class="w-40">
                            <option value="saw">SAW</option>
                            <option value="wp">Weighted Product</option>
                            <option value="both">Keduanya</option>
                        </Select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium">Kategori:</label>
                        <Select v-model="categoryFilter" class="w-40">
                            <option value="">Semua</option>
                            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                        </Select>
                    </div>
                    <Button @click="calculate" :disabled="isLoading">
                        {{ isLoading ? 'Menghitung...' : 'Hitung' }}
                    </Button>
                    <Button variant="outline" @click="resetWeights">Reset Bobot</Button>
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
                                <span class="text-xs text-muted-foreground uppercase">{{ c.type }}</span>
                            </div>
                            <Slider v-model="weights[c.key]" />
                            <div class="text-xs text-right text-muted-foreground">
                                {{ (weights[c.key] || 0).toFixed(2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Loading State -->
        <Card v-if="isLoading">
            <CardContent class="py-12 text-center">
                <p class="text-muted-foreground">Menghitung peringkat produk...</p>
            </CardContent>
        </Card>

        <!-- SAW Results -->
        <Card v-if="results.saw && results.saw.length > 0">
            <CardHeader class="flex flex-row items-center justify-between">
                <CardTitle>Hasil SAW (Simple Additive Weighting)</CardTitle>
                <Badge variant="secondary">{{ results.saw.length }} produk</Badge>
            </CardHeader>
            <CardContent>
                <DataTable
                    :columns="sawColumns"
                    :data="results.saw"
                    :internal-pagination="true"
                    :internal-per-page="5"
                    empty-text="Tidak ada hasil SAW"
                >
                    <template #cell-rank="{ value }">
                        <span :class="getRankClass(value)">
                            {{ value }}
                        </span>
                    </template>
                    <template #cell-rating="{ value }">
                        <div class="flex items-center gap-1">
                            <span>{{ value }}</span>
                            <span class="text-yellow-500">★</span>
                        </div>
                    </template>
                </DataTable>
            </CardContent>
        </Card>

        <!-- WP Results -->
        <Card v-if="results.wp && results.wp.length > 0">
            <CardHeader class="flex flex-row items-center justify-between">
                <CardTitle>Hasil Weighted Product</CardTitle>
                <Badge variant="secondary">{{ results.wp.length }} produk</Badge>
            </CardHeader>
            <CardContent>
                <DataTable
                    :columns="wpColumns"
                    :data="results.wp"
                    :internal-pagination="true"
                    :internal-per-page="5"
                    empty-text="Tidak ada hasil WP"
                >
                    <template #cell-rank="{ value }">
                        <span :class="getRankClass(value)">
                            {{ value }}
                        </span>
                    </template>
                </DataTable>
            </CardContent>
        </Card>
    </div>
</template>