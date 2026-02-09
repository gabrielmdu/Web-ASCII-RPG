<script setup lang="ts">
import './App.css';
import Navbar from '@/components/ui/Navbar.vue';
</script>

<template>
  <div class="flex flex-col min-h-screen">
    <Navbar />
    <RouterView />
  </div>
</template>

<style scoped></style>
