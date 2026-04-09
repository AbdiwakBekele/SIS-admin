<template>
    <div class="bg-white rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-sm font-semibold">Enrolled Students</p>
                <p class="text-xs text-gray-500 mt-1">{{ students.length }} student(s) enrolled in this class</p>
            </div>
        </div>

        <div v-if="!students.length" class="text-center py-12 text-gray-400">
            <i class="fa-solid fa-users text-4xl mb-3"></i>
            <p class="text-lg font-medium">No students enrolled</p>
        </div>

        <table v-else class="w-full table-auto text-sm border mt-2">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Student Name</th>
                    <th class="px-4 py-2 text-left">ID Number</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Current Grade</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="student in students" :key="student.uuid" class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <div>
                                <p class="font-medium">{{ student.name }}</p>
                                <p v-if="student.first_name || student.last_name" class="text-xs text-gray-500">
                                    {{ student.first_name }} {{ student.last_name }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <span class="text-gray-600">{{ student.idNumber || 'N/A' }}</span>
                    </td>
                    <td class="px-4 py-2">
                        <span :class="[
                            'px-2 py-1 rounded text-xs font-medium',
                            student.enrollment_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                        ]">
                            {{ student.enrollment_status || 'enrolled' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <span v-if="student.final_grade_letter" class="font-medium">
                            {{ student.final_grade_letter }}
                            <span v-if="student.final_grade_numeric" class="text-xs text-gray-500">
                                ({{ student.final_grade_numeric }}%)
                            </span>
                        </span>
                        <span v-else class="text-gray-400">Not graded yet</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Enrollment Summary -->
        <div v-if="students.length" class="mt-4 p-4 bg-gray-50 rounded-lg">
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Total Enrolled</p>
                    <p class="text-2xl font-bold">{{ students.length }}</p>
                </div>
                <div>
                    <p class="text-gray-600">With Grades</p>
                    <p class="text-2xl font-bold">{{ studentsWithGrades }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Pending</p>
                    <p class="text-2xl font-bold">{{ students.length - studentsWithGrades }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    students: { type: Array, default: () => [] },
})

const emit = defineEmits(['view-profile'])

const studentsWithGrades = computed(() => {
    return props.students.filter(s => s.final_grade_letter || s.final_grade_numeric).length
})
</script>
