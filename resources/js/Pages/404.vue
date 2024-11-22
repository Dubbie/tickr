<script setup>
import TheButton from '@/Components/TheButton.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { IconArrowLeft } from '@tabler/icons-vue';

const handleBack = () => {
    window.history.back();
};
</script>

<template>
    <SidebarLayout title="404">
        <div class="flex flex-col items-center pt-12">
            <p class="mb-3 text-xs font-semibold text-indigo-500">404 error</p>
            <h1 class="mb-6 text-4xl font-semibold">We lost this page</h1>

            <div class="text-center text-sm text-zinc-500 dark:text-zinc-400">
                <p>
                    We searched high and low, but couldn't find what you're
                    looking for.
                </p>
                <p>Let's find a better place for you to go.</p>
            </div>

            <div class="mt-6 flex items-center justify-center gap-x-3">
                <TheButton @click="handleBack">
                    <IconArrowLeft class="size-4 opacity-50" />
                    <span>Go back</span>
                </TheButton>
                <TheButton variant="primary" :href="route('dashboard')"
                    >Go to Dashboard</TheButton
                >
            </div>
        </div>
    </SidebarLayout>
</template>
