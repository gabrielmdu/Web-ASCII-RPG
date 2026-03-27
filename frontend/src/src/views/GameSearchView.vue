<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue';
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { GameSearchSort, useGameFilters, type GameFilter } from '@/composables/gameFilters';
import api, { apiCall } from '@/lib/api';
import GameCard from '@/components/dashboard/GameCard.vue';
import type { Game } from '@/common/types';
import Button from '@/components/ui/button/Button.vue';
import { MoveDownIcon, MoveUpIcon, SquareXIcon } from '@lucide/vue';
import { useRoute, useRouter } from 'vue-router';
import { useMediaQuery } from '@vueuse/core';

interface GameSearchResult {
  data: Game[];
  page: number;
  perPage: number;
  total: number;
}

const router = useRouter();
const route = useRoute();
const isSmall = useMediaQuery('(max-width: 600px)');

const loading = ref<boolean>(true);
const error = ref<string>('');

const getDefaultGameResult = (): GameSearchResult => {
  return {
    data: [],
    page: 0,
    perPage: 0,
    total: 0,
  };
};

const gameSearchResult = ref<GameSearchResult>(getDefaultGameResult());

const fetchGamesCallback = async (filters: GameFilter) => {
  syncUrlParams();

  const result = await apiCall(() => api.get('/api/games', { params: filters }));

  loading.value = false;

  if (!result.success) {
    error.value = result.error!;
    gameSearchResult.value = getDefaultGameResult();
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

/** Syncs filters with URL query params */
const syncUrlParams = () => {
  router.replace({
    query: {
      search: filters.value.search || undefined,
      sort: Object.values(GameSearchSort).includes(filters.value.sort as GameSearchSort)
        ? filters.value.sort
        : GameSearchSort.CREATED_AT,
      public: filters.value.public ? 'true' : undefined,
      asc: filters.value.asc ? 'true' : 'false',
      page: filters.value.page !== 1 ? String(filters.value.page) : undefined,
    },
  });
};

const { filters, resetFilters, setPage } = useGameFilters(fetchGamesCallback);

// sets loading when the filters change to avoid delay with debounce
watch(
  () => filters.value,
  () => {
    error.value = '';
    loading.value = true;
  },
  { deep: true },
);

/** Page model for manual pagination. The set is called only when manually changed */
const pageModel = computed({
  get: () => filters.value.page,
  set: (value) => {
    setPage(value);
  },
});

// fetches the games as soon as the page loads by setting the filters according to the URL params
onMounted(() => {
  filters.value = {
    search: (route.query.search as string) || filters.value.search,
    sort: (route.query.sort as GameSearchSort) || filters.value.sort,
    public: route.query.public === 'true',
    asc: route.query.asc === 'true',
    page: route.query.page ? parseInt(route.query.page as string) : filters.value.page,
  };
});
</script>

<template>
  <div class="w-full mx-auto max-w-5xl px-2 sm:px-10 space-y-8">
    <!-- Games section -->
    <section class="flex flex-col gap-6 mb-4">
      <div class="flex items-center justify-between gap-x-2 border-b-2 border-lime-900 pb-2">
        <h2 class="text-2xl font-bold uppercase">Games</h2>
        <span class="bg-lime-900 text-lime-400 px-2 py-0.5 text-xs">{{
          gameSearchResult.total
        }}</span>
      </div>

      <!-- Filters -->
      <div class="flex flex-col gap-1 p-4 pt-2 border-2 border-lime-900 bg-black/50">
        <div class="flex justify-end items-center gap-1 text-red-400">
          <SquareXIcon :size="14" />
          <span class="text-red-400 hover:underline cursor-pointer" @click="resetFilters">
            Reset filters
          </span>
        </div>

        <div class="flex flex-col md:flex-row gap-4">
          <div class="relative flex-1 min-w-32">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lime-800">></span>
            <Input
              v-model="filters.search"
              placeholder="SEARCH_DATABASE..."
              class="pl-8 bg-transparent border-lime-800 rounded-none focus-visible:ring-lime-500"
            />
          </div>

          <div class="flex gap-1">
            <Select v-model="filters.sort" class="">
              <SelectTrigger class="flex-1 @md:max-w-28 @md:w-28">
                <SelectValue placeholder="Sort by" />
              </SelectTrigger>
              <SelectContent>
                <SelectLabel>Sort by</SelectLabel>
                <SelectItem value="created_at"> Creation </SelectItem>
                <SelectItem value="name"> Name </SelectItem>
                <SelectItem value="last_modified"> Updated </SelectItem>
                <SelectItem value="creator_name"> Creator </SelectItem>
              </SelectContent>
            </Select>
            <Button
              @click="filters.asc = !filters.asc"
              variant="outline"
              class="flex gap-0 px-5 hover:bg-gray-700"
              size="icon"
            >
              <MoveUpIcon :stroke-width="3" :color="!filters.asc ? 'gray' : 'white'" />
              <MoveDownIcon :stroke-width="3" :color="filters.asc ? 'gray' : 'white'" />
            </Button>
          </div>

          <div class="flex items-center gap-2">
            <Checkbox id="public-only" v-model="filters.public" />
            <Label for="public-only">Public only</Label>
          </div>
        </div>
      </div>

      <!-- Search results -->
      <div class="grid grid-cols-1 gap-3">
        <!-- Game list header -->
        <div
          class="grid grid-cols-8 gap-3 items-center border border-lime-700 px-2 py-3 sm:text-sm/3.5 text-xs/3.5 uppercase text-slate-200"
        >
          <div class="col-span-2">Game</div>
          <div class="col-span-2">Creator</div>
          <div class="">Version</div>
          <div class="col-span-2 text-center md:text-left">Last update</div>
          <div class="text-center">Access</div>
        </div>

        <GameCard
          v-if="!!gameSearchResult.total && !error"
          v-for="game in gameSearchResult.data"
          :key="game.id"
          :game="game"
          :is-active="!loading"
        />
        <div v-else-if="!loading && !error" class="border border-lime-800 p-4 text-center">
          No results. Try changing or
          <span class="underline cursor-pointer hover:text-red-500" @click="resetFilters">
            resetting the filters </span
          >.
        </div>
        <div v-else-if="!!error" class="border border-red-400 text-red-400 p-4">
          Error: {{ error }}
        </div>
      </div>

      <!-- Pagination -->
      <footer class="flex justify-center border-t-2 border-lime-900 pt-6">
        <div class="flex gap-2">
          <Pagination
            v-slot="{ page }"
            :items-per-page="gameSearchResult.perPage"
            :total="gameSearchResult.total"
            v-model:page="pageModel"
            show-edges
            :sibling-count="isSmall ? 0 : 1"
            :disabled="!gameSearchResult.total"
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
  </div>
</template>
