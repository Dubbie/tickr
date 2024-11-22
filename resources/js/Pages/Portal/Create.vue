<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextareaInputGuest from '@/Components/TextareaInputGuest.vue';
import TextInputGuest from '@/Components/TextInputGuest.vue';
import TextInputGuestContainer from '@/Components/TextInputGuestContainer.vue';
import TheButton from '@/Components/TheButton.vue';
import AppGuestLayout from '@/Layouts/AppGuestLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { IconBook, IconMail, IconQuote } from '@tabler/icons-vue';
import axios from 'axios';
import { ref } from 'vue';

const customer = usePage().props.customer;

const MODE_SUBMITTING = 'submitting';
const MODE_CREATING = 'creating';
const MODE_SUBMITTED = 'submitted';

const form = useForm({
    customerEmail: '',
    subject: '',
    message: '',
});

const mode = ref(MODE_CREATING);

const handleSubmit = async () => {
    console.log(form.data());
    mode.value = MODE_SUBMITTING;

    try {
        const response = await axios.post(
            route('api.customer.ticket.store', customer.unique_link),
            {
                subject: form.subject,
                description: form.message,
                priority: 'medium',
                contact_email: form.customerEmail,
            },
        );

        console.log(response);
        mode.value = MODE_SUBMITTED;
    } catch (err) {
        console.log('Error while submitting ticket.');
        console.log(err);

        mode.value = MODE_CREATING;
    }
};
</script>

<template>
    <AppGuestLayout title="New ticket">
        <div v-if="mode === MODE_CREATING">
            <div class="space-y-3">
                <!-- Customer Email -->
                <div>
                    <InputLabel class="sr-only" for="customer-email"
                        >Your e-mail</InputLabel
                    >

                    <TextInputGuestContainer :icon="IconMail">
                        <TextInputGuest
                            type="email"
                            id="customer-email"
                            class="w-full text-sm"
                            name="customer-email"
                            v-model="form.customerEmail"
                            placeholder="Enter your e-mail address..."
                        />
                    </TextInputGuestContainer>
                </div>

                <!-- Subject -->
                <div>
                    <InputLabel class="sr-only" for="customer-email"
                        >Subject</InputLabel
                    >

                    <TextInputGuestContainer :icon="IconQuote">
                        <TextInputGuest
                            id="subject"
                            class="w-full text-sm"
                            name="subject"
                            v-model="form.subject"
                            placeholder="Enter the subject..."
                        />
                    </TextInputGuestContainer>
                </div>

                <!-- Message -->
                <div>
                    <InputLabel class="sr-only" for="message"
                        >Message</InputLabel
                    >

                    <TextInputGuestContainer :icon="IconBook">
                        <TextareaInputGuest
                            id="message"
                            name="message"
                            class="w-full text-sm"
                            v-model="form.message"
                            placeholder="Enter your message..."
                        />
                    </TextInputGuestContainer>
                </div>

                <div class="space-y-1">
                    <TheButton
                        variant="primary"
                        class="w-full"
                        @click="handleSubmit"
                        >Submit ticket</TheButton
                    >
                    <TheButton
                        class="w-full"
                        variant="ghost"
                        :href="route('portal.index', customer.unique_link)"
                        >Back to tickets</TheButton
                    >
                </div>
            </div>
        </div>

        <div v-else-if="mode === MODE_SUBMITTING" class="text-center">
            <p class="mb-2 text-2xl font-bold">Creating your ticket!</p>
            <p class="text-sm font-medium text-zinc-500">
                This should not take long.
            </p>
        </div>

        <div v-else class="text-center">
            <p class="mb-2 text-2xl font-bold">Ticket created!</p>
            <p class="text-sm font-medium text-zinc-800">
                Once anything changes with your ticket, we will send you a
                notification.
            </p>

            <div class="mt-6 flex justify-center">
                <TheButton :href="route('portal.index', customer.unique_link)"
                    >Show tickets</TheButton
                >
            </div>
        </div>
    </AppGuestLayout>
</template>
