<script setup>
import TextareaInput from '@/Components/TextareaInput.vue';
import TheButton from '@/Components/TheButton.vue';
import TheCard from '@/Components/TheCard.vue';
import TicketPriority from '@/Components/TicketPriority.vue';
import TicketStatus from '@/Components/TicketStatus.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { IconArrowLeft } from '@tabler/icons-vue';

defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    message: '',
});

const handleBack = () => {
    window.history.back();
};
</script>

<template>
    <AppLayout>
        <div class="mb-6">
            <h1 class="text-xl font-bold text-zinc-800">
                {{ ticket.ticket_number }}
            </h1>
            <TheButton variant="ghost" class="-ml-3" @click="handleBack">
                <IconArrowLeft class="size-4" stroke-width="2" />
                <span>Back</span>
            </TheButton>
        </div>

        <div class="grid grid-cols-3 gap-x-6">
            <div class="col-span-2">
                <TheCard>
                    <div>
                        <p class="text-xs font-medium text-zinc-500">
                            {{ ticket.formatted_created_at }}
                        </p>
                        <h2 class="font-semibold">{{ ticket.subject }}</h2>
                    </div>

                    <div class="mt-6">
                        <p>{{ ticket.description }}</p>
                    </div>
                </TheCard>

                <!-- Replies should go here -->

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
                    <TheButton>Submit reply</TheButton>
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
                            <TicketStatus :status="ticket.status" interactive />
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
                </TheCard>

                <TheCard>
                    <p class="mb-3 text-sm font-semibold">Ticket details</p>

                    <div class="space-y-2">
                        <div class="grid grid-cols-3 gap-x-3 text-xs">
                            <p class="font-medium text-zinc-500">
                                Ticket number
                            </p>

                            <p class="col-span-2">{{ ticket.ticket_number }}</p>
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

                            <p class="col-span-2">0</p>
                        </div>

                        <div class="grid grid-cols-3 gap-x-3 text-xs">
                            <p class="font-medium text-zinc-500">
                                Last replier
                            </p>

                            <p class="col-span-2">Nobody</p>
                        </div>
                    </div>
                </TheCard>
            </div>
        </div>
    </AppLayout>
</template>
