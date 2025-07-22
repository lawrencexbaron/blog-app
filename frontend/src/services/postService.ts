import api from '@/services/api'

export function createPost(title: string, body: string, tagsInput: string) {
  return api.post('/posts', {
    title,
    body,
    tagsInput,
  })
}

export function updatePost(id: string, title: string, body: string, tagsInput: string) {
  return api.put(`/posts/${id}`, {
    title,
    body,
    tagsInput,
  })
}

export function deletePost(id: string) {
  return api.delete(`/posts/${id}`)
}
export function fetchPosts(page: number = 1) {
  return api.get('/posts', { params: { page } })
}

export function fetchPostById(id: string) {
  return api.get(`/posts/${id}`)
}

export function searchPosts(query: string) {
  return api.post(`/posts/search`, { query })
}
