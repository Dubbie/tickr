<script setup>
import { IconBookmarkFilled } from '@tabler/icons-vue';
import { computed } from 'vue';

const { priority, size, interactive } = defineProps({
    priority: {
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

const priorityLabel = computed(() => {
    return {
        low: 'Low',
        medium: 'Medium',
        high: 'High',
    }[priority];
});

const colorClasses = computed(() => {
    return {
        low: 'text-teal-500',
        medium: 'text-indigo-500',
        high: 'text-red-500',
    }[priority];
});

const sizeClasses = computed(() => {
    return {
        sm: 'h-5 px-2',
        md: 'h-7 px-2.5',
    }[size];
});

const interactiveClasses = computed(() => {
    return interactive ? 'cursor-pointer hover:bg-zinc-900/5' : '';
});
</script>

<template>
    <div
        class="inline-flex items-center justify-center gap-x-1 rounded-md text-xs font-semibold"
        :class="[sizeClasses, interactiveClasses]"
    >
        <IconBookmarkFilled
            class="size-4"
            :class="colorClasses"
            stroke-width="2"
        />
        <p>{{ priorityLabel }}</p>
    </div>
</template>
