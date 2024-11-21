<script setup>
import PageTitle from '@/Components/PageTitle.vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

defineProps({
    userId: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <SidebarLayout>
        <PageTitle class="mt-0.5">User details</PageTitle>
    </SidebarLayout>
</template>
