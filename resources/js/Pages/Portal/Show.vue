<script setup>
import AppGuestLayout from '@/Layouts/AppGuestLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import TicketDetails from './partials/TicketDetails.vue';
import TheButton from '@/Components/TheButton.vue';
import { IconArrowLeft } from '@tabler/icons-vue';
import TicketLoading from './partials/TicketLoading.vue';

const { ticketNumber } = defineProps({
    ticketNumber: {
        type: String,
        required: true,
    },
});

const customer = usePage().props.customer;
const loadingTicket = ref(true);
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

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            mode="out-in"
        >
            <TicketLoading v-if="loadingTicket" />

            <div v-else>
                <div v-if="!ticket">Something went wrong...</div>
                <TicketDetails
                    v-else
                    :ticket="ticket"
                    @update-ticket="fetchTicket"
                />
            </div>
        </transition>
    </AppGuestLayout>
</template>
