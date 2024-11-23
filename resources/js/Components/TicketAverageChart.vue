<script setup>
import { useForm } from '@inertiajs/vue3';
import BarChart from './BarChart.vue';
import DateRangeInput from './DateRangeInput.vue';
import { onMounted, ref } from 'vue';

const form = useForm({
    range: 'last_week',
});

const loading = ref(true);
const averageCreated = ref(0);
const averageSolved = ref(0);
const chartData = ref({
    labels: ['January', 'February', 'March'],
    datasets: [
        {
            label: 'Tickets created',
            data: [40, 20, 12],
        },
    ],
});

const fetchAverages = async () => {
    loading.value = true;

    try {
        const response = await axios.get(route('api.ticket.averages'));

        // Reassign chartData with new values to ensure reactivity
        chartData.value = {
            labels: Object.keys(response.data.created.daily_counts),
            datasets: [
                {
                    label: 'Tickets solved',
                    data: Object.values(response.data.solved.daily_counts),
                    backgroundColor: '#6963b5',
                },
                {
                    label: 'Tickets created',
                    data: Object.values(response.data.created.daily_counts),
                    backgroundColor: '#bda2f6',
                    borderRadius: {
                        topLeft: 12,
                        topRight: 12,
                    },
                },
            ],
        };

        // Update average
        averageCreated.value = response.data.created.average;
        averageSolved.value = response.data.created.solved;
    } catch (err) {
        console.log('Error while loading averages.');
        console.log(err);
    } finally {
        loading.value = false;
    }
};

const chartOptions = {
    responsive: true,
    elements: {
        bar: {
            borderSkipped: false,
        },
    },
    scales: {
        x: {
            grid: {
                display: false,
                lineWidth: 0,
            },
            stacked: true,
        },
        y: {
            alignToPixels: true,
            grid: {
                borderDash: [1, 1],
                lineWidth: 2,
                drawTicks: false,
            },
            border: {
                display: false,
                dash: function (context) {
                    if (context.index > 0) {
                        return [2, 4];
                    }
                },
            },
            ticks: {
                padding: 10,
            },
            stacked: true,
            beginAtZero: true,
        },
    },
    plugins: {
        legend: false,
    },
};

onMounted(() => {
    fetchAverages();
});
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <p class="text-xl font-semibold">Average tickets created</p>

            <DateRangeInput v-model="form.range" />
        </div>

        <div class="flex items-center gap-x-6">
            <div class="w-1/4">
                <div class="mb-6">
                    <p class="mb-3 text-sm text-zinc-500 dark:text-zinc-400">
                        Avg. Ticket Created
                    </p>
                    <p class="font-serif text-3xl font-bold">
                        {{ averageCreated }}
                    </p>
                </div>

                <div>
                    <p class="mb-3 text-sm text-zinc-500 dark:text-zinc-400">
                        Avg. Ticket Closed
                    </p>
                    <p class="font-serif text-3xl font-bold">0</p>
                </div>
            </div>

            <div class="flex aspect-video w-3/4 items-center">
                <BarChart
                    :chart-data="chartData"
                    :chart-options="chartOptions"
                />
            </div>
        </div>
    </div>
</template>
