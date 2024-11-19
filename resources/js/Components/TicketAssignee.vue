<script setup>
defineProps({
    assignee: Object,
    interactive: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div
        class="rounded-md p-1"
        :class="{ 'cursor-pointer hover:bg-zinc-900/5': interactive }"
    >
        <div class="flex items-center space-x-2 text-xs">
            <template v-if="!assignee">
                <div
                    class="size-6 rounded-md border border-dashed border-zinc-900/40 bg-transparent"
                ></div>

                <p class="text-zinc-500">Nobody</p>
            </template>
            <template v-else>
                <img
                    src="//picsum.photos/96"
                    alt=""
                    class="size-6 rounded-md"
                />

                <p>{{ assignee.name }}</p>
            </template>
        </div>
    </div>
</template>
