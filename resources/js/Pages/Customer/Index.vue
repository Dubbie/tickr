<script setup>
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';

const loading = ref(false);
const customers = ref([]);
const debounceTimeout = ref(null);

const form = useForm({
    query: '',
});

let abortController = null;

const fetchCustomers = async () => {
    if (abortController) {
        abortController.abort();
    }

    abortController = new AbortController();
    loading.value = true;

    try {
        const response = await axios.get(route('api.customer.index'), {
            params: { ...form.data() },
            signal: abortController.signal,
        });

        customers.value = response.data;
    } catch (err) {
        if (axios.isCancel(err)) {
            console.log('Request canceled');
        } else {
            console.log('Error while loading customers!');
            console.log(err);
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCustomers();
});

watch(
    () => form.query,
    () => {
        clearTimeout(debounceTimeout.value);

        debounceTimeout.value = setTimeout(() => {
            fetchCustomers();
        }, 300);
    },
);
</script>

<template>
    <AppLayout>
        <h1 class="mb-6 text-xl font-bold text-zinc-800">Customers</h1>

        <TextInput v-model="form.query" class="mb-6 w-full text-sm" />

        <div class="mb-3 grid grid-cols-3 gap-x-3 text-sm font-bold">
            <p>Name</p>
            <p>Email</p>
            <p>ID</p>
        </div>

        <div v-if="loading">
            <p>Loading customers...</p>
        </div>
        <div v-else>
            <div
                v-for="customer in customers"
                :key="customer.id"
                class="grid grid-cols-3 gap-x-3"
            >
                <p class="font-semibold">{{ customer.name }}</p>
                <p>{{ customer.email }}</p>
                <p>{{ customer.id }}</p>
            </div>
        </div>
    </AppLayout>
</template>
