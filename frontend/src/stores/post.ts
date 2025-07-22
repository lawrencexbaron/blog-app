import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import {
  fetchPosts as fetchPostsService,
  fetchPostById as fetchPostByIdService,
  searchPosts as searchPostsService,
  deletePost as deletePostService,
  createPost as createPostService,
  updatePost as updatePostService,
} from '@/services/postService'

interface Post {
  id: string
  title: string
  body: string
  tags: { id: string; name: string }[]
}

export const usePostStore = defineStore('post', () => {
  const posts = ref<Post[]>([])
  const currentPost = ref<Post | null>(null)
  const loading = ref(false)
  const searchQuery = ref('')
  const currentPage = ref(1)
  const lastPage = ref(1)
  const nextPage = ref('')
  const previousPage = ref('')
  const title = ref('')
  const body = ref('')
  const postId = ref('')
  const tags = ref<string[]>([])
  const tagsInput = ref('')

  const fetchPosts = async (page: number = 1) => {
    loading.value = true
    try {
      const response = await fetchPostsService(page)
      posts.value = response?.data?.data.data ?? []
      currentPage.value = response?.data?.data.current_page ?? 1
      lastPage.value = response?.data?.data.last_page ?? 1
      nextPage.value = response?.data?.data.next_page_url ?? ''
      previousPage.value = response?.data?.data.prev_page_url ?? ''
    } catch (error) {
      console.error('Error fetching posts:', error)
    } finally {
      loading.value = false
    }
  }
  const fetchPostById = async (id: string) => {
    try {
      const response = await fetchPostByIdService(id)
      currentPost.value = response && response.data ? response.data : null
    } catch (error) {
      console.error('Error fetching post by ID:', error)
    }
  }

  const updatePost = async () => {
    if (!postId.value || !title.value || !body.value) {
      console.error('Cannot update post: title or body is empty')
      return
    }
    try {
      const response = await updatePostService(
        postId.value,
        title.value,
        body.value,
        tagsInput.value,
      )
      currentPost.value = response.data
      fetchPosts(1)
    } catch (error) {
      console.error('Error updating post:', error)
    }
  }

  const createPost = async () => {
    if (!title.value || !body.value) {
      console.error('Cannot create post: title or body is empty')
      return
    }
    try {
      const response = await createPostService(title.value, body.value, tagsInput.value)
      currentPost.value = response.data
      fetchPosts(1)
    } catch (error) {
      console.error('Error creating post:', error)
    }
  }

  const deletePost = async (id: string) => {
    try {
      const response = await deletePostService(id)

      posts.value = posts.value.filter((post) => post.id !== id.toString())
      if (currentPost.value?.id === id.toString()) {
        currentPost.value = null
      }
    } catch (error) {
      console.error('Error deleting post:', error)
    }
  }

  const searchPosts = async (query: string) => {
    searchQuery.value = query
    loading.value = true
    try {
      const response = await searchPostsService(query)
      posts.value = response?.data?.data ?? []
      currentPage.value = response?.data?.current_page ?? 1
      lastPage.value = response?.data?.last_page ?? 1
      nextPage.value = response?.data?.next_page_url ?? ''
      previousPage.value = response?.data?.prev_page_url ?? ''
    } catch (error) {
      console.error('Error searching posts:', error)
    } finally {
      loading.value = false
    }
  }

  return {
    posts,
    currentPost,
    loading,
    fetchPosts,
    searchQuery,
    updatePost,
    currentPage,
    lastPage,
    postId,
    fetchPostById,
    nextPage,
    title,
    tagsInput,
    body,
    previousPage,
    createPost,
    searchPosts,
    deletePost,
    tags,
  }
})
