<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import TheButton from '@/Components/TheButton.vue';
import { IconArrowLeft } from '@tabler/icons-vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-8 text-center">
            <h1 class="mb-2 text-3xl font-semibold tracking-tight">
                Forgot password?
            </h1>
            <p class="text-sm text-zinc-600">
                No worries, we'll send you reset instructions.
            </p>
        </div>

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 flex flex-col gap-y-2">
                <TheButton
                    variant="primary"
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Reset password
                </TheButton>
                <TheButton
                    class="w-full"
                    variant="ghost"
                    :href="route('login')"
                >
                    <IconArrowLeft class="size-4" />
                    <span>Back to log in</span>
                </TheButton>
            </div>
        </form>
    </GuestLayout>
</template>
