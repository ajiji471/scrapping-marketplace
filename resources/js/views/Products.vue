<!-- resources/js/views/Products.vue -->
<template>
    <div class="space-y-4">
        <!-- Filters -->
        <Card>
            <CardContent class="pt-6 flex gap-4 flex-wrap">
                <Input 
                    v-model="filters.category" 
                    placeholder="Filter kategori..."
                    class="w-48"
                />
                <Input 
                    v-model.number="filters.min_margin" 
                    type="number"
                    placeholder="Min margin %"
                    class="w-32"
                />
                <Button @click="loadProducts">Filter</Button>
                <Button variant="secondary" @click="showAddModal = true">+ Tambah Produk</Button>
            </CardContent>
        </Card>

        <!-- Table -->
        <Card>
            <Table>
                <thead class="border-b bg-muted/50">
                    <tr>
                        <th class="h-12 px-4 text-left font-medium">Nama</th>
                        <th class="h-12 px-4 text-left font-medium">Kategori</th>
                        <th class="h-12 px-4 text-left font-medium">Harga China</th>
                        <th class="h-12 px-4 text-left font-medium">Harga Indo</th>
                        <th class="h-12 px-4 text-left font-medium">Total Cost</th>
                        <th class="h-12 px-4 text-left font-medium">Margin</th>
                        <th class="h-12 px-4 text-left font-medium">Terjual</th>
                        <th class="h-12 px-4 text-left font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="p in products" 
                        :key="p.id" 
                        class="border-b hover:bg-muted/50 cursor-pointer"
                        @click="$router.push(`/products/${p.id}`)"
                    >
                        <td class="p-4 font-medium">{{ p.name }}</td>
                        <td class="p-4">
                            <Badge variant="secondary">{{ p.category }}</Badge>
                        </td>
                        <td class="p-4">¥{{ p.price_china_cny }}</td>
                        <td class="p-4">Rp{{ formatNumber(p.price_indonesia_idr) }}</td>
                        <td class="p-4">Rp{{ formatNumber(p.total_cost_from_china) }}</td>
                        <td class="p-4">
                            <Badge :variant="getMarginVariant(p.margin_percent)">
                                {{ p.margin_percent }}%
                            </Badge>
                        </td>
                        <td class="p-4">{{ p.marketplace_summary?.total_sold || 0 }}</td>
                        <td class="p-4">
                            <Button 
                                variant="ghost" 
                                size="sm"
                                @click.stop="editPrice(p)"
                            >
                                Update Harga
                            </Button>
                        </td>
                    </tr>
                </tbody>
            </Table>
            
            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t">
                <span class="text-sm text-muted-foreground">
                    Total {{ pagination.total }} produk
                </span>
                <div class="flex gap-1">
                    <Button
                        v-for="page in pagination.last_page"
                        :key="page"
                        :variant="pagination.current_page === page ? 'default' : 'outline'"
                        size="sm"
                        @click="goToPage(page)"
                    >
                        {{ page }}
                    </Button>
                </div>
            </div>
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

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { productApi } from '@api/client'
import Card from '@components/ui/Card.vue'
import CardContent from '@components/ui/CardContent.vue'
import Button from '@components/ui/Button.vue'
import Input from '@components/ui/Input.vue'
import Badge from '@components/ui/Badge.vue'
import Table from '@components/ui/Table.vue'
import Dialog from '@components/ui/Dialog.vue'

const products = ref([])
const pagination = ref({ current_page: 1, last_page: 1, total: 0 })
const filters = reactive({ category: '', min_margin: '' })
const showAddModal = ref(false)
const showPriceModal = ref(false)
const editingProduct = ref(null)

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

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num)

const getMarginVariant = (margin) => {
    if (margin > 40) return 'success'
    if (margin > 20) return 'warning'
    return 'destructive'
}

async function loadProducts(page = 1) {
    try {
        const params = { page, ...filters }
        const res = await productApi.getAll(params)
        products.value = res.data || []
        pagination.value = {
            current_page: res.current_page,
            last_page: res.last_page,
            total: res.total,
        }
    } catch (e) {
        alert('Gagal memuat produk: ' + e.message)
    }
}

function goToPage(page) {
    loadProducts(page)
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
        loadProducts()
    } catch (e) {
        alert('Gagal menyimpan: ' + e.message)
    }
}

async function savePriceUpdate() {
    try {
        await productApi.updatePrice(editingProduct.value.id, { ...priceUpdate })
        showPriceModal.value = false
        loadProducts(pagination.value.current_page)
    } catch (e) {
        alert('Gagal update harga: ' + e.message)
    }
}

onMounted(() => loadProducts())
</script>