<template>
  <transition name="modal" appear>
    <div
      v-if="show"
      class="fixed inset-0 z-50 flex items-center justify-center bg-gray-50 bg-opacity-10"
      @click.self="emit('close')"
    >
      <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <button
          class="absolute top-2 right-2 text-gray-400 hover:text-gray-600"
          @click="emit('close')"
        >
          ✕
        </button>
        <slot />
      </div>
    </div>
  </transition>
</template>

<script setup lang="ts">
defineProps<{
  show: boolean
}>()

const emit = defineEmits<{
  (e: 'close'): void
}>()
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>
