<script setup>
import DropdownFilter from '@/Components/DropdownFilter.vue';
import GenericTable from '@/Components/GenericTable.vue';
import NewTicketModal from '@/Components/NewTicketModal.vue';
import PageTitle from '@/Components/PageTitle.vue';
import TabContainer from '@/Components/TabContainer.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import TicketAssignee from '@/Components/TicketAssignee.vue';
import TicketPriority from '@/Components/TicketPriority.vue';
import TicketStatus from '@/Components/TicketStatus.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { IconExclamationCircle, IconPlus, IconSearch } from '@tabler/icons-vue';
import { computed, onMounted, ref, watch } from 'vue';

let abortController = null;

const debounceTimeout = ref(null);
const showNewTicketModal = ref(false);
const counts = ref({
    open: 0,
    archived: 0,
});

const openCount = computed(() => counts.value.open);
const archivedCount = computed(() => counts.value.archived);
const tabOptions = computed(() => {
    return [
        {
            label: 'Open tickets',
            name: 'open',
            badge: {
                label: openCount.value,
                color: 'indigo',
            },
        },
        {
            label: 'Archived',
            name: 'archived',
            badge: {
                label: archivedCount.value,
                color: 'teal',
            },
        },
    ];
});

const ticketsTable = ref();

const form = useForm({
    query: '',
    tab: tabOptions.value[0].name,
    priority: [],
});

const columns = [
    {
        key: 'ticket_number',
        label: 'Ticket ID',
    },
    {
        key: 'subject',
        label: 'Subject',
        class: 'col-span-2',
    },
    {
        key: 'customer',
        label: 'Customer',
        class: 'col-span-2',
    },
    {
        key: 'priority',
        label: 'Priority',
        class: 'text-center',
    },
    {
        key: 'status',
        label: 'Status',
        class: 'text-center',
    },
    {
        key: 'assignee',
        label: 'Assignee',
    },
    {
        key: 'updated_at',
        label: 'Last update',
        class: 'text-right',
    },
];

const priorityOptions = [
    {
        label: 'Low',
        value: 'low',
    },
    {
        label: 'Medium',
        value: 'medium',
    },
    {
        label: 'High',
        value: 'high',
    },
];

const fetchCounts = async () => {
    if (abortController) abortController.abort();

    abortController = new AbortController();

    try {
        const response = await axios.get(route('api.ticket.counts'), {
            params: {
                query: form.query,
            },
            signal: abortController.signal,
        });

        counts.value.open = response.data.open || 0;
        counts.value.archived = response.data.archived || 0;
    } catch (err) {
        console.error('Error fetching ticket counts!', err);
    }
};

const handleFilterUpdate = (key, newValue) => {
    form[key] = newValue.value;
};

watch(
    () => form.query,
    () => {
        clearTimeout(debounceTimeout.value);

        debounceTimeout.value = setTimeout(() => {
            fetchCounts();
        }, 300);
    },
);

onMounted(() => {
    // Load counts
    fetchCounts();
});
</script>

<template>
    <SidebarLayout title="Tickets">
        <div class="mb-6 flex items-center justify-between gap-x-12">
            <div class="flex flex-1 items-center gap-x-6">
                <PageTitle margin-bottom="mb-0">Tickets</PageTitle>
                <div class="relative flex-1">
                    <TextInput
                        v-model="form.query"
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

        <div class="flex items-center gap-x-3">
            <TabContainer
                :tabs="tabOptions"
                :active-tab="form.tab"
                @switch-tab="form.tab = $event"
            />

            <div class="flex items-center gap-x-1">
                <DropdownFilter
                    label="Priority"
                    :icon="IconExclamationCircle"
                    :options="priorityOptions"
                    @update="handleFilterUpdate('priority', $event)"
                />
            </div>
        </div>

        <GenericTable
            ref="ticketsTable"
            class="mt-6"
            grid-classes="grid-cols-9 items-center"
            :columns="columns"
            :options="{
                perPage: 10,
                apiUrl: route('api.ticket.index'),
                filters: { ...form.data() },
            }"
            @reset="form.reset()"
        >
            <template #subject="{ entry }">
                <Link
                    :href="route('ticket.show', entry.ticket_number)"
                    class="block overflow-hidden"
                >
                    <p class="truncate font-semibold">{{ entry.subject }}</p>
                </Link>
            </template>
            <template #customer="{ entry }">
                <div class="flex items-center gap-x-3">
                    <img
                        :src="entry.profile_photo_url"
                        alt=""
                        class="size-6 rounded-md"
                    />
                    <p class="truncate text-xs">{{ entry.contact_email }}</p>
                </div>
            </template>
            <template #priority="{ entry }">
                <TicketPriority :priority="entry.priority" />
            </template>
            <template #status="{ entry }">
                <TicketStatus :status="entry.status" />
            </template>
            <template #assignee="{ entry }">
                <TicketAssignee :assignee="entry.assignee" />
            </template>
            <template #updated_at="{ entry }">
                <p>{{ entry.formatted_updated_at }}</p>
            </template>
        </GenericTable>

        <NewTicketModal
            :show="showNewTicketModal"
            @close="showNewTicketModal = false"
        />
    </SidebarLayout>
</template>
