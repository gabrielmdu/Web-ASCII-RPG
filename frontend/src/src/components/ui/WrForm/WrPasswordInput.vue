<script lang="ts" setup>
import { ref } from 'vue';
import {
  InputGroup,
  InputGroupInput,
  InputGroupButton,
  InputGroupAddon,
} from '@/components/ui/input-group/index';
import { FieldError } from '../field';
import EyeIcon from '@/assets/icons/view-icon.svg';
import ClosedEyeIcon from '@/assets/icons/closed-eye-icon.svg';

const passwordType = ref<string>('password');

const props = defineProps<{
  field: Record<string, any>;
  errors: string[];
}>();

const onShowPassword = () =>
  (passwordType.value = passwordType.value == 'password' ? 'text' : 'password');
</script>

<template>
  <InputGroup
    class="rounded-none border-2 border-lime-800 bg-transparent focus-visible:ring-lime-500"
  >
    <InputGroupInput
      id="form-password"
      :type="passwordType"
      v-bind="props.field"
      :aria-invalid="!!props.errors.length"
    />
    <InputGroupAddon align="inline-end">
      <InputGroupButton variant="default_warpg" type="button" @click.prevent="onShowPassword">
        <ClosedEyeIcon v-if="passwordType === 'password'" />
        <EyeIcon v-else />
      </InputGroupButton>
    </InputGroupAddon>
  </InputGroup>
  <FieldError v-if="props.errors.length" :errors="props.errors" />
</template>
