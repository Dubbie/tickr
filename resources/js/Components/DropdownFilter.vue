<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import TextInput from './TextInput.vue';
import { IconSearch } from '@tabler/icons-vue';

const { label, icon, options, selectedOptions } = defineProps({
    label: {
        type: String,
        required: true,
    },
    icon: {
        type: Function,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
    selectedOptions: {
        type: Array,
        default: () => [],
    },
});

const search = ref('');
const isOpen = ref(false);
const dropdownRef = ref();
const localSelected = ref([...selectedOptions]);
const focusedIndex = ref(0);

const filteredOptions = computed(() => {
    return options.filter((opt) =>
        opt.label.toLowerCase().includes(search.value.toLowerCase()),
    );
});

const handleClickOutside = (event) => {
    if (
        isOpen.value &&
        dropdownRef.value &&
        !dropdownRef.value.contains(event.target)
    ) {
        closeDropdown();
    }
};

const closeDropdown = () => {
    isOpen.value = false;
    focusedIndex.value = 0;
};

const updateFilters = () => {
    emit('update', localSelected);
};

const handleKeyDown = (event) => {
    if (!isOpen.value) return;

    const focusedOption = filteredOptions.value[focusedIndex.value];

    switch (event.key) {
        case 'Escape':
            closeDropdown();
            break;
        case 'ArrowDown':
            event.preventDefault();
            focusedIndex.value =
                (focusedIndex.value + 1) % filteredOptions.value.length;
            break;
        case 'ArrowUp':
            event.preventDefault();
            focusedIndex.value =
                (focusedIndex.value - 1 + filteredOptions.value.length) %
                filteredOptions.value.length;
            break;
        case ' ':
        case 'Enter':
            event.preventDefault();

            if (focusedOption) {
                const index = localSelected.value.indexOf(focusedOption.value);
                if (index > -1) {
                    localSelected.value.splice(index, 1); // Remove if selected
                } else {
                    localSelected.value.push(focusedOption.value); // Add if not selected
                }
                updateFilters();
            }
            break;
    }
};

const selectedLabels = computed(() => {
    if (localSelected.value.length === 0) return null;

    if (localSelected.value.length > 2)
        return [`${localSelected.value.length} selected`];

    return options
        .filter((opt) => localSelected.value.includes(opt.value))
        .map((opt) => opt.label);
});

const emit = defineEmits(['update']);

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleKeyDown);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
    <div class="relative" ref="dropdownRef">
        <div
            class="flex cursor-pointer items-center gap-x-2 rounded-md border border-dashed border-zinc-900/20 px-2 py-1 text-zinc-800 hover:bg-zinc-900/5 dark:border-white/20 dark:text-zinc-200 dark:hover:bg-white/5"
            @click="isOpen = !isOpen"
            :aria-expanded="isOpen.toString()"
            :aria-controls="`${label}-dropdown`"
        >
            <div class="flex items-center gap-x-2">
                <component :is="icon" class="size-5" />
                <p class="select-none text-xs font-semibold">{{ label }}</p>
            </div>

            <div
                v-if="localSelected.length > 0"
                class="flex items-center gap-x-1 border-l border-zinc-900/20 pl-2 text-xs font-medium dark:border-zinc-100/20"
            >
                <p
                    v-for="opt in selectedLabels"
                    :key="opt"
                    class="rounded-md bg-zinc-900/10 px-2 dark:bg-zinc-100/10"
                >
                    {{ opt }}
                </p>
            </div>
        </div>

        <transition
            enter-active-class="transition origin-top ease-out duration-200"
            enter-from-class="scale-90 opacity-0"
            enter-to-class="scale-100 opacity-100"
            leave-active-class="transition origin-center ease-in duration-150"
            leave-from-class="scale-100 opacity-100"
            leave-to-class="scale-90 opacity-0"
        >
            <div
                class="absolute left-0 top-full z-10 min-w-48 translate-y-1 rounded-lg bg-white text-sm font-medium ring-1 ring-zinc-900/10 dark:bg-zinc-800 dark:ring-white/10"
                v-show="isOpen"
                :id="`${label}-dropdown`"
                role="menu"
            >
                <div
                    class="relative border-b border-zinc-900/10 dark:border-zinc-100/10"
                >
                    <TextInput
                        v-model="search"
                        v-bind="{
                            class: 'w-full text-sm',
                            placeholder: label,
                            hasIcon: true,
                            borderless: true,
                        }"
                    />
                    <IconSearch
                        class="absolute left-3 top-2 size-4 text-zinc-500 dark:text-zinc-400"
                    />
                </div>
                <div class="p-1" v-if="filteredOptions.length">
                    <div
                        v-for="(opt, index) in filteredOptions"
                        :key="opt.value"
                        :class="[
                            'flex items-center rounded-md px-2 py-1',
                            {
                                'bg-zinc-950/5 dark:bg-white/5':
                                    focusedIndex === index,
                            },
                        ]"
                        @mouseenter="focusedIndex = index"
                        role="menuitem"
                    >
                        <input
                            type="checkbox"
                            :id="`ch-${opt.value}`"
                            :value="opt.value"
                            class="rounded border-zinc-900/30 bg-transparent focus:outline-none focus:ring-0 focus:ring-offset-0 dark:border-zinc-100/30"
                            v-model="localSelected"
                            @change="updateFilters()"
                        />
                        <label
                            :for="`ch-${opt.value}`"
                            class="block flex-1 select-none pl-2"
                            >{{ opt.label }}</label
                        >
                    </div>
                </div>
                <div v-else class="p-4 text-center">
                    <p>No results found.</p>
                </div>
            </div>
        </transition>
    </div>
</template>
