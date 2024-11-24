<script setup>
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    pill: {
        type: Boolean,
        default: false,
    },
    hasIcon: {
        type: Boolean,
        default: false,
    },
    borderless: {
        type: Boolean,
        default: false,
    },
});

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

const roundingClass = computed(() => {
    return props.pill ? 'rounded-full' : 'rounded-md';
});

const paddingClass = computed(() => {
    if (props.hasIcon) {
        return 'pl-8 py-1 lg:pl-9 lg:py-1.5';
    }

    return props.pill ? 'px-6 py-2.5' : 'px-2 py-1.5 lg:px-3 lg:py-2';
});

const borderClasses = computed(() => {
    if (props.borderless) return 'ring-0 focus:ring-0';

    return 'ring-1 ring-black/15 hover:ring-black/30 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 dark:ring-white/10 dark:hover:ring-white/20 dark:focus:ring-indigo-500';
});

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-none dark:bg-white/5 dark:text-zinc-100"
        :class="[roundingClass, paddingClass, borderClasses]"
        v-model="model"
        ref="input"
    />
</template>
