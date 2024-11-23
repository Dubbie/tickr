<script setup>
import { IconChecks } from '@tabler/icons-vue';
import { computed } from 'vue';

const { size } = defineProps({
    size: {
        type: String,
        default: 'lg',
    },
});

const paddingClass = computed(() => {
    return {
        sm: 'p-2',
        md: 'p-2.5',
        lg: 'p-3',
    }[size];
});

const sizeClass = computed(() => {
    return {
        sm: 'size-4',
        md: 'size-5',
        lg: 'size-6',
    }[size];
});
</script>

<template>
    <div class="rounded-xl bg-teal-500 text-white" :class="paddingClass">
        <IconChecks :class="sizeClass" />
    </div>
</template>
