<template>
    <PageHeader :title="studentName" :navs="[]">
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <!-- Student Details Full Width -->
        <div class="p-3 shadow-md">
            <!-- Tabs Section -->
            <div class="rounded-lg">
                <!-- Tab Menus -->
                <div class="border-b-2 border-gray-200">
                    <!-- 1st Row -->
                    <div class="w-full flex gap-1 pl-0">
                        <div v-for="(tab, idx) in tabs.slice(0, tabs.indexOf('Career Planning') + 1)"
                            :key="`r1-${idx}`" @click="activeTab = tab" :class="[
                                'flex-1 min-w-max whitespace-nowrap cursor-pointer px-3 pt-2.5 pb-1.5 text-sm text-center transition-all duration-150 flex items-center justify-center gap-2',
                                activeTab === tab
                                    ? 'bg-blue-200 shadow-inner border border-blue-400 border-b-0 rounded-t-lg text-blue-900 font-medium'
                                    : 'bg-white border border-gray-300 border-b-0 rounded-t-lg -mb-px z-20 shadow-lg relative -top-1 text-gray-700 hover:text-blue-600 hover:bg-gray-50'
                            ]">
                            <i :class="[
                                getTabIcon(tab),
                                activeTab === tab ? 'text-blue-700' : 'text-gray-500',
                                'text-sm'
                            ]"></i>
                            <span class="flex items-center gap-1">
                                <span>{{ tab }}</span>
                                <span
                                    v-if="tab === 'Progress Report' && hasUnreadProgressReport"
                                    class="inline-flex w-2 h-2 rounded-full bg-red-600"
                                ></span>
                                <span
                                    v-if="tab === 'Report Card' && hasUnreadReportCard"
                                    class="inline-flex w-2 h-2 rounded-full bg-red-600"
                                ></span>
                            </span>
                        </div>
                    </div>

                    <!-- 2nd Row -->
                    <div class="w-full flex gap-1 pl-0">
                        <div v-for="(tab, idx) in tabs.slice(tabs.indexOf('Career Planning') + 1)"
                            :key="`r2-${idx}`" @click="activeTab = tab" :class="[
                                'flex-1 min-w-max whitespace-nowrap cursor-pointer px-3 pt-2.5 pb-1.5 text-sm text-center transition-all duration-150 flex items-center justify-center gap-2',
                                activeTab === tab
                                    ? 'bg-blue-200 shadow-inner border border-blue-400 border-b-0 rounded-t-lg text-blue-900 font-medium'
                                    : 'bg-white border border-gray-300 border-b-0 rounded-t-lg -mb-px z-20 shadow-lg relative -top-1 text-gray-700 hover:text-blue-600 hover:bg-gray-50'
                            ]">
                            <i :class="[
                                getTabIcon(tab),
                                activeTab === tab ? 'text-blue-700' : 'text-gray-500',
                                'text-sm'
                            ]"></i>
                            <span class="flex items-center gap-1">
                                <span>{{ tab }}</span>
                                <span
                                    v-if="tab === 'Progress Report' && hasUnreadProgressReport"
                                    class="inline-flex w-2 h-2 rounded-full bg-red-600"
                                ></span>
                                <span
                                    v-if="tab === 'Report Card' && hasUnreadReportCard"
                                    class="inline-flex w-2 h-2 rounded-full bg-red-600"
                                ></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tab Content -->
                <div>
                    <!-- Student Detail -->
                    <div v-if="activeTab === 'Student Details'">
                        <StudentDetailsTab :student="student" :school-years="schoolYears"
                            :grade-levels="gradeLevels" :sportAndActivities="sportAndActivities" />
                    </div>

                    <!-- Demographic Info -->
                    <div v-if="activeTab === 'Demographics'">
                        <DemographicsTab :student="student" />
                    </div>

                    <!-- Student Notes -->
                    <div v-if="activeTab === 'Notes'">
                        <StudentNotes :student="student" />
                    </div>

                    <!-- Career Planning -->
                    <div v-if="activeTab === 'Career Planning'">
                        <CareerPlanningTab :student="student" :career-pathways="careerPathways"
                            :postsecondary-choices="postsecondaryChoices" 
                            :grade-levels="gradeLevels"
                            :school-years="schoolYears" />
                    </div>

                    <!-- Academic History -->
                    <div v-if="activeTab === 'Academic History'">
                        <AcademicHistoryTab :student="student" :school-years="schoolYears"
                            :grade-levels="gradeLevels" :academic-courses="academicCourses"
                            :grading-systems="gradingSystems" />
                    </div>

                    <!-- Gamification -->
                    <div v-if="activeTab === 'Gamification'">
                        <GamificationTab :student="student" />
                    </div>

                    <!-- Schedule -->
                    <div v-if="activeTab === 'Schedule'">
                        <ScheduleTab :student="student" />
                    </div>

                    <!-- Progress Report (read-only, published only) -->
                    <div v-if="activeTab === 'Progress Report'">
                        <ProgressReportTab
                            :student="student"
                            :reports="progressBlocks"
                            :school-years="schoolYears"
                            :grade-levels="gradeLevels"
                            :academic-courses="academicCourses"
                            :read-only="true"
                        />
                    </div>

                    <div v-if="activeTab === 'Report Card'">
                        <ReportCardTab :student="student" :reports="reportBlocks"
                            :school-years="schoolYears" :grade-levels="gradeLevels"
                            :academic-courses="academicCourses" :grading-systems="gradingSystems" />
                    </div>

                    <!-- Attendance -->
                    <div v-if="activeTab === 'Attendance'">
                        <AttendanceTab :student="student" />
                    </div>

                    <!-- Health Record -->
                    <div v-if="activeTab === 'Medical/Health'">
                        <HealthRecordTab :student="student" />
                    </div>

                    <!-- Behaviors/Incidents  -->
                    <div v-if="activeTab === 'Behavior/Incidents'">
                        <IncidentTab :student="student" />
                    </div>

                    <!-- Financial  -->
                    <div v-if="activeTab === 'Financial'">
                        <FinancialTab :student="student" />
                    </div>

                    <!-- Transcript -->
                    <div v-if="activeTab === 'Transcript'">
                        <TranscriptTab :student="student" :reports="transcriptBlocks"
                            :grade-levels="gradeLevels" :academic-courses="academicCourses"
                            :school-years="schoolYears" :grading-systems="gradingSystems" />
                    </div>
                </div>
            </div>
        </div>
    </ParentTransition>
