<script setup>
import TabContainer from '@/Components/TabContainer.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import TheSkeleton from '@/Components/TheSkeleton.vue';
import TicketLine from '@/Components/TicketLine.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { useTicketStore } from '@/stores/ticketStore';

const ticketStore = useTicketStore();

ticketStore.fetchTickets();
ticketStore.fetchTicketCounts();
</script>

<template>
    <SidebarLayout>
        <h1 class="mb-6 text-xl font-bold text-zinc-800">Tickets</h1>

        <TextInput v-model="ticketStore.form.query" class="w-full text-sm" />

        <TabContainer
            class="my-3"
            :tabs="ticketStore.updatedTabOptions"
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

        <div class="mb-1 h-px bg-zinc-900/10"></div>

        <div class="-mx-3">
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
                mode="out-in"
            >
                <div v-if="ticketStore.loading" class="space-y-1">
                    <TheSkeleton
                        v-for="i in ticketStore.form.perPage"
                        :key="i"
                        class="h-11 w-full"
                    />
                </div>
                <div v-else class="space-y-1">
                    <TicketLine
                        v-for="ticket in ticketStore.tickets"
                        :key="ticket.ticket_number"
                        :ticket="ticket"
                    />
                </div>
            </transition>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between">
            <TheButton
                :disabled="ticketStore.form.page === 1"
                @click="
                    ticketStore.form.page--;
                    ticketStore.fetchTickets();
                "
            >
                Previous
            </TheButton>
            <TheButton
                :disabled="ticketStore.lastPage === ticketStore.form.page"
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
