<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    isActive: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="flex items-center gap-x-2 rounded-md px-3 py-1.5 text-sm font-semibold"
        :class="{
            'bg-white text-zinc-800 shadow-sm ring-1 ring-inset ring-zinc-900/10':
                isActive,
            'text-zinc-500 hover:bg-zinc-900/5 hover:text-zinc-800': !isActive,
        }"
    >
        <component :is="icon" class="size-4" />
        <slot />
    </Link>
</template>
