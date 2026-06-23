<!-- resources/js/views/ProductDetail.vue -->
<template>
    <div v-if="product" class="space-y-6">
        <div class="flex items-center gap-4">
            <Button variant="outline" size="sm" @click="$router.back()">← Kembali</Button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info -->
            <Card class="lg:col-span-2">
                <CardHeader>
                    <CardTitle>{{ product.name }}</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-muted-foreground">Kategori</label>
                            <p class="font-medium">{{ product.category }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">Subkategori</label>
                            <p class="font-medium">{{ product.subcategory || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">Harga China</label>
                            <p class="font-medium">¥{{ product.price_china_cny }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">Harga Indonesia</label>
                            <p class="font-medium">Rp{{ formatNumber(product.price_indonesia_idr) }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">Total Cost</label>
                            <p class="font-medium">Rp{{ formatNumber(product.total_cost_from_china) }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">Margin</label>
                            <p class="font-medium" :class="product.margin_percent > 30 ? 'text-green-600' : ''">
                                {{ product.margin_percent }}%
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Marketplace Stats -->
            <Card>
                <CardHeader>
                    <CardTitle>Marketplace</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-3">
                        <div v-for="mp in product.marketplace_data" :key="mp.id" class="flex justify-between items-center p-3 bg-muted rounded-lg">
                            <span class="font-medium capitalize">{{ mp.marketplace }}</span>
                            <div class="text-right">
                                <p class="text-sm">Rp{{ formatNumber(mp.current_price) }}</p>
                                <p class="text-xs text-muted-foreground">{{ mp.sold_count }} terjual</p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Price History -->
        <Card>
            <CardHeader>
                <CardTitle>Riwayat Harga</CardTitle>
            </CardHeader>
            <CardContent>
                <Table>
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="h-12 px-4 text-left font-medium">Tanggal</th>
                            <th class="h-12 px-4 text-left font-medium">Sumber</th>
                            <th class="h-12 px-4 text-left font-medium">Harga</th>
                            <th class="h-12 px-4 text-left font-medium">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="h in product.price_history" :key="h.id" class="border-b hover:bg-muted/50">
                            <td class="p-4">{{ formatDate(h.recorded_at) }}</td>
                            <td class="p-4">
                                <Badge :variant="h.source === 'china' ? 'default' : 'secondary'">
                                    {{ h.source }}
                                </Badge>
                            </td>
                            <td class="p-4">{{ h.currency }} {{ formatNumber(h.price) }}</td>
                            <td class="p-4 text-muted-foreground">{{ h.note || '-' }}</td>
                        </tr>
                    </tbody>
                </Table>
            </CardContent>
        </Card>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { productApi } from '@api/client'
import Card from '@components/ui/Card.vue'
import CardHeader from '@components/ui/CardHeader.vue'
import CardTitle from '@components/ui/CardTitle.vue'
import CardContent from '@components/ui/CardContent.vue'
import Button from '@components/ui/Button.vue'
import Badge from '@components/ui/Badge.vue'
import Table from '@components/ui/Table.vue'

const route = useRoute()
const product = ref(null)

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num)
const formatDate = (date) => new Date(date).toLocaleDateString('id-ID')

onMounted(async () => {
    try {
        product.value = await productApi.getById(route.params.id)
    } catch (e) {
        console.error(e)
    }
})
</script>