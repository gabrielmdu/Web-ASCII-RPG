<script lang="ts" setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
//import GameCard from '@/components/GameCard.vue';
//import SessionCard from '@/components/SessionCard.vue';
import { Pagination } from '@/components/ui/pagination';
import SessionCard from '@/components/dashboard/SessionCard.vue';

const authStore = useAuthStore();
const player = authStore.user!;

const searchQuery = ref('');
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

      <section class="md:col-span-8 flex flex-col gap-6">
        <div class="flex items-center justify-between gap-x-2 border-b-2 border-lime-900 pb-2">
          <h2 class="text-2xl font-bold uppercase">Games</h2>
          <span class="bg-lime-900 text-lime-400 px-2 py-0.5 text-xs">27</span>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 p-4 border-2 border-lime-900 bg-black/50">
          <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lime-800">></span>
            <Input
              v-model="searchQuery"
              placeholder="SEARCH_DATABASE..."
              class="pl-8 bg-transparent border-lime-800 rounded-none focus-visible:ring-lime-500"
            />
          </div>
          <select
            class="bg-black border-2 border-lime-800 text-lime-500 px-4 py-2 rounded-none focus:outline-lime-500"
          >
            <option>ALL_GENRES</option>
            <option>FANTASY</option>
            <option>SCI-FI</option>
          </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
          <div v-for="i in 10" :key="i">Game {{ i }}</div>
        </div>

        <footer class="flex justify-center border-t-2 border-lime-900 pt-6">
          <div class="flex gap-2">
            <Button
              variant="outline"
              class="border-lime-800 rounded-none text-lime-600 hover:text-lime-400"
            >
              [ PREV ]
            </Button>
            <Button variant="outline" class="border-lime-500 rounded-none bg-lime-500/10">
              01
            </Button>
            <Button variant="outline" class="border-lime-800 rounded-none text-lime-600">
              02
            </Button>
            <Button
              variant="outline"
              class="border-lime-800 rounded-none text-lime-600 hover:text-lime-400"
            >
              [ NEXT ]
            </Button>
          </div>
        </footer>
      </section>
    </main>
  </div>
</template>
