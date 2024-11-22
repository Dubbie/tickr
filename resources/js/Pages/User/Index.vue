<script setup>
import PageTitle from '@/Components/PageTitle.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { IconPlus, IconSearch, IconTrash } from '@tabler/icons-vue';
import { inject, onBeforeUnmount, onMounted, ref } from 'vue';
import NewUserModal from '@/Components/NewUserModal.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import axios from 'axios';
import GenericTable from '@/Components/GenericTable.vue';
import { EVENTS } from '@/constants';

const emitter = inject('emitter');
const showNewUserModal = ref(false);
const showDeleteUserModal = ref(false);
const selectedUser = ref(null);
const usersTable = ref();

const tableColumns = [
    {
        key: 'name',
        label: 'Name',
        class: 'text-left',
    },
    {
        key: 'email',
        label: 'Email',
        class: 'text-left',
    },
    {
        key: 'actions',
        label: '',
    },
];

const form = useForm({
    query: '',
    tab: 'open',
});

const handleCloseDelete = () => {
    showDeleteUserModal.value = false;
    selectedUser.value = null;
};

const handleConfirmDelete = async () => {
    try {
        await axios.delete(route('api.user.destroy', selectedUser.value.id));

        handleCloseDelete();
        emitter.emit(EVENTS.REFRESH_USERS);
    } catch (err) {
        console.log('Error while deleting user.');
        console.log(err);
    }
};

const selectUserForDelete = (user) => {
    selectedUser.value = user;
    showDeleteUserModal.value = true;
};

const refreshUsers = () => {
    usersTable.value.refresh();
};

onMounted(() => {
    emitter.on(EVENTS.REFRESH_USERS, refreshUsers);
});

onBeforeUnmount(() => {
    emitter.off(EVENTS.REFRESH_USERS);
});
</script>

<template>
    <SidebarLayout>
        <div class="mb-6 flex items-center justify-between gap-x-12">
            <div class="flex flex-1 items-center gap-x-6">
                <PageTitle margin-bottom="mb-0">Users</PageTitle>
                <div class="relative flex-1">
                    <TextInput
                        v-model="form.query"
                        class="w-full text-sm/6 md:w-64"
                        placeholder="Search in users..."
                        has-icon
                    />
                    <IconSearch
                        class="absolute left-2 top-1.5 size-5 text-zinc-400 lg:top-2"
                    />
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-1">
                <TheButton variant="primary" @click="showNewUserModal = true">
                    <IconPlus class="size-4" />
                    <span>New user</span>
                </TheButton>
            </div>
        </div>

        <GenericTable
            ref="usersTable"
            grid-classes="grid-cols-3 items-center"
            :columns="tableColumns"
            :options="{
                apiUrl: route('api.user.index'),
                perPage: 10,
                filters: { ...form.data() },
            }"
            @reset="form.reset()"
        >
            <template #name="{ entry }">
                <Link :href="route('user.show', entry)">
                    <p class="font-semibold">{{ entry.name }}</p>
                </Link>
            </template>

            <template #actions="{ entry }">
                <div class="-my-1 flex justify-end gap-x-3">
                    <TheButton
                        variant="ghost"
                        square
                        @click="selectUserForDelete(entry)"
                    >
                        <IconTrash class="size-5" />
                    </TheButton>
                </div>
            </template>
        </GenericTable>

        <NewUserModal
            :show="showNewUserModal"
            @close="showNewUserModal = false"
        />

        <ConfirmModal
            :show="showDeleteUserModal"
            confirm-label="Delete user"
            @close="handleCloseDelete"
            @confirm="handleConfirmDelete"
        >
            <template #title>Delete user</template>

            <template #body>
                <p>Are you sure you want to delete this user?</p>
                <p>This action is irreversible.</p>
            </template>
        </ConfirmModal>
    </SidebarLayout>
</template>
