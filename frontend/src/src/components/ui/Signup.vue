<script lang="ts" setup>
import SignupForm from './SignupForm.vue';
import WrHeader from './WrForm/WrHeader.vue';
</script>

<template>
  <div class="flex flex-col items-center justify-center mt-2 sm:mt-6">
    <WrHeader title="[ SIGN_UP ]" description="Enter a world of adventure." />
    <SignupForm />
  </div>
</template>
