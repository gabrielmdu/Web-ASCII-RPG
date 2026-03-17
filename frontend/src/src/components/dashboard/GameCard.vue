<script setup lang="ts">
import type { Game } from '@/common/types';
import { formatDistanceToNow } from 'date-fns';

const { game } = defineProps<{
  game: Game;
}>();
</script>

<template>
  <div class="grid grid-cols-5 gap-3 items-center border border-lime-400 p-2 text-sm cursor-pointer">
    <div class="">
      {{ game.name }}
    </div>

    <div class="">
      {{ game.creator?.name }}
    </div>

    <div class="">Ver: {{ game.version }}</div>

    <div class="">
      <span :title="new Date(game.lastModified).toLocaleString()">
        {{ formatDistanceToNow(new Date(game.lastModified), { addSuffix: true }) }}
      </span>
    </div>

    <div class="">
      {{ game.isPublic }}
    </div>
  </div>
</template>
