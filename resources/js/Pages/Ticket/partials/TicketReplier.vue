<script setup>
import TextareaInput from '@/Components/TextareaInput.vue';
import TheButton from '@/Components/TheButton.vue';
import { EVENTS } from '@/constants';
import { useForm } from '@inertiajs/vue3';
import { inject, ref } from 'vue';

const emitter = inject('emitter');
const ticket = inject('ticket');
const savingReply = ref(false);
const form = useForm({
    message: '',
});

const handleReply = async () => {
    savingReply.value = true;

    try {
        await axios.post(
            route('api.ticket.reply.store', ticket.value.ticket_number),
            {
                message: form.message,
            },
        );

        emitter.emit(EVENTS.REFRESH_TICKET);
        form.reset();
    } catch (err) {
        console.log('Error while saving ticket reply.');
        console.log(err);
    } finally {
        savingReply.value = false;
    }
};
</script>

<template>
    <div>
        <div>
            <div class="flex items-start gap-x-3">
                <img
                    :src="$page.props.auth.user.profile_photo_url"
                    alt=""
                    class="size-8 rounded-md"
                />

                <TextareaInput
                    v-model="form.message"
                    class="w-full text-sm"
                    rows="2"
                    placeholder="Write a reply..."
                />
            </div>
        </div>
        <div class="ml-11 mt-3">
            <TheButton
                variant="primary"
                @click="handleReply"
                :disabled="savingReply"
                >Submit reply</TheButton
            >
        </div>
    </div>
</template>
