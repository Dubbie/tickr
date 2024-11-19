<script setup>
import TheCard from '@/Components/TheCard.vue';
import { computed, inject } from 'vue';

const ticket = inject('ticket');

const lastReplier = computed(() => {
    if (ticket.value?.replies.length === 0) return 'Nobody';

    // Sort replies by created_at in descending order
    const sortedReplies = [...ticket.value.replies].sort((a, b) => {
        return new Date(b.created_at) - new Date(a.created_at);
    });

    // Get the latest reply and return the replier's name
    const latestReply = sortedReplies[0];
    return latestReply.replier.name;
});
</script>

<template>
    <TheCard>
        <p class="mb-3 text-sm font-semibold">Ticket details</p>

        <div class="space-y-2">
            <div class="grid grid-cols-3 gap-x-3 text-xs">
                <p class="font-medium text-zinc-500">Ticket number</p>

                <p class="col-span-2">
                    {{ ticket.ticket_number }}
                </p>
            </div>

            <div class="grid grid-cols-3 gap-x-3 text-xs">
                <p class="font-medium text-zinc-500">Created at</p>

                <p class="col-span-2">
                    {{ ticket.created_at }}
                </p>
            </div>

            <div class="grid grid-cols-3 gap-x-3 text-xs">
                <p class="font-medium text-zinc-500">Updated at</p>

                <p class="col-span-2">
                    {{ ticket.updated_at }}
                </p>
            </div>

            <div class="grid grid-cols-3 gap-x-3 text-xs">
                <p class="font-medium text-zinc-500">Replies</p>

                <p class="col-span-2">{{ ticket.replies.length }}</p>
            </div>

            <div class="grid grid-cols-3 gap-x-3 text-xs">
                <p class="font-medium text-zinc-500">Last replier</p>

                <p class="col-span-2">{{ lastReplier }}</p>
            </div>
        </div>
    </TheCard>
</template>
