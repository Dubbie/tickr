<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    customers: Array,
});
</script>

<template>
    <AppLayout>
        <p>Customers!</p>

        <div>
            <div
                v-for="customer in customers"
                :key="customer.id"
                class="grid grid-cols-3"
            >
                <p>{{ customer.name }}</p>
                <p>{{ customer.email }}</p>
                <p>{{ customer.id }}</p>
            </div>
        </div>
    </AppLayout>
</template>
