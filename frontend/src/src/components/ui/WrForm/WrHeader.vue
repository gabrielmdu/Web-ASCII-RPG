<script setup lang="ts">
import { Item, ItemContent, ItemDescription, ItemTitle } from '@/components/ui/item';

defineProps<{
  title: string;
  description?: string;
}>();
</script>

<template>
  <Item class="w-full sm:max-w-sm">
    <ItemContent class="gap-0">
      <ItemTitle class="text-xl">{{ title }}</ItemTitle>
      <ItemDescription v-if="!!description">{{ description }}</ItemDescription>
    </ItemContent>
  </Item>
</template>
