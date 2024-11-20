<script setup>
import { router, useForm } from '@inertiajs/vue3';
import InputLabel from './InputLabel.vue';
import Modal from './Modal.vue';
import TextInput from './TextInput.vue';
import TextareaInput from './TextareaInput.vue';
import TheButton from './TheButton.vue';
import { computed, inject, onMounted, ref, watch } from 'vue';
import axios from 'axios';
import SelectInput from './SelectInput.vue';
import { IconInfoCircleFilled } from '@tabler/icons-vue';

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emitter = inject('emitter');
const customers = ref([]);
const loadingCustomers = ref(false);
const submittingTicket = ref(false);
const form = useForm({
    customerId: null,
    contactEmail: '',
    subject: '',
    description: '',
});

const customerOptions = computed(() => {
    if (customers.value.length === 0) return [];

    return customers.value.map((customer) => {
        return {
            label: customer.name,
            value: customer.uuid,
        };
    });
});

const isFormValid = computed(() => {
    const hasCustomerSelected = form.customerId !== null;
    const subjectFilled = form.subject.length > 0;
    const descriptionFilled = form.description.length > 0;
    const emailFilled = form.contactEmail.length > 0;

    return (
        hasCustomerSelected && subjectFilled && descriptionFilled && emailFilled
    );
});

const handleSubmit = async () => {
    submittingTicket.value = true;

    try {
        const response = await axios.post(route('api.ticket.store'), {
            customer_uuid: form.customerId,
            contact_email: form.contactEmail,
            subject: form.subject,
            description: form.description,
            priority: 'medium',
        });

        emit('close');
        form.reset();
        router.visit(route('ticket.show', response.data.ticket.ticket_number));
    } catch (err) {
        console.log('Error while submitting ticket.');
        console.log(err);
    } finally {
        submittingTicket.value = false;
    }
};

const fetchCustomers = async () => {
    loadingCustomers.value = true;

    try {
        const response = await axios.get(route('api.customer.index'));

        customers.value = response.data;
    } catch (err) {
        console.log('Error while loading customers.');
        console.log(err);
    }
};

const emit = defineEmits(['close']);

watch(
    () => form.customerId,
    (newId) => {
        if (!newId) {
            form.contactEmail = '';
            return;
        }

        const customer = customers.value.find((customer) => {
            return customer.uuid === newId;
        });
        form.contactEmail = customer.email;
    },
);

onMounted(() => {
    fetchCustomers();
});
</script>

<template>
    <Modal :show="show" max-width="sm" @close="$emit('close')">
        <div class="p-6">
            <h2 class="mb-1 font-semibold text-zinc-800 dark:text-zinc-100">
                New ticket
            </h2>
            <p class="text-sm text-zinc-500">
                Create a new ticket for a customer.
            </p>

            <div class="mt-6">
                <InputLabel for="customer-id">Customer</InputLabel>
                <SelectInput
                    v-model="form.customerId"
                    :options="customerOptions"
                    empty-label="Please choose a customer..."
                />
            </div>

            <div v-if="form.customerId" class="mt-3 space-y-3">
                <div>
                    <InputLabel for="contact-email">Contact email</InputLabel>
                    <TextInput
                        type="email"
                        id="contact-email"
                        name="contact-email"
                        class="w-full text-sm"
                        v-model="form.contactEmail"
                    />
                    <p class="mt-2 text-xs font-medium text-zinc-500">
                        Notifications will be sent here.
                    </p>
                </div>

                <div>
                    <InputLabel for="subject">Subject</InputLabel>
                    <TextInput
                        id="subject"
                        name="subject"
                        class="w-full text-sm"
                        v-model="form.subject"
                    />
                </div>

                <div>
                    <InputLabel for="description">Description</InputLabel>
                    <TextareaInput
                        id="description"
                        name="description"
                        class="w-full text-sm"
                        v-model="form.description"
                    />
                </div>
            </div>

            <div
                v-else
                class="mt-3 flex items-start gap-x-3 rounded-md bg-zinc-100 p-4 text-sm font-medium text-zinc-600"
            >
                <IconInfoCircleFilled class="size-5 shrink-0 text-zinc-400" />
                <p>To create a new ticket, please choose a customer first.</p>
            </div>

            <div class="mt-3 flex justify-end gap-x-1">
                <TheButton variant="ghost" @click="emit('close')"
                    >Cancel</TheButton
                >
                <TheButton
                    variant="primary"
                    @click="handleSubmit"
                    :disabled="!isFormValid"
                    >Save ticket</TheButton
                >
            </div>
        </div>
    </Modal>
</template>
