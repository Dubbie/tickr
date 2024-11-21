<script setup>
import TicketLine from './TicketLine.vue';
import TicketListHeader from './TicketListHeader.vue';

defineProps({
    tickets: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <TicketListHeader />

    <div class="mb-1 h-px bg-zinc-900/10 dark:bg-white/10"></div>

    <div class="space-y-1">
        <TicketLine
            v-for="ticket in tickets"
            :key="ticket.ticket_number"
            :ticket="ticket"
        />
    </div>
</template>
