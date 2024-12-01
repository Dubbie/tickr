<script setup>
import axios from 'axios';
import { ref, watch, onMounted, reactive } from 'vue';
import TheSkeleton from './TheSkeleton.vue';
import ThePagination from './ThePagination.vue';
import TheButton from './TheButton.vue';
import { IconSortAscending, IconSortDescending } from '@tabler/icons-vue';

const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    gridClasses: {
        type: String,
        required: true,
    },
    options: {
        type: Object,
        default: () => ({}),
    },
});

let abortController = null;

// Reactive state for entries and pagination
const entries = ref([]);
const page = ref(props.options.page || 1);
const lastPage = ref(null);
const isLoading = ref(props.options.apiUrl || false);
const debounceTimeout = ref(null);
const sortField = ref(null);
const sortOrder = ref('asc');

// Reactive filters object
const filters = reactive({});

const oldFilters = reactive({});

// Define fields that should trigger debounce
const debounceFields = ['query']; // Fields to trigger debouncing

// Function to sync filters with props
const syncFilters = () => {
    // Save old filters for debouncing
    Object.assign(oldFilters, filters);

    Object.assign(filters, props.options.filters || {}); // Sync props.options.filters into filters
    filters.perPage = props.options.perPage || 10; // Default perPage if not provided
};

// Initial sync
syncFilters();

// Sorting function
const toggleSort = (field) => {
    if (sortField.value === field) {
        // Toggle sort order
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        // Set new sort field
        sortField.value = field;
        sortOrder.value = 'asc'; // Default to ascending order
    }
    fetchPaginatedData(); // Refresh data with new sort
};

// Fetch data function
const fetchPaginatedData = async () => {
    if (abortController) abortController.abort();

    abortController = new AbortController();
    isLoading.value = true;

    try {
        const response = await axios.get(props.options.apiUrl, {
            params: {
                page: page.value,
                sortField: sortField.value,
                sortOrder: sortOrder.value,
                ...filters, // Spread dynamic filters into request parameters
            },
            signal: abortController.signal,
        });

        entries.value = response.data.data;
        page.value = response.data.current_page;
        lastPage.value = response.data.last_page;
    } catch (err) {
        console.error('Error fetching paginated data:', err);
    } finally {
        isLoading.value = false;
    }
};

// Watch filters for changes and trigger a data refresh
watch(
    filters,
    (newFilters) => {
        const changedField = Object.keys(newFilters).find(
            (key) => newFilters[key] !== oldFilters[key],
        );

        // Check if the changed field is in the debounceFields array
        const shouldDebounce = debounceFields.includes(changedField);

        if (shouldDebounce) {
            clearTimeout(debounceTimeout.value);

            debounceTimeout.value = setTimeout(() => {
                page.value = 1; // Reset to the first page when filters change
                fetchPaginatedData();
            }, 300);
        } else {
            // For other filters, fetch data immediately without debounce
            fetchPaginatedData();
        }
    },
    { deep: true },
);

// Watch the page value to trigger a fetch
watch(page, () => {
    fetchPaginatedData();
});

// Watch props.options.filters for changes and sync them with `filters`
watch(
    () => props.options.filters,
    () => {
        syncFilters(); // Re-sync filters when props.options.filters changes
    },
    { deep: true },
);

// Initialize data on mount
onMounted(() => {
    if (props.options.apiUrl) fetchPaginatedData();
});

// Expose refresh method and filters
const refresh = () => {
    page.value = 1; // Reset to the first page
    fetchPaginatedData(); // Fetch data
};

defineExpose({ refresh });
defineEmits(['reset']);
</script>

<template>
    <div>
        <div
            class="w-full rounded-lg ring-1 ring-zinc-900/10 dark:ring-white/10"
        >
            <div
                class="grid rounded-tl-lg rounded-tr-lg border-b bg-zinc-900/5 dark:border-b-white/5 dark:bg-white/5"
                :class="gridClasses"
            >
                <div
                    v-for="col in columns"
                    :key="col.key"
                    class="flex gap-x-2 px-3 py-2 text-xs font-semibold text-zinc-500 dark:text-zinc-400"
                    :class="[
                        col.class || '',
                        { 'cursor-pointer': col.sortable },
                    ]"
                    @click="col.sortable && toggleSort(col.key)"
                >
                    <p>{{ col.label }}</p>
                    <div v-if="col.sortable">
                        <IconSortAscending
                            v-if="sortField === col.key && sortOrder === 'asc'"
                            class="size-4"
                        />
                        <IconSortDescending
                            v-if="sortField === col.key && sortOrder === 'desc'"
                            class="size-4"
                        />
                    </div>
                </div>
            </div>

            <!-- data -->
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
                mode="out-in"
            >
                <div v-if="isLoading">
                    <div
                        v-for="i in options.perPage"
                        :key="i"
                        class="grid"
                        :class="gridClasses"
                    >
                        <div
                            v-for="col in columns"
                            :key="col.name"
                            class="px-3 py-2 text-sm font-medium"
                            :class="col.class || ''"
                        >
                            <TheSkeleton class="h-5 w-1/2" v-if="col.label" />
                        </div>
                    </div>
                </div>
                <div v-else>
                    <template v-if="entries.length">
                        <div
                            v-for="entry in entries"
                            :key="entry"
                            class="grid border-b last-of-type:border-none hover:bg-zinc-500/5 dark:border-b-white/5 dark:hover:bg-zinc-700/20"
                            :class="gridClasses"
                        >
                            <div
                                v-for="col in columns"
                                :key="col.key"
                                class="truncate px-3 py-2 text-sm font-medium"
                                :class="col.class || ''"
                            >
                                <!-- Scoped Slot for Custom Content -->
                                <slot
                                    :name="col.key"
                                    :value="entry[col.key]"
                                    :entry="entry"
                                >
                                    {{ entry[col.key] }}
                                </slot>
                            </div>
                        </div>
                    </template>

                    <div v-else class="flex flex-col items-center py-12">
                        <p class="mb-3 text-xs font-semibold text-indigo-500">
                            No entries
                        </p>
                        <h2
                            class="text-4xl font-semibold text-zinc-900 dark:text-zinc-100"
                        >
                            We can't find anything
                        </h2>

                        <div
                            class="my-6 text-center text-sm text-zinc-500 dark:text-zinc-400"
                        >
                            <p>
                                We tried searching for what you are looking for,
                                but couldn't find anything.
                            </p>
                            <p>Want to try again?</p>
                        </div>

                        <TheButton variant="primary" @click="$emit('reset')"
                            >Reset filter</TheButton
                        >
                    </div>
                </div>
            </transition>
        </div>

        <!-- pagination -->
        <div v-if="lastPage && lastPage !== 1" class="mt-6">
            <ThePagination v-model:current-page="page" :last-page="lastPage" />
        </div>
    </div>
</template>
