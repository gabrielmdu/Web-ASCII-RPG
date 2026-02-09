<script lang="ts" setup>
import LoginForm from '@/components/ui/LoginForm.vue';
import WrHeader from '@/components/ui/WrForm/WrHeader.vue';
</script>

<template>
  <div class="flex flex-col items-center justify-center mt-2 sm:mt-6">
    <WrHeader title="[ LOGIN ]" description="Identify yourself, creature." />
    <LoginForm />
  </div>
</template>
