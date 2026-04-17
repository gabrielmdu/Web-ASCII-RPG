<script setup lang="ts">
import { computed } from 'vue';
import type { GameSettings } from '@/common/types';

const { sceneWidth, title, settings } = defineProps<{
  sceneWidth: number;
  title: string;
  settings: GameSettings;
}>();

/** Title with left and right chars. Needed for calculating the center position */
const titleWithChars = computed(() => {
  const { title_left, title_right } = settings.chars!.header;
  return `${title_left}${title}${title_right}`;
});

/** Top line */
const top = computed(() => {
  const { top_left, top_right } = settings.chars!.header;
  return top_left + '-'.repeat(sceneWidth) + top_right + '\n';
});

/** Middle line with the title centralized */
const middle = computed(() => {
  const { mid_left, mid_right } = settings.chars!.header;

  const paddedTitle = titleWithChars.value.padStart(
    Math.ceil(sceneWidth / 2) + Math.floor(titleWithChars.value.length / 2),
  );
  return mid_left + paddedTitle.padEnd(sceneWidth) + mid_right + '\n';
});

/** Bottom line */
const bottom = computed(() => {
  const { bottom_left, bottom_right } = settings.chars!.header;
  return bottom_left + '-'.repeat(sceneWidth) + bottom_right + '\n';
});
</script>

<template>{{ top }}{{ middle }}{{ bottom }}</template>
