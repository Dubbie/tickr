<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block pb-2 text-sm font-medium text-zinc-500">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
