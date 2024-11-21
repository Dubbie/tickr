<script setup>
import ThePagination from './ThePagination.vue';
import TheSkeleton from './TheSkeleton.vue';
import TicketLine from './TicketLine.vue';
import TicketListHeader from './TicketListHeader.vue';

defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
    tickets: {
        type: Array,
        required: true,
    },
    perPage: {
        type: Number,
        default: 5,
    },
    checkboxes: {
        type: Boolean,
        default: false,
    },
    currentPage: Number,
    lastPage: Number,
});

defineEmits(['change-page']);
</script>

<template>
    <div>
        <div class="rounded-lg ring-1 ring-zinc-900/10 dark:ring-white/10">
            <TicketListHeader :show-checkbox="checkboxes" />

            <div>
                <transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                    mode="out-in"
                >
                    <div v-if="loading" class="space-y-1">
                        <TheSkeleton
                            v-for="i in perPage"
                            :key="i"
                            class="h-11 w-full"
                        />
                    </div>
                    <div v-else>
                        <TicketLine
                            v-for="ticket in tickets"
                            :key="ticket.ticket_number"
                            :ticket="ticket"
                            :show-checkbox="checkboxes"
                        />
                    </div>
                </transition>
            </div>
        </div>

        <!-- Pagination -->
        <ThePagination
            v-if="tickets.length > 0 && currentPage"
            class="mt-6"
            :current-page="currentPage"
            :last-page="lastPage"
            @update:current-page="$emit('change-page', $event)"
        />
    </div>
</template>
