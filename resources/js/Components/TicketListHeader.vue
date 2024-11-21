<script setup>
import { ref } from 'vue';
import Checkbox from './Checkbox.vue';

defineProps({
    showCheckbox: {
        type: Boolean,
        default: false,
    },
});

const checked = ref(false);
</script>

<template>
    <div
        class="flex gap-x-6 rounded-tl-lg rounded-tr-lg border-b border-b-zinc-900/10 bg-zinc-50 px-5 py-2 dark:border-b-white/5 dark:bg-white/5"
    >
        <Checkbox v-if="showCheckbox" v-model:checked="checked" />
        <div
            class="grid grow grid-cols-8 gap-x-6 text-xs font-semibold text-zinc-500 dark:text-zinc-400"
        >
            <p class="text-left">Ticket ID</p>
            <p class="col-span-2">Subject</p>
            <p class="col-span-2">Customer</p>
            <p class="text-center">Priority</p>
            <p class="text-center">Status</p>
            <p class="text-right">Last update</p>
        </div>
    </div>
</template>