</template>

<script setup>
import { reactive, ref, inject, computed, onMounted, onBeforeUnmount, watch } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"

// Import student-side tab components
import StudentDetailsTab from './Tabs/StudentDetailsTab.vue'
import DemographicsTab from './Tabs/DemographicsTab.vue'
import StudentNotes from './Tabs/StudentNotes.vue'
import CareerPlanningTab from './Tabs/CareerPlanningTab.vue'
import AcademicHistoryTab from './Tabs/AcademicHistoryTab.vue'
import GamificationTab from './Tabs/GamificationTab.vue'
import ScheduleTab from './Tabs/ScheduleTab.vue'
import ProgressReportTab from './Tabs/ProgressReportTab.vue'
import ReportCardTab from './Tabs/ReportCardTab.vue'
import AttendanceTab from './Tabs/AttendanceTab.vue'
import HealthRecordTab from './Tabs/HealthRecordTab.vue'
import IncidentTab from './Tabs/IncidentTab.vue'
import FinancialTab from './Tabs/FinancialTab.vue'
import TranscriptTab from './Tabs/TranscriptTab.vue'

const store = useStore()
const route = useRoute()
const router = useRouter()

const emitter = inject("emitter")
const $trans = inject("$trans")

const isLoading = ref(false)

const activeTab = ref("Student Details")
const tabs = ["Financial", "Progress Report", "Report Card", "Transcript", "Academic History", "Career Planning", "Student Details", "Demographics", "Behavior/Incidents", "Gamification", "Attendance", "Schedule", "Medical/Health"]

const student = reactive({})
const gradeLevels = ref([])
const academicCourses = ref([])
const schoolYears = ref([])
const postsecondaryChoices = ref([])
const careerPathways = ref([])
const sportAndActivities = ref([])
const gradingSystems = ref([])

// Report Card, Progress Report & Transcript Data
const reportBlocks = ref([])
const progressBlocks = ref([])
const transcriptBlocks = ref([])
const loadingReportCard = ref(false)
const loadingTranscript = ref(false)

// Unread indicators for tabs
const hasUnreadReportCard = ref(false)
const hasUnreadProgressReport = ref(false)

const profileImage = computed(() =>
    student?.contact?.photoUrl ||
    student?.contact?.photo_url ||
    student?.contact?.photo ||
    null
)

const hasActualPhoto = computed(() => {
    return !!(student?.contact?.photoUrl ||
        student?.contact?.photo_url ||
        student?.contact?.photo)
})

const studentName = computed(() => {
    if (!student.firstName && !student.lastName) {
        return 'Your Profile'
    }
    const nameParts = [student.firstName, student.middleName, student.lastName].filter(Boolean)
    return nameParts.join(' ') || 'Your Profile'
})

async function loadReportCard() {
    loadingReportCard.value = true
    try {
        const response = await store.dispatch('student/studentProfile/getReportCard', {
            uuid: student.uuid,
            publishedOnly: true // Student side shows only published report cards
        })
        reportBlocks.value = response?.reports || response?.data?.reports || response || []
        hasUnreadReportCard.value = Array.isArray(reportBlocks.value)
            ? reportBlocks.value.some((block) => !block?.meta?.seenByStudent)
            : false
    } catch (error) {
        console.error('Failed to load report card:', error)
        reportBlocks.value = []
    } finally {
        loadingReportCard.value = false
    }
}

