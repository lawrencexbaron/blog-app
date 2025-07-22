<template>
  <div class="mx-auto w-[580px]">
    <h1 class="text-3xl font-bold text-slate-600">Post Page</h1>

    <div class="my-10">
      <button
        class="cursor-pointer px-3 py-2 text-white bg-slate-600 rounded hover:bg-slate-700"
        @click="((createModalVisible = true), (postStore.title = ''), (postStore.body = ''))"
      >
        Create Post
      </button>
    </div>

    <Modal v-if="createModalVisible" :show="createModalVisible" @close="createModalVisible = false">
      <form @submit.prevent="handleCreatePost">
        <h2 class="text-xl font-bold mb-4">Create New Post</h2>
        <div class="mb-4">
          <TextInput
            type="text"
            class="rounded mt-2"
            id="title"
            label="Title"
            v-model="postStore.title"
            required
          />
        </div>
        <div class="mb-4">
          <TextInput
            type="text"
            class="rounded mt-2"
            id="tags"
            label="Tags (comma separated)"
            v-model="postStore.tagsInput"
            required
          />
        </div>
        <div class="mb-4">
          <TextInput
            type="text"
            class="rounded mt-2"
            id="body"
            label="Body"
            v-model="postStore.body"
            required
          />
        </div>
        <Button
          :disabled="loading"
          :loading="loading"
          type="submit"
          class="bg-blue-500 text-white px-4 py-2 rounded"
          >{{ loading ? 'Loading...' : 'Create Post' }}</Button
        >
      </form>
    </Modal>
    <Modal v-if="editModalVisible" :show="editModalVisible" @close="editModalVisible = false">
      <form @submit.prevent="handleEditPost">
        <h2 class="text-xl font-bold mb-4">Edit Post</h2>
        <div class="mb-4">
          <input type="hidden" v-model="postStore.postId" />
          <TextInput
            type="text"
            class="rounded mt-2"
            id="title"
            label="Title"
            v-model="postStore.title"
            required
          />
        </div>
        <div class="mb-4">
          <TextInput
            type="text"
            class="rounded mt-2"
            id="tags"
            label="Tags (comma separated)"
            v-model="postStore.tagsInput"
            required
          />
        </div>
        <div class="mb-4">
          <TextInput
            type="text"
            class="rounded mt-2"
            id="body"
            label="Body"
            v-model="postStore.body"
            required
          />
        </div>
        <Button
          :disabled="loading"
          :loading="loading"
          type="submit"
          class="bg-blue-500 text-white px-4 py-2 rounded"
          >{{ loading ? 'Loading...' : 'Update Post' }}</Button
        >
      </form>
    </Modal>

    <div class="mb-10">
      <input
        type="text"
        v-model="postStore.searchQuery"
        placeholder="Search posts..."
        class="border border-gray-300 rounded p-2"
        @keydown.enter="handleSearch"
      />
    </div>

    <div class="flex flex-col mt-4">
      <p v-if="postStore.loading" class="text-gray-400">Loading posts...</p>
      <div
        v-else
        class="border text-black border-gray-500 mb-4 p-4"
        v-for="item in postStore.posts"
        :key="item.id"
      >
        <h2 class="text-lg font-bold">{{ item.title }}</h2>
        <p class="text-gray-700">{{ item.body }}</p>
        <div class="my-2">
          <span
            v-for="(tag, index) in item.tags ?? []"
            :key="index"
            class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded mr-2"
          >
            {{ tag.name }}
          </span>
        </div>
        <div class="flex mt-6 gap-6">
          <p class="text-red-500 cursor-pointer" @click="handleDelete(item.id)">Delete</p>
          <p
            class="text-blue-500 cursor-pointer"
            @click="
              ((editModalVisible = true),
              (postStore.title = item.title),
              (postStore.postId = item.id),
              (postStore.tags = item.tags.map((tag) => tag.name)),
              (postStore.tagsInput = item.tags.map((tag) => tag.name).join(',')),
              (postStore.body = item.body))
            "
          >
            Edit
          </p>
        </div>
      </div>
    </div>
    <div>
      <div class="flex justify-between mt-4" v-if="!postStore.loading">
        <button
          class="bg-gray-300 text-gray-800 px-4 py-2 rounded cursor-pointer"
          @click="postStore.fetchPosts(postStore.currentPage - 1)"
          :disabled="postStore.currentPage === 1"
        >
          Previous
        </button>
        <button
          class="bg-gray-300 text-gray-800 px-4 py-2 rounded cursor-pointer"
          @click="postStore.fetchPosts(postStore.currentPage + 1)"
          :disabled="postStore.currentPage === postStore.lastPage"
        >
          Next
        </button>
        <span class="text-gray-600">
          Page {{ postStore.currentPage }} of {{ postStore.lastPage }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { usePostStore } from '@/stores/post'
import { onMounted, ref } from 'vue'
import TextInput from '@/components/common/TextInput.vue'
import Modal from '@/components/common/Modal.vue'
import Button from '@/components/common/Button.vue'

// const { posts, fetchPosts, loading } = usePostStore()
const postStore = usePostStore()
const createModalVisible = ref(false)
const editModalVisible = ref(false)
const loading = ref(false)

const handleSearch = () => {
  try {
    postStore.searchPosts(postStore.searchQuery)
  } catch (error) {
    console.error('Error searching posts:', error)
  }
  if (postStore.searchQuery.trim() === '') {
    postStore.fetchPosts()
  } else {
    postStore.searchPosts(postStore.searchQuery)
  }
}

const handleCreatePost = async () => {
  loading.value = true
  if (!postStore.title || !postStore.body) {
    console.error('Title and body are required to create a post.')
    loading.value = false
    return
  }

  await postStore.createPost()

  postStore.title = ''
  postStore.body = ''

  createModalVisible.value = false
  loading.value = false
}

const handleEditPost = async () => {
  loading.value = true
  if (!postStore.title || !postStore.body) {
    console.error('Title and body are required to edit a post.')
    loading.value = false
    return
  }

  await postStore.updatePost()

  postStore.title = ''
  postStore.body = ''

  editModalVisible.value = false
  loading.value = false
}

const handleDelete = (id: string) => {
  // yes or no confirmation dialog
  if (confirm('Are you sure you want to delete this post?')) {
    try {
      postStore.deletePost(id)
      // remove the post from the local store
      postStore.posts = postStore.posts.filter((post) => post.id !== id)
    } catch (error) {
      console.error('Error deleting post:', error)
    }
  }
}

onMounted(() => {
  if (postStore.posts.length === 0) {
    postStore.fetchPosts()
  }
})
</script>
