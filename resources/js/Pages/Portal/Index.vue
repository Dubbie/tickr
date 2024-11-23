<script setup>
import CustomerTicket from '@/Components/CustomerTicket.vue';
import TabContainer from '@/Components/TabContainer.vue';
import TheButton from '@/Components/TheButton.vue';
import AppGuestLayout from '@/Layouts/AppGuestLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { IconPlus } from '@tabler/icons-vue';
import { computed, onMounted, ref } from 'vue';

const customer = usePage().props.customer;
const loading = ref(true);
const tickets = ref([]);

// Function to fetch tickets
const fetchTickets = async () => {
    loading.value = true;
    try {
        const { data } = await axios.get(
            route('api.customer.ticket.index', customer.unique_link),
        );
        tickets.value = data.tickets;
    } catch (error) {
        console.error('Error fetching tickets:', error);
    } finally {
        loading.value = false;
    }
};

// Computed properties for ticket filtering
const archivedStatuses = ['closed', 'resolved'];
const openTickets = computed(() =>
    tickets.value.filter((ticket) => !archivedStatuses.includes(ticket.status)),
);
const archivedTickets = computed(() =>
    tickets.value.filter((ticket) => archivedStatuses.includes(ticket.status)),
);

const filteredTickets = computed(() =>
    activeTab.value === 'open' ? openTickets.value : archivedTickets.value,
);

const tabOptions = computed(() => {
    return [
        {
            label: 'Open tickets',
            name: 'open',
            badge: {
                label: openTickets.value.length,
                color: 'indigo',
            },
        },
        {
            label: 'Archived',
            name: 'archived',
            badge: {
                label: archivedTickets.value.length,
                color: 'teal',
            },
        },
    ];
});
const activeTab = ref(tabOptions.value[0].name);

// Fetch tickets when component mounts
onMounted(fetchTickets);
</script>

<template>
    <AppGuestLayout max-width="lg" title="Tickets">
        <h1 class="text-2xl font-bold">Tickets</h1>

        <div class="flex items-center justify-between">
            <TabContainer
                class="my-3"
                :tabs="tabOptions"
                :active-tab="activeTab"
                @switch-tab="activeTab = $event"
            />

            <TheButton
                variant="primary"
                :href="route('portal.create', customer.unique_link)"
            >
                <IconPlus class="size-4" stroke-width="2.5" />
                <span>New ticket</span>
            </TheButton>
        </div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            mode="out-in"
        >
            <div v-if="loading" class="space-y-3">
                <div
                    v-for="i in 5"
                    :key="i"
                    class="h-16 animate-pulse rounded-md bg-zinc-200"
                ></div>
            </div>

            <div v-else>
                <div
                    v-if="filteredTickets.length === 0"
                    class="rounded-md bg-white py-6 text-center"
                >
                    <p class="text-sm font-medium">
                        No tickets found in this category.
                    </p>
                </div>

                <div v-else class="space-y-3">
                    <CustomerTicket
                        v-for="ticket in filteredTickets"
                        :key="ticket.ticket_number"
                        :ticket="ticket"
                    />
                </div>
            </div>
        </transition>
    </AppGuestLayout>
</template>
