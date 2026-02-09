import axios, { AxiosError, type AxiosInstance, type AxiosResponse } from 'axios';

const api: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  withCredentials: true,
  withXSRFToken: true,
});

async function getCsrfCookie() {
  await api.get('http://localhost:8080/sanctum/csrf-cookie');
}

export interface ApiResult<T = any> {
  success: boolean;
  data?: T;
  error?: string;
}

async function call<T>(
  callback: () => Promise<AxiosResponse<T>>,
  withCredentials: boolean = false,
): Promise<ApiResult<T>> {
  try {
    if (withCredentials) {
      await getCsrfCookie();
    }
    const response = await callback();
    return { success: true, data: response.data };
  } catch (err) {
    const error = err as AxiosError<{ message?: string }>;
    return {
      success: false,
      error: error.response?.data?.message || 'Request failed.',
    };
  }
}

export const apiCallWithCredentials = <T>(
  callback: () => Promise<AxiosResponse<T>>,
): Promise<ApiResult<T>> => call(callback, true);

export const apiCall = <T>(callback: () => Promise<AxiosResponse<T>>): Promise<ApiResult<T>> =>
  call(callback, false);

export default api;
