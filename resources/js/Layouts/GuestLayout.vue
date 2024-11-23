<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ThePattern from '@/Components/ThePattern.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center pt-6 sm:pt-24">
        <div class="relative">
            <Link href="/" class="relative z-10">
                <ApplicationLogo size="md" />
            </Link>

            <!-- pattern -->
            <ThePattern
                class="absolute left-1/2 top-1/2 size-[600px] -translate-x-1/2 -translate-y-1/2 [mask-image:radial-gradient(circle,#000_10%,transparent_70%)]"
            />
        </div>

        <div class="relative z-10 mt-3 w-full px-6 py-4 sm:max-w-sm">
            <slot />
        </div>
    </div>
</template>
