<script lang="ts" setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import { useForm, Field as VeeField } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import { z } from 'zod';
import { useAuthStore } from '@/stores/auth';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import WrSubmitButton from '@/components/ui/WrForm/WrSubmitButton.vue';
import WrPasswordInput from '@/components/ui/WrForm/WrPasswordInput.vue';

const authStore = useAuthStore();
const route = useRoute();

const token = ref<string>(route.params.token as string);
const email = ref<string>(route.query.email as string);

const error = ref<string>('');

const passwordSchema = z
  .string()
  .min(8, 'Password must be at least 8 characters')
  .max(64, 'Password must be at most 64 characters')
  .regex(/[A-Za-z]/, 'Password must contain at least one letter')
  .regex(/[0-9]/, 'Password must contain at least one number');

const formSchema = toTypedSchema(
  z
    .object({
      password: passwordSchema,
      passwordConfirmation: z.string().min(1, 'Required'),
    })
    .refine((data) => data.password === data.passwordConfirmation, {
      path: ['passwordConfirmation'],
      message: 'Passwords must match',
    }),
);

const { handleSubmit } = useForm({
  validationSchema: formSchema,
});

const onSubmit = handleSubmit(async (values) => {
  const result = await authStore.resetPassword(
    token.value,
    email.value,
    values.password,
    values.passwordConfirmation,
  );

  if (result.success) {
    emit('password-reset');
  } else {
    error.value = result.error!;
  }
});

const emit = defineEmits<{
  'password-reset': [];
}>();
</script>

<template>
  <Card class="w-full sm:max-w-sm rounded-none border-lime-400 border-dashed mb-4">
    <CardContent>
      <form @submit="onSubmit" id="form-reset-password">
        <FieldGroup>
          <VeeField v-slot="{ field, errors }" name="password">
            <Field :data-invalid="!!errors.length">
              <FieldLabel class="text-lime-400" for="form-password"> Password </FieldLabel>
              <WrPasswordInput :field="field" :errors="errors" />
            </Field>
          </VeeField>

          <VeeField v-slot="{ field, errors }" name="passwordConfirmation">
            <Field :data-invalid="!!errors.length">
              <FieldLabel class="text-lime-400" for="form-password-confirmation">
                Password confirmation
              </FieldLabel>
              <WrPasswordInput :field="field" :errors="errors" />
            </Field>
          </VeeField>
        </FieldGroup>
      </form>
    </CardContent>

    <CardFooter>
      <WrSubmitButton :error="error" formId="form-reset-password" />
    </CardFooter>
  </Card>
</template>
