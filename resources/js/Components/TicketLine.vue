<script setup>
import { Link } from '@inertiajs/vue3';
import TicketPriority from './TicketPriority.vue';
import TicketStatus from './TicketStatus.vue';
import Checkbox from './Checkbox.vue';
import { ref } from 'vue';

defineProps({
    ticket: {
        type: Object,
        required: true,
    },
    showCheckbox: {
        type: Boolean,
        default: false,
    },
});

const checked = ref(false);
</script>

<template>
    <div
        class="flex items-center gap-x-6 border-b border-zinc-900/5 px-5 py-2 text-zinc-800 last-of-type:border-none hover:bg-zinc-900/5 dark:border-white/5 dark:text-zinc-100 dark:hover:bg-white/5"
    >
        <Checkbox v-if="showCheckbox" v-model:checked="checked" />
        <Link
            class="grid grow grid-cols-8 items-center gap-x-6 text-sm"
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
    </div>
</template>
