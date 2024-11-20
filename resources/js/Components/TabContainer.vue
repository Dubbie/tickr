<script setup>
import TheTab from './TheTab.vue';

defineProps({
    activeTab: {
        type: String,
        required: true,
    },
    tabs: {
        type: Array,
        required: true,
    },
    fullWidth: {
        type: Boolean,
        default: false,
    },
});

const getBadgeColorClass = (color) => {
    return {
        teal: 'bg-teal-500',
        indigo: 'bg-indigo-500',
        green: 'bg-green-500',
    }[color];
};

const emit = defineEmits(['switch-tab']);
</script>

<template>
    <div class="flex gap-x-1 rounded-md bg-zinc-900/5 p-1 dark:bg-white/5">
        <TheTab
            v-for="tab in tabs"
            :key="tab.name"
            :is-active="activeTab === tab.name"
            :class="{ 'w-full': fullWidth }"
            @click="emit('switch-tab', tab.name)"
        >
            <span>{{ tab.label }}</span>
            <span
                v-if="tab.badge"
                class="rounded-md px-1.5 text-center text-xs font-bold text-white"
                :class="getBadgeColorClass(tab.badge.color)"
                >{{ tab.badge.label }}</span
            >
        </TheTab>
    </div>
</template>
