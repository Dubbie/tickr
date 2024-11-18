<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block pb-1 text-sm font-medium text-zinc-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
