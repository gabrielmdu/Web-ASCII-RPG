<script setup lang="ts">
import {
  AlertDialog,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '../button';


const { title = 'Confirm' } = defineProps<{
  title?: string;
  text: string;
  confirmBtnText: string;
  isLoading: boolean;
  error: string | null;
}>();

const open = defineModel<boolean>();

const emit = defineEmits<{
  confirm: [];
}>();
</script>

<template>
  <AlertDialog v-model:open="open">
    <AlertDialogContent
      class="bg-violet-950 rounded-none border-4 border-violet-500 shadow-[7px_9px] shadow-slate-300"
    >
      <AlertDialogHeader>
        <AlertDialogTitle class="text-lime-300 underline">{{ title }}</AlertDialogTitle>
        <AlertDialogDescription class="text-white text-md">
          {{ text }}
        </AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <AlertDialogCancel :disabled="isLoading" class="bg-transparent">Cancel</AlertDialogCancel>
        <Button @click="emit('confirm')" :disabled="isLoading" variant="destructive">
          {{ confirmBtnText }}
        </Button>
      </AlertDialogFooter>
      <div class="text-red-500" v-if="error">Error: {{ error }}</div>
    </AlertDialogContent>
  </AlertDialog>
</template>
