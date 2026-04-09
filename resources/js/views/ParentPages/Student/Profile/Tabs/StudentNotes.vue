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
                <div class="grid grid-cols-12 text-left gap-y-2 px-4 pt-2">
                    <div class="col-span-12">
                        <h3 class="text-sm font-semibold">{{ studentNote.date?.formatted || studentNote.date || '-' }}</h3>
                    </div>
                    <div class="col-span-12">
                        <p class="text-sm"><strong>Title:</strong></p>
                        <span class="text-gray-600 text-sm">{{ studentNote.title || '-' }}</span>
                    </div>
                    <div class="col-span-12">
                        <p class="text-sm"><strong>Description:</strong></p>
                        <div class="text-gray-600 text-sm" v-html="studentNote.description || '-'"></div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="pr-6 py-5 text-center">
            <i class="fa-solid fa-file-circle-exclamation text-2xl mb-2 text-gray-500"></i>
            <p class="text-gray-500">No Student Notes Recorded Yet.</p>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue"

const props = defineProps({
    student: Object,
})
</script>
