<template>
  <AppLayout>
    <router-view :key="$route.fullPath" />
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from './components/layouts/Layout.vue'
</script>
