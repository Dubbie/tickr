<script setup>
import { Pie } from 'vue-chartjs';

import { Chart as ChartJS, ArcElement } from 'chart.js';

ChartJS.register([ArcElement]);

defineProps({
    chartData: {
        type: Object,
        required: true,
    },
    chartOptions: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <div class="aspect-square w-1/2">
        <Pie :data="chartData" :options="chartOptions" />
    </div>
</template>
