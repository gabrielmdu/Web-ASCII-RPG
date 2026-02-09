<script lang="ts" setup>
import { useAuthStore } from '@/stores/auth';
import { Button } from '@/components/ui/button';

const authStore = useAuthStore();
</script>

<template>
  <div v-if="authStore.isAuthenticated">Welcome, {{ authStore.user?.name }}!</div>

  <div>
    <Button onclick="alert('hey')">Hey</Button>
  </div>
</template>
