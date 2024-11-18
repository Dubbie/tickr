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
        class="rounded-md border-2 border-black pl-9 pr-3 shadow-[0_2px_0_0_rgb(0,0,0)] focus:border-indigo-500 focus:shadow-[0_2px_0_0_rgb(99,102,241)] focus:outline-none focus:ring-0"
        v-model="model"
        ref="input"
    />
</template>
