<script lang="ts" setup>
import { ref } from 'vue';
import { useForm, Field as VeeField } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import { z } from 'zod';
import { Input } from '@/components/ui/input';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field';
import WrSubmitButton from '@/components/ui/WrForm/WrSubmitButton.vue';
import WrPasswordInput from '@/components/ui/WrForm/WrPasswordInput.vue';

const authStore = useAuthStore();
const router = useRouter();

const error = ref<string>('');

const formSchema = toTypedSchema(
  z.object({
    email: z.string().email().min(1, 'Required').max(255, 'E-mail is too long!'),
    password: z.string().min(1, 'Required').max(64, 'Password is too long!'),
  }),
);

const { handleSubmit } = useForm({
  validationSchema: formSchema,
});

const onSubmit = handleSubmit(async (values) => {
  error.value = '';
  const result = await authStore.login(values.email, values.password);
  if (!result.success) {
    error.value = result.error!;
    return;
  }

  // check if this is necessary - routes/index.ts
  const routeName = authStore.isVerified ? '/' : 'verify-email-prompt';
  router.push(routeName);
});
</script>

<template>
  <Card class="w-full sm:max-w-sm rounded-none border-lime-400 border-dashed">
    <CardContent>
      <form @submit="onSubmit" id="form-login">
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

          <VeeField v-slot="{ field, errors }" name="password">
            <Field :data-invalid="!!errors.length">
              <div class="flex justify-between">
                <FieldLabel class="text-lime-400" for="form-password"> Password </FieldLabel>
                <RouterLink to="/forgot-password" class="text-purple-500 hover:underline">
                  [ RECOVER_PASSWORD ]
                </RouterLink>
              </div>
              <WrPasswordInput :field="field" :errors="errors" />
            </Field>
          </VeeField>
        </FieldGroup>
      </form>
    </CardContent>

    <CardFooter>
      <WrSubmitButton :error="error" formId="form-login" />
    </CardFooter>
  </Card>
</template>
