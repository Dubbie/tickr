<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-md border-none pl-9 pr-3 ring-1 ring-inset ring-zinc-900/20 hover:ring-zinc-900/30 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        v-model="model"
        ref="input"
    />
</template>
