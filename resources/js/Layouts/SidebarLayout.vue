<script setup>
import TheSidebar from '@/Components/TheSidebar.vue';
</script>

<template>
    <div class="min-h-svh bg-white antialiased dark:bg-zinc-800">
        <div class="flex min-h-svh">
            <TheSidebar />

            <div
                class="max-h-svh flex-1 overflow-y-scroll p-6 lg:p-8 dark:text-white"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
