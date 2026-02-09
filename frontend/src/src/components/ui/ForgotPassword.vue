<script lang="ts" setup>
import { ref } from 'vue';
import WrHeader from '@/components/ui/WrForm/WrHeader.vue';
import ForgotPasswordForm from './ForgotPasswordForm.vue';

const canShowSuccessMessage = ref<boolean>(false);

const showSuccessMessage = () => {
  canShowSuccessMessage.value = true;
};
</script>

<template>
  <div class="flex flex-col items-center justify-center mt-2 sm:mt-6">
    <WrHeader
      title="[ RECOVER_PASSWORD ]"
      description="We will send a reset link through a magic spell."
    />
    <ForgotPasswordForm v-if="!canShowSuccessMessage" @email-sent="showSuccessMessage" />
    <div
      v-else
      class="sm:max-w-sm w-full text-2xl text-green-500 border-green-500 border-2 px-5 py-2"
    >
      The email with the password reset link was sent successfully.
    </div>
  </div>
</template>
