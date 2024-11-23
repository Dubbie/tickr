<script setup>
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import PieChart from './PieChart.vue';

const loading = ref(true);
const categories = ref(null);

const chartData = ref(null);
const chartOptions = {
    responsive: true,
    plugins: {
        legend: false,
        tooltip: {
            callbacks: {
                label: function (tooltipItem) {
                    return `${tooltipItem.raw} tickets`;
                },
            },
        },
    },
};

const fetchCategories = async () => {
    loading.value = true;

    try {
        const response = await axios.get(route('api.ticket.ttfr'));
        categories.value = response.data;

        updateChart();
    } catch (err) {
        console.log('Error while loading ttfr chart data');
        console.log(err);
    } finally {
        loading.value = false;
    }
};

const updateChart = () => {
    chartData.value = {
        labels: Object.keys(categories.value),
        datasets: [
            {
                data: Object.values(categories.value),
                backgroundColor: [
                    '#2db976',
                    '#ffc061',
                    '#cab5f8',
                    '#9571dd',
                    '#eb6363',
                ],
            },
        ],
    };
};

const total = computed(() => {
    if (!categories.value) return 0;

    let _total = 0;

    Object.values(categories.value).forEach((amount) => {
        _total += amount;
    });

    return _total;
});

const getPercentage = (value) => {
    return Math.round((value / total.value) * 100);
};

const getColorByCategory = (category) => {
    return {
        '0-1 hours': '#2db976',
        '1-8 hours': '#ffc061',
        '8-24 hours': '#cab5f8',
        '>24 hours': '#9571dd',
        'No reply': '#eb6363',
    }[category];
};

onMounted(() => {
    fetchCategories();
});
</script>

<template>
    <div class="flex flex-col gap-y-6">
        <p class="text-xl font-semibold">Ticket by First Reply Time</p>

        <div class="flex grow items-center gap-x-6">
            <PieChart
                v-if="categories"
                :chart-data="chartData"
                :chart-options="chartOptions"
                class="max-w-80 grow"
            />

            <div class="flex-1 space-y-4">
                <div
                    v-for="(value, cat) in categories"
                    :key="cat"
                    class="flex items-center justify-between text-sm"
                >
                    <div
                        class="mr-2 size-2 rounded-sm"
                        :style="{
                            backgroundColor: getColorByCategory(cat),
                        }"
                    ></div>
                    <p class="mr-6 grow font-medium text-zinc-500">{{ cat }}</p>
                    <p class="font-semibold">{{ getPercentage(value) }}%</p>
                </div>
            </div>
        </div>
    </div>
</template>
