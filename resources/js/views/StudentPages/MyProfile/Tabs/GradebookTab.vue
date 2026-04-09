<template>
    <div class="bg-white rounded-lg border-r px-4 py-5 text-center mt-2">
        <div v-if="student?.gradebook && student.gradebook.length > 0">
            <div v-for="(semester, semIdx) in student.gradebook" :key="semIdx" class="mb-5">
                <p class="text-left"> <strong>{{ semester.name || `Semester ${semIdx + 1}` }}</strong> </p>
                <div class="rounded-xl overflow-hidden shadow-lg ring-1 ring-blue-300/50 dark:ring-white/10 mt-2">
                    <table class="min-w-full divide-y-2 divide-blue-400 dark:divide-blue-300 bg-white dark:bg-neutral-900">
                        <thead class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-md">
                            <tr>
                                <th scope="col" class="sm:px-2 xl:px-4 py-4 text-left text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                    Course Title
                                </th>
                                <th v-for="(assignment, aIdx) in getAssignments(semester)" :key="aIdx" 
                                    scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                    {{ assignment.name }}
                                </th>
                                <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-blue-400 dark:divide-blue-300 striped-rows">
                            <tr v-for="(course, cIdx) in semester.courses" :key="cIdx" class="data-row">
                                <td class="py-2 sm:px-2 xl:px-4 text-sm border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">{{ course.title }}</td>
                                <td v-for="(assignment, aIdx) in getAssignments(semester)" :key="aIdx"
                                    class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">
                                    {{ getAssignmentGrade(course, assignment) }}
                                </td>
                                <td class="py-2 sm:px-2 xl:px-4 text-sm text-center last:border-r-0 text-gray-900 dark:text-gray-400">{{ course.total || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- No Progress Report Message -->
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Progress Reports</p>
            <p class="text-xs text-gray-500 mt-1">Progress reports are not available at this time.</p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    student: Object
})

const getAssignments = (semester) => {
    if (!semester.courses || semester.courses.length === 0) return []
    const firstCourse = semester.courses[0]
    return firstCourse.assignments || []
}

const getAssignmentGrade = (course, assignment) => {
    if (!course.assignments) return '-'
    const assignmentData = course.assignments.find(a => a.name === assignment.name || a.id === assignment.id)
    return assignmentData?.grade || assignmentData?.score || '-'
}
</script>

