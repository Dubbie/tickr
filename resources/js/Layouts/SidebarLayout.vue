<script setup>
import SearchModal from '@/Components/SearchModal.vue';
import TheSidebar from '@/Components/TheSidebar.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const { title } = defineProps({
    title: String,
});

const transformedTitle = computed(() => {
    const companyName = usePage().props.company.name;

    if (title) {
        return `${title} - ${companyName}`;
    }

    return companyName;
});
</script>

<template>
    <Head :title="transformedTitle" />

    <div class="min-h-svh bg-white antialiased dark:bg-zinc-800">
        <div class="flex min-h-svh">
            <TheSidebar />

            <div
                class="max-h-svh flex-1 overflow-y-scroll p-6 lg:p-8 dark:text-white"
            >
                <slot />
            </div>
        </div>

        <SearchModal />
    </div>
</template>
