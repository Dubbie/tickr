<script setup>
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue';
import { IconSelector } from '@tabler/icons-vue';
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        required: false,
    },
    emptyLabel: {
        type: String,
        default: 'Please choose...',
    },
    options: {
        type: Array,
        required: true,
    },
    dropdownPosition: {
        type: String,
        default: 'left',
    },
});

const selectedOption = computed(() => {
    return props.options.find((option) => {
        return option.value === props.modelValue;
    });
});

const label = computed(() => {
    if (!selectedOption.value) {
        return props.emptyLabel;
    }

    return selectedOption.value.label;
});

const handleChange = (newValue) => {
    emit('update:model-value', newValue);
};

const emit = defineEmits(['update:model-value']);

const dropdownPositionClasses = computed(() => {
    return {
        left: 'left-0',
        right: 'right-0',
    }[props.dropdownPosition];
});
</script>

<template>
    <Listbox
        :model-value="modelValue"
        @update:model-value="handleChange"
        as="div"
        class="relative"
        v-slot="{ open }"
    >
        <ListboxButton
            class="w-full rounded-md px-3 py-1.5 text-sm ring-inset"
            :class="{
                'ring-2 ring-indigo-500': open,
                'ring-1 ring-zinc-900/10 hover:ring-zinc-900/20': !open,
            }"
        >
            <div class="flex justify-between">
                <p>{{ label }}</p>

                <IconSelector class="-mr-2 size-5 text-zinc-500" />
            </div>
        </ListboxButton>

        <ListboxOptions
            class="absolute top-full z-10 mt-1 max-h-64 overflow-y-scroll rounded-lg bg-white p-1 shadow-lg shadow-black/5 ring-1 ring-inset ring-zinc-900/10"
            :class="dropdownPositionClasses"
        >
            <ListboxOption
                v-for="option in options"
                :key="option"
                :value="option.value"
                v-slot="{ active, selected }"
            >
                <div
                    class="cursor-pointer rounded-md px-2 py-1 text-sm"
                    :class="{
                        'bg-indigo-600 text-white': active,
                        'bg-zinc-900/5 font-medium': selected,
                    }"
                >
                    <p>{{ option.label }}</p>
                </div>
            </ListboxOption>
        </ListboxOptions>
    </Listbox>
</template>