async function loadProgressReport() {
    try {
        const response = await store.dispatch('student/studentProfile/getProgressReport', {
            uuid: student.uuid,
            publishedOnly: true, // Student side shows only published progress reports
        })
        progressBlocks.value = response?.reports || response?.data?.reports || []
        hasUnreadProgressReport.value = Array.isArray(progressBlocks.value)
            ? progressBlocks.value.some((report) => !report?.meta?.seenByStudent)
            : false
    } catch (error) {
        console.error('Failed to load progress report:', error)
        progressBlocks.value = []
    }
}

async function loadTranscript() {
    loadingTranscript.value = true
    try {
        const response = await store.dispatch('student/studentProfile/getTranscript', {
            uuid: student.uuid,
            highSchoolOnly: true
        })
        transcriptBlocks.value = response?.blocks || response?.data?.blocks || response || []
    } catch (error) {
        console.error('Failed to load transcript:', error)
        transcriptBlocks.value = []
    } finally {
        loadingTranscript.value = false
    }
}

const getStudent = async () => {
    isLoading.value = true
    await store
        .dispatch("student/studentProfile/myProfile")
        .then((response) => {
            Object.assign(student, response['student'])
            gradeLevels.value = response['gradeLevels'] || []
            schoolYears.value = response['schoolYears'] || []
            postsecondaryChoices.value = response['postsecondaryChoices'] || []
            careerPathways.value = response['careerPathways'] || []
            sportAndActivities.value = response['sportAndActivities'] || []
            academicCourses.value = response['academicCourses'] || []
            gradingSystems.value = response['gradingSystems'] || []

            // Load report card and transcript when student data loads
            if (student.uuid) {
                loadReportCard()
                loadProgressReport()
                loadTranscript()
            }
            isLoading.value = false
        })
        .catch((e) => {
            isLoading.value = false
            router.push({ name: "StudentDashboard" })
        })
}

// Listen for refresh events to reload report card and transcript
emitter.on('refreshItem', () => {
    if (student.uuid) {
        loadReportCard()
        loadProgressReport()
        loadTranscript()
    }
})

onMounted(async () => {
    emitter.on("studentUpdate", () => {
        getStudent()
    })

    syncActiveTabFromRoute()
    await getStudent()
})

onBeforeUnmount(() => {
    emitter.all.delete("studentUpdate")
    emitter.all.delete("refreshItem")
})

watch(
    () => route.query.tab,
    () => {
        syncActiveTabFromRoute()
    }
)

function syncActiveTabFromRoute() {
    const requestedTab = typeof route.query.tab === "string" ? route.query.tab : ""
    if (requestedTab && tabs.includes(requestedTab)) {
        activeTab.value = requestedTab
    }
}

// When student opens Progress Report / Report Card tab, mark corresponding items as read
watch(
    () => activeTab.value,
    async (newTab) => {
        if (!student.uuid) return

        if (newTab === "Progress Report" && hasUnreadProgressReport.value) {
            try {
                await store.dispatch("student/studentProfile/markProgressReportSeen", {
                    uuid: student.uuid,
                })
                hasUnreadProgressReport.value = false
                // Refresh nav badge counters
                store.dispatch("badges/fetch")
            } catch (e) {
                console.error("Failed to mark progress reports as read:", e)
            }
        }

        if (newTab === "Report Card" && hasUnreadReportCard.value) {
            try {
                await store.dispatch("student/studentProfile/markReportCardSeen", {
                    uuid: student.uuid,
                })
                hasUnreadReportCard.value = false
                // Refresh nav badge counters
                store.dispatch("badges/fetch")
            } catch (e) {
                console.error("Failed to mark report cards as read:", e)
            }
        }
    }
)

// Get icon for each tab
function getTabIcon(tabName) {
    const iconMap = {
        'Financial': 'fa-solid fa-dollar-sign',
        'Notes': 'fa-solid fa-note-sticky',
        'Progress Report': 'fa-solid fa-chart-line',
        'Report Card': 'fa-solid fa-file-lines',
        'Transcript': 'fa-solid fa-graduation-cap',
        'Academic History': 'fa-solid fa-book',
        'Career Planning': 'fa-solid fa-briefcase',
        'Student Details': 'fa-solid fa-id-card',
        'Behavior/Incidents': 'fa-solid fa-triangle-exclamation',
        'Gamification': 'fa-solid fa-trophy',
        'Attendance': 'fa-solid fa-calendar-check',
        'Schedule': 'fa-solid fa-calendar-days',
        'Demographics': 'fa-solid fa-users',
        'Medical/Health': 'fa-solid fa-heart-pulse',
    }
    return iconMap[tabName] || 'fa-solid fa-circle'
}
</script>
