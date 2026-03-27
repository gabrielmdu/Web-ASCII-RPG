<script lang="ts" setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import {
  DoorOpenIcon,
  GamepadIcon,
  LayoutDashboardIcon,
  LogOutIcon,
  UserRoundPlusIcon,
} from 'lucide-vue-next';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
  await authStore.logout();
  router.push('/');
};
</script>

<template>
  <header class="border-gray-950 border-8 bg-gray-950">
    <div class="border-2 md:border-3 border-white">
      <nav class="container flex md:h-14 items-center justify-between px-2 md:px-4">
        <!-- Left: logo -->
        <RouterLink to="/" class="text-5xl text-white pr-4">
          WA<span class="text-green-500">RPG</span>
        </RouterLink>

        <!-- Right: navigation -->
        <div class="flex items-center gap-1 md:gap-3">
          <RouterLink to="/game-search">
            <Button
              class="text-lg text-lime-400 hover:bg-lime-400 border-lime-400"
              variant="outline"
            >
              <GamepadIcon class="hidden md:block" /> Games
            </Button>
          </RouterLink>

          <template v-if="authStore.isAuthenticated">
            <!-- User dropdown -->
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button class="underline text-lg" variant="link">
                  {{ authStore.user?.name }}
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent class="w-32 rounded-none bg-slate-900 text-cyan-300" align="end">
                <DropdownMenuItem as-child>
                  <RouterLink to="/dashboard"><LayoutDashboardIcon />Dashboard</RouterLink>
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem @click="handleLogout"><LogOutIcon />Logout</DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </template>

          <template v-else>
            <RouterLink class="hidden md:block" to="/login">
              <Button class="text-lg border border-purple-700" variant="ghost_warpg">
                <DoorOpenIcon /> Login
              </Button>
            </RouterLink>
            <RouterLink to="/signup">
              <Button class="text-lg" variant="default_warpg">
                <UserRoundPlusIcon /> <span class="hidden md:block">Sign Up</span>
              </Button>
            </RouterLink>
          </template>
        </div>
      </nav>
    </div>
  </header>
</template>
