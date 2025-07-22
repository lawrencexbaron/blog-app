<template>
  <div class="flex flex-col">
    <label :for="id" class="font-medium">{{ label }}</label>
    <div class="relative">
      <input
        :id="id"
        v-model="inputValue"
        @input="onInput"
        :type="type"
        class="border border-gray-300 p-2 rounded mt-1 w-full"
        :disabled="loading"
      />
      <span v-if="loading" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
          <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
          ></circle>
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
          ></path>
        </svg>
      </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits, watch, ref } from 'vue'

const props = defineProps<{
  modelValue: string
  label: string
  id: string
  type: 'text' | 'email' | 'password'
  loading?: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const inputValue = ref(props.modelValue)

watch(
  () => props.modelValue,
  (val) => {
    inputValue.value = val
  },
)

function onInput() {
  emit('update:modelValue', inputValue.value)
}

const loading = props.loading ?? false
</script>
