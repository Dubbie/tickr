<script setup>
import { Link, router } from '@inertiajs/vue3';
import TheSearch from './TheSearch.vue';
import SidebarNavigation from './SidebarNavigation.vue';
import TheButton from './TheButton.vue';
import { useTheme } from '@/composables/useTheme';
import Dropdown from './Dropdown.vue';
import DropdownLink from './DropdownLink.vue';

const { theme, toggleTheme } = useTheme();

const handleLogout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div
        class="flex max-h-dvh min-h-dvh w-64 flex-col gap-4 border-r border-zinc-900/10 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-900"
    >
        <Link
            :href="route('dashboard')"
            class="mr-4 flex h-10 items-center gap-2 px-2"
        >
            <img
                src="//picsum.photos/128"
                class="block size-6 shrink-0 overflow-hidden rounded-md"
            />
            <p
                class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100"
            >
                {{ $page.props.company.name }}
            </p>
        </Link>

        <TheSearch />

        <SidebarNavigation />

        <div class="flex-1"></div>

        <nav class="min-h-auto flex flex-col overflow-visible">
            <TheButton variant="ghost" size="sm" @click="toggleTheme"
                >{{ theme === 'light' ? 'Dark' : 'Light' }} theme</TheButton
            >
        </nav>

        <Dropdown align="bottom-left">
            <template #trigger>
                <div
                    class="flex cursor-pointer items-center gap-x-3 rounded-md p-2 hover:bg-zinc-900/5 dark:hover:bg-white/10"
                >
                    <img
                        :src="$page.props.auth.user.profile_photo_url"
                        class="size-8 rounded-md ring-1 ring-zinc-900/10"
                    />

                    <div class="overflow-hidden">
                        <p
                            class="truncate text-sm font-semibold dark:text-white"
                        >
                            {{ $page.props.auth.user.name }}
                        </p>
                        <p class="truncate text-xs font-semibold text-zinc-400">
                            {{ $page.props.auth.user.email }}
                        </p>
                    </div>
                </div>
            </template>

            <template #content>
                <div>
                    <DropdownLink @click="handleLogout" href="#!"
                        >Logout</DropdownLink
                    >
                </div>
            </template>
        </Dropdown>
    </div>
</template>
