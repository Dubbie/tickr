<script setup>
import TheCard from '@/Components/TheCard.vue';
import TheSkeleton from '@/Components/TheSkeleton.vue';
</script>

<template>
    <div>
        <TheCard>
            <div class="flex h-7 items-center justify-start gap-x-3">
                <div class="h-full">
                    <TheSkeleton class="h-full w-20" />
                </div>

                <div class="h-full grow">
                    <TheSkeleton class="h-full w-24" />
                </div>

                <div class="h-full">
                    <TheSkeleton class="h-full w-32" />
                </div>
            </div>

            <div class="mt-3">
                <TheSkeleton class="h-7 w-64" />
            </div>

            <div class="mt-3">
                <TheSkeleton class="h-7 w-2/3" />
            </div>
        </TheCard>

        <div class="mt-6 space-y-6">
            <div v-for="i in 3" :key="i" class="h-16 rounded-md bg-zinc-200" />
        </div>
    </div>
</template>
