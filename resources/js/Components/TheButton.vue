<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    color: {
        type: String,
        default: 'zinc',
    },
    type: {
        type: String,
        default: 'button',
    },
    size: {
        type: String,
        default: 'md',
    },
    variant: {
        type: String,
        default: 'default',
    },
    square: {
        type: Boolean,
        default: false,
    },
    href: String,
});

const colorMap = {
    default: {
        zinc: 'ring-1 ring-inset ring-zinc-900/20 bg-white text-black hover:ring-zinc-900/30 disabled:text-zinc-400 disabled:ring-zinc-400/20 disabled:pointer-events-none dark:bg-white/5 dark:text-white dark:ring-white/10 dark:hover:ring-white/20 dark:hover:bg-white/10 disabled:dark:ring-white/5 disabled:dark:text-zinc-600',
    },
    primary: {
        zinc: 'bg-zinc-800 text-white hover:bg-zinc-950 disabled:pointer-events-none disabled:bg-zinc-400 dark:bg-white dark:text-black dark:hover:bg-zinc-200',
    },
    ghost: {
        zinc: 'text-zinc-900 hover:bg-zinc-900/10 dark:text-zinc-400 dark:hover:bg-white/5 dark:hover:text-white',
    },
};

const colorClasses = computed(() => {
    return colorMap[props.variant][props.color] ?? '';
});

const sizeClasses = computed(() => {
    if (props.square) {
        return {
            sm: 'size-7',
            md: 'size-8',
        }[props.size];
    }

    return {
        sm: 'text-sm/6 font-semibold px-2 py-1',
        md: 'text-sm/6 font-semibold px-2 py-1 lg:px-3 lg:py-1.5',
    }[props.size];
});
</script>

<template>
    <component
        :is="href ? Link : 'button'"
        :href="href"
        class="flex cursor-pointer items-center justify-center gap-x-1 rounded-md"
        :class="[colorClasses, sizeClasses]"
    >
        <slot />
    </component>
</template>
