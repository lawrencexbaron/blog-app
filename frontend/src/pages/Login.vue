<template>
  <div class="flex flex-col items-center justify-center max-w-5xl w-full">
    <form
      @submit.prevent="handleLogin"
      class="px-6 py-8 border max-w-5xl w-full rounded border-gray-300"
    >
      <h1>🔑 Login Page</h1>
      <small class="my-4 text-red-500" v-if="errorMessage">
        {{ errorMessage }}
      </small>
      <div class="h-auto text-white">Please enter your credentials to log in.</div>
      <div>
        <TextInput type="email" class="rounded" id="email" label="Email" v-model="email" required />
      </div>
      <div class="mt-4">
        <TextInput
          type="password"
          class="rounded"
          id="password"
          label="Password"
          v-model="password"
          required
        />
      </div>
      <Button
        type="submit"
        :disabled="loading"
        :loading="loading"
        class="bg-blue-500 text-white mt-2 px-4 py-2 rounded"
      >
        <template #default>
          {{ loading ? 'Loading...' : 'Login' }}
        </template>
      </Button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { login } from '@/services/authService'
import TextInput from '@/components/common/TextInput.vue'
import Button from '@/components/common/Button.vue'
import { storeToRefs } from 'pinia'

const router = useRouter()
const email = ref('')
const password = ref('')
const loading = ref(false)
const errorMessage = ref('')
const auth = useAuthStore()

const handleLogin = async () => {
  try {
    loading.value = true
    const { data } = await login(email.value, password.value)

    auth.setUser(data.data.user)
    auth.setToken(data.data.token)

    router.push('/')
  } catch (error: any) {
    console.log('Login error:', error.response?.data.error || error)
    errorMessage.value = error.response?.data.error || 'Login failed. Please try again.'
    console.error('Login failed:', error)
  } finally {
    loading.value = false
  }
}
</script>
