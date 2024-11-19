<script setup>
import { computed } from 'vue';

const { status, size, interactive } = defineProps({
    status: {
        type: String,
        required: true,
    },
    size: {
        type: String,
        default: 'md',
    },
    interactive: {
        type: Boolean,
        default: false,
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
    let baseClasses = {
        open: 'bg-green-500/15 text-green-600',
        in_progress: 'bg-indigo-500/15 text-indigo-600',
        closed: 'bg-teal-500/15 text-teal-600',
        resolved: 'bg-yellow-500/15 text-yellow-600',
    };
    let extra = '';

    if (interactive) {
        baseClasses.open += ' hover:bg-green-500/30';
        baseClasses.in_progress += ' hover:bg-indigo-500/30';
        baseClasses.closed += ' hover:bg-teal-500/30';
        baseClasses.resolved += ' hover:bg-yellow-500/30';

        extra = ' cursor-pointer';
    }

    return baseClasses[status] + extra;
});

const sizeClasses = computed(() => {
    return {
        sm: 'h-5 px-2',
        md: 'h-7 px-2.5',
    }[size];
});
</script>

<template>
    <span
        class="inline-flex items-center justify-center rounded-md text-xs font-semibold"
        :class="[colorClasses, sizeClasses]"
    >
        {{ statusLabel }}
    </span>
</template>
