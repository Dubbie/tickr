<script setup>
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';
import TheSkeleton from './TheSkeleton.vue';
import ThePagination from './ThePagination.vue';

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

const entries = ref([]);
const page = ref(props.options.page || 1);
const perPage = computed(() => props.options.perPage || 10);
const query = computed(() => props.options.query || '');
const lastPage = ref(null);
const isLoading = ref(props.options.apiUrl ?? false);
const debounceTimeout = ref(null);

const fetchPaginatedData = async () => {
    if (abortController) abortController.abort();

    abortController = new AbortController();
    isLoading.value = true;

    try {
        const response = await axios.get(props.options.apiUrl, {
            params: {
                query: query.value,
                page: page.value,
                perPage: perPage.value,
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

const refresh = () => {
    page.value = 1;
    fetchPaginatedData();
};

watch(page, () => {
    fetchPaginatedData();
});

watch(query, () => {
    clearTimeout(debounceTimeout.value);

    debounceTimeout.value = setTimeout(() => {
        page.value = 1;
        fetchPaginatedData();
    }, 300);
});

onMounted(() => {
    if (props.options.apiUrl) fetchPaginatedData();
});

defineExpose({ refresh });
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
                        v-for="i in props.options.perPage"
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
                    <div
                        v-for="entry in entries"
                        :key="entry"
                        class="grid border-b last-of-type:border-none dark:border-b-white/5 dark:hover:bg-zinc-700/20"
                        :class="gridClasses"
                    >
                        <div
                            v-for="col in columns"
                            :key="col.key"
                            class="px-3 py-2 text-sm font-medium"
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
                </div>
            </transition>
        </div>

        <!-- pagination -->
        <div v-if="lastPage" class="mt-6">
            <ThePagination v-model:current-page="page" :last-page="lastPage" />
        </div>
    </div>
</template>
