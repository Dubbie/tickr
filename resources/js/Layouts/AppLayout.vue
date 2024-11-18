<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="bg-zinc-900 text-white antialiased lg:bg-zinc-950">
        <div
            class="relative isolate flex min-h-svh w-full flex-col bg-zinc-900 lg:bg-zinc-950"
        >
            <header class="flex items-center px-4">
                <div class="min-w-0 flex-1 text-white">
                    <nav class="flex flex-1 items-center gap-4 py-2.5">
                        <span class="relative max-lg:hidden">
                            <Link
                                :href="route('dashboard')"
                                class="p-2 font-bold"
                                >LHD</Link
                            >
                        </span>

                        <div
                            class="h-6 w-px bg-zinc-950/10 max-lg:hidden dark:bg-white/10"
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
                                        class="flex cursor-pointer items-center gap-x-3"
                                    >
                                        <img
                                            src="//picsum.photos/64"
                                            alt=""
                                            class="size-7 rounded-md"
                                        />
                                        <p class="text-sm font-bold">
                                            {{ $page.props.auth.user.name }}
                                        </p>
                                    </div>
                                </template>

                                <template #content>
                                    <div class="text-black">
                                        <p>This is the content.</p>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>
                    </nav>
                </div>
            </header>

            <main class="flex flex-1 flex-col pb-2 lg:px-2">
                <div
                    class="grow p-6 lg:rounded-lg lg:bg-zinc-900 lg:p-10 lg:shadow-sm lg:ring-1 lg:ring-white/10"
                >
                    <div class="mx-auto max-w-6xl">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
