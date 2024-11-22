<script setup>
import axios from 'axios';
import { ref, watch, onMounted, reactive } from 'vue';
import TheSkeleton from './TheSkeleton.vue';
import ThePagination from './ThePagination.vue';
import TheButton from './TheButton.vue';

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

const fetchPaginatedData = async () => {
    if (abortController) abortController.abort();

    abortController = new AbortController();
    isLoading.value = true;

    try {
        const response = await axios.get(props.options.apiUrl, {
            params: {
                page: page.value,
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
        <div class="w-full rounded-lg ring-1 dark:ring-white/10">
            <div
                class="grid rounded-tl-lg rounded-tr-lg border-b dark:border-b-white/5 dark:bg-white/5"
                :class="gridClasses"
            >
                <div
                    v-for="col in columns"
                    :key="col.name"
                    class="px-3 py-2 text-xs font-semibold text-zinc-500 dark:text-zinc-400"
                    :class="col.class || ''"
                >
                    {{ col.label }}
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
                            class="grid border-b last-of-type:border-none dark:border-b-white/5 dark:hover:bg-zinc-700/20"
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
