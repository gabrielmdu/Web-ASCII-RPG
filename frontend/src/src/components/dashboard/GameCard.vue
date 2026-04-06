<script setup lang="ts">
import { computed } from 'vue';
import { GameSessionStatus, type Game } from '@/common/types';
import { formatDistanceToNow } from 'date-fns';
import {
  CloudBackupIcon,
  GamepadIcon,
  InfoIcon,
  LockKeyholeIcon,
  LockKeyholeOpenIcon,
  UserRoundIcon,
} from '@lucide/vue';

const { game, isActive = true } = defineProps<{
  game: Game;
  isActive?: boolean;
}>();

const activeSession = computed(
  () => game.sessions?.find((s) => s.status === GameSessionStatus.ACTIVE) ?? null,
);
</script>

<template>
  <div
    class="grid grid-cols-8 gap-3 items-center border rounded-md border-lime-400 hover:bg-slate-900 p-2 text-sm/3.5"
    :class="{
      'cursor-pointer': isActive,
      //'text-stone-200': !isActive,
      'animate-pulse': !isActive,
      'border-sky-500': activeSession,
    }"
  >
    <div class="flex items-center gap-1 col-span-2 text-purple-400">
      <GamepadIcon class="shrink-0 hidden xs:block" color="#a380ad" :size="14" /> {{ game.name }}
    </div>

    <div class="flex items-center gap-1 col-span-2 text-amber-400">
      <UserRoundIcon class="shrink-0 hidden xs:block" color="#fcdb03" :size="14" />
      {{ game.creator?.name }}
    </div>

    <div class="flex items-center gap-1 text-slate-400">
      <InfoIcon class="shrink-0 hidden xs:block" :size="14" color="#cccccc" /> {{ game.version }}
    </div>

    <div class="flex items-center gap-1 col-span-2 text-slate-400">
      <CloudBackupIcon class="shrink-0 hidden xs:block" :size="16" color="#cccccc" />
      <span :title="new Date(game.lastModified).toLocaleString()">
        {{ formatDistanceToNow(new Date(game.lastModified), { addSuffix: true }) }}
      </span>
    </div>

    <div class="flex justify-center">
      <span :title="(game.isPublic ? 'Public' : 'Private') + ' game'">
        <LockKeyholeOpenIcon color="#aeff70" :size="20" v-if="game.isPublic" />
        <LockKeyholeIcon color="#d64b4b" :size="20" v-else />
      </span>
    </div>
  </div>
</template>
