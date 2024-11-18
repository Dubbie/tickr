<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import AppGuestLayout from '@/Layouts/AppGuestLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    customerId: '',
    customerEmail: '',
});
</script>

<template>
    <AppGuestLayout>
        <div class="space-y-3">
            <!-- Customer ID -->
            <div class="grid grid-cols-3 items-center gap-x-6">
                <InputLabel for="customer-id">Customer ID</InputLabel>
                <TextInput
                    id="customer-id"
                    name="customer-id"
                    class="col-span-2"
                    v-model="form.customerId"
                />
            </div>

            <!-- Customer Email -->
            <div class="grid grid-cols-3 items-center gap-x-6">
                <InputLabel for="customer-email">E-mail</InputLabel>
                <TextInput
                    type="email"
                    id="customer-email"
                    name="customer-email"
                    class="col-span-2"
                    v-model="form.customerEmail"
                />
            </div>
        </div>
    </AppGuestLayout>
</template>
