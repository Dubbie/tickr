<script setup>
import { computed } from 'vue';

const { reply } = defineProps({
    reply: {
        type: Object,
        required: true,
    },
});

const imageUrl = computed(() => {
    if (reply.email) {
        return '//picsum.photos/96';
    }

    return reply.replier.profile_photo_url;
});
</script>

<template>
    <div class="flex items-start gap-x-3 text-sm">
        <div>
            <img :src="imageUrl" alt="" class="size-8 rounded-md" />
        </div>

        <div class="grow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold text-zinc-900">
                        {{ reply.replier.name }}
                    </p>
                    <p
                        class="mb-2 text-xs font-medium text-zinc-500"
                        v-if="reply.email"
                    >
                        {{ reply.email }}
                    </p>
                </div>

                <p class="text-xs text-zinc-500">{{ reply.time_ago }}</p>
            </div>

            <p class="font-medium text-zinc-600">{{ reply.message }}</p>
        </div>
    </div>
</template>
