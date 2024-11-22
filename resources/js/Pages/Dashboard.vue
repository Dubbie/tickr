<script setup>
import PageTitle from '@/Components/PageTitle.vue';
import TheStat from '@/Components/TheStat.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';

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
    }
};

onMounted(() => {
    fetchCounts();
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
    </SidebarLayout>
</template>
