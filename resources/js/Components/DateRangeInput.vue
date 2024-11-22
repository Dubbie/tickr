<script setup>
import { ref } from 'vue';
import {
    format,
    startOfWeek,
    endOfWeek,
    subWeeks,
    startOfMonth,
    endOfMonth,
    subMonths,
} from 'date-fns';
import SelectInput from './SelectInput.vue';

const modelValue = defineModel();

// Dynamic ranges
const ranges = ref(generateDateRanges());

// Function to generate dynamic date ranges
function generateDateRanges() {
    const now = new Date();

    // Get this week's start and end
    const thisWeekStart = startOfWeek(now, { weekStartsOn: 1 }); // Monday start
    const thisWeekEnd = endOfWeek(now, { weekStartsOn: 1 });

    // Last week's start and end
    const lastWeekStart = subWeeks(thisWeekStart, 1);
    const lastWeekEnd = subWeeks(thisWeekEnd, 1);

    // This month's start and end
    const thisMonthStart = startOfMonth(now);
    const thisMonthEnd = endOfMonth(now);

    // Last month's start and end
    const lastMonthStart = subMonths(thisMonthStart, 1);
    const lastMonthEnd = subMonths(thisMonthEnd, 1);

    return [
        {
            label: `${format(thisWeekStart, 'MMM d')} - ${format(thisWeekEnd, 'MMM d')}`,
            value: 'this_week',
        },
        {
            label: `${format(lastWeekStart, 'MMM d')} - ${format(lastWeekEnd, 'MMM d')}`,
            value: 'last_week',
        },
        {
            label: `${format(thisMonthStart, 'MMM d')} - ${format(thisMonthEnd, 'MMM d')}`,
            value: 'this_month',
        },
        {
            label: `${format(lastMonthStart, 'MMM d')} - ${format(lastMonthEnd, 'MMM d')}`,
            value: 'last_month',
        },
    ];
}
</script>

<template>
    <div class="flex items-center justify-end">
        <SelectInput v-model="modelValue" :options="ranges" />
    </div>
</template>
