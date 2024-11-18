<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link, router } from '@inertiajs/vue3';

const handleLogout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="antialiased lg:bg-zinc-100">
        <div
            class="relative isolate flex min-h-svh w-full flex-col bg-white lg:bg-zinc-100"
        >
            <header class="flex items-center px-4">
                <div class="min-w-0 flex-1">
                    <nav class="flex flex-1 items-center gap-4 py-2.5">
                        <span class="relative max-lg:hidden">
                            <Link
                                :href="route('dashboard')"
                                class="p-2 text-sm font-semibold text-zinc-800"
                                >LaraDesk</Link
                            >
                        </span>

                        <div
                            class="h-6 w-px bg-zinc-950/10 max-lg:hidden"
                            aria-hidden="true"
                        ></div>

                        <div class="flex items-center gap-3 max-lg:hidden">
                            <NavLink
                                :href="route('dashboard')"
                                :is-active="route().current('dashboard')"
                                >Dashboard</NavLink
                            >
                            <NavLink
                                :href="route('customer.index')"
                                :is-active="route().current('customer.index')"
                                >Customers</NavLink
                            >
                        </div>

                        <div class="-ml-4 flex-1" aria-hidden="true"></div>

                        <div class="flex items-center gap-3">
                            <Dropdown>
                                <template #trigger>
                                    <div
                                        class="flex cursor-pointer items-center gap-x-3 rounded-lg p-1.5 hover:bg-zinc-950/10"
                                    >
                                        <img
                                            src="//picsum.photos/64"
                                            alt=""
                                            class="size-6 rounded-md"
                                        />
                                    </div>
                                </template>

                                <template #content>
                                    <div class="p-2 text-black">
                                        <DropdownLink
                                            @click="handleLogout"
                                            href="#!"
                                            >Logout</DropdownLink
                                        >
                                    </div>
                                </template>
                            </Dropdown>
                        </div>
                    </nav>
                </div>
            </header>

            <main class="flex flex-1 flex-col pb-2 lg:px-2">
                <div
                    class="grow p-6 lg:rounded-lg lg:bg-white lg:p-10 lg:shadow-sm lg:ring-1 lg:ring-zinc-950/5"
                >
                    <div class="mx-auto max-w-6xl">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
