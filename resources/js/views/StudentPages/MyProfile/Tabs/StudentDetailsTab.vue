<template>
    <!-- Personal Info -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong> Personal Info </strong> </p>
        <hr class="mx-4">

        <div class="grid grid-cols-5">
            <!-- Profile Pic -->
            <div class="col-span-1 mt-2">
                <div class="relative w-28 h-28 mx-auto">
                    <div class="w-28 h-28 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-full flex items-center justify-center shadow-lg border border-gray-200">
                        <i class="fas fa-user text-white text-4xl"></i>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-gray-600 text-sm">ID: {{ student.idNumber }}</p>
                </div>
            </div>

            <div class="col-span-4 grid grid-cols-3 text-left">
                <!-- Full name -->
                <div class="col-span-1 flex items-start gap-6 mt-3 ml-4">
                    <span class="text-lg">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold">Full Name</p>
                        <span class="text-gray-600 text-sm">
                            {{ student.firstName }} {{ student.middleName }} {{ student.lastName }}
                        </span>
                    </div>
                </div>

                <!-- Preferred Name -->
                <div class="col-span-1 flex items-start gap-6 mt-3 ml-4">
                    <span class="text-lg">
                        <i class="fa-solid fa-file-signature"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold">Preferred Name</p>
                        <span class="text-gray-600 text-sm">{{ student.preferedName ?? '-' }}</span>
                    </div>
                </div>

                <!-- Date of Birth -->
                <div class="col-span-1 flex items-start gap-6 mt-3 ml-4">
                    <span class="text-lg">
                        <i class="fa-solid fa-calendar-days"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold">Date of Birth</p>
                        <span class="text-gray-600 text-sm">{{ student.birthDate?.formatted || '-' }}</span>
                    </div>
                </div>

                <!-- Grade Level -->
                <div class="col-span-1 flex items-start gap-4 mt-3 ml-4">
                    <span class="text-lg">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold">
                            Grade Level
                        </p>
                        <span class="text-gray-600 text-sm">{{ currentGradeLevelDisplay }}</span>
                    </div>
                </div>

                <!-- Username -->
                <div class="col-span-1 flex items-start gap-6 mt-3 ml-4">
                    <span class="text-lg">
                        <i class="fa-solid fa-address-card"></i>
                    </span>

                    <div>
                        <p class="text-sm font-bold">Username</p>
                        <span class="text-gray-600 text-sm">{{ student.username }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Term -->
    <div class="bg-white rounded-lg border-r pr-6 py-3 text-center mt-2">
        <div class="flex items-end justify-between mb-2">
            <p class="text-sm font-semibold mx-4">Academic Terms</p>
        </div>
        <hr class="mx-4">

        <div class="grid grid-cols-10">
            <div v-for="(slot, index) in academicTermSlots" :key="index"
                class="col-span-2 grid grid-cols-4 m-2 py-2 rounded-lg shadow">
                <div class="col-span-4 text-left ml-2">
                    <div class="flex items-center justify-between text-sm font-bold">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>{{ slot.title }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-4 text-left ml-2">
                    <template v-if="slot.data">
                        <span class="text-gray-600 text-sm block">
                            Start: {{ slot.data.startDate?.formatted || '-' }}
                        </span>
                        <span class="text-gray-600 text-sm block">
                            End: {{ slot.data.endDate?.formatted || '-' }}
                        </span>
                    </template>
                    <template v-else>
                        <span class="text-gray-400 italic text-sm">No data</span>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong> Contact Info </strong> </p>
        <hr class="mx-4">

        <div class="col-span-4 grid grid-cols-8 text-left pl-5">
            <!-- Phone Number -->
            <div class="col-span-1 mt-2">
                <p class="text-sm"><strong>Cell Phone</strong></p>
                <span class="text-gray-600 text-sm">{{ student.phoneNumber ?? '-' }}</span>
            </div>
            <!-- Home Number -->
            <div class="col-span-1 mt-2">
                <p class="text-sm"><strong>Home Phone</strong></p>
                <span class="text-gray-600 text-sm">{{ student.homePhone ?? '-' }}</span>
            </div>
            <!-- Email -->
            <div class="col-span-2 mt-3">
                <p class="text-sm"><strong>Personal Email</strong></p>
                <span class="text-gray-600 text-sm">{{ student.email ?? '-' }}</span>
            </div>
            <!--School Email -->
            <div class="col-span-2 mt-3">
                <p class="text-sm"><strong>School Email</strong></p>
                <span class="text-gray-600 text-sm">{{ student.schoolEmail ?? '-' }}</span>
            </div>
            <!-- Current Address-->
            <div class="col-span-2 mt-2">
                <p class="text-sm"><strong>Current Address</strong></p>
                <template v-if="student.currentAddress && student.currentAddress.present">
                    <span class="text-gray-600 text-sm">
                        {{ student.currentAddress.present.addressLine1 }},
                        <template v-if="student.currentAddress.present.addressLine2">
                            {{ student.currentAddress.present.addressLine2 }},
                        </template>
                        <br>
                        {{ student.currentAddress.present.city }},
                        {{ student.currentAddress.present.state }}
                        {{ student.currentAddress.present.zipcode }},
                        {{ student.currentAddress.present.country }}
                    </span>
                </template>
                <template v-else>
                    <span class="text-gray-400 text-sm italic">No current address found</span>
                </template>
            </div>
        </div>
    </div>

    <!-- Sports, Clubs, and Activities  -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong> Sports, Clubs, and Activities </strong>
        </p>
        <hr class="mx-4 mt-3">

        <div v-if="student.studentSchoolActivities && student.studentSchoolActivities.length > 0">
            <div v-for="activity in student.studentSchoolActivities" :key="activity.uuid || activity.id" class="bg-gray-200 rounded-lg mx-4 my-2 p-2">
                <div class="flex items-center justify-between mx-2 mb-2">
                    <strong>
                        Activity:
                        <a :href="router.resolve({ name: 'SchoolActivityShow', params: { uuid: activity.activityUuid } }).href"
                            target="_blank" rel="noopener noreferrer"
                            class="text-blue-600 hover:text-blue-800 hover:underline cursor-pointer"
                            title="View activity details (opens in new tab)">
                            {{ activity.activityTitle }}
                            <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-xs opacity-70"></i>
                        </a>
                    </strong>
                </div>
                <hr class="mx-4">

                <div class="grid grid-cols-12 text-left px-2">
                    <div class="col-span-3 mt-2">
                        <p class="text-sm"><strong>Type</strong></p>
                        <span class="text-gray-600 text-sm">{{ activity.activityType }}</span>
                    </div>
                    <div class="col-span-3 mt-2">
                        <p class="text-sm"><strong>Start Date</strong></p>
                        <span class="text-gray-600 text-sm">{{ activity.activityStartDate?.formatted }}</span>
                    </div>
                    <div class="col-span-3 mt-2">
                        <p class="text-sm"><strong>End Date</strong></p>
                        <span class="text-gray-600 text-sm">{{ activity.activityEndDate?.formatted }}</span>
                    </div>
                    <div class="col-span-3 mt-2">
                        <p class="text-sm"><strong>Status</strong></p>
                        <span class="text-gray-600 text-sm">Active</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Sports, Clubs, and Activities</p>
            <p class="text-xs text-gray-500 mt-1">Sports, Clubs, and Activities information is not available at this time.</p>
        </div>
    </div>

    <!-- Documents -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong>Documents</strong>
        </p>
        <hr class="mx-4 my-3">

        <!-- List -->
        <div class="grid grid-cols-12">
            <template v-if="student.contact && student.contact.documents && student.contact.documents.length > 0">
                <div v-for="document in student.contact.documents" :key="document.uuid"
                    class="grid grid-cols-6 col-span-6 text-left bg-gray-100 border border-gray-300 rounded-lg p-3 mx-4 mt-2">
                    <span class="col-span-1 mt-2 ml-4">
                        <i class="fa-solid fa-file-pdf"></i>
                    </span>
                    <div class="col-span-4 mt-2">
                        <p class="text-sm">{{ document.title }}</p>
                        <span v-if="document.type" class="text-xs text-gray-600">
                            {{ document.type.title || document.type.name || document.type.label }}
                        </span>
                        <div class="text-xs text-gray-500" v-if="document.start_date || document.end_date">
                            {{ document.start_date || '—' }} → {{ document.end_date || '—' }}
                        </div>
                    </div>
                    <div class="col-span-1 mt-2 text-right">
                        <button class="mx-1" title="Download" @click="downloadDocument(document)"
                            :disabled="!hasDownloadableMedia(document)">
                            <i class="fa-solid fa-download"></i>
                        </button>
                    </div>
                </div>
            </template>

            <div v-else class="col-span-12 mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
                <p class="text-sm text-gray-600 font-medium">No Documents</p>
                <p class="text-xs text-gray-500 mt-1">Documents information is not available at this time.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, inject, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useToast } from "vue-toastification"
import { useStore } from 'vuex'

const emitter = inject("emitter")
const store = useStore()
const router = useRouter()
const route = useRoute()

const studentUuid = computed(() => props.student?.uuid || route.params?.uuid || "")

const props = defineProps({
    student: Object,
    sportAndActivities: Object,
    schoolYears: Array,
    gradeLevels: Array,
})

const toast = useToast()

const avatarUrl = computed(() =>
    props.student?.contact?.photoUrl ||
    props.student?.contact?.photo_url ||
    props.student?.contact?.photo ||
    null
)

const hasActualPhoto = computed(() => {
    return !!(props.student?.contact?.photoUrl ||
        props.student?.contact?.photo_url ||
        props.student?.contact?.photo)
})

const defaultTermTitles = ['Term I', 'Term II', 'Term III', 'Term IV', 'Term V']
const academicTermSlots = computed(() => {
    const terms = props.student?.academicTerms ?? []
    const padded = []
    for (let i = 0; i < 5; i++) {
        const term = terms[i] ?? null
        padded.push({ index: i, title: defaultTermTitles[i], data: term })
    }
    return padded
})

/** Current grade level only - from status === 'current' */
const currentGradeLevel = computed(() => {
    const histories = props.student?.academicHistories ?? props.student?.academic_histories ?? []
    const current = histories.find(h => h?.is_current === true)
    return current?.gradeLevel ?? current?.grade_level ?? null
})
/** Display name for current grade level */
const currentGradeLevelDisplay = computed(() => {
    const gl = currentGradeLevel.value
    if (!gl) return '-'
    return gl?.name ?? gl?.shortName ?? gl?.displayName ?? gl?.code ?? '-'
})

function hasDownloadableMedia(document) {
    return !!(document?.media?.length && document.media[0]?.status) || !!document?.mediaToken
}

async function downloadDocument(document) {
    if (!studentUuid.value) return
    if (!hasDownloadableMedia(document)) { toast.error("No file available for download"); return }
    const mediaUuid = document.media?.[0]?.uuid
    await store.dispatch("student/studentProfile/downloadDocument", {
        studentUuid: studentUuid.value,
        documentUuid: document.uuid,
        mediaUuid,
    })
}
</script>
