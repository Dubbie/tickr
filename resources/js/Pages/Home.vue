<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextareaInputGuest from '@/Components/TextareaInputGuest.vue';
import TextInputGuest from '@/Components/TextInputGuest.vue';
import TextInputGuestContainer from '@/Components/TextInputGuestContainer.vue';
import TheButton from '@/Components/TheButton.vue';
import AppGuestLayout from '@/Layouts/AppGuestLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { IconBook, IconMail, IconUser } from '@tabler/icons-vue';
import { ref, watch } from 'vue';

const verified = ref(null);
const loadingVerification = ref(false);
let abortController = null;
const debounceTimeout = ref(false);

const form = useForm({
    customerId: '',
    customerEmail: '',
    message: '',
});

const verifyCustomerId = async () => {
    if (abortController) {
        abortController.abort();
    }

    if (form.customerId.length < 3) {
        verified.value = null;
        return;
    }

    abortController = new AbortController();
    loadingVerification.value = true;

    const customerId = form.customerId;

    try {
        const response = await axios.get(route('api.customer.id.verify'), {
            params: { customerId },
            signal: abortController.signal,
        });

        // Check if successfull
        verified.value = response.data.success;
    } catch (err) {
        if (axios.isCancel(err)) {
            console.log('Request Canceled');
        } else {
            console.log('Error while verifying customer id.');
            console.log(err);
        }
    } finally {
        loadingVerification.value = false;
    }
};

watch(
    () => form.customerId,
    () => {
        clearTimeout(debounceTimeout.value);

        debounceTimeout.value = setTimeout(() => {
            verifyCustomerId();
        }, 300);
    },
);
</script>

<template>
    <AppGuestLayout>
        <h1 class="mb-3 text-center text-xl font-bold">New ticket</h1>

        <div class="space-y-3">
            <!-- Customer ID -->
            <div>
                <InputLabel class="sr-only" for="customer-id"
                    >Customer ID</InputLabel
                >

                <TextInputGuestContainer :icon="IconUser">
                    <TextInputGuest
                        id="customer-id"
                        name="customer-id"
                        class="w-full text-sm"
                        v-model="form.customerId"
                        placeholder="Enter your customer ID..."
                    />
                </TextInputGuestContainer>

                <p v-if="loadingVerification">Checking customer id...</p>
                <template v-if="verified !== null">
                    <p v-if="verified">Good news, we found the customer!</p>
                    <p v-else>Bad news, this customer id is wrong.</p>
                </template>
            </div>

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
