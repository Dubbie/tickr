<script setup>
import TheCard from './TheCard.vue';
import TheSkeleton from './TheSkeleton.vue';

// Props for the component
const { title, value, subtitle } = defineProps({
    title: {
        type: String,
        required: true,
    },
    icon: {
        type: Function,
        required: true,
    },
    value: [String, Number],
    subtitle: String,
});
</script>

<template>
    <TheCard class="flex items-start gap-x-4">
        <div class="rounded-md p-2 ring-1 ring-zinc-900/10 dark:ring-white/10">
            <component
                :is="icon"
                class="size-5 text-zinc-600 dark:text-zinc-400"
            />
        </div>

        <div>
            <p class="mb-1 text-xs font-semibold text-zinc-500">{{ title }}</p>

            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
                mode="out-in"
            >
                <div v-if="value !== null">
                    <p class="text-3xl font-bold">{{ value }}</p>
                </div>
                <div v-else>
                    <TheSkeleton class="h-9 w-full" />
                </div>
            </transition>

            <p v-if="subtitle">{{ subtitle }}</p>
        </div>
    </TheCard>
</template>
