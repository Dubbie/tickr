<script setup></script>

<template>
    <div class="min-h-svh bg-zinc-100">
        <div class="mx-auto max-w-2xl py-6 lg:py-12">
            <div>
                <div
                    class="rounded-tl-2xl rounded-tr-2xl bg-indigo-500 p-8 text-white"
                >
                    <h1 class="text-2xl font-bold">
                        ACME Inc. Customer Service Center
                    </h1>
                </div>

                <div class="rounded-bl-2xl rounded-br-2xl bg-white p-8">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
