<!-- resources/js/components/ui/DataTable.vue -->
<script setup>
import { ref, computed } from 'vue'
import { cn } from '@lib/utils'
import Pagination from './Pagination.vue'

const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
        default: () => []
    },
    meta: {
        type: Object,
        default: () => ({
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
            from: 0,
            to: 0,
        })
    },
    loading: {
        type: Boolean,
        default: false
    },
    emptyText: {
        type: String,
        default: 'Tidak ada data'
    },
    showPagination: {
        type: Boolean,
        default: true
    },
    internalPagination: {
        type: Boolean,
        default: false
    },
    internalPerPage: {
        type: Number,
        default: 5
    },
    rowClass: {
        type: [String, Function],
        default: ''
    },
    onRowClick: {
        type: Function,
        default: null
    }
})

const emit = defineEmits(['page-change', 'per-page-change', 'row-click'])

const internalPage = ref(1)

const hasData = computed(() => props.data && props.data.length > 0)

const internalMeta = computed(() => {
    if (!props.internalPagination) return props.meta
    
    const total = props.data.length
    const perPage = props.internalPerPage
    const lastPage = Math.ceil(total / perPage) || 1
    
    return {
        current_page: Math.min(internalPage.value, lastPage),
        last_page: lastPage,
        total: total,
        per_page: perPage,
        from: total > 0 ? (internalPage.value - 1) * perPage + 1 : 0,
        to: Math.min(internalPage.value * perPage, total),
    }
})

const displayData = computed(() => {
    if (!props.internalPagination) return props.data
    
    const start = (internalPage.value - 1) * props.internalPerPage
    const end = start + props.internalPerPage
    return props.data.slice(start, end)
})

const activeMeta = computed(() => {
    return props.internalPagination ? internalMeta.value : props.meta
})

function getCellValue(item, column) {
    const rawValue = item[column.key]
    
    // PRIORITAS: gunakan formatter kalau ada
    if (column.formatter && typeof column.formatter === 'function') {
        return column.formatter(rawValue, item)
    }
    
    return rawValue
}

function getCellClass(column) {
    const align = column.align || 'left'
    return cn(
        'p-4',
        align === 'center' && 'text-center',
        align === 'right' && 'text-right',
        column.class
    )
}

function getHeaderClass(column) {
    const align = column.align || 'left'
    return cn(
        'h-12 px-4 text-left font-medium',
        align === 'center' && 'text-center',
        align === 'right' && 'text-right',
        column.width,
        column.headerClass
    )
}

function handleRowClick(item) {
    if (props.onRowClick) {
        props.onRowClick(item)
    }
    emit('row-click', item)
}

function getRowClass(item, index) {
    const base = 'border-b hover:bg-muted/50 transition-colors'
    if (typeof props.rowClass === 'function') {
        return cn(base, props.rowClass(item, index))
    }
    return cn(base, props.rowClass)
}

function handlePageChange(page) {
    if (props.internalPagination) {
        internalPage.value = page
    } else {
        emit('page-change', page)
    }
}

function handlePerPageChange(perPage) {
    if (props.internalPagination) {
        internalPage.value = 1
    }
    emit('per-page-change', perPage)
}
</script>

<template>
    <div class="w-full">
        <!-- Table -->
        <div class="w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="border-b bg-muted/50">
                    <tr>
                        <th 
                            v-for="column in columns" 
                            :key="column.key"
                            :class="getHeaderClass(column)"
                        >
                            {{ column.label }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loading -->
                    <tr v-if="loading">
                        <td :colspan="columns.length" class="p-8 text-center">
                            <div class="flex items-center justify-center gap-2 text-muted-foreground">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memuat data...</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Empty -->
                    <tr v-else-if="!hasData">
                        <td :colspan="columns.length" class="p-8 text-center text-muted-foreground">
                            {{ emptyText }}
                        </td>
                    </tr>

                    <!-- Data -->
                    <tr 
                        v-else
                        v-for="(item, index) in displayData" 
                        :key="item.id || item.product_id || index"
                        :class="getRowClass(item, index)"
                        @click="handleRowClick(item)"
                    >
                        <td 
                            v-for="column in columns" 
                            :key="column.key"
                            :class="getCellClass(column)"
                        >
                            <slot 
                                :name="`cell-${column.key}`" 
                                :value="item[column.key]" 
                                :item="item"
                                :index="index"
                            >
                                {{ getCellValue(item, column) }}
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <Pagination 
            v-if="showPagination && activeMeta.last_page > 1"
            :meta="activeMeta"
            @page-change="handlePageChange"
            @per-page-change="handlePerPageChange"
        />
    </div>
</template>