<!-- resources/js/components/ui/Select.vue -->
<template>
    <select
        :class="cn('flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring', props.class)"
        v-bind="$attrs"
    >
        <slot />
    </select>
</template>

<script setup>
import { cn } from '@lib/utils'
const props = defineProps({ class: { type: String, default: '' } })
</script>