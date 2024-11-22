<script setup>
import Modal from './Modal.vue';
import PageTitle from './PageTitle.vue';
import TheButton from './TheButton.vue';

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    confirmLabel: {
        type: String,
        default: 'Do action',
    },
});

defineEmits(['close', 'confirm']);
</script>

<template>
    <Modal max-width="sm" :show="show" @close="$emit('close')">
        <div class="p-6">
            <PageTitle size="sm"><slot name="title" /></PageTitle>

            <div class="my-6 text-sm">
                <slot name="body" />
            </div>

            <div class="flex justify-end gap-x-3">
                <TheButton variant="ghost" @click="$emit('close')"
                    >Cancel</TheButton
                >
                <TheButton variant="primary" @click="$emit('confirm')">{{
                    confirmLabel
                }}</TheButton>
            </div>
        </div>
    </Modal>
</template>
