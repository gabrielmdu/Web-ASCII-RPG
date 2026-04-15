<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRoute } from 'vue-router';
import { GameSessionStatus, type Game, type GameSession } from '@/common/types';
import { useUiApiCall } from '@/composables/uiApiCall';
import api from '@/lib/api';
import Scene from '@/components/game/Scene.vue';

const authStore = useAuthStore();
const player = authStore.user!;

const { gameSlug } = useRoute().params;

const game = ref<Game | null>(null);
const currentSession = ref<GameSession | null>(null);

const { uiApiCall, isLoading, error } = useUiApiCall();

onMounted(async () => {
  const gameResult = await uiApiCall(() => api.get(`/api/games/${gameSlug}`));

  if (!gameResult.success) {
    return;
  }

  game.value = gameResult.data.data;
  currentSession.value =
    game.value?.sessions.find((s) => s.status === GameSessionStatus.ACTIVE) ?? null;
  console.log(game.value);

  if (!currentSession.value) {
    // creates a new session
    const sessionResult = await uiApiCall(() => api.post('/api/game-sessions', { gameSlug }));
    if (!sessionResult.success) {
      return;
    }

    currentSession.value = sessionResult.data.data;
    //game.value?.sessions.push(currentSession.value!);
    player.activeSessions.push(currentSession.value!);
  }
});
</script>

<template>
  <div v-if="isLoading">Loading...</div>
  <div v-else-if="error">Error: {{ error }}</div>
  <div v-else>
    <div v-if="currentSession?.currentScene">
      <Scene :scene="currentSession!.currentScene" />
    </div>
  </div>
</template>
