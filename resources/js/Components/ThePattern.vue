<script setup>
const color = '#cccccc';
</script>

<template>
    <div
        class="bg-zinc-400 opacity-40"
        :style="{
            backgroundSize: '43px 43px',
            backgroundImage: `linear-gradient(${color} 1px, transparent 1px), linear-gradient(to right, ${color} 1px, #ffffff 1px)`,
        }"
    ></div>
</template>
