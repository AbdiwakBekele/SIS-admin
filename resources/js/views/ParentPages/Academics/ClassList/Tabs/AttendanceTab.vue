<template>
    <div class="bg-white rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold">Attendance</h3>
        </div>
        <table class="w-full table-auto text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Student</th>
                    <th class="px-4 py-2 text-left">Days Present</th>
                    <th class="px-4 py-2 text-left">Days Absent</th>
                    <th class="px-4 py-2 text-left">Attendance %</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="att in attendance" :key="att.studentId" class="border-b">
                    <td class="px-4 py-2">{{ att.studentName }}</td>
                    <td class="px-4 py-2">{{ att.present }}</td>
                    <td class="px-4 py-2">{{ att.absent }}</td>
                    <td class="px-4 py-2">
                        {{ ((att.present / (att.present + att.absent)) * 100).toFixed(1) }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
const props = defineProps({
    attendance: { type: Array, default: () => [] },
})

const emit = defineEmits([])
</script>
