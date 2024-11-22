<script setup>
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const { maxWidth } = defineProps({
    maxWidth: {
        type: String,
        default: 'xs',
    },
});

const maxWidthClass = computed(() => {
    return `max-w-${maxWidth}`;
});
</script>

<template>
    <Head title="Setup Wizard - Tickr" />

    <div class="min-h-svh bg-zinc-100">
        <div class="mx-auto py-6 lg:py-12" :class="maxWidthClass">
            <div class="flex flex-col items-center justify-center">
                <h1 class="text-xl font-bold">Setup Wizard</h1>
            </div>

            <div class="mt-12">
                <slot />
            </div>
        </div>
    </div>
</template>
