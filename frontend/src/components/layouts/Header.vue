<template>
  <header class="text-white p-4 bg-slate-700">
    <div class="flex items-center justify-between max-w-5xl mx-auto">
      <nav class="mt-2">
        <router-link to="/" class="text-lg font-bold">Blog App</router-link>
      </nav>
      <nav class="flex gap-4">
        <template v-if="isAuthenticated">
          <router-link to="/posts" class="hover:underline">Posts</router-link>
          <button @click="handleLogout" class="text-red-500 cursor-pointer hover:underline">
            Logout
          </button>
        </template>
        <template v-else>
          <router-link to="/login" class="hover:underline">Login</router-link>
          <router-link to="/register" class="hover:underline">Register</router-link>
        </template>
      </nav>
    </div>
  </header>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'

const authStore = useAuthStore()
const router = useRouter()

// const isAuthenticated = authStore.isAuthenticated
const { isAuthenticated } = storeToRefs(authStore)

const handleLogout = () => {
  authStore.logout()
  // Optionally redirect to home or login page
  router.push('/login')
}
</script>
