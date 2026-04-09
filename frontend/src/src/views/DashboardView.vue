<script lang="ts" setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import api from '@/lib/api';
import { useUiApiCall } from '@/composables/uiApiCall';
import {
  AlertDialog,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import SessionCard from '@/components/dashboard/SessionCard.vue';
import type { GameSession } from '@/common/types';
import { Button } from '@/components/ui/button';

const authStore = useAuthStore();
const player = authStore.user!;

const isDeleteDialogOpen = ref<boolean>(false);
const sessionDelete = ref<GameSession | null>(null);

const { uiApiCall, isLoading, error } = useUiApiCall();

const handleDeleteDialog = (session: GameSession) => {
  error.value = null;
  sessionDelete.value = session;
  isDeleteDialogOpen.value = true;
};

const deleteSession = async () => {
  const result = await uiApiCall(() => api.delete(`/api/game-sessions/${sessionDelete.value?.id}`));

  if (result.success) {
    player.activeSessions = player.activeSessions!.filter((s) => s.id !== sessionDelete.value!.id);
    isDeleteDialogOpen.value = false;
  }
};
</script>

<template>
  <!-- Alert dialog - delete session -->
  <AlertDialog v-model:open="isDeleteDialogOpen">
    <AlertDialogContent
      class="bg-violet-950 rounded-none border-4 border-violet-500 shadow-[7px_9px] shadow-slate-300"
    >
      <AlertDialogHeader>
        <AlertDialogTitle class="text-lime-300 underline">Confirm deletion</AlertDialogTitle>
        <AlertDialogDescription class="text-white text-md">
          Delete session for {{ sessionDelete?.game?.name }}?
        </AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <AlertDialogCancel :disabled="isLoading" class="bg-transparent">Cancel</AlertDialogCancel>
        <Button @click="deleteSession" :disabled="isLoading" variant="destructive">Delete</Button>
      </AlertDialogFooter>
      <div class="text-red-500" v-if="error">Error: {{ error }}</div>
    </AlertDialogContent>
  </AlertDialog>

  <div class="w-full mx-auto max-w-5xl px-2 sm:px-10 space-y-8">
    <header
      class="border-2 border-lime-500 p-6 bg-lime-950/20 shadow-[0_0_15px_rgba(132,204,22,0.2)]"
    >
      <h1 class="text-4xl md:text-5xl uppercase tracking-tighter">Welcome, {{ player.name }}.</h1>
      <p class="text-lime-700 mt-2">SYSTEM_STATUS: ONLINE // ACCESS_LEVEL: PLAYER</p>
    </header>

    <main class="grid grid-cols-1 md:grid-cols-12 gap-8">
      <!-- Sessions section -->
      <section class="md:col-span-4 space-y-4">
        <div class="flex items-center justify-between gap-x-2 border-b-2 border-sky-900 pb-2">
          <h2 class="text-2xl font-bold uppercase">Active_Sessions</h2>
          <span class="bg-sky-900 text-sky-400 px-2 py-0.5 text-xs"
            >{{ player.activeSessions?.length }}/5</span
          >
        </div>

        <div class="flex flex-col gap-4">
          <div
            v-if="player.activeSessions?.length"
            v-for="session in player.activeSessions"
            :key="session.id"
          >
            <SessionCard :session="session" @delete="handleDeleteDialog" />
          </div>
          <div v-else class="px-3 py-2 border rounded-none border-sky-500">
            You have no active game sessions.<br />Start one by playing a game!
          </div>
        </div>
      </section>
    </main>
  </div>
</template>
