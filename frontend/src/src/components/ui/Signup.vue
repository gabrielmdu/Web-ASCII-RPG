<script lang="ts" setup>
import { MilestoneIcon } from 'lucide-vue-next';
import SignupForm from './SignupForm.vue';
import WrHeader from './WrForm/WrHeader.vue';
import WrFooter from './WrForm/WrFooter.vue';
</script>

<template>
  <div class="flex flex-col items-center justify-center mt-2 sm:mt-6">
    <WrHeader title="[ SIGN_UP ]" description="Enter a world of adventure." />
    <SignupForm />
    <WrFooter link="/login">
      Already among us? <span class="underline"> Login here</span> <MilestoneIcon />
    </WrFooter>
  </div>
</template>
