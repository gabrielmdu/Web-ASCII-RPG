import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import HomeView from '@/views/HomeView.vue';
import LoginView from '@/views/LoginView.vue';
import SignupView from '@/views/SignupView.vue';
import ResetPasswordView from '@/views/ResetPasswordView.vue';
import ForgotPasswordView from '@/views/ForgotPasswordView.vue';
import VerifyEmailPromptView from '@/views/VerifyEmailPromptView.vue';
import VerifyEmailView from '@/views/VerifyEmailView.vue';

const routes = [
  // public routes
  { path: '/', name: 'home', component: HomeView },
  { path: '/login', name: 'login', component: LoginView, meta: { guest: true } },
  { path: '/signup', name: 'signup', component: SignupView, meta: { guest: true } },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: ForgotPasswordView,
    meta: { guest: true },
  },
  {
    path: '/reset-password/:token',
    name: 'reset-password',
    component: ResetPasswordView,
    meta: { guest: true },
  },

  // unverified access
  {
    path: '/verify-email-prompt',
    name: 'verify-email-prompt',
    component: VerifyEmailPromptView,
    meta: { unverifiedOnly: true },
  },

  // unverified or not logged in access - e-mail verification link
  {
    path: '/verify-email',
    name: 'verify-email',
    component: VerifyEmailView,
    meta: { verificationEntry: true },
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach(async (to) => {
  const authStore = useAuthStore();

  if (!authStore.isInitialized) {
    await authStore.fetchUser();
  }

  const name = to.name as string;

  const guestOnly = to.meta?.guest;
  const requiresAuth = to.meta?.requiresAuth;
  const verifiedOnly = to.meta?.verifiedOnly;
  const unverifiedOnly = to.meta?.unverifiedOnly;
  const verificationEntry = to.meta?.verificationEntry;

  const loggedIn = authStore.isAuthenticated;
  const verified = authStore.isVerified;

  // helper: avoids redirecting to the same route (prevents infinite loops)
  const redirectTo = (target: { name: string }) => (target.name === name ? true : target);

  // guest-only pages - if already logged in, send to home
  if (guestOnly && loggedIn) {
    return redirectTo({ name: 'home' });
  }

  // routes that require authentication
  if (requiresAuth && !loggedIn) {
    // unauthenticated - send to home
    return redirectTo({ name: 'home' });
  }

  // routes only for verified users
  if (verifiedOnly) {
    if (!loggedIn) {
      // if not logged in, send to home
      return redirectTo({ name: 'home' });
    }

    if (!verified) {
      // logged in but not verified - send to verify prompt
      return redirectTo({ name: 'verify-email-prompt' });
    }
  }

  // routes exclusively for unverified users
  if (unverifiedOnly) {
    if (!loggedIn || verified) {
      return redirectTo({ name: 'home' });
    }
  }

  // verification entry route (email link landing)
  if (verificationEntry) {
    // if already verified, no need to be here
    if (verified) {
      return redirectTo({ name: 'home' });
    }

    // allow access whether logged in or not
    return true;
  }

  // special rule: unverified users cannot access home
  if (name === 'home' && loggedIn && !verified) {
    return redirectTo({ name: 'verify-email-prompt' });
  }

  return true;
});

export default router;
