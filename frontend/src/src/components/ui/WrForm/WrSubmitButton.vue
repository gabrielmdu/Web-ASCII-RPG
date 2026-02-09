<script lang="ts" setup>
import { useAuthStore } from '@/stores/auth';
import { Button } from '@/components/ui/button';
import { Field } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';

const authStore = useAuthStore();

const props = defineProps<{
  formId: string;
  error?: string;
}>();
</script>

<template>
  <Field orientation="vertical">
    <Button
      class="bg-lime-400 text-md rounded-none"
      type="submit"
      :form="props.formId"
      :disabled="authStore.loading"
    >
      <Spinner v-if="authStore.loading" />
      <slot>[ SUBMIT ]</slot>
    </Button>
    <span v-if="props.error" class="text-red-700">{{ props.error }}</span>
  </Field>
</template>
