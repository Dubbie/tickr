<script setup>
const { reply } = defineProps({
    reply: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="flex items-start gap-x-3 text-sm">
        <div class="shrink-0">
            <img
                :src="reply.profile_photo_url"
                alt=""
                class="size-6 rounded-md"
            />
        </div>

        <div class="grow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ reply.replier.name }}
                    </p>
                    <p
                        class="text-xs font-medium text-zinc-500 dark:text-zinc-400"
                        v-if="reply.email"
                    >
                        {{ reply.email }}
                    </p>
                </div>

                <p class="text-xs text-zinc-500">{{ reply.time_ago }}</p>
            </div>

            <p class="mt-2 font-medium text-zinc-600 dark:text-zinc-400">
                {{ reply.message }}
            </p>
        </div>
    </div>
</template>
