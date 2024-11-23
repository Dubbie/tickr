<script setup>
import { EVENTS } from '@/constants';
import { Link, useForm } from '@inertiajs/vue3';
import { inject, nextTick, onMounted, ref, watch } from 'vue';
import Modal from './Modal.vue';
import TextInput from './TextInput.vue';
import axios from 'axios';

let abortController = null;

const emitter = inject('emitter');
const loading = ref(false);
const results = ref([]);
const show = ref(false);
const input = ref();
const debounceTimeout = ref(null);
const titleClasses =
    'mb-1 text-xs font-medium text-zinc-500 dark:text-zinc-400';

const form = useForm({
    search: '',
});

const showSearch = () => {
    form.reset();
    show.value = true;

    nextTick(() => {
        input.value.focus();
    });
};

const fetchResults = async () => {
    if (abortController) {
        abortController.abort();
    }

    abortController = new AbortController();
    loading.value = true;

    try {
        const response = await axios.get(route('api.search'), {
            params: { ...form.data() },
        });

        results.value = response.data;
    } catch (err) {
        console.log('Error while searching');
        console.log(err);
    } finally {
        loading.value = false;
    }
};

watch(
    () => form.search,
    () => {
        if (form.search.length < 3) {
            results.value = [];
            return;
        }

        clearTimeout(debounceTimeout.value);

        debounceTimeout.value = setTimeout(() => {
            fetchResults();
        }, 300);
    },
);

onMounted(() => {
    emitter.on(EVENTS.SHOW_SEARCH, showSearch);
});
</script>

<template>
    <Modal :show="show" @close="show = false" max-width="sm">
        <div class="p-4">
            <TextInput
                ref="input"
                v-model="form.search"
                class="w-full"
                placeholder="Search everywhere..."
                autofocus
            />

            <div class="mt-4">
                <p class="mb-1 font-semibold">Results</p>

                <p
                    v-if="!results.tickets"
                    class="text-sm text-zinc-500 dark:text-zinc-400"
                >
                    Start typing to seach.
                </p>

                <div v-else class="space-y-3 text-sm">
                    <div v-if="results.tickets.length > 0">
                        <p :class="titleClasses">Tickets</p>

                        <div
                            v-for="ticket in results.tickets"
                            :key="ticket.uuid"
                            class="-mx-2 -my-1"
                        >
                            <Link
                                :href="
                                    route('ticket.show', ticket.ticket_number)
                                "
                                class="block rounded-md px-2 py-1 hover:bg-zinc-900/5 dark:hover:bg-white/5"
                            >
                                <p class="truncate">{{ ticket.subject }}</p>
                            </Link>
                        </div>
                    </div>

                    <div v-if="results.customers.length > 0">
                        <p :class="titleClasses">Customers</p>

                        <div
                            v-for="customer in results.customers"
                            :key="customer.uuid"
                            class="-mx-2 -my-1"
                        >
                            <Link
                                :href="route('customer.show', customer.uuid)"
                                class="block rounded-md px-2 py-1 hover:bg-zinc-900/5 dark:hover:bg-white/5"
                            >
                                <p>{{ customer.name }}</p>
                            </Link>
                        </div>
                    </div>

                    <div v-if="results.users.length > 0">
                        <p :class="titleClasses">Users</p>

                        <div
                            v-for="user in results.users"
                            :key="user.id"
                            class="-mx-2 -my-1"
                        >
                            <Link
                                :href="route('user.show', user)"
                                class="block rounded-md px-2 py-1 hover:bg-zinc-900/5 dark:hover:bg-white/5"
                            >
                                <p>{{ user.name }}</p>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
