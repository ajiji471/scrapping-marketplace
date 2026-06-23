<!-- resources/js/components/ui/Tabs.vue -->
<template>
    <div>
        <div class="flex border-b">
            <button
                v-for="tab in tabs"
                :key="tab.value"
                @click="activeTab = tab.value"
                :class="cn(
                    'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
                    activeTab === tab.value 
                        ? 'border-primary text-primary' 
                        : 'border-transparent text-muted-foreground hover:text-foreground'
                )"
            >
                {{ tab.label }}
            </button>
        </div>
        <div class="mt-4">
            <slot :activeTab="activeTab" />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { cn } from '@lib/utils'

defineProps({
    tabs: Array,
    defaultTab: String,
})

const activeTab = ref(props.defaultTab)
</script>