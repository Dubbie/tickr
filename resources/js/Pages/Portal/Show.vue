<script setup>
import AppGuestLayout from '@/Layouts/AppGuestLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import TicketDetails from './partials/TicketDetails.vue';
import TheButton from '@/Components/TheButton.vue';
import { IconArrowLeft } from '@tabler/icons-vue';

const { ticketNumber } = defineProps({
    ticketNumber: {
        type: String,
        required: true,
    },
});

const customer = usePage().props.customer;
const loadingTicket = ref(false);
const ticket = ref(null);

const fetchTicket = async () => {
    loadingTicket.value = true;

    try {
        const response = await axios.get(
            route('api.customer.ticket.show', {
                link: customer.unique_link,
                ticketNumber: ticketNumber,
            }),
        );

        ticket.value = response.data;
    } catch (err) {
        console.log('Error while loading ticket.');
        console.log(err);
    } finally {
        loadingTicket.value = false;
    }
};

const handleBack = () => {
    window.history.back();
};

onMounted(() => {
    fetchTicket();
});
</script>

<template>
    <AppGuestLayout max-width="lg" :title="ticketNumber">
        <TheButton variant="ghost" class="mb-3" @click="handleBack">
            <IconArrowLeft class="size-4" />
            <span>Back to tickets</span>
        </TheButton>

        <div v-if="loadingTicket">
            <p>Loading ticket details...</p>
        </div>

        <div v-else>
            <div v-if="!ticket">Something went wrong...</div>
            <TicketDetails
                v-else
                :ticket="ticket"
                @update-ticket="fetchTicket"
            />
        </div>
    </AppGuestLayout>
</template>
