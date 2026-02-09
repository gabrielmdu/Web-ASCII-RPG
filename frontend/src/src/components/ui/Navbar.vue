<script lang="ts" setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
  await authStore.logout();
  router.push('/');
};
</script>

<template>
  <header class="border-gray-950 border-8 bg-gray-950">
    <div class="border-3 border-white">
      <nav class="container mx-auto flex h-14 items-center justify-between px-4">
        <!-- Left: Brand -->
        <RouterLink to="/" class="text-5xl text-white">
          WA<span class="text-green-500">RPG</span>
        </RouterLink>

        <!-- Right: Navigation -->
        <div class="flex items-center gap-3">
          <template v-if="authStore.isAuthenticated">
            <!-- User dropdown -->
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button class="underline" variant="link">
                  {{ authStore.user?.name }}
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="">My profile</DropdownMenuItem>
                <DropdownMenuItem @click="handleLogout">Logout</DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </template>

          <template v-else>
            <RouterLink to="/login">
              <Button class="text-xl" variant="ghost_warpg">Login</Button>
            </RouterLink>
            <RouterLink to="/signup">
              <Button class="text-xl" variant="default_warpg">Sign Up</Button>
            </RouterLink>
          </template>
        </div>
      </nav>
    </div>
  </header>
</template>
