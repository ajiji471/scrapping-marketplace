<!-- resources/js/views/Products.vue -->
<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { productApi } from '@api/client'
import Card from '@components/ui/card/Card.vue'
import CardContent from '@components/ui/card/CardContent.vue'
import Button from '@components/ui/button/Button.vue'
import Input from '@components/ui/Input.vue'
import Badge from '@components/ui/Badge.vue'
import Dialog from '@components/ui/Dialog.vue'
import DataTable from '@components/ui/DataTable.vue'

const router = useRouter()

const products = ref([])
const paginationMeta = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    per_page: 5,
    from: 0,
    to: 0,
})
const filters = reactive({ category: '', min_margin: '' })
const showAddModal = ref(false)
const showPriceModal = ref(false)
const editingProduct = ref(null)
const perPage = ref(5)
const loading = ref(false)

const newProduct = reactive({
    name: '', category: '', subcategory: '',
    price_china_cny: 0, price_indonesia_idr: 0,
    shipping_cost_idr: 0, tax_estimate_idr: 0, weight_kg: 0,
})

const priceUpdate = reactive({
    price_china_cny: null,
    price_indonesia_idr: null,
    note: '',
})

const columns = [
    { key: 'name', label: 'Nama', width: 'w-48' },
    { key: 'category', label: 'Kategori' },
    { 
        key: 'price_china_cny', 
        label: 'Harga China',
        formatter: (val) => `¥${val}`
    },
    { 
        key: 'price_indonesia_idr', 
        label: 'Harga Indo',
        formatter: (val) => `Rp${new Intl.NumberFormat('id-ID').format(val)}`
    },
    { 
        key: 'total_cost_from_china', 
        label: 'Total Cost',
        formatter: (val) => `Rp${new Intl.NumberFormat('id-ID').format(val)}`
    },
    { 
        key: 'margin_percent', 
        label: 'Margin',
        formatter: (val) => `${val}%`
    },
    { 
        key: 'marketplace_summary', 
        label: 'Terjual',
        formatter: (val) => val?.total_sold || 0
    },
    { 
        key: 'actions', 
        label: 'Aksi',
        align: 'center'
    },
]

const formatNumber = (num) => {
    if (!num && num !== 0) return '0'
    return new Intl.NumberFormat('id-ID').format(num)
}

const getMarginVariant = (margin) => {
    if (margin > 40) return 'success'
    if (margin > 20) return 'warning'
    return 'destructive'
}

async function loadProducts(page = 1) {
    loading.value = true
    try {
        const params = { 
            page,
            per_page: perPage.value,
        }
        if (filters.category) params.category = filters.category
        if (filters.min_margin) params.min_margin = filters.min_margin
        
        const res = await productApi.getAll(params)
        
        products.value = res.data || []
        paginationMeta.value = {
            current_page: res.current_page || 1,
            last_page: res.last_page || 1,
            total: res.total || 0,
            per_page: res.per_page || perPage.value,
            from: res.from || 0,
            to: res.to || 0,
        }
    } catch (e) {
        console.error('Error loading products:', e)
        alert('Gagal memuat produk: ' + (e.response?.data?.message || e.message))
    } finally {
        loading.value = false
    }
}

function handlePageChange(page) {
    loadProducts(page)
}

function handlePerPageChange(newPerPage) {
    perPage.value = newPerPage
    loadProducts(1)
}

function handleRowClick(item) {
    router.push(`/products/${item.id}`)
}

function editPrice(product) {
    editingProduct.value = product
    priceUpdate.price_china_cny = product.price_china_cny
    priceUpdate.price_indonesia_idr = product.price_indonesia_idr
    priceUpdate.note = ''
    showPriceModal.value = true
}

async function saveProduct() {
    try {
        await productApi.create({ ...newProduct })
        showAddModal.value = false
        Object.keys(newProduct).forEach(k => {
            newProduct[k] = ['price_china_cny', 'price_indonesia_idr', 'shipping_cost_idr', 'tax_estimate_idr', 'weight_kg'].includes(k) ? 0 : ''
        })
        loadProducts(1)
    } catch (e) {
        alert('Gagal menyimpan: ' + (e.response?.data?.message || e.message))
    }
}

