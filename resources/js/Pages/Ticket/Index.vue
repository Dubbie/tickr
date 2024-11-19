<script setup>
import TextInput from '@/Components/TextInput.vue';
import TicketPriority from '@/Components/TicketPriority.vue';
import TicketStatus from '@/Components/TicketStatus.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';

const loading = ref(false);
const tickets = ref([]);
const debounceTimeout = ref(null);

const form = useForm({
    query: '',
});

let abortController = null;

const fetchTickets = async () => {
    if (abortController) {
        abortController.abort();
    }

    abortController = new AbortController();
    loading.value = true;

    try {
        const response = await axios.get(route('api.ticket.index'), {
            params: { ...form.data() },
            signal: abortController.signal,
        });

        tickets.value = response.data;
    } catch (err) {
        if (axios.isCancel(err)) {
            console.log('Request canceled');
        } else {
            console.log('Error while loading tickets!');
            console.log(err);
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchTickets();
});

watch(
    () => form.query,
    () => {
        clearTimeout(debounceTimeout.value);

        debounceTimeout.value = setTimeout(() => {
            fetchTickets();
        }, 300);
    },
);
</script>

<template>
    <AppLayout>
        <h1 class="mb-6 text-xl font-bold text-zinc-800">Tickets</h1>

        <TextInput v-model="form.query" class="mb-6 w-full text-sm" />

        <div class="-mx-3">
            <div class="mb-3 grid grid-cols-6 gap-x-3 px-3 text-sm font-bold">
                <p>Customer</p>
                <p>Subject</p>
                <p>Assigned to</p>
                <p class="text-center">Status</p>
                <p class="text-center">Priority</p>
                <p class="text-center">Updated at</p>
            </div>

            <div v-if="loading">
                <p>Loading tickets...</p>
            </div>
            <div v-else>
                <Link
                    v-for="ticket in tickets"
                    :key="ticket.ticket_number"
                    class="grid grid-cols-6 items-center gap-x-3 rounded-md px-3 py-2 hover:bg-zinc-900/5"
                    :class="{ 'opacity-50': ticket.status === 'resolved' }"
                    :href="route('ticket.show', ticket.ticket_number)"
                >
                    <div>
                        <p class="text-sm font-semibold">
                            {{ ticket.customer.name }}
                        </p>
                        <p class="text-xs font-semibold text-zinc-500">
                            {{ ticket.customer.email }}
                        </p>
                    </div>
                    <p class="truncate text-sm font-semibold">
                        {{ ticket.subject }}
                    </p>
                    <div>
                        <div class="flex space-x-3 text-sm font-medium">
                            <template v-if="ticket.assignee">
                                <img
                                    :src="ticket.assignee.profile_photo_url"
                                    :alt="`${ticket.assignee.name}'s profile photo`"
                                    class="size-6 rounded-md"
                                />
                                <p>
                                    {{ ticket.assignee.name }}
                                </p>
                            </template>
                            <template v-else>
                                <div
                                    class="size-6 rounded-md border border-dashed border-zinc-400"
                                ></div>

                                <p class="text-zinc-400">Noone</p>
                            </template>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <TicketStatus :status="ticket.status" />
                    </div>
                    <div class="flex justify-center">
                        <TicketPriority :priority="ticket.priority" />
                    </div>
                    <p class="text-center text-sm font-medium">
                        {{ ticket.formatted_updated_at }}
                    </p>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
