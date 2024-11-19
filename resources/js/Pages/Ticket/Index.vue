<script setup>
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
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

        <div class="mb-3 grid grid-cols-3 gap-x-3 text-sm font-bold">
            <p>Customer</p>
            <p>Subject</p>
            <p>Status</p>
        </div>

        <div v-if="loading">
            <p>Loading tickets...</p>
        </div>
        <div v-else>
            <div
                v-for="ticket in tickets"
                :key="ticket.ticket_number"
                class="grid grid-cols-3 gap-x-3"
            >
                <p class="font-semibold">{{ ticket.customer.name }}</p>
                <p>{{ ticket.subject }}</p>
                <p>{{ ticket.status }}</p>
            </div>
        </div>
    </AppLayout>
</template>
