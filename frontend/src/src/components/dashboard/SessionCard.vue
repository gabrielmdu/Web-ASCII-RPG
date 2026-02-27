<script setup lang="ts">
import type { GameSession } from '@/common/types';
import { Button } from '@/components/ui/button';
import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { formatDistanceToNow } from 'date-fns';

const { session } = defineProps<{
  session: GameSession;
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

        <div class="mt-2 h-2.5 w-2.5 rounded-full bg-emerald-500 shrink-0 animate-pulse" />
      </div>

      <div class="my-3 h-px bg-slate-200" />

      <Button class="w-full rounded-none bg-sky-600 hover:bg-sky-700 text-white font-medium">
        Continue
      </Button>
    </CardContent>
  </Card>
</template>
