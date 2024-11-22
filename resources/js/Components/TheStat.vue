<script setup>
import TheCard from './TheCard.vue';
import TheSkeleton from './TheSkeleton.vue';

// Props for the component
const { title, value, subtitle } = defineProps({
    title: {
        type: String,
        required: true,
    },
    value: [String, Number],
    subtitle: String,
});
</script>

<template>
    <TheCard padding-class="py-4 px-6">
        <p class="mb-3 text-xs font-semibold text-zinc-500">{{ title }}</p>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            mode="out-in"
        >
            <div v-if="value">
                <p class="font-serif text-3xl font-semibold">{{ value }}</p>
            </div>
            <div v-else>
                <TheSkeleton class="h-9 w-full" />
            </div>
        </transition>

        <p v-if="subtitle">{{ subtitle }}</p>
    </TheCard>
</template>
