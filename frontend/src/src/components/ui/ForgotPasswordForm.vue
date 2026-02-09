<script lang="ts" setup>
import { useForm, Field as VeeField } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import { z } from 'zod';
import { Input } from '@/components/ui/input';
import { useAuthStore } from '@/stores/auth';
import { ref } from 'vue';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field';
import WrSubmitButton from '@/components/ui/WrForm/WrSubmitButton.vue';

const authStore = useAuthStore();

const error = ref<string>('');

const formSchema = toTypedSchema(
  z.object({
    email: z.string().email().min(1, 'Required').max(255, 'E-mail too long!'),
  }),
);

const { handleSubmit } = useForm({
  validationSchema: formSchema,
});

const emit = defineEmits<{
  'email-sent': [];
}>();

const onSubmit = handleSubmit(async (values) => {
  const result = await authStore.forgotPassword(values.email);

  if (result.success) {
    emit('email-sent');
  } else {
    error.value = result.error!;
  }
});
</script>

<template>
  <Card class="w-full sm:max-w-sm rounded-none border-lime-400 border-dashed mb-4">
    <CardContent>
      <form @submit="onSubmit" id="form-forgot-password">
        <FieldGroup>
          <VeeField v-slot="{ field, errors }" name="email">
            <Field :data-invalid="!!errors.length">
              <FieldLabel class="text-lime-400" for="form-email"> E-mail </FieldLabel>
              <Input
                class="rounded-none border-2 border-lime-800 bg-transparent focus-visible:ring-lime-500 placeholder:text-lime-900"
                id="form-email"
                type="email"
                v-bind="field"
                placeholder="user@mail.com"
                :aria-invalid="!!errors.length"
              />
              <FieldError v-if="errors.length" :errors="errors" />
            </Field>
          </VeeField>
        </FieldGroup>
      </form>
    </CardContent>

    <CardFooter>
      <WrSubmitButton :error="error" formId="form-forgot-password" />
    </CardFooter>
  </Card>
</template>
