<script setup lang="ts">
import type { GameSession } from '@/common/types';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { PlayIcon, Trash2Icon } from '@lucide/vue';
import { formatDistanceToNow } from 'date-fns';

const { session } = defineProps<{
  session: GameSession;
}>();

const emit = defineEmits<{
  delete: [session: GameSession];
}>();
</script>

<template>
  <Card class="group w-full py-4 rounded-none border-sky-500">
    <CardContent class="px-4">
      <div class="flex items-start justify-between gap-3">
        <div class="space-y-1 min-w-0">
          <h3 class="font-semibold text-sky-500">
            {{ session.game?.name }}
          </h3>

          <p class="text-xs text-slate-200">
            Last played
            <span class="font-medium italic text-slate-400">
              {{ formatDistanceToNow(new Date(session.updatedAt), { addSuffix: true }) }}
            </span>
          </p>
        </div>

        <div class="mt-2 h-2.5 w-2.5 rounded-full bg-emerald-500 shrink-0 animate-pulse"></div>
      </div>

      <div class="my-3 h-px bg-slate-200"></div>

      <div class="flex gap-2">
        <Button
          class="rounded-none bg-red-600 hover:bg-red-700 text-white"
          @click="emit('delete', session)"
        >
          <Trash2Icon />
        </Button>
        <Button
          class="@container grow rounded-none bg-sky-600 hover:bg-sky-700 text-white font-medium"
        >
          <PlayIcon /> <span class="hidden @[100px]:block">Continue</span>
        </Button>
      </div>
    </CardContent>
  </Card>
</template>
