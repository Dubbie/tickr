<script setup>
import TheButton from '@/Components/TheButton.vue';
import TheCard from '@/Components/TheCard.vue';
import TicketReply from '@/Components/TicketReply.vue';
import { IconArrowLeft } from '@tabler/icons-vue';
import axios from 'axios';
import { inject, onMounted, onUnmounted, provide, ref } from 'vue';
import TicketDetails from './partials/TicketDetails.vue';
import TicketActions from './partials/TicketActions.vue';
import TicketCustomer from './partials/TicketCustomer.vue';
import TicketReplier from './partials/TicketReplier.vue';
import { EVENTS } from '@/constants';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import PageTitle from '@/Components/PageTitle.vue';
import { router } from '@inertiajs/vue3';

const { ticketNumber } = defineProps({
    ticketNumber: {
        type: String,
        required: true,
    },
});

const emitter = inject('emitter');
const ticket = ref(null);
const loadingTicket = ref(false);

provide('ticket', ticket);

const handleBack = () => {
    window.history.back();
};

const fetchTicket = async () => {
    loadingTicket.value = true;

    try {
        const response = await axios.get(
            route('api.ticket.show', ticketNumber),
        );

        ticket.value = response.data.ticket;
    } catch (err) {
        if (err.response?.status === 404) {
            console.log('Ticket not found.');
            router.visit(route('404'));
        } else {
            console.log('Error while loading ticket.');
            console.log(err);
        }
    } finally {
        loadingTicket.value = false;
    }
};

const setUpEvents = () => {
    emitter.on(EVENTS.REFRESH_TICKET, fetchTicket);
};

const tearDownEvents = () => {
    emitter.off(EVENTS.REFRESH_TICKET);
};

onMounted(() => {
    fetchTicket();
    setUpEvents();
});

onUnmounted(() => {
    tearDownEvents();
});
</script>

<template>
    <SidebarLayout :title="ticketNumber">
        <div class="mb-6 mt-0.5">
            <PageTitle margin-bottom="mb-0">{{ ticketNumber }}</PageTitle>
            <TheButton variant="ghost" class="-ml-3" @click="handleBack">
                <IconArrowLeft class="size-4" stroke-width="2" />
                <span>Back</span>
            </TheButton>
        </div>

        <div v-if="!ticket">
            <p>Loading ticket...</p>
        </div>

        <div v-else>
            <div class="grid grid-cols-3 gap-x-6">
                <div class="col-span-2">
                    <TheCard>
                        <div>
                            <p class="text-xs font-medium text-zinc-500">
                                {{ ticket.formatted_created_at }}
                            </p>
                            <h2 class="font-semibold">{{ ticket.subject }}</h2>
                        </div>

                        <div class="mt-3">
                            <p class="text-sm text-zinc-800 dark:text-zinc-200">
                                {{ ticket.description }}
                            </p>
                        </div>
                    </TheCard>

                    <!-- Replies should go here -->
                    <div
                        v-if="ticket.replies.length > 0"
                        class="mt-6 space-y-6"
                    >
                        <TicketReply
                            v-for="reply in ticket.replies"
                            :key="reply.id"
                            :reply="reply"
                        />
                    </div>

                    <!-- Reply here -->
                    <div class="mt-6">
                        <TicketReplier v-if="!ticket.is_archived" />
                        <p v-else class="text-sm font-semibold text-zinc-500">
                            This ticket has been archived, you can no longer
                            reply to it.
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    <TicketCustomer />
                    <TicketActions />
                    <TicketDetails />
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
