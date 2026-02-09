import { defineStore } from 'pinia';
import api, { apiCall, apiCallWithCredentials, type ApiResult } from '@/lib/api';

interface State {
  user: User | null;
  loading: boolean;
  isInitialized: boolean;
}

interface User {
  name: string;
  email: string;
  is_verified: boolean;
}

const authChannel = new BroadcastChannel('auth-channel');

// listens for auth changes from other tabs
authChannel.onmessage = (event) => {
  const store = useAuthStore();

  if (event.data.type === 'logout') {
    store.user = null;
  }

  if (event.data.type === 'login' || event.data.type === 'signup') {
    store.user = event.data.user;
  }
};

export const useAuthStore = defineStore('auth', {
  state: (): State => ({
    user: null,
    loading: false,
    isInitialized: false,
  }),
  getters: {
    isAuthenticated: (state): boolean => !!state.user,
    isVerified: (state): boolean => (!!state.user ? state.user.is_verified : false),
  },
  actions: {
    async fetchUser() {
      try {
        const res = await api.get('/api/user');
        this.user = res.data.user;
      } catch {
        this.user = null;
      } finally {
        this.isInitialized = true;
      }
    },
    async login(email: string, password: string): Promise<ApiResult> {
      this.loading = true;

      const result = await apiCallWithCredentials(() => api.post('/login', { email, password }));

      if (result.success) {
        this.user = result.data?.user;
        authChannel.postMessage({ type: 'login', user: { ...this.user } });
      }

      this.loading = false;
      return result;
    },
    async signup(
      name: string,
      email: string,
      password: string,
      passwordConfirmation: string,
    ): Promise<ApiResult> {
      this.loading = true;

      const result = await apiCallWithCredentials(() =>
        api.post('/register', {
          name,
          email,
          password,
          password_confirmation: passwordConfirmation,
        }),
      );

      if (result.success) {
        this.user = result.data?.user;
        authChannel.postMessage({ type: 'signup', user: { ...this.user } });
      }

      this.loading = false;
      return result;
    },
    async logout(): Promise<ApiResult> {
      this.loading = true;

      const result = await apiCall(() => api.post('/logout'));
      if (result.success) {
        this.user = null;
        authChannel.postMessage({ type: 'logout' });
      }

      this.loading = false;
      return result;
    },
    async forgotPassword(email: string): Promise<ApiResult> {
      this.loading = true;
      const result = await apiCall(() => api.post('/forgot-password', { email }));

      this.loading = false;
      return result;
    },
    async resetPassword(
      token: string,
      email: string,
      password: string,
      passwordConfirmation: string,
    ): Promise<ApiResult> {
      this.loading = true;
      const result = await apiCall(() =>
        api.post('/reset-password', {
          token,
          email,
          password,
          password_confirmation: passwordConfirmation,
        }),
      );

      this.loading = false;
      return result;
    },
    async sendVerificationEmail(): Promise<ApiResult> {
      this.loading = true;
      const result = await apiCall(() => api.post('/email/verification-notification'));

      this.loading = false;
      return result;
    },
    async verifyEmail(url: string): Promise<ApiResult> {
      this.loading = true;

      const result = await apiCallWithCredentials(() => api.get(url));
      if (result.success) {
        this.user = result.data?.user;
      }

      this.loading = false;
      return result;
    },
  },
});
