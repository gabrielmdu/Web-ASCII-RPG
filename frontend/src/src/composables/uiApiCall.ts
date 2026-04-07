import { apiCall } from '@/lib/api';
import type { AxiosResponse } from 'axios';
import { ref } from 'vue';

export const useUiApiCall = () => {
  const isLoading = ref<boolean>(false);
  const error = ref<string | null>(null);

  const uiApiCall = async <T>(callback: () => Promise<AxiosResponse<T>>) => {
    error.value = null;
    isLoading.value = true;

    const result = await apiCall(() => callback());

    isLoading.value = false;

    if (!result.success) {
      error.value = result.error!;
    }

    return result;
  };

  return { uiApiCall, isLoading, error };
};
