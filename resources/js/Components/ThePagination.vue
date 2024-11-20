<script setup>
import { IconArrowNarrowLeft, IconArrowNarrowRight } from '@tabler/icons-vue';
import { computed } from 'vue';

// Props passed to the component
const { currentPage, lastPage } = defineProps({
    currentPage: {
        type: Number,
        required: true,
    },
    lastPage: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['update:currentPage']);

const changePage = (newPage) => {
    emit('update:currentPage', newPage);
};

const parts = computed(() => {
    const maxVisiblePages = 11; // Total number of elements, including gaps
    const pages = [];
    const edgePages = 2; // Number of pages to always show at the start and end
    const middlePages = maxVisiblePages - (edgePages * 2 + 2); // Adjust middle

    if (lastPage <= maxVisiblePages) {
        // Show all pages if total pages fit within the limit
        for (let i = 1; i <= lastPage; i++) {
            pages.push({ type: 'page', number: i });
        }
        return pages;
    }

    // Edge case: Start of pagination
    if (currentPage <= edgePages + middlePages / 2) {
        for (let i = 1; i <= edgePages + middlePages; i++) {
            pages.push({ type: 'page', number: i });
        }
        pages.push({ type: 'gap' });
        pages.push({ type: 'page', number: lastPage });
        return pages;
    }

    // Edge case: End of pagination
    if (currentPage >= lastPage - edgePages - middlePages / 2) {
        pages.push({ type: 'page', number: 1 });
        pages.push({ type: 'gap' });
        for (
            let i = lastPage - edgePages - middlePages + 1;
            i <= lastPage;
            i++
        ) {
            pages.push({ type: 'page', number: i });
        }
        return pages;
    }

    // General case: Middle of pagination
    pages.push({ type: 'page', number: 1 });
    pages.push({ type: 'gap' });

    const startPage = currentPage - Math.floor(middlePages / 2);
    const endPage = currentPage + Math.floor(middlePages / 2);

    for (let i = startPage; i <= endPage; i++) {
        pages.push({ type: 'page', number: i });
    }

    pages.push({ type: 'gap' });
    pages.push({ type: 'page', number: lastPage });

    return pages;
});
</script>

<template>
    <div class="flex items-center justify-between gap-x-3 text-sm">
        <button
            :disabled="currentPage === 1"
            class="flex h-8 items-center justify-center gap-x-2 rounded-md px-3 font-semibold text-white hover:bg-white/5 disabled:text-zinc-600"
            @click="changePage(currentPage - 1)"
        >
            <IconArrowNarrowLeft class="-ml-1 size-4 opacity-50" />
            <span>Previous</span>
        </button>

        <div class="flex items-center gap-x-1">
            <template v-for="(part, index) in parts" :key="index">
                <button
                    v-if="part.type === 'page'"
                    class="size-8 rounded-md font-semibold text-white"
                    :class="{
                        'bg-white/5': part.number === currentPage,
                        'hover:bg-white/5': part.number !== currentPage,
                    }"
                    @click="changePage(part.number)"
                >
                    {{ part.number }}
                </button>
                <span v-else class="size-8 text-center leading-8">...</span>
            </template>
        </div>

        <button
            :disabled="currentPage === lastPage"
            class="flex h-8 items-center justify-center gap-x-2 rounded-md px-3 font-semibold text-white hover:bg-white/5 disabled:text-zinc-600"
            @click="changePage(currentPage + 1)"
        >
            <span>Next</span>
            <IconArrowNarrowRight class="-mr-1 size-4 opacity-50" />
        </button>
    </div>
</template>
