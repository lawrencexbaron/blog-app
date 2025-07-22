<template>
  <div class="flex flex-col items-center justify-center max-w-5xl w-full">
    <form
      @submit.prevent="handleRegister"
      class="px-6 py-8 border max-w-5xl w-full rounded border-gray-300"
    >
      <h1>🔑 Register Page</h1>
      <small class="my-4 text-red-500" v-if="errorMessage">
        {{ errorMessage }}
      </small>
      <div class="h-auto text-white">Please enter your credentials to register.</div>
      <div>
        <TextInput type="email" class="rounded" id="email" label="Email" v-model="email" required />
      </div>
      <div class="mt-4">
        <TextInput type="text" class="rounded" id="name" label="Name" v-model="name" required />
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
      <div class="mt-4">
        <TextInput
          type="password"
          class="rounded"
          id="confirmPassword"
          label="Confirm Password"
          v-model="confirmPassword"
          required
        />
      </div>
      <Button
        type="submit"
        class="text-white px-4 mt-2 py-2 rounded"
        :disabled="loading"
        :loading="loading"
      >
        <template #default>
          {{ loading ? 'Loading...' : 'Register' }}
        </template>
      </Button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { register } from '@/services/authService'
import TextInput from '@/components/common/TextInput.vue'
import Button from '@/components/common/Button.vue'

const router = useRouter()
const email = ref('')
const name = ref('')
const password = ref('')
const confirmPassword = ref('')
const loading = ref(false)
const errorMessage = ref('')

const auth = useAuthStore()

const handleRegister = async () => {
  if (password.value !== confirmPassword.value) {
    console.error('Passwords do not match')
    errorMessage.value = 'Passwords do not match'
    return
  }

  try {
    loading.value = true
    const { data } = await register({
      email: email.value,
      name: name.value,
      password: password.value,
      confirmPassword: confirmPassword.value,
    })
    auth.setUser(data.data.user)
    auth.setToken(data.data.token)

    router.push('/')
  } catch (error: any) {
    console.log('Registration error:', error.response?.data.error || error)
    errorMessage.value = error.response?.data.error || 'Registration failed. Please try again.'
    console.error('Registration failed:', error)
  } finally {
    loading.value = false
  }
}
</script>
