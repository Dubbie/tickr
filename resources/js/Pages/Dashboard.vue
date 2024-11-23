<script setup>
import PageTitle from '@/Components/PageTitle.vue';
import TheCard from '@/Components/TheCard.vue';
import TheStat from '@/Components/TheStat.vue';
import TicketAverageChart from '@/Components/TicketAverageChart.vue';
import TicketTTFRChart from '@/Components/TicketTTFRChart.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import {
    IconCheck,
    IconClock,
    IconQuestionMark,
    IconTicket,
} from '@tabler/icons-vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const loading = ref(true);
const statComponents = ref([
    {
        label: 'Created tickets',
        icon: IconTicket,
        value: null,
    },
    {
        label: 'Open tickets',
        icon: IconQuestionMark,
        value: null,
    },
    {
        label: 'Solved tickets',
        icon: IconCheck,
        value: null,
    },
    {
        label: 'Average First Time Reply',
        icon: IconClock,
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

onMounted(() => {
    fetchCounts();
});
</script>

<template>
    <SidebarLayout title="Dashboard">
        <PageTitle class="mt-0.5">Dashboard</PageTitle>

        <div class="grid grid-cols-4 gap-x-6">
            <TheStat
                v-for="stat in statComponents"
                :key="stat.label"
                :icon="stat.icon"
                :title="stat.label"
                :value="stat.value"
            />
        </div>

        <div class="mt-6 grid grid-cols-8 gap-x-6">
            <TheCard class="col-span-5">
                <TicketAverageChart />
            </TheCard>
            <TheCard class="col-span-3">
                <TicketTTFRChart />
            </TheCard>
        </div>
    </SidebarLayout>
</template>
