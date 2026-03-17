<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Checkbox } from '@/components/ui/checkbox';
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination';
import SessionCard from '@/components/dashboard/SessionCard.vue';
import { useGameFilters, type GameFilter } from '@/composables/gameFields';
import api, { apiCall } from '@/lib/api';
import GameCard from '@/components/dashboard/GameCard.vue';
import type { Game } from '@/common/types';

interface GameSearchResult {
  data: Game[];
  page: number;
  perPage: number;
  total: number;
}

const authStore = useAuthStore();
const player = authStore.user!;

const loading = ref<boolean>(false);

const gameSearchResult = ref<GameSearchResult>({
  data: [],
  page: 0,
  perPage: 0,
  total: 0,
});

async function wait() {
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve(true);
    }, 2000);
  });
}

const fetchGamesCallback = async (filters: GameFilter) => {
  loading.value = true;

  const result = await apiCall(() => api.get('/api/games', { params: filters }));

  //await wait();

  loading.value = false;

  if (!result.success) {
    // deal with error here
    return;
  }

  const { data, meta } = result.data;

  gameSearchResult.value = {
    ...gameSearchResult.value,
    data: data,
    page: meta.current_page,
    perPage: meta.per_page,
    total: meta.total,
  };

  console.log(result, filters);
};

const { filters } = useGameFilters(fetchGamesCallback);

onMounted(() => {
  fetchGamesCallback(filters.value);
});
</script>

<template>
  <div class="w-full mx-auto max-w-5xl px-2 sm:px-10 space-y-8">
    <header
      class="border-2 border-lime-500 p-6 bg-lime-950/20 shadow-[0_0_15px_rgba(132,204,22,0.2)]"
    >
      <h1 class="text-4xl md:text-5xl uppercase tracking-tighter">Welcome, {{ player.name }}.</h1>
      <p class="text-lime-700 mt-2">SYSTEM_STATUS: ONLINE // ACCESS_LEVEL: PLAYER</p>
    </header>

    <main class="grid grid-cols-1 md:grid-cols-12 gap-8">
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
            <SessionCard :session="session" />
          </div>
          <div v-else class="px-3 py-2 border rounded-none border-sky-500">
            You have no active game sessions.<br />Start one by playing a game!
          </div>
        </div>
      </section>

      <section class="md:col-span-8 flex flex-col gap-6 mb-4">
        <div class="flex items-center justify-between gap-x-2 border-b-2 border-lime-900 pb-2">
          <h2 class="text-2xl font-bold uppercase">Games</h2>
          <span class="bg-lime-900 text-lime-400 px-2 py-0.5 text-xs">{{
            gameSearchResult.total
          }}</span>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 p-4 border-2 border-lime-900 bg-black/50">
          <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lime-800">></span>
            <Input
              v-model="filters.search"
              placeholder="SEARCH_DATABASE..."
              class="pl-8 bg-transparent border-lime-800 rounded-none focus-visible:ring-lime-500"
            />
          </div>
          <div class="flex items-center gap-2">
            <Checkbox id="terms" v-model="filters.public" />
            <Label for="terms">Public only</Label>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
          <GameCard v-for="game in gameSearchResult.data" :key="game.id" :game="game" />
        </div>

        <footer class="flex justify-center border-t-2 border-lime-900 pt-6">
          <div class="flex gap-2">
            <Pagination
              v-slot="{ page }"
              :items-per-page="gameSearchResult.perPage"
              :total="gameSearchResult.total"
              v-model:page="filters.page"
              show-edges
              :sibling-count="1"
            >
              <PaginationContent v-slot="{ items }">
                <PaginationPrevious> <- </PaginationPrevious>
                <template v-for="(item, index) in items" :key="index">
                  <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === page"
                  >
                    {{ item.value }}
                  </PaginationItem>
                  <PaginationEllipsis v-else :index="index" :key="item.type" />
                </template>
                <PaginationNext> -> </PaginationNext>
              </PaginationContent>
            </Pagination>
          </div>
        </footer>
      </section>
    </main>
  </div>
</template>
