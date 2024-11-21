<script setup>
import NewTicketModal from '@/Components/NewTicketModal.vue';
import PageTitle from '@/Components/PageTitle.vue';
import TabContainer from '@/Components/TabContainer.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import TicketsList from '@/Components/TicketsList.vue';
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
            :tabs="ticketStore.updatedTabOptions"
            :active-tab="ticketStore.tab"
            @switch-tab="ticketStore.setTab($event)"
        />

        <TicketsList
            class="mt-6"
            :tickets="ticketStore.tickets"
            :loading="ticketStore.loading"
            :current-page="ticketStore.form.page"
            :per-page="ticketStore.form.perPage"
            :last-page="ticketStore.lastPage"
            checkboxes
            @change-page="ticketStore.form.page = $event"
        />

        <NewTicketModal
            :show="showNewTicketModal"
            @close="showNewTicketModal = false"
        />
    </SidebarLayout>
</template>
