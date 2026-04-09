<template>
    <!-- Student Notes -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-2">
            <p class="text-sm font-semibold mx-4">Student Notes</p>
        </div>
        <hr class="mx-4" />

        <div v-if="student?.studentNotes && student.studentNotes.length > 0">
            <div v-for="studentNote in student.studentNotes" :key="studentNote.uuid"
                class="bg-gray-100 border border-gray-300 mx-3 mb-3 rounded-lg shadow-md">
                <div class="grid grid-cols-12 text-left gap-y-2">
                    <div class="col-span-12 flex items-center justify-between px-4 pt-2">
                        <h3 class="text-sm font-semibold">{{ studentNote.date }}</h3>
                    </div>
                    <div class="col-span-12 px-4 pb-2">
                        <p class="text-sm"><strong>{{ studentNote.title }}</strong></p>
                        <p class="text-gray-600 text-sm mt-1" v-html="studentNote.description"></p>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Student Notes</p>
            <p class="text-xs text-gray-500 mt-1">Student Notes information is not available at this time.</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    student: Object,
})
</script>

