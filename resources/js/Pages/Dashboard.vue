<script setup>
import BarChart from '@/Components/BarChart.vue';
import DateRangeInput from '@/Components/DateRangeInput.vue';
import PageTitle from '@/Components/PageTitle.vue';
import TheStat from '@/Components/TheStat.vue';
import TicketTTFRChart from '@/Components/TicketTTFRChart.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const form = useForm({
    range: 'this_week',
});

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

const loading = ref(true);
const statComponents = ref([
    {
        label: 'Created tickets',
        value: null,
    },
    {
        label: 'Open tickets',
        value: null,
    },
    {
        label: 'Solved tickets',
        value: null,
    },
    {
        label: 'Average First Time Reply',
        value: null,
    },
]);

const fetchCounts = async () => {
    loading.value = true;

    try {
        const response = await axios.get(route('api.ticket.counts'));

        statComponents.value[0].value = response.data.total;
        statComponents.value[1].value = response.data.open;
        statComponents.value[2].value = response.data.archived;
        statComponents.value[3].value = response.data.ttfr;
    } catch (err) {
        console.log('Error while loading counts');
        console.log(err);
    } finally {
        loading.value = false;
    }
};

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
    fetchCounts();
    fetchAverages();
});
</script>

<template>
    <SidebarLayout title="Dashboard">
        <PageTitle class="mt-0.5">Dashboard</PageTitle>

        <div class="grid grid-cols-4 gap-x-4">
            <TheStat
                v-for="stat in statComponents"
                :key="stat.label"
                :title="stat.label"
                :value="stat.value"
            />
        </div>

        <div class="mt-12 grid grid-cols-3 gap-x-12">
            <div class="col-span-2">
                <div class="mb-6 flex items-center justify-between">
                    <p class="text-xl font-semibold">Average tickets created</p>

                    <DateRangeInput v-model="form.range" />
                </div>

                <div class="grid grid-cols-4 items-center gap-x-4">
                    <div>
                        <div class="mb-6">
                            <p
                                class="mb-3 text-sm text-zinc-500 dark:text-zinc-400"
                            >
                                Avg. Ticket Created
                            </p>
                            <p class="text-3xl font-semibold">
                                {{ averageCreated }}
                            </p>
                        </div>

                        <div>
                            <p
                                class="mb-3 text-sm text-zinc-500 dark:text-zinc-400"
                            >
                                Avg. Ticket Closed
                            </p>
                            <p class="text-3xl font-semibold">0</p>
                        </div>
                    </div>

                    <div class="col-span-3">
                        <BarChart
                            :chart-data="chartData"
                            :chart-options="chartOptions"
                        />
                    </div>
                </div>
            </div>
            <div>
                <TicketTTFRChart />
            </div>
        </div>
    </SidebarLayout>
</template>
