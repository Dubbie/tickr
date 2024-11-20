<script setup>
import TextareaInput from '@/Components/TextareaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import TheCard from '@/Components/TheCard.vue';
import TicketPriority from '@/Components/TicketPriority.vue';
import TicketReply from '@/Components/TicketReply.vue';
import TicketStatus from '@/Components/TicketStatus.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { ticket } = defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});

const savingReply = ref(false);
const form = useForm({
    email: ticket.contact_email,
    message: '',
});

const emit = defineEmits(['update-ticket']);

const handleReply = async () => {
    savingReply.value = true;

    try {
        await axios.post(
            route('api.customer.ticket.reply.store', {
                link: ticket.customer.unique_link,
                ticketNumber: ticket.ticket_number,
            }),
            {
                email: form.email,
                message: form.message,
            },
        );

        emit('update-ticket');
        form.reset();
    } catch (err) {
        console.log('Error while saving ticket reply.');
        console.log(err);
    } finally {
        savingReply.value = false;
    }
};
</script>

<template>
    <div>
        <TheCard>
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-x-1">
                    <TicketStatus :status="ticket.status" />
                    <TicketPriority :priority="ticket.priority" />
                </div>
                <p class="text-xs text-zinc-500">{{ ticket.time_ago }}</p>
            </div>

            <p class="mt-3 font-semibold">{{ ticket.subject }}</p>

            <p class="mt-3 text-sm">{{ ticket.description }}</p>
        </TheCard>

        <div v-if="ticket.replies.length > 0" class="mt-6 space-y-3">
            <TicketReply
                v-for="reply in ticket.replies"
                :key="reply.id"
                :reply="reply"
            />
        </div>

        <div class="mt-6">
            <template v-if="!ticket.is_archived">
                <div class="flex items-start gap-x-3">
                    <img
                        :src="ticket.profile_photo_url"
                        class="size-8 rounded-md"
                    />

                    <div class="grow space-y-1">
                        <TextInput
                            type="email"
                            v-model="form.email"
                            class="w-full text-sm"
                        />

                        <TextareaInput
                            v-model="form.message"
                            class="w-full text-sm"
                            rows="2"
                            placeholder="Type your message here..."
                        />
                    </div>
                </div>

                <div class="mt-3 flex gap-x-1 pl-11">
                    <TheButton variant="primary" @click="handleReply"
                        >Submit reply</TheButton
                    >
                    <TheButton>Mark as resolved</TheButton>
                </div>
            </template>
            <template v-else>
                <p class="text-center text-sm font-semibold text-zinc-500">
                    This ticket has been archived, you can no longer reply to
                    it.
                </p>
            </template>
        </div>
    </div>
</template>
