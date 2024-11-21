<script setup>
import TableCell from '@/Components/TableCell.vue';
import TableHeading from '@/Components/TableHeading.vue';
import TheSkeleton from '@/Components/TheSkeleton.vue';
import TheTable from '@/Components/TheTable.vue';

defineProps({
    rows: {
        type: Number,
        required: true,
    },
});

const heightClass = 'h-6';
</script>

<template>
    <TheTable size="sm">
        <template #headings>
            <TableHeading>
                <TheSkeleton class="w-10" :class="heightClass" />
            </TableHeading>
            <TableHeading>
                <TheSkeleton class="w-16" :class="heightClass" />
            </TableHeading>
            <TableHeading>
                <TheSkeleton class="w-24" :class="heightClass" />
            </TableHeading>
        </template>
        <template #rows>
            <tr v-for="i in rows" :key="i">
                <TableCell>
                    <TheSkeleton class="w-28" :class="heightClass" />
                </TableCell>
                <TableCell>
                    <TheSkeleton class="w-32" :class="heightClass" />
                </TableCell>
                <TableCell>
                    <TheSkeleton class="w-56" :class="heightClass" />
                </TableCell>
            </tr>
        </template>
    </TheTable>
</template>
