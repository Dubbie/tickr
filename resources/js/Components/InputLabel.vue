<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label
        class="block pb-2 text-xs font-medium text-zinc-800 dark:text-zinc-100"
    >
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
