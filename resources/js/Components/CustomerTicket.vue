<script setup>
import TicketStatus from './TicketStatus.vue';

defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="rounded-md bg-white px-4 py-3">
        <div class="flex items-start">
            <div class="grow">
                <p class="text-xs font-semibold text-zinc-400">
                    {{ ticket.formatted_created_at }}
                </p>

                <p class="text-sm font-medium text-zinc-800">
                    {{ ticket.subject }}
                </p>
            </div>

            <TicketStatus :status="ticket.status" size="sm" />
        </div>
    </div>
</template>
