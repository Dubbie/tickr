<script setup>
import {
    IconHome,
    IconTicket,
    IconUsers,
    IconUsersGroup,
} from '@tabler/icons-vue';
import SidebarNavLink from './SidebarNavLink.vue';
</script>

<template>
    <nav class="min-h-auto flex flex-col space-y-1 overflow-visible">
        <SidebarNavLink
            :href="route('dashboard')"
            :icon="IconHome"
            :is-active="route().current('dashboard')"
            >Home</SidebarNavLink
        >
        <SidebarNavLink
            :href="route('customer.index')"
            :icon="IconUsers"
            :is-active="route().current('customer.*')"
            >Customers</SidebarNavLink
        >
        <SidebarNavLink
            :href="route('ticket.index')"
            :icon="IconTicket"
            :is-active="route().current('ticket.*')"
            >Tickets</SidebarNavLink
        >
        <SidebarNavLink
            :href="route('user.index')"
            :icon="IconUsersGroup"
            :is-active="route().current('user.*')"
            >Users</SidebarNavLink
        >
    </nav>
</template>
