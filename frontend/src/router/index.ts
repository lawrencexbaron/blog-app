import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/pages/Home.vue'
import Post from '@/pages/Posts.vue'
import Login from '@/pages/Login.vue'
import Register from '@/pages/Register.vue'
import { useAuthStore } from '@/stores/auth'

const routes = [
  { path: '/', redirect: '/posts' },
  { path: '/posts', component: Post, meta: { requiresAuth: true }, name: 'post' },
  { path: '/login', component: Login, name: 'login' },
  { path: '/register', component: Register, name: 'register' },
]

export const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.token) {
    return next('/login')
  }

  if (to.name === 'login' && auth.token) {
    return next('/posts/1')
  }

  next()
})
