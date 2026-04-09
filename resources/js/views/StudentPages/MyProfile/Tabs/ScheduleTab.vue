<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong> Schedule Info </strong> </p>
        <hr class="mx-4">

        <div v-if="student?.schedule && student.schedule.length > 0">
            <div class="grid grid-cols-5 my-2">
                <div class="col-span-1">
                    <span class="text-sm">MONDAY</span>
                </div>
                <div class="col-span-1">
                    <span class="text-sm">TUESDAY</span>
                </div>
                <div class="col-span-1">
                    <span class="text-sm">WEDNESDAY</span>
                </div>
                <div class="col-span-1">
                    <span class="text-sm">THURSDAY</span>
                </div>
                <div class="col-span-1">
                    <span class="text-sm">FRIDAY</span>
                </div>
            </div>

            <div class="grid grid-cols-5 text-left">
                <div v-for="(daySchedule, dayIndex) in student.schedule" :key="dayIndex" class="col-span-1">
                    <div v-for="(classItem, classIndex) in daySchedule.classes" :key="classIndex"
                        class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-3">
                        <p class="text-sm"><strong>{{ classItem.courseName || classItem.title }}</strong></p>
                        <span class="text-gray-600" style="font-size:12px">Location: {{ classItem.location || '-' }}</span>
                        <br>
                        <span class="text-gray-600" style="font-size:12px">Teacher: {{ classItem.teacherName || '-' }}</span><br>
                        <span class="text-gray-600" style="font-size:12px">{{ classItem.startTime || '-' }} - {{ classItem.endTime || '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Schedule</p>
            <p class="text-xs text-gray-500 mt-1">Schedule information is not available at this time.</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    student: Object
})
</script>

