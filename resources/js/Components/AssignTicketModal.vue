<script setup>
import { computed, onMounted, ref } from 'vue';
import Modal from './Modal.vue';
import TheButton from './TheButton.vue';
import axios from 'axios';
import SelectInput from './SelectInput.vue';
import { useForm } from '@inertiajs/vue3';
import PageTitle from './PageTitle.vue';

const { show, ticketNumber, assigneeId } = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    ticketNumber: {
        type: String,
        required: true,
    },
    assigneeId: Number,
});

const loadingUsers = ref(true);
const assigning = ref(false);
const users = ref([]);
const form = useForm({
    userId: assigneeId,
});

const userOptions = computed(() => {
    return users.value.map((user) => {
        return {
            label: user.name,
            value: user.id,
        };
    });
});

const fetchUsers = async () => {
    loadingUsers.value = true;

    try {
        const response = await axios.get(route('api.user.index'));

        users.value = response.data;
    } catch (err) {
        console.log('Error while loading users.');
        console.log(err);
    } finally {
        loadingUsers.value = false;
    }
};

const assignToUser = async () => {
    assigning.value = true;

    try {
        const response = await axios.post(
            route('api.ticket.assign', ticketNumber),
            {
                user_id: form.userId,
            },
        );

        if (response.data.success) {
            form.reset();
            emit('update');
            emit('close');
        }
    } catch (err) {
        console.log('Error while loading assigning to user.');
        console.log(err);
    } finally {
        assigning.value = false;
    }
};

const emit = defineEmits(['close', 'update']);

onMounted(() => {
    fetchUsers();
});
</script>

<template>
    <Modal :show="show" max-width="sm" @close="$emit('close')">
        <div class="p-4">
            <PageTitle size="sm" margin-bottom="mb-1">Assign ticket</PageTitle>
            <p class="mb-6 text-sm text-zinc-500 dark:text-zinc-400">
                Choose who is responsible for handling this ticket.
            </p>

            <div v-if="loadingUsers">
                <p>Loading users...</p>
            </div>
            <div v-else>
                <SelectInput v-model="form.userId" :options="userOptions" />
            </div>

            <div class="mt-3 flex justify-end gap-x-1">
                <TheButton variant="ghost" @click="$emit('close')"
                    >Cancel</TheButton
                >
                <TheButton variant="primary" @click="assignToUser"
                    >Assign to user</TheButton
                >
            </div>
        </div>
    </Modal>
</template>
