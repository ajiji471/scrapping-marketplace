<!-- resources/js/components/ui/Slider.vue -->
<template>
    <div class="flex items-center gap-3">
        <input
            type="range"
            :min="min"
            :max="max"
            :step="step"
            :value="modelValue"
            @input="$emit('update:modelValue', Number($event.target.value))"
            class="flex-1 h-2 bg-secondary rounded-lg appearance-none cursor-pointer accent-primary"
        />
        <span class="text-sm w-12 text-right tabular-nums">{{ formattedValue }}</span>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: Number,
    min: { type: Number, default: 0 },
    max: { type: Number, default: 1 },
    step: { type: Number, default: 0.05 },
})

defineEmits(['update:modelValue'])

const formattedValue = computed(() => props.modelValue?.toFixed(2) ?? '0.00')
</script>