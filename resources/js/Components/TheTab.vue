<script setup>
import { defineProps } from 'vue';

defineProps({
    isActive: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <p
        class="flex cursor-pointer items-baseline justify-center gap-x-2 rounded px-3 py-1 text-sm font-semibold transition-all"
        :class="{
            'bg-white': isActive,
            'opacity-50': !isActive,
        }"
    >
        <slot />
    </p>
</template>
