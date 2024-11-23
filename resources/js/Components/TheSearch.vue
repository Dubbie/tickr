<script setup>
import { EVENTS } from '@/constants';
import { inject } from 'vue';

const emitter = inject('emitter');
</script>

<template>
    <div>
        <button
            class="w-full cursor-pointer rounded-md border-none bg-zinc-900/5 px-4 py-2.5 text-left text-sm font-medium text-zinc-600 placeholder:text-zinc-400 dark:bg-white/10 dark:text-zinc-500"
            @click="emitter.emit(EVENTS.SHOW_SEARCH)"
        >
            <p>Search...</p>
        </button>
    </div>
</template>
