<script setup lang="ts">
import { computed, ref } from 'vue';
import { GameSessionStatus, type Game, type GameSession } from '@/common/types';
import { formatDistanceToNow } from 'date-fns';
import {
  CloudBackupIcon,
  GamepadIcon,
  InfoIcon,
  LockKeyholeIcon,
  LockKeyholeOpenIcon,
  PlayIcon,
  Trash2Icon,
  UserRoundIcon,
} from '@lucide/vue';
import Button from '../ui/button/Button.vue';
import { useRouter } from 'vue-router';

const { game, isActive = true } = defineProps<{
  game: Game;
  isActive?: boolean;
}>();

const emit = defineEmits<{
  play: [game: Game];
  deleteSession: [game: Game, session: GameSession];
}>();

const isOpen = ref<boolean>(false);

const activeSession = computed(
  () => game.sessions?.find((s) => s.status === GameSessionStatus.ACTIVE) ?? null,
);
</script>

<template>
  <div class="border rounded-md border-lime-400" :class="{ 'border-sky-500': activeSession }">
    <div
      class="grid grid-cols-8 gap-3 items-center rounded-md hover:bg-emerald-950 p-2 text-sm/3.5"
      :class="{
        'cursor-pointer': isActive,
        //'text-stone-200': !isActive,
        'animate-pulse': !isActive,
        'border-b-0': isOpen,
        'rounded-b-none': isOpen,
      }"
      @click="isOpen = !isOpen"
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

    <!-- Game details -->

    <div class="border-t border-lime-500/30 mx-2" v-if="isOpen"></div>
    <div class="text-sm p-2 grid grid-cols-8 gap-3" v-if="isOpen">
      <div class="col-span-6">
        {{ game.description }}
      </div>
      <div class="flex flex-col-reverse lg:flex-row gap-2 col-span-2">
        <Button
          class="bg-red-600 hover:bg-red-700 text-white"
          @click="emit('deleteSession', game, activeSession!)"
          v-if="activeSession"
        >
          <Trash2Icon />
        </Button>
        <Button
          class="lg:grow bg-lime-600 text-white hover:bg-lime-200 hover:text-lime-950"
          :class="{
            'bg-sky-600': activeSession,
            'hover:bg-sky-700': activeSession,
            'hover:text-white': activeSession,
          }"
          @click="emit('play', game)"
        >
          <PlayIcon />
          <span class="hidden md:block">
            {{ activeSession ? 'Continue' : 'Play' }}
          </span>
        </Button>
      </div>
    </div>
  </div>
</template>
