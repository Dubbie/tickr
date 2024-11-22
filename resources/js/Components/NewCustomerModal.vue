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
const submitting = ref(false);
const form = useForm({
    name: '',
    email: '',
});

const isFormValid = computed(() => {
    const nameFilled = form.name.length > 0;
    const emailFilled = form.email.length > 0;

    return nameFilled && emailFilled;
});

const handleSubmit = async () => {
    submitting.value = true;

    try {
        await axios.post(route('api.customer.store'), {
            ...form.data(),
        });

        emit('close');
        emitter.emit(EVENTS.REFRESH_CUSTOMERS);
        form.reset();
    } catch (err) {
        console.log('Error while submitting customer.');
        console.log(err);
    } finally {
        submitting.value = false;
    }
};

const emit = defineEmits(['close']);
</script>

<template>
    <Modal :show="show" max-width="sm" @close="$emit('close')">
        <div class="p-6">
            <PageTitle size="sm" margin-bottom="mb-1"> New customer </PageTitle>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Create a new customer for your team.
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
            </div>

            <div class="mt-3 flex justify-end gap-x-1">
                <TheButton variant="ghost" @click="emit('close')"
                    >Cancel</TheButton
                >
                <TheButton
                    variant="primary"
                    @click="handleSubmit"
                    :disabled="!isFormValid"
                    >Save customer</TheButton
                >
            </div>
        </div>
    </Modal>
</template>
