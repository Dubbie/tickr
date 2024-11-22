<script setup>
import PageTitle from '@/Components/PageTitle.vue';
import TheTable from '@/Components/TheTable.vue';
import TableCell from '@/Components/TableCell.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { IconPlus, IconSearch, IconTrash } from '@tabler/icons-vue';
import { inject, onMounted, onUnmounted, ref, watch } from 'vue';
import ThePagination from '@/Components/ThePagination.vue';
import TableHeading from '@/Components/TableHeading.vue';
import NewUserModal from '@/Components/NewUserModal.vue';
import { EVENTS } from '@/constants';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import axios from 'axios';

const emitter = inject('emitter');
const lastPage = ref(null);
const loading = ref(true);
const users = ref([]);
const debounceTimeout = ref(null);
const showNewUserModal = ref(false);
const showDeleteUserModal = ref(false);
const selectedUser = ref(null);

const form = useForm({
    query: '',
    page: 1,
    perPage: 10,
});

let abortController = null;

const fetchUsers = async () => {
    if (abortController) {
        abortController.abort();
    }

    abortController = new AbortController();
    loading.value = true;

    try {
        const response = await axios.get(route('api.user.index'), {
            params: { ...form.data() },
            signal: abortController.signal,
        });

        users.value = response.data.data;
        form.page = response.data.current_page;
        lastPage.value = response.data.last_page;
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

const handlePageChange = (newPage) => {
    form.page = newPage;
    fetchUsers();
};

const handleCloseDelete = () => {
    showDeleteUserModal.value = false;
    selectedUser.value = null;
};

const handleConfirmDelete = async () => {
    console.log('Delete');

    try {
        await axios.delete(route('api.user.destroy', selectedUser.value.id));

        handleCloseDelete();
        fetchUsers();
    } catch (err) {
        console.log('Error while deleting user.');
        console.log(err);
    }
};

const selectUserForDelete = (user) => {
    selectedUser.value = user;
    showDeleteUserModal.value = true;
};

watch(
    () => form.query,
    () => {
        clearTimeout(debounceTimeout.value);

        debounceTimeout.value = setTimeout(() => {
            fetchUsers();
        }, 300);
    },
);

onMounted(() => {
    fetchUsers();
    emitter.on(EVENTS.REFRESH_USERS, fetchUsers);
});

onUnmounted(() => {
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

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            mode="out-in"
        >
            <div v-if="loading">
                <p>Loading users...</p>
            </div>
            <div v-else>
                <TheTable size="sm">
                    <template #headings>
                        <TableHeading>Name</TableHeading>
                        <TableHeading>Email</TableHeading>
                        <TableHeading class="text-right">Tickets</TableHeading>
                        <TableHeading class="text-right"></TableHeading>
                    </template>

                    <template #rows>
                        <tr v-for="user in users" :key="user.id">
                            <TableCell>
                                <Link
                                    class="block font-semibold"
                                    :href="route('user.show', user.id)"
                                    >{{ user.name }}</Link
                                >
                            </TableCell>
                            <TableCell>
                                <Link
                                    class="block"
                                    :href="route('user.show', user.id)"
                                    >{{ user.email }}</Link
                                >
                            </TableCell>
                            <TableCell>
                                <Link
                                    class="block text-right"
                                    :href="route('user.show', user.id)"
                                >
                                    {{ user.tickets_count }}
                                </Link>
                            </TableCell>
                            <TableCell>
                                <div class="-my-2 flex justify-end gap-x-1">
                                    <TheButton
                                        variant="ghost"
                                        square
                                        @click="selectUserForDelete(user)"
                                    >
                                        <IconTrash class="size-5" />
                                    </TheButton>
                                </div>
                            </TableCell>
                        </tr>
                    </template>
                </TheTable>
            </div>
        </transition>
        <div v-if="users.length > 0">
            <ThePagination
                class="mt-6"
                :current-page="form.page"
                :last-page="lastPage"
                @update:current-page="handlePageChange"
            />
        </div>

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
