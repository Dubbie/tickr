<script setup>
defineProps({
    icon: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <div class="relative">
        <slot />

        <component
            :is="icon"
            class="absolute left-3 top-2 size-5 text-zinc-500"
        />
    </div>
</template>
