<script setup>
import { computed, inject } from 'vue';

const config = inject('tableConfig');

const sizeClasses = computed(() => {
    return {
        sm: 'px-4 py-2',
        md: 'px-4 py-3',
    }[config.size];
});
</script>

<template>
    <td
        class="relative border-b border-zinc-950/5 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] sm:first:pl-1 sm:last:pr-1 dark:border-white/5"
        :class="sizeClasses"
    >
        <slot />
    </td>
</template>
