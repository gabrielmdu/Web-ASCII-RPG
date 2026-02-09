<script lang="ts" setup>
import { useAuthStore } from '@/stores/auth';
import { onMounted, ref } from 'vue';
import { useTimer } from '@/composables/timer.ts';
import { useRoute, useRouter } from 'vue-router';
import { defineRule, validate } from 'vee-validate';
import WrHeader from './WrForm/WrHeader.vue';

const authStore = useAuthStore();
const route = useRoute();
const router = useRouter();

const { count, startCountdown } = useTimer(10);
const showGoBackHome = ref<boolean>(false);

const statusText = ref<string>('Your email is being verified, please wait.');
const error = ref<string>('');

onMounted(async () => {
  const verifyLink = decodeURI(route.query.verify_url as string);

  if (verifyLink === 'undefined') {
    statusText.value = 'Email verification link is invalid.';
    return;
  }

  const apiResult = await authStore.verifyEmail(verifyLink);
  if (apiResult.success) {
    statusText.value = apiResult.data?.message;
    showGoBackHome.value = true;

    startCountdown(() => router.push({ name: 'home' }));
  } else {
    error.value = apiResult.error!;
  }
});
</script>

<template>
  <div class="flex flex-col items-center justify-center mt-2 sm:mt-6">
    <WrHeader title="[ EMAIL_VERIFICATION ]" description="You have to do it, there's no escape." />
    <div class="sm:max-w-sm w-full text-2xl text-green-500 border-green-500 border-2 px-5 py-2">
      <p>{{ statusText }}</p>
      <p v-if="error" class="text-red-700">Error: {{ error }}</p>
      <p v-if="showGoBackHome">Redirecting you to the home page in {{ count }} seconds</p>
    </div>
  </div>
</template>
