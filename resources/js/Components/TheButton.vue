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
    href: String,
});

const colorMap = {
    default: {
        zinc: 'ring-1 ring-inset ring-zinc-900/20 bg-white text-black hover:ring-zinc-900/30',
    },
    primary: {
        zinc: 'bg-black text-white hover:bg-zinc-800 disabled:pointer-events-none disabled:bg-zinc-400',
    },
    ghost: {
        zinc: 'text-zinc-900 hover:bg-zinc-900/10 dark:text-zinc-400 dark:hover:bg-white/5 dark:hover:text-white',
    },
};

const colorClasses = computed(() => {
    return colorMap[props.variant][props.color] ?? '';
});

const sizeClasses = computed(() => {
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
        class="flex items-center justify-center gap-x-1 rounded-md"
        :class="[colorClasses, sizeClasses]"
    >
        <slot />
    </component>
</template>
