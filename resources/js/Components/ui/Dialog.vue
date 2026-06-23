<!-- resources/js/components/ui/Dialog.vue -->
<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="open" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" @click.self="close">
                <div class="bg-background rounded-lg shadow-lg max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <slot />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
defineProps({ open: Boolean })
const emit = defineEmits(['update:open'])

const close = () => emit('update:open', false)
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>