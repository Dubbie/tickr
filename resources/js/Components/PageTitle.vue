<script setup>
defineProps({
    marginBottom: {
        type: String,
        default: 'mb-6',
    },
});
</script>

<template>
    <h1
        class="text-xl font-bold text-zinc-800 dark:text-white"
        :class="marginBottom"
    >
        <slot />
    </h1>
</template>
