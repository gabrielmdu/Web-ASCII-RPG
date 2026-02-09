<script lang="ts" setup>
import { ref } from 'vue';
import WrHeader from '@/components/ui/WrForm/WrHeader.vue';
import ResetPasswordForm from '@/components/ui/ResetPasswordForm.vue';

const canShowSuccessMessage = ref<boolean>(false);

const showSuccessMessage = () => {
  canShowSuccessMessage.value = true;
};
</script>

<template>
  <div class="flex flex-col items-center justify-center mt-2 sm:mt-6">
    <WrHeader title="[ RESET_PASSWORD ]" description="Set your new password here." />
    <ResetPasswordForm v-if="!canShowSuccessMessage" @password-reset="showSuccessMessage" />
    <div
      v-else
      class="sm:max-w-sm w-full text-2xl text-green-500 border-green-500 border-2 px-5 py-2"
    >
      Your password was successfully reset. Please
      <RouterLink to="/login" class="text-purple-500 underline">login</RouterLink>.
    </div>
  </div>
</template>
