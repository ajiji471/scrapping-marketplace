<!-- resources/js/components/ui/Select.vue -->
<script setup>
import { cn } from '@lib/utils'
import { ChevronDown } from 'lucide-vue-next'

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    class: { type: String, default: '' }
})

const emit = defineEmits(['update:modelValue', 'change'])

function handleChange(event) {
    const value = event.target.value
    emit('update:modelValue', value)
    emit('change', value)
}
</script>
<template>
    <div class="relative">
        <select
            :value="modelValue"
            @change="handleChange"
            :class="cn(
                'flex h-9 w-full appearance-none rounded-md border border-input bg-transparent px-3 py-1 pr-8 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50',
                props.class
            )"
        >
            <slot />
        </select>
        <ChevronDown class="absolute right-2 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" />
    </div>
</template>