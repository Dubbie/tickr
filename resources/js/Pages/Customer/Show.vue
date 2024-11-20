<script setup>
import PageTitle from '@/Components/PageTitle.vue';
import TheButton from '@/Components/TheButton.vue';
import TicketLine from '@/Components/TicketLine.vue';
import TicketListHeader from '@/Components/TicketListHeader.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Link } from '@inertiajs/vue3';
import { IconArrowLeft } from '@tabler/icons-vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const { customerUuid } = defineProps({
    customerUuid: {
        type: String,
        required: true,
    },
});

const loading = ref(true);
const customer = ref(null);

const handleBack = () => {
    window.history.back();
};

const fetchCustomer = async () => {
    loading.value = true;

    try {
        const response = await axios.get(
            route('api.customer.show', customerUuid),
        );

        customer.value = response.data;
    } catch (err) {
        console.log('Error while loading customer details.');
        console.log(err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCustomer();
});
</script>

<template>
    <SidebarLayout>
        <div class="mb-6 mt-0.5">
            <PageTitle margin-bottom="mb-0">Customer details</PageTitle>
            <TheButton variant="ghost" class="-ml-3" @click="handleBack">
                <IconArrowLeft class="size-4" stroke-width="2" />
                <span>Back</span>
            </TheButton>
        </div>

        <div v-if="loading">
            <p>Loading customer details...</p>
        </div>
        <div v-else>
            <div class="grid grid-cols-2 gap-x-12">
                <div class="flex items-center gap-x-6">
                    <img
                        :src="customer.profile_photo_url"
                        alt=""
                        class="size-12 rounded-lg"
                    />

                    <div>
                        <h2 class="text-lg font-semibold">
                            {{ customer.name }}
                        </h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            {{ customer.email }}
                        </p>
                    </div>
                </div>

                <div class="text-sm">
                    <div class="flex gap-x-3">
                        <p class="font-medium text-zinc-500 dark:text-zinc-400">
                            Unique link:
                        </p>
                        <Link
                            class="font-semibold text-blue-500"
                            :href="route('portal.index', customer.unique_link)"
                            >{{
                                route('portal.index', customer.unique_link)
                            }}</Link
                        >
                    </div>
                    <div class="flex gap-x-3">
                        <p class="font-medium text-zinc-500 dark:text-zinc-400">
                            Created at:
                        </p>
                        <p>{{ customer.created_at }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="mb-3 text-lg font-semibold">Tickets</h3>

                <div v-if="customer.tickets.length === 0">
                    <p class="font-semibold text-zinc-500 dark:text-zinc-400">
                        Customer has no tickets yet.
                    </p>
                </div>
                <div v-else class="-mx-3">
                    <TicketListHeader />

                    <div class="space-y-1">
                        <TicketLine
                            v-for="ticket in customer.tickets"
                            :key="ticket.ticket_number"
                            :ticket="ticket"
                        />
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
