import { defineStore } from 'pinia'
import { ref, computed, watchEffect } from 'vue'
import { router } from '@/router'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<any>(null)

  const isAuthenticated = computed(() => !!token.value)

  function setToken(newToken: string) {
    token.value = newToken
    localStorage.setItem('token', newToken)
  }

  function setUser(newUser: any) {
    user.value = newUser
  }

  function clearToken() {
    token.value = null
    localStorage.removeItem('token')
  }

  function logout() {
    clearToken()
    user.value = null
    router.push('/login')
  }

  watchEffect(() => {
    const localToken = localStorage.getItem('token')
    if (localToken !== token.value) {
      token.value = localToken
    }
  })

  return {
    token,
    user,
    isAuthenticated,
    setToken,
    setUser,
    clearToken,
    logout,
  }
})
