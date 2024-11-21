<script setup>
import TheCard from '@/Components/TheCard.vue';
import { Link } from '@inertiajs/vue3';
import { inject } from 'vue';

const ticket = inject('ticket');
</script>

<template>
    <TheCard padding-class="p-2">
        <Link
            class="flex items-center gap-x-3 rounded-md p-2 hover:bg-black/5"
            :href="route('customer.show', ticket.customer.uuid)"
        >
            <img
                :src="ticket.profile_photo_url"
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
        </Link>
    </TheCard>
</template>
