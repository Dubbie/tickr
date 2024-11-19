<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    maxWidth: {
        type: String,
        default: 'xs',
    },
});

const maxWidthClass = computed(() => {
    return `max-w-${props.maxWidth}`;
});
</script>

<template>
    <div class="min-h-svh bg-zinc-100">
        <div class="mx-auto py-6 lg:py-12" :class="maxWidthClass">
            <div class="flex flex-col items-center justify-center">
                <img
                    src="//picsum.photos/96"
                    alt="Company logo"
                    class="mb-3 size-16 rounded-xl shadow-lg"
                />

                <h1 class="text-xl font-bold">ACME Inc.</h1>
                <p class="text-xs font-semibold text-zinc-500">
                    Customer Service Center
                </p>
            </div>

            <div class="mt-12">
                <slot />
            </div>

            <footer class="py-6 text-center text-xs">
                <Link
                    :href="route('login')"
                    class="font-medium text-zinc-400 hover:text-zinc-900"
                    >Admin login</Link
                >
            </footer>
        </div>
    </div>
</template>
