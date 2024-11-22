<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from './InputLabel.vue';
import Modal from './Modal.vue';
import TextInput from './TextInput.vue';
import TheButton from './TheButton.vue';
import { computed, inject, ref } from 'vue';
import axios from 'axios';
import PageTitle from './PageTitle.vue';
import { EVENTS } from '@/constants';

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emitter = inject('emitter');
const submittingUser = ref(false);
const form = useForm({
    name: '',
    email: '',
    password: '',
});

const isFormValid = computed(() => {
    const nameFilled = form.name.length > 0;
    const emailFilled = form.email.length > 0;
    const passFilled = form.password.length > 0;

    return nameFilled && emailFilled && passFilled;
});

const handleSubmit = async () => {
    submittingUser.value = true;

    try {
        await axios.post(route('api.user.store'), {
            ...form.data(),
        });

        emit('close');
        emitter.emit(EVENTS.REFRESH_USERS);
        form.reset();
    } catch (err) {
        console.log('Error while submitting user.');
        console.log(err);
    } finally {
        submittingUser.value = false;
    }
};

const emit = defineEmits(['close']);
</script>

<template>
    <Modal :show="show" max-width="sm" @close="$emit('close')">
        <div class="p-6">
            <PageTitle size="sm" margin-bottom="mb-1"> New user </PageTitle>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Create a new user for your team.
            </p>

            <div class="mt-6 space-y-3">
                <div>
                    <InputLabel for="name">Name</InputLabel>
                    <TextInput
                        id="name"
                        name="name"
                        class="w-full text-sm"
                        v-model="form.name"
                        autocomplete="off"
                    />
                </div>

                <div>
                    <InputLabel for="email">Email</InputLabel>
                    <TextInput
                        type="email"
                        id="email"
                        name="email"
                        class="w-full text-sm"
                        v-model="form.email"
                        autocomplete="off"
                    />
                </div>

                <div>
                    <InputLabel for="password">Password</InputLabel>
                    <TextInput
                        type="password"
                        id="password"
                        name="password"
                        class="w-full text-sm"
                        v-model="form.password"
                        autocomplete="new-password"
                    />
                </div>
            </div>

            <div class="mt-3 flex justify-end gap-x-1">
                <TheButton variant="ghost" @click="emit('close')"
                    >Cancel</TheButton
                >
                <TheButton
                    variant="primary"
                    @click="handleSubmit"
                    :disabled="!isFormValid"
                    >Save user</TheButton
                >
            </div>
        </div>
    </Modal>
</template>
