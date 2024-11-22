<script setup>
import { Link, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const { maxWidth, title } = defineProps({
    maxWidth: {
        type: String,
        default: 'xs',
    },
    title: String,
});

const maxWidthClass = computed(() => {
    return `max-w-${maxWidth}`;
});

const transformedTitle = computed(() => {
    const companyName = usePage().props.company.name + ' CSC';

    if (title) {
        return `${title} - ${companyName}`;
    }

    return companyName;
});
</script>

<template>
    <Head :title="transformedTitle" />

    <div class="min-h-svh bg-zinc-100">
        <div class="mx-auto py-6 lg:py-12" :class="maxWidthClass">
            <div class="flex flex-col items-center justify-center">
                <img
                    src="//picsum.photos/96"
                    alt="Company logo"
                    class="mb-3 size-16 rounded-xl shadow-lg"
                />

                <h1 class="text-xl font-bold">
                    {{ $page.props.company.name }}
                </h1>
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
