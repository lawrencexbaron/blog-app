<template>
  <button
    :type="props.type"
    :disabled="props.disabled || loading"
    :loading="props.loading"
    class="bg-slate-600 text-white w-full py-2 px-4 rounded cursor-pointer hover:bg-slate-700"
    @click="$emit('click')"
  >
    <slot />
  </button>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

const props = defineProps<{
  type: 'button' | 'submit' | 'reset'
  loading?: boolean
  disabled?: boolean
}>()

const emit = defineEmits<{
  (e: 'click'): void
}>()
</script>
