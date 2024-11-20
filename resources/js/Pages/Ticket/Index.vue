<script setup>
import NewTicketModal from '@/Components/NewTicketModal.vue';
import PageTitle from '@/Components/PageTitle.vue';
import TabContainer from '@/Components/TabContainer.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import ThePagination from '@/Components/ThePagination.vue';
import TheSkeleton from '@/Components/TheSkeleton.vue';
import TicketLine from '@/Components/TicketLine.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { useTicketStore } from '@/stores/ticketStore';
import { IconPlus, IconSearch } from '@tabler/icons-vue';
import { ref } from 'vue';

const ticketStore = useTicketStore();

const showNewTicketModal = ref(false);

ticketStore.fetchTickets();
ticketStore.fetchTicketCounts();
ticketStore.init();
</script>

<template>
    <SidebarLayout>
        <div class="mb-6 flex items-center justify-between gap-x-12">
            <div class="flex flex-1 items-center gap-x-6">
                <PageTitle margin-bottom="mb-0">Tickets</PageTitle>
                <div class="relative flex-1">
                    <TextInput
                        v-model="ticketStore.form.query"
                        class="w-full text-sm/6 md:w-64"
                        placeholder="Search by subject..."
                        has-icon
                    />
                    <IconSearch
                        class="absolute left-2 top-1.5 size-5 text-zinc-400 lg:top-2"
                    />
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-1">
                <TheButton variant="primary" @click="showNewTicketModal = true">
                    <IconPlus class="size-4" />
                    <span>New ticket</span>
                </TheButton>
            </div>
        </div>

        <TabContainer
            class="my-3"
            :tabs="ticketStore.updatedTabOptions"
            :active-tab="ticketStore.tab"
            @switch-tab="ticketStore.setTab($event)"
        />

        <div class="-mx-3 mt-6">
            <div
                class="mb-3 grid grid-cols-8 gap-x-6 px-3 text-xs font-semibold text-zinc-600 dark:text-zinc-500"
            >
                <p class="text-center">Ticket ID</p>
                <p class="col-span-2">Subject</p>
                <p class="col-span-2">Customer</p>
                <p class="text-center">Priority</p>
                <p class="text-center">Status</p>
                <p class="text-center">Last update</p>
            </div>
        </div>

        <div class="mb-1 h-px bg-zinc-900/10 dark:bg-white/10"></div>

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
        <ThePagination
            v-if="ticketStore.tickets.length > 0"
            class="mt-6"
            :current-page="ticketStore.form.page"
            :last-page="ticketStore.lastPage"
            @update:current-page="ticketStore.form.page = $event"
        />

        <NewTicketModal
            :show="showNewTicketModal"
            @close="showNewTicketModal = false"
        />
    </SidebarLayout>
</template>
