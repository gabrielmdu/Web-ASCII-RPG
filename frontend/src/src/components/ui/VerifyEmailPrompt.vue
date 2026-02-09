<script lang="ts" setup>
import { useAuthStore } from '@/stores/auth';
import { computed, onMounted, ref } from 'vue';
import { useTimer } from '@/composables/timer.ts';
import { useRouter } from 'vue-router';
import WrHeader from '@/components/ui/WrForm/WrHeader.vue';
import { Button } from './button';
import { Spinner } from './spinner';

const authStore = useAuthStore();
const router = useRouter();

const { count, startCountdown } = useTimer(60);
const error = ref<string>('');

onMounted(() => {
  count.value = 0;
  router.replace({ name: 'verify-email-prompt' });
});

const canSendLink = computed(() => count.value <= 0 && !authStore.loading);

const handleSendActivationLink = async () => {
  error.value = '';
  const result = await authStore.sendVerificationEmail();

  if (!result.success) {
    error.value = result.error!;
  } else {
    startCountdown();
  }
};
</script>

<template>
  <div class="flex flex-col items-center justify-center mt-2 sm:mt-6">
    <WrHeader title="[ LOGIN ]" description="Identify yourself, creature." />
    <div class="sm:max-w-sm w-full text-2xl text-green-500 border-green-500 border-2 px-5 py-2">
      <p>
        We have sent you a verification link to
        <span class="text-purple-500">{{ authStore.user?.email }}</span
        >.
      </p>
      <p>
        If you didn't receive the message, you can send it again{{
          count <= 0 ? '.' : ' in ' + count + ' seconds.'
        }}
      </p>
    </div>

    <Button
      class="sm:max-w-sm w-full mt-6 bg-lime-400 text-md rounded-none"
      @click="handleSendActivationLink"
      :disabled="!canSendLink"
    >
      <Spinner v-if="authStore.loading" />
      [ SEND_LINK ]
    </Button>
    <div v-if="!!error" class="w-full sm:max-w-sm text-red-700">Error: {{ error }}</div>
  </div>
</template>
