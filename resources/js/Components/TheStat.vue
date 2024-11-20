<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: String,
        required: true,
    },
    subtitle: String,
});
</script>

<template>
    <div class="px-8">
        <p class="mb-3 text-xs font-semibold text-zinc-500">{{ title }}</p>

        <p class="font-serif text-3xl font-semibold">{{ value }}</p>

        <p v-if="subtitle">{{ subtitle }}</p>
    </div>
</template>
