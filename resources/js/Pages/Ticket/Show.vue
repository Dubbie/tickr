<script setup>
import AssignTicketModal from '@/Components/AssignTicketModal.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import TheButton from '@/Components/TheButton.vue';
import TheCard from '@/Components/TheCard.vue';
import TicketAssignee from '@/Components/TicketAssignee.vue';
import TicketPriority from '@/Components/TicketPriority.vue';
import TicketReply from '@/Components/TicketReply.vue';
import TicketStatus from '@/Components/TicketStatus.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { IconArrowLeft } from '@tabler/icons-vue';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';

const { ticketNumber } = defineProps({
    ticketNumber: {
        type: String,
        required: true,
    },
});

const ticket = ref(null);
const replies = ref([]);
const showAssignModal = ref(false);
const loadingTicket = ref(false);
const savingReply = ref(false);
const form = useForm({
    message: '',
});

const handleBack = () => {
    window.history.back();
};

const handleReply = async () => {
    savingReply.value = true;

    try {
        await axios.post(route('api.ticket.reply.store', ticketNumber), {
            message: form.message,
        });

        fetchTicket();
        form.reset();
    } catch (err) {
        console.log('Error while saving ticket reply.');
        console.log(err);
    } finally {
        savingReply.value = false;
    }
};

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

const fetchTicket = async () => {
    loadingTicket.value = true;

    try {
        const response = await axios.get(
            route('api.ticket.show', ticketNumber),
        );

        ticket.value = response.data;
    } catch (err) {
        console.log('Error while loading ticket.');
        console.log(err);
    } finally {
        loadingTicket.value = false;
    }
};

onMounted(() => {
    fetchTicket();
});
</script>

<template>
    <AppLayout>
        <div class="mb-6">
            <h1 class="text-xl font-bold text-zinc-800">
                {{ ticket?.ticket_number ?? '...' }}
            </h1>
            <TheButton variant="ghost" class="-ml-3" @click="handleBack">
                <IconArrowLeft class="size-4" stroke-width="2" />
                <span>Back</span>
            </TheButton>
        </div>

        <div v-if="!ticket">
            <p>Loading ticket...</p>
        </div>

        <div v-else>
            <div class="grid grid-cols-3 gap-x-6">
                <div class="col-span-2">
                    <TheCard>
                        <div>
                            <p class="text-xs font-medium text-zinc-500">
                                {{ ticket.formatted_created_at }}
                            </p>
                            <h2 class="font-semibold">{{ ticket.subject }}</h2>
                        </div>

                        <div class="mt-3">
                            <p>{{ ticket.description }}</p>
                        </div>
                    </TheCard>

                    <!-- Replies should go here -->
                    <div
                        v-if="ticket.replies.length > 0"
                        class="mt-6 space-y-3"
                    >
                        <TicketReply
                            v-for="reply in ticket.replies"
                            :key="reply.id"
                            :reply="reply"
                        />
                    </div>

                    <!-- Reply here -->
                    <div class="mt-6">
                        <div class="flex items-start gap-x-3">
                            <img
                                src="//picsum.photos/96"
                                alt=""
                                class="size-8 rounded-md"
                            />

                            <TextareaInput
                                v-model="form.message"
                                class="w-full text-sm"
                                rows="2"
                                placeholder="Write a reply..."
                            />
                        </div>
                    </div>

                    <div class="ml-11 mt-3">
                        <TheButton variant="primary" @click="handleReply"
                            >Submit reply</TheButton
                        >
                    </div>
                </div>

                <div class="space-y-3">
                    <TheCard>
                        <div class="flex items-center gap-x-3">
                            <img
                                src="//picsum.photos/96"
                                :alt="ticket.customer.name"
                                class="size-10 rounded-md"
                            />

                            <div>
                                <p class="text-sm font-semibold">
                                    {{ ticket.customer.name }}
                                </p>
                                <p class="text-xs font-semibold text-zinc-400">
                                    {{ ticket.customer.email }}
                                </p>
                            </div>
                        </div>
                    </TheCard>

                    <TheCard class="space-y-1">
                        <div class="grid grid-cols-3 items-center gap-x-3">
                            <p class="text-xs font-medium text-zinc-500">
                                Ticket status
                            </p>

                            <div class="col-span-2">
                                <TicketStatus
                                    :status="ticket.status"
                                    interactive
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-3 items-center gap-x-3">
                            <p class="text-xs font-medium text-zinc-500">
                                Priority
                            </p>
                            <div class="col-span-2">
                                <TicketPriority
                                    :priority="ticket.priority"
                                    interactive
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-3 items-center gap-x-3">
                            <p class="text-xs font-medium text-zinc-500">
                                Assigned to
                            </p>
                            <div class="col-span-2">
                                <TicketAssignee
                                    :assignee="ticket.assignee"
                                    interactive
                                    @click="showAssignModal = true"
                                />
                            </div>
                        </div>
                    </TheCard>

                    <TheCard>
                        <p class="mb-3 text-sm font-semibold">Ticket details</p>

                        <div class="space-y-2">
                            <div class="grid grid-cols-3 gap-x-3 text-xs">
                                <p class="font-medium text-zinc-500">
                                    Ticket number
                                </p>

                                <p class="col-span-2">
                                    {{ ticket.ticket_number }}
                                </p>
                            </div>

                            <div class="grid grid-cols-3 gap-x-3 text-xs">
                                <p class="font-medium text-zinc-500">
                                    Created at
                                </p>

                                <p class="col-span-2">
                                    {{ ticket.created_at }}
                                </p>
                            </div>

                            <div class="grid grid-cols-3 gap-x-3 text-xs">
                                <p class="font-medium text-zinc-500">
                                    Updated at
                                </p>

                                <p class="col-span-2">
                                    {{ ticket.updated_at }}
                                </p>
                            </div>

                            <div class="grid grid-cols-3 gap-x-3 text-xs">
                                <p class="font-medium text-zinc-500">Replies</p>

                                <p class="col-span-2">{{ replies.length }}</p>
                            </div>

                            <div class="grid grid-cols-3 gap-x-3 text-xs">
                                <p class="font-medium text-zinc-500">
                                    Last replier
                                </p>

                                <p class="col-span-2">{{ lastReplier }}</p>
                            </div>
                        </div>
                    </TheCard>
                </div>
            </div>

            <AssignTicketModal
                :show="showAssignModal"
                :assignee-id="ticket.assigned_to"
                :ticket-number="ticket.ticket_number"
                @close="showAssignModal = false"
                @update="fetchTicket"
            />
        </div>
    </AppLayout>
</template>
