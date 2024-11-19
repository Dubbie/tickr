<script setup>
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    pill: {
        type: Boolean,
        default: false,
    },
    rows: {
        type: [Number, String],
        default: 6,
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
    return props.pill ? 'px-6 py-2.5' : 'px-2 py-1.5 lg:px-3 lg:py-2';
});

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        class="border-none ring-1 ring-inset ring-zinc-900/10 hover:ring-zinc-900/20 focus:ring-2 focus:ring-inset focus:ring-indigo-500"
        :class="[roundingClass, paddingClass]"
        v-model="model"
        ref="input"
        :rows="rows"
    />
</template>
