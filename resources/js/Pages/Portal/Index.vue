<script setup>
import TheButton from '@/Components/TheButton.vue';
import AppGuestLayout from '@/Layouts/AppGuestLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const customer = usePage().props.customer;
const loading = ref(true);
const tickets = ref([]);
const tab = ref('open');

const fetchTickets = async () => {
    loading.value = true;

    try {
        const response = await axios.get(
            route('api.customer.ticket.index', customer.unique_link),
        );

        tickets.value = response.data.tickets;
    } catch (err) {
        console.log('Error while fetching tickets.');
        console.log(err);
    } finally {
        loading.value = false;
    }
};

const openTickets = computed(() => {
    if (tickets.value.length === 0) return [];

    // Filter based on statuses
    return tickets.value.filter((ticket) => ticket.status !== 'closed');
});

const archivedTickets = computed(() => {
    if (tickets.value.length === 0) return [];

    // Filter based on statuses
    return tickets.value.filter((ticket) => ticket.status === 'closed');
});

const filteredTickets = computed(() => {
    if (tab.value === 'open') {
        return openTickets.value;
    }

    return archivedTickets.value;
});

onMounted(() => {
    fetchTickets();
});
</script>

<template>
    <AppGuestLayout max-width="lg">
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold">Tickets</p>

            <TheButton :href="route('portal.create', customer.unique_link)"
                >New ticket</TheButton
            >
        </div>

        <div class="flex gap-x-3 py-2 text-sm font-semibold">
            <p @click="tab = 'open'">
                <span class="mr-2">Open tickets</span>
                <span
                    class="rounded-md bg-indigo-400 px-1.5 text-xs font-bold text-white"
                    >{{ openTickets.length }}</span
                >
            </p>

            <p @click="tab = 'archived'">
                <span class="mr-2">Archived</span>
                <span
                    class="rounded-md bg-teal-400 px-1.5 text-xs font-bold text-white"
                    >{{ archivedTickets.length }}</span
                >
            </p>
        </div>

        <div v-if="loading" class="space-y-3">
            <div
                v-for="i in 5"
                :key="i"
                class="h-16 animate-pulse rounded-md bg-zinc-200"
            ></div>
        </div>

        <div v-else class="space-y-3">
            <div
                v-for="ticket in filteredTickets"
                :key="ticket.ticket_number"
                class="rounded-md px-4 py-3 ring-1 ring-zinc-900/10"
            >
                <div class="flex items-start">
                    <div class="grow">
                        <p class="text-xs font-semibold text-zinc-400">
                            {{ ticket.created_at }}
                        </p>

                        <p class="text-sm font-medium text-zinc-800">
                            {{ ticket.subject }}
                        </p>
                    </div>

                    <span>{{ ticket.status }}</span>
                </div>
            </div>
        </div>
    </AppGuestLayout>
</template>
