<script setup>
import TableBody from '@/Components/TableBody.vue';
import TableCell from '@/Components/TableCell.vue';
import TableHead from '@/Components/TableHead.vue';
import TableHeader from '@/Components/TableHeader.vue';
import TableRow from '@/Components/TableRow.vue';
import TheSkeleton from '@/Components/TheSkeleton.vue';
import TheTable from '@/Components/TheTable.vue';

defineProps({
    rows: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <TheTable>
        <TableHead>
            <TableHeader>
                <TheSkeleton class="h-6 w-10" />
            </TableHeader>
            <TableHeader>
                <TheSkeleton class="h-6 w-16" />
            </TableHeader>
            <TableHeader>
                <TheSkeleton class="h-6 w-24" />
            </TableHeader>
        </TableHead>
        <TableBody>
            <TableRow v-for="i in rows" :key="i">
                <TableCell>
                    <TheSkeleton class="h-6 w-28" />
                </TableCell>
                <TableCell>
                    <TheSkeleton class="h-6 w-32" />
                </TableCell>
                <TableCell>
                    <TheSkeleton class="h-6 w-56" />
                </TableCell>
            </TableRow>
        </TableBody>
    </TheTable>
</template>
