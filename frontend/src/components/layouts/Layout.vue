<template>
  <div class="min-h-screen flex flex-col">
    <Header />
    <main class="flex-1 p-4 my-10 max-w-7xl mx-auto">
      <slot />
    </main>
    <Footer />
  </div>
</template>

<script setup lang="ts">
import Header from './Header.vue'
import Footer from './Footer.vue'
</script>
