<script setup>
import { provide } from 'vue';

const props = defineProps({
    size: {
        type: String,
        default: 'md',
    },
});

provide('tableConfig', props);
</script>

<template>
    <table
        class="min-w-full text-left text-sm/6 text-zinc-900 dark:text-zinc-100"
    >
        <thead>
            <tr>
                <slot name="headings" />
            </tr>
        </thead>
        <tbody>
            <slot name="rows" />
        </tbody>
    </table>
</template>
