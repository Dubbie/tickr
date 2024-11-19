<script setup>
import { computed } from 'vue';

const { status } = defineProps({
    status: {
        type: String,
        required: true,
    },
});

const statusLabel = computed(() => {
    return {
        open: 'Open',
        in_progress: 'In Progress',
        closed: 'Closed',
        resolved: 'Resolved',
    }[status];
});

const colorClasses = computed(() => {
    return {
        open: 'bg-green-500/15 text-green-500',
        in_progress: 'bg-indigo-500/15 text-indigo-500',
        closed: 'bg-teal-500/15 text-teal-500',
        resolved: 'bg-yellow-500/15 text-yellow-500',
    }[status];
});
</script>

<template>
    <span
        class="inline-flex h-5 items-center justify-center rounded-md px-2 text-xs font-semibold"
        :class="colorClasses"
    >
        {{ statusLabel }}
    </span>
</template>
