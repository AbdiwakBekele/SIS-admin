<template>
    <div class="bg-white rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
            <!-- Left Column -->
            <div class="space-y-6 flex flex-col">
                <!-- Course Image -->
                <div v-if="course.image" class="rounded-lg overflow-hidden shadow-md">
                    <img :src="course.image" alt="Course image" class="w-full h-48 object-cover" />
                </div>

                <!-- Course Information -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-white rounded-lg p-5 space-y-3 border border-blue-200 flex flex-col h-full">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-info-circle text-blue-600"></i>
                        <h3 class="text-sm font-semibold text-blue-900">Course Information</h3>
                    </div>
                    <div class="bg-white rounded-lg p-4 space-y-2 text-sm border border-blue-200">
                        <div v-if="course.title" class="flex items-start gap-2">
                            <span class="font-medium text-gray-600 min-w-[80px]">Title:</span>
                            <span class="text-gray-900">{{ course.title }}</span>
                        </div>
                        <div v-if="course.code" class="flex items-start gap-2">
                            <span class="font-medium text-gray-600 min-w-[80px]">Code:</span>
                            <span class="text-gray-900">{{ course.code }}</span>
                        </div>
                        <div v-if="course.section" class="flex items-start gap-2">
                            <span class="font-medium text-gray-600 min-w-[80px]">Section:</span>
                            <span class="text-gray-900">{{ course.section }}</span>
                        </div>
                        <div v-if="course.category" class="flex items-start gap-2">
                            <span class="font-medium text-gray-600 min-w-[80px]">Category:</span>
                            <span class="text-gray-900">{{ course.category }}</span>
                        </div>
                        <div v-if="course.subjectTitle" class="flex items-start gap-2">
                            <span class="font-medium text-gray-600 min-w-[80px]">Subject:</span>
                            <span class="text-gray-900">
                                {{ course.subjectTitle }}
                                <span v-if="course.subjectCode">({{ course.subjectCode }})</span>
                            </span>
                        </div>
                        <div v-if="course.gradeLevelTitle" class="flex items-start gap-2">
                            <span class="font-medium text-gray-600 min-w-[80px]">Grade Level:</span>
                            <span class="text-gray-900">{{ course.gradeLevelTitle }}</span>
                        </div>
                        <div v-if="course.credits" class="flex items-start gap-2">
                            <span class="font-medium text-gray-600 min-w-[80px]">Credits:</span>
                            <span class="text-gray-900">{{ course.credits }}</span>
                        </div>
                        <div v-if="course.schoolYearName" class="flex items-start gap-2">
                            <span class="font-medium text-gray-600 min-w-[80px]">School Year:</span>
                            <span class="text-gray-900">{{ course.schoolYearName }}</span>
                        </div>
                    </div>
                </div>

                <!-- Enrollment Information -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-white rounded-lg p-5 space-y-3 border border-blue-200 flex flex-col h-full">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-users text-blue-600"></i>
                        <h3 class="text-sm font-semibold text-blue-900">Enrollment</h3>
                    </div>
                    <div class="bg-white rounded-lg p-4 space-y-2 text-sm border border-blue-200">
                        <div v-if="course.currentEnrollment !== null && course.currentEnrollment !== undefined"
                            class="flex items-center justify-between">
                            <span class="font-medium text-gray-600">Current Students:</span>
                            <span class="text-gray-900 font-semibold">
                                {{ course.currentEnrollment }}<span v-if="enrollmentSize"> / {{ enrollmentSize }}</span>
                                students
                            </span>
                        </div>
                        <div v-if="enrollmentSize && (course.currentEnrollment === null || course.currentEnrollment === undefined)"
                            class="flex items-center justify-between">
                            <span class="font-medium text-gray-600">Max Enrollment:</span>
                            <span class="text-gray-900 font-semibold">{{ enrollmentSize }} students</span>
                        </div>
                        <div v-if="enrollmentSize && course.currentEnrollment !== null && course.currentEnrollment !== undefined"
                            class="pt-3 border-t border-blue-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-600">Available Spots:</span>
                                <span class="text-gray-900 font-semibold">
                                    {{ Math.max(0, enrollmentSize - course.currentEnrollment) }} remaining
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full transition-all"
                                    :style="{ width: `${Math.min(100, (course.currentEnrollment / enrollmentSize) * 100)}%` }">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6 flex flex-col">
                <!-- Class Schedule -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-white rounded-lg p-5 space-y-4 border border-blue-200 flex flex-col h-full">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-blue-600"></i>
                        <h3 class="text-sm font-semibold text-blue-900">Class Schedule</h3>
                    </div>
                    <div v-if="course.schedule?.items && course.schedule.items.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div v-for="(item, index) in course.schedule.items" :key="index"
                            class="bg-white rounded-lg p-3 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                    {{ item.day?.substring(0, 3) || 'N/A' }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm">{{ item.day }}</p>
                                    <p v-if="item.startTime && item.endTime" class="text-xs text-gray-600 mt-0.5">
                                        <i class="fa-solid fa-clock text-blue-500 mr-1"></i>
                                        {{ formatTime(item.startTime) }} - {{ formatTime(item.endTime) }}
                                    </p>
                                    <p v-else class="text-xs text-gray-400 italic">No time set</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="course.schedule?.day || course.schedule?.time" class="space-y-2">
                        <div v-if="course.schedule?.day"
                            class="bg-white rounded-lg p-3 shadow-sm border border-blue-200">
                            <p class="text-xs text-gray-600 mb-1">Days</p>
                            <p class="font-semibold text-gray-900">{{ course.schedule.day }}</p>
                        </div>
                        <div v-if="course.schedule?.time"
                            class="bg-white rounded-lg p-3 shadow-sm border border-blue-200">
                            <p class="text-xs text-gray-600 mb-1">Time</p>
                            <p class="font-semibold text-gray-900">{{ course.schedule.time }}</p>
                        </div>
                    </div>
                    <div v-else class="bg-white rounded-lg p-4 text-center border border-blue-200">
                        <i class="fa-solid fa-calendar-xmark text-gray-300 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-500">Schedule not set</p>
                    </div>
                    <div v-if="course.schedule?.location"
                        class="bg-white rounded-lg p-3 shadow-sm border border-blue-200">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-blue-500"></i>
                            <div>
                                <p class="text-xs text-gray-600">Location</p>
                                <p class="font-medium text-gray-900 text-sm">{{ course.schedule.location }}</p>
                            </div>
                        </div>
                    </div>
                    <div v-if="course.virtualLink" class="bg-white rounded-lg p-3 shadow-sm border border-blue-200">
                        <a :href="course.virtualLink" target="_blank" rel="noopener noreferrer"
                            class="flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline group">
                            <i class="fa-solid fa-video text-blue-500 group-hover:text-blue-700"></i>
                            <div>
                                <p class="text-xs text-gray-600">Virtual Link</p>
                                <p class="font-medium text-sm">Join Virtual Class <i
                                        class="fa-solid fa-external-link text-[10px] ml-1"></i></p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Instructors -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-white rounded-lg p-5 space-y-4 border border-blue-200 flex flex-col h-full">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard-user text-blue-600"></i>
                        <h3 class="text-sm font-semibold text-blue-900">Assigned Staff</h3>
                    </div>
                    <div v-if="!instructors.length" class="bg-white rounded-lg p-4 text-center border border-blue-200">
                        <i class="fa-solid fa-user-slash text-gray-300 text-xl mb-2"></i>
                        <p class="text-sm text-gray-500">No instructors assigned yet.</p>
                    </div>
                    <div v-else class="space-y-3">
                        <div v-for="inst in instructors" :key="inst.email || inst.name"
                            class="bg-white rounded-lg p-3 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-3">
                                <div v-if="inst.avatar || inst.photoUrl" class="flex-shrink-0">
                                    <img :src="inst.avatar || inst.photoUrl" alt="Avatar"
                                        class="w-12 h-12 rounded-full object-cover border-2 border-blue-200" />
                                </div>
                                <div v-else
                                    class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 border-2 border-blue-200 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ inst.name }}
                                        <span class="text-xs text-gray-500 font-normal">({{ inst.role }})</span>
                                    </p>
                                    <div class="mt-1 space-y-1">
                                        <div v-if="inst.email" class="flex items-center gap-2">
                                            <a :href="`mailto:${inst.email}`"
                                                class="text-xs text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                                <i class="fa-solid fa-envelope text-[10px]"></i>
                                                {{ inst.email }}
                                            </a>
                                        </div>
                                        <div v-if="inst.phone" class="text-xs text-gray-600">
                                            <i class="fa-solid fa-phone text-[10px] mr-1"></i>
                                            {{ inst.phone }}
                                        </div>
                                        <div v-if="inst.codeNumber" class="text-xs text-gray-500">
                                            <span class="font-medium">Code:</span> {{ inst.codeNumber }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Description -->
                <div v-if="course.sectionDescription"
                    class="bg-gradient-to-br from-blue-50 to-white rounded-lg p-5 space-y-3 border border-blue-200 flex flex-col h-full">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-file-lines text-blue-600"></i>
                        <h3 class="text-sm font-semibold text-blue-900">Section Description</h3>
                    </div>
                    <div
                        class="bg-white rounded-lg p-4 text-sm text-gray-700 whitespace-pre-wrap border border-blue-200">
                        {{ course.sectionDescription }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Description - Full Width -->
        <div class="mt-6 bg-gradient-to-br from-blue-50 to-white rounded-lg p-5 space-y-3 border border-blue-200">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-book text-blue-600"></i>
                <h3 class="text-sm font-semibold text-blue-900">Course Description</h3>
            </div>
            <div v-if="course.description"
                class="bg-white rounded-lg p-4 text-sm text-gray-700 whitespace-pre-wrap border border-blue-200">
                {{ course.description }}
            </div>
            <div v-else class="bg-white rounded-lg p-4 text-center border border-blue-200">
                <i class="fa-solid fa-file-circle-question text-gray-300 text-xl mb-2"></i>
                <p class="text-sm text-gray-500 italic">No description available.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    course: {
        type: Object,
        required: true,
        default: () => ({
            title: '',
            code: '',
            section: '',
            category: '',
            subjectTitle: '',
            subjectCode: '',
            gradeLevelTitle: '',
            credits: 0,
            description: '',
            sectionDescription: '',
            image: '',
            maxEnrollment: null,
            enrollmentSize: null,
            currentEnrollment: null,
            schoolYearName: '',
            virtualLink: '',
            schedule: {
                time: '',
                day: '',
                location: '',
                items: [],
            },
            schoolTerms: [],
        }),
    },
    instructors: {
        type: Array,
        default: () => [],
    },
})

const enrollmentSize = computed(() => {
    return props.course.enrollmentSize || props.course.maxEnrollment || null
})

function formatTime(timeString) {
    if (!timeString) return ''
    // Handle both "12:00:00" and "12:00" formats
    const time = timeString.split(':').slice(0, 2).join(':')
    // Convert to 12-hour format if needed
    const [hours, minutes] = time.split(':')
    const hour = parseInt(hours, 10)
    const ampm = hour >= 12 ? 'PM' : 'AM'
    const hour12 = hour % 12 || 12
    return `${hour12}:${minutes} ${ampm}`
}
</script>

<style scoped>
/* Add any component-specific styles if needed */
</style>