async function savePriceUpdate() {
    try {
        await productApi.updatePrice(editingProduct.value.id, { ...priceUpdate })
        showPriceModal.value = false
        loadProducts(paginationMeta.value.current_page)
    } catch (e) {
        alert('Gagal update harga: ' + (e.response?.data?.message || e.message))
    }
}

onMounted(() => loadProducts(1))
</script>

<template>
    <div class="space-y-4">
        <!-- Filters -->
        <Card>
            <CardContent class="pt-6 flex gap-4 flex-wrap items-end">
                <div>
                    <label class="text-sm font-medium mb-1 block">Kategori</label>
                    <Input 
                        v-model="filters.category" 
                        placeholder="Filter kategori..."
                        class="w-48"
                    />
                </div>
                <div>
                    <label class="text-sm font-medium mb-1 block">Min Margin (%)</label>
                    <Input 
                        v-model.number="filters.min_margin" 
                        type="number"
                        placeholder="Min margin %"
                        class="w-32"
                    />
                </div>
                <Button @click="loadProducts(1)">Filter</Button>
                <Button variant="secondary" @click="showAddModal = true">+ Tambah Produk</Button>
            </CardContent>
        </Card>

        <!-- DataTable -->
        <Card>
            <DataTable
                :columns="columns"
                :data="products"
                :meta="paginationMeta"
                :loading="loading"
                :show-pagination="true"
                empty-text="Tidak ada data produk"
                @page-change="handlePageChange"
                @per-page-change="handlePerPageChange"
                @row-click="handleRowClick"
            >
                <template #cell-category="{ value }">
                    <Badge variant="secondary">{{ value }}</Badge>
                </template>

                <template #cell-margin_percent="{ value }">
                    <Badge :variant="getMarginVariant(value)">
                        {{ value }}%
                    </Badge>
                </template>

                <template #cell-actions="{ item }">
                    <Button 
                        variant="ghost" 
                        size="sm"
                        @click.stop="editPrice(item)"
                    >
                        Update Harga
                    </Button>
                </template>
            </DataTable>
        </Card>

        <!-- Add Modal -->
        <Dialog v-model:open="showAddModal">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Tambah Produk Baru</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium">Nama Produk</label>
                        <Input v-model="newProduct.name" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Kategori</label>
                            <Input v-model="newProduct.category" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Subkategori</label>
                            <Input v-model="newProduct.subcategory" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Harga China (CNY)</label>
                            <Input v-model.number="newProduct.price_china_cny" type="number" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Harga Indonesia (IDR)</label>
                            <Input v-model.number="newProduct.price_indonesia_idr" type="number" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Ongkir (IDR)</label>
                            <Input v-model.number="newProduct.shipping_cost_idr" type="number" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Pajak Estimasi (IDR)</label>
                            <Input v-model.number="newProduct.tax_estimate_idr" type="number" />
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Berat (kg)</label>
                        <Input v-model.number="newProduct.weight_kg" type="number" step="0.1" />
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <Button variant="outline" @click="showAddModal = false">Batal</Button>
                    <Button @click="saveProduct">Simpan</Button>
                </div>
            </div>
        </Dialog>

        <!-- Update Price Modal -->
        <Dialog v-model:open="showPriceModal">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Update Harga: {{ editingProduct?.name }}</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium">Harga China Baru (CNY)</label>
                        <Input v-model.number="priceUpdate.price_china_cny" type="number" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Harga Indonesia Baru (IDR)</label>
                        <Input v-model.number="priceUpdate.price_indonesia_idr" type="number" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Catatan</label>
                        <Input v-model="priceUpdate.note" placeholder="e.g. kenaikan harga komponen" />
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <Button variant="outline" @click="showPriceModal = false">Batal</Button>
                    <Button @click="savePriceUpdate">Update</Button>
                </div>
            </div>
        </Dialog>
    </div>
</template>