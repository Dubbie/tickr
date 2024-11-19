<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextareaInputGuest from '@/Components/TextareaInputGuest.vue';
import TextInputGuest from '@/Components/TextInputGuest.vue';
import TextInputGuestContainer from '@/Components/TextInputGuestContainer.vue';
import TheButton from '@/Components/TheButton.vue';
import AppGuestLayout from '@/Layouts/AppGuestLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { IconBook, IconMail } from '@tabler/icons-vue';

const form = useForm({
    customerEmail: '',
    message: '',
});
</script>

<template>
    <AppGuestLayout>
        <h1 class="mb-3 text-center text-xl font-bold">New ticket</h1>

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

            <!-- Message -->
            <div>
                <InputLabel class="sr-only" for="message">Message</InputLabel>

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

            <TheButton class="w-full">Submit ticket</TheButton>
        </div>
    </AppGuestLayout>
</template>
