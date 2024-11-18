<script setup></script>

<template>
    <div class="min-h-svh bg-zinc-100">
        <div class="mx-auto max-w-xs py-6 lg:py-12">
            <div class="flex flex-col items-center justify-center">
                <img
                    src="//picsum.photos/96"
                    alt="Company logo"
                    class="mb-3 size-16 rounded-xl shadow-lg"
                />

                <h1 class="text-xl font-bold">ACME Inc.</h1>
                <p class="text-xs font-semibold text-zinc-500">
                    Customer Service Center
                </p>
            </div>

            <div class="mt-12">
                <slot />
            </div>
        </div>
    </div>
</template>
