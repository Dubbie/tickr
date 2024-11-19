<script setup>
defineProps({
    paddingClass: {
        type: String,
        default: 'p-4',
    },
});
</script>

<template>
    <div
        class="rounded-lg bg-white ring-1 ring-zinc-900/10"
        :class="paddingClass"
    >
        <slot />
    </div>
</template>
