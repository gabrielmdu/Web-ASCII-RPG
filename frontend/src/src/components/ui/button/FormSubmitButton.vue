<script lang="ts" setup>
import { useAuthStore } from '@/stores/auth';
import { Button } from '.';
import { Spinner } from '../spinner';

const authStore = useAuthStore();
</script>

<template>
  <Button type="submit" class="w-full">
    <Spinner v-if="authStore.loading" />
    <slot>Submit</slot>
  </Button>
</template>
