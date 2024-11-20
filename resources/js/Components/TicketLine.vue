<script setup>
import { Link } from '@inertiajs/vue3';
import TicketPriority from './TicketPriority.vue';
import TicketStatus from './TicketStatus.vue';

defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Link
        class="grid grid-cols-8 items-center gap-x-6 rounded-md px-3 py-2 text-sm text-zinc-800 hover:bg-zinc-900/5 dark:text-zinc-100 dark:hover:bg-white/5"
        :href="route('ticket.show', ticket.ticket_number)"
    >
        <div>
            <p>{{ ticket.ticket_number }}</p>
        </div>
        <p class="col-span-2 truncate font-semibold">
            {{ ticket.subject }}
        </p>
        <div class="col-span-2 flex items-center gap-x-2">
            <img
                :src="ticket.profile_photo_url"
                alt=""
                class="size-6 rounded-md"
            />
            <p class="truncate font-medium">
                {{ ticket.customer.email }}
            </p>
        </div>
        <div class="flex justify-center">
            <TicketPriority :priority="ticket.priority" />
        </div>
        <div class="flex justify-center">
            <TicketStatus :status="ticket.status" />
        </div>
        <p class="text-right font-medium">
            {{ ticket.formatted_updated_at }}
        </p>
    </Link>
</template>
