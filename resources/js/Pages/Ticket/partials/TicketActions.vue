<script setup>
import AssignTicketModal from '@/Components/AssignTicketModal.vue';
import TheCard from '@/Components/TheCard.vue';
import TicketAssignee from '@/Components/TicketAssignee.vue';
import TicketPriority from '@/Components/TicketPriority.vue';
import TicketStatus from '@/Components/TicketStatus.vue';
import { EVENTS } from '@/constants';
import { inject, ref } from 'vue';

const ticket = inject('ticket');
const emitter = inject('emitter');

const showAssignModal = ref(false);
</script>

<template>
    <TheCard class="space-y-1">
        <div class="grid grid-cols-3 items-center gap-x-3">
            <p class="text-xs font-medium text-zinc-500">Ticket status</p>

            <div class="col-span-2">
                <TicketStatus :status="ticket.status" interactive />
            </div>
        </div>

        <div class="grid grid-cols-3 items-center gap-x-3">
            <p class="text-xs font-medium text-zinc-500">Priority</p>
            <div class="col-span-2">
                <TicketPriority :priority="ticket.priority" interactive />
            </div>
        </div>

        <div class="grid grid-cols-3 items-center gap-x-3">
            <p class="text-xs font-medium text-zinc-500">Assigned to</p>
            <div class="col-span-2">
                <TicketAssignee
                    :assignee="ticket.assignee"
                    interactive
                    @click="showAssignModal = true"
                />
            </div>
        </div>

        <AssignTicketModal
            :show="showAssignModal"
            :assignee-id="ticket.assigned_to"
            :ticket-number="ticket.ticket_number"
            @close="showAssignModal = false"
            @update="emitter.emit(EVENTS.REFRESH_TICKET)"
        />
    </TheCard>
</template>
