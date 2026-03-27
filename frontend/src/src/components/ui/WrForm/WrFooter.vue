<script lang="ts" setup>
defineProps<{
  link: string;
}>();
</script>

<template>
  <div class="p-3 text-amber-400">
    <RouterLink :to="link" class="flex gap-1">
      <slot></slot>
    </RouterLink>
  </div>
</template>
