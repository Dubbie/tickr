<script setup>
import { computed } from 'vue';

const props = defineProps({
    marginBottom: {
        type: String,
        default: 'mb-6',
    },
    size: {
        type: String,
        default: 'lg',
    },
});

const sizeClasses = computed(() => {
    return {
        sm: 'text-base font-semibold',
        md: 'text-lg font-bold',
        lg: 'text-2xl font-bold',
    }[props.size];
});
</script>

<template>
    <h1
        class="text-zinc-800 dark:text-white"
        :class="[marginBottom, sizeClasses]"
    >
        <slot />
    </h1>
</template>
