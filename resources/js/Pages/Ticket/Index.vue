<script setup>
import TabContainer from '@/Components/TabContainer.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import TicketLine from '@/Components/TicketLine.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { useTicketStore } from '@/stores/ticketStore';

const ticketStore = useTicketStore();

ticketStore.fetchTickets();
</script>

<template>
    <SidebarLayout>
        <h1 class="mb-6 text-xl font-bold text-zinc-800">Tickets</h1>

        <TextInput v-model="ticketStore.form.query" class="w-full text-sm" />

        <TabContainer
            class="my-3"
            :tabs="ticketStore.tabOptions"
            :active-tab="ticketStore.tab"
            @switch-tab="ticketStore.setTab($event)"
        />

        <div class="-mx-3 mt-3">
            <div
                class="mb-3 grid grid-cols-6 gap-x-3 px-3 text-xs font-semibold text-zinc-600"
            >
                <p class="text-center">Ticket ID</p>
                <p>Subject</p>
                <p class="text-center">Priority</p>
                <p class="text-center">Status</p>
                <p>Customer</p>
                <p class="text-center">Last update</p>
            </div>
        </div>

        <div class="h-px bg-zinc-900/10"></div>

        <div class="-mx-3">
            <div v-if="ticketStore.loading">
                <p>Loading tickets...</p>
            </div>
            <div v-else class="space-y-1">
                <TicketLine
                    v-for="ticket in ticketStore.tickets"
                    :key="ticket.ticket_number"
                    :ticket="ticket"
                />
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between">
            <TheButton
                :disabled="ticketStore.form.page === 1 || ticketStore.loading"
                @click="
                    ticketStore.form.page--;
                    ticketStore.fetchTickets();
                "
            >
                Previous
            </TheButton>
            <TheButton
                :disabled="
                    ticketStore.lastPage === ticketStore.form.page ||
                    ticketStore.loading
                "
                @click="
                    ticketStore.form.page++;
                    ticketStore.fetchTickets();
                "
            >
                Next
            </TheButton>
        </div>
    </SidebarLayout>
</template>
