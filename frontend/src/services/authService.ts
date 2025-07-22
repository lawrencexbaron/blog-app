import api from '@/services/api'

export function login(email: string, password: string) {
  return api.post('/auth/login', {
    email,
    password,
  })
}

export function register({
  email,
  name,
  password,
  confirmPassword,
}: {
  email: string
  name: string
  password: string
  confirmPassword: string
}) {
  return api.post('/auth/register', {
    email,
    name,
    password,
    password_confirmation: confirmPassword,
  })
}
