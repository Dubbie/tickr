<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TheButton from '@/Components/TheButton.vue';
import WizardLayout from '@/Layouts/WizardLayout.vue';
import { useForm } from '@inertiajs/vue3';

const { error } = defineProps({
    error: String,
});

const form = useForm({
    companyName: '',
    saName: '',
    saEmail: '',
    saPassword: '',
});

const finishSetup = () => {
    form.post(route('wizard.finish'));
};
</script>

<template>
    <WizardLayout>
        <div>
            <div v-if="error">{{ error }}</div>

            <div class="mb-3 mt-6">
                <p class="font-semibold">Company details</p>
                <p class="text-sm font-medium text-zinc-500">
                    Data here will be visible to your customers.
                </p>
            </div>

            <div>
                <InputLabel for="company_name">Company name</InputLabel>
                <TextInput
                    name="company_name"
                    id="company_name"
                    class="w-full text-sm"
                    v-model="form.companyName"
                    autocomplete="off"
                />
                <InputError :message="form.errors.companyName" />
            </div>

            <div class="mb-3 mt-6">
                <p class="font-semibold">Super admin details</p>
                <p class="text-sm font-medium text-zinc-500">
                    This account will have access to everything.
                </p>
            </div>

            <div class="space-y-3">
                <div>
                    <InputLabel for="sa_name">Name</InputLabel>
                    <TextInput
                        name="sa_name"
                        id="sa_name"
                        class="w-full text-sm"
                        v-model="form.saName"
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.saName" />
                </div>

                <div>
                    <InputLabel for="sa_email">Email</InputLabel>
                    <TextInput
                        type="email"
                        name="sa_email"
                        id="sa_email"
                        class="w-full text-sm"
                        v-model="form.saEmail"
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.saEmail" />
                </div>

                <div>
                    <InputLabel for="sa_password">Password</InputLabel>
                    <TextInput
                        type="password"
                        name="sa_password"
                        id="sa_password"
                        class="w-full text-sm"
                        v-model="form.saPassword"
                        autocomplete="new-password"
                    />
                </div>
                <InputError :message="form.errors.saPassword" />
            </div>

            <div class="mt-6">
                <TheButton variant="primary" class="w-full" @click="finishSetup"
                    >Finish setup</TheButton
                >
            </div>
        </div>
    </WizardLayout>
</template>
