<script setup>
import TheCard from '@/Components/TheCard.vue';
import { inject } from 'vue';

const ticket = inject('ticket');
</script>

<template>
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
                    {{ ticket.contact_email }}
                </p>
            </div>
        </div>
    </TheCard>
</template>
