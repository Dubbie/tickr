<script setup>
import { IconPointFilled } from '@tabler/icons-vue';
import TicketStatus from './TicketStatus.vue';
import { Link } from '@inertiajs/vue3';

const { ticket } = defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});

const link = route('portal.show', {
    link: ticket.customer.unique_link,
    ticketNumber: ticket.ticket_number,
});
</script>

<template>
    <Link class="block rounded-md bg-white px-4 py-3" :href="link">
        <div class="flex items-start">
            <div class="grow">
                <p class="text-xs font-semibold text-zinc-400">
                    {{ ticket.time_ago }}
                </p>

                <p class="text-sm font-medium text-zinc-800">
                    {{ ticket.subject }}
                </p>
            </div>

            <TicketStatus :status="ticket.status" size="sm" />
        </div>

        <div
            v-if="ticket.replies.length > 0"
            class="mt-2 flex items-center gap-x-2 text-xs"
        >
            <div
                class="flex items-center justify-center rounded-full border border-dashed border-zinc-400"
            >
                <IconPointFilled class="size-4 text-zinc-400" />
            </div>
            <p class="font-semibold text-zinc-500">
                {{ ticket.replies.length }} replies
            </p>
        </div>
    </Link>
</template>
