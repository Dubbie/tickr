<script setup>
import PageTitle from '@/Components/PageTitle.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { IconLink, IconPlus, IconSearch } from '@tabler/icons-vue';
import GenericTable from '@/Components/GenericTable.vue';
import { inject, onBeforeUnmount, onMounted, ref } from 'vue';
import { EVENTS } from '@/constants';

const form = useForm({
    query: '',
});

const customersTable = ref();
const emitter = inject('emitter');
const columns = [
    {
        key: 'name',
        label: 'Name',
    },
    {
        key: 'email',
        label: 'Email',
    },
    {
        key: 'tickets',
        label: 'Tickets',
        class: 'text-right',
    },
    {
        key: 'actions',
        label: '',
    },
];

const handleCustomerLink = (customer) => {
    router.visit(route('portal.index', customer.unique_link));
};

const refreshCustomers = () => {
    customersTable.value.refresh();
};

onMounted(() => {
    emitter.on(EVENTS.REFRESH_CUSTOMERS, refreshCustomers);
});

onBeforeUnmount(() => {
    emitter.off(EVENTS.REFRESH_CUSTOMERS);
});
</script>

<template>
    <SidebarLayout>
        <div class="mb-6 flex items-center justify-between gap-x-12">
            <div class="flex flex-1 items-center gap-x-6">
                <PageTitle margin-bottom="mb-0">Customers</PageTitle>
                <div class="relative flex-1">
                    <TextInput
                        v-model="form.query"
                        class="w-full text-sm/6 md:w-64"
                        placeholder="Search in customers..."
                        has-icon
                    />
                    <IconSearch
                        class="absolute left-2 top-1.5 size-5 text-zinc-400 lg:top-2"
                    />
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-1">
                <TheButton variant="primary">
                    <IconPlus class="size-4" />
                    <span>New customer</span>
                </TheButton>
            </div>
        </div>

        <GenericTable
            ref="customersTable"
            grid-classes="grid-cols-4 items-center"
            :columns="columns"
            :options="{
                apiUrl: route('api.customer.index'),
                perPage: 10,
                filters: { ...form.data() },
            }"
        >
            <template #tickets="{ entry }">
                <p>{{ entry.tickets_count }}</p>
            </template>

            <template #actions="{ entry }">
                <div class="-my-2 flex justify-end gap-x-3">
                    <TheButton
                        variant="ghost"
                        size="sm"
                        square
                        @click="handleCustomerLink(entry)"
                    >
                        <IconLink class="size-4" />
                    </TheButton>
                </div>
            </template>
        </GenericTable>
    </SidebarLayout>
</template>
