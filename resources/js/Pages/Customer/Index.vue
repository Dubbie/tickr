<script setup>
import PageTitle from '@/Components/PageTitle.vue';
import TheTable from '@/Components/TheTable.vue';
import TableBody from '@/Components/TableBody.vue';
import TableCell from '@/Components/TableCell.vue';
import TableHead from '@/Components/TableHead.vue';
import TableHeader from '@/Components/TableHeader.vue';
import TableRow from '@/Components/TableRow.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import { IconLink, IconPlus, IconSearch } from '@tabler/icons-vue';
import { onMounted, ref, watch } from 'vue';
import ThePagination from '@/Components/ThePagination.vue';
import CustomerListSkeleton from './partials/CustomerListSkeleton.vue';

const lastPage = ref(null);
const loading = ref(true);
const customers = ref([]);
const debounceTimeout = ref(null);

const form = useForm({
    query: '',
    page: 1,
    perPage: 5,
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

        customers.value = response.data.data;
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
    fetchCustomers();
};

const handleCustomerLink = (customer) => {
    router.visit(route('portal.index', customer.unique_link));
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
    <SidebarLayout>
        <div class="mb-6 flex items-center justify-between gap-x-12">
            <div class="flex flex-1 items-center gap-x-6">
                <PageTitle margin-bottom="mb-0">Customers</PageTitle>
                <div class="relative flex-1">
                    <TextInput
                        v-model="form.query"
                        class="w-full text-sm/6 md:w-64"
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
                <CustomerListSkeleton :rows="form.perPage" />
            </div>
            <div v-else>
                <TheTable>
                    <TableHead>
                        <TableHeader>Name</TableHeader>
                        <TableHeader>Email</TableHeader>
                        <TableHeader class="text-right">Tickets</TableHeader>
                        <TableHeader class="text-right">Actions</TableHeader>
                    </TableHead>
                    <TableBody>
                        <TableRow
                            v-for="customer in customers"
                            :key="customer.unique_link"
                        >
                            <TableCell>
                                <Link
                                    class="font-semibold"
                                    :href="
                                        route('customer.show', customer.uuid)
                                    "
                                    >{{ customer.name }}</Link
                                >
                            </TableCell>
                            <TableCell>
                                <p>{{ customer.email }}</p>
                            </TableCell>
                            <TableCell>
                                <p class="text-right">
                                    {{ customer.tickets_count }}
                                </p>
                            </TableCell>
                            <TableCell>
                                <div class="-my-2 flex justify-end gap-x-1">
                                    <TheButton
                                        variant="ghost"
                                        square
                                        @click="handleCustomerLink(customer)"
                                    >
                                        <IconLink class="size-5" />
                                    </TheButton>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </TheTable>
            </div>
        </transition>
        <div v-if="customers.length > 0">
            <ThePagination
                class="mt-6"
                :current-page="form.page"
                :last-page="lastPage"
                @update:current-page="handlePageChange"
            />
        </div>
    </SidebarLayout>
</template>
