<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    isActive: {
        type: Boolean,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="p-2 text-sm font-semibold"
        :class="{
            'text-zinc-800': isActive,
            'text-zinc-600 hover:text-zinc-800': !isActive,
        }"
    >
        <slot />
    </Link>
</template>
