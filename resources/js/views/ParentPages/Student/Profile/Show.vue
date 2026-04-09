<template>

    <PageHeader :title="headerTitle" :navs="[
        {
            label: $trans('student.children'),
            path: 'MyChildrenProfileList',
        },
    ]">
        <PageHeaderAction class="mr-3" name="MyChildrenProfile" :title="$trans('student.children')"
            :actions="['list']">

        </PageHeaderAction>
    </PageHeader>

    <ParentTransition appear :visibility="showFilter">
        <FilterForm @refresh="emitter.emit('listItems')" :pre-requisites="preRequisites" @hide="showFilter = false" />
    </ParentTransition>


    <ParentTransition appear :visibility="true">
        <ShowItem :key="route.params.uuid" :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'MyChildrenProfileList' })" @refresh="emitter.emit('listItems')">

            <!-- Student Details Grid -->
            <div class="grid grid-cols-11 gap-2 p-3 shadow-md">
                <!-- Left Section -->
                <div class="col-span-2">
                    <div class="bg-white rounded-lg border pt-1 pb-2">
                        <!-- Header -->
                        <p class="text-sm text-left mx-4 mt-1">
                            <strong>Students</strong>
                            <span class="text-gray-500 ml-1">({{ filteredSortedStudents.length }})</span>
                        </p>
                        <hr class="mx-4 mb-2">

                        <!-- Search -->
                        <div class="px-4 pb-2">
                            <input v-model.trim="studentSearch" type="text" placeholder="Search by name / ID..."
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                        </div>

                        <!-- Scrollable list -->
                        <div class="px-2">
                            <div class="max-h-[70vh] overflow-y-auto pr-1">
                                <div v-for="stu in filteredSortedStudents" :key="stu.uuid"
                                    @click="selectStudent(stu.uuid)" :class="[
                                        'cursor-pointer px-2 py-2 text-sm sm:w-auto text-left mx-1 rounded',
                                        stu.uuid === route.params.uuid
                                            ? 'border border-blue-400 bg-blue-50 text-blue-900 font-semibold'
                                            : 'border border-transparent hover:border-gray-200'
                                    ]">
                                    <div class="flex flex-col leading-tight">
                                        <span>{{ (stu.firstName || '') + ' ' + (stu.lastName || '') }}</span>
                                        <span class="text-xs text-gray-500" v-if="stu.studentId">ID:
                                            {{ stu.studentId }}</span>
                                    </div>
                                </div>

                                <div v-if="!filteredSortedStudents.length" class="text-xs text-gray-400 px-2 py-4">
                                    No students match your search.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Right Section with Tabs -->
                <div class="col-span-9 rounded-lg">

                    <!-- Tab Menus -->
                    <div class="border-b-2 border-gray-200">
                        <!-- 1st Row (More Indented) -->
                        <div class="w-full flex gap-1 pl-12">
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
                                <span>{{ tab }}</span>
                            </div>
                        </div>

                        <!-- 2nd Row (Left Aligned) -->
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
                                <span>{{ tab }}</span>
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

                        <!-- Career Planning -->
                        <div v-if="activeTab === 'Career Planning'">
                            <CareerPlanningTab :student="student" :career-pathways="careerPathways"
                                :postsecondary-choices="postsecondaryChoices" />
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
                        <div v-if="activeTab === 'Cafeteria'">
                            <CafeteriaTab :student="student" />
                        </div>

                        <!-- Transportation  -->
                        <div v-if="activeTab === 'Transportation'">
                            <TransportationTab :student="student" />
                        </div>

                        <!-- Library  -->
                        <div v-if="activeTab === 'Library'">
                            <LibraryTab :student="student" />
                        </div>

                        <!-- Behaviors/Incidents  -->
                        <div v-if="activeTab === 'Behavior/Incidents'">
                            <IncidentTab :student="student" />
                        </div>

                        <!-- Financial  -->
                        <div v-if="activeTab === 'Financial'">
                            <FinancialTab :student="student" />
                        </div>
                    </div>

                    <!-- Transcript -->
                    <div v-if="activeTab === 'Transcript'">
                        <TranscriptTab :student="student" :reports="transcriptBlocks"
                            :grade-levels="gradeLevels" :academic-courses="academicCourses"
                            :school-years="schoolYears" :grading-systems="gradingSystems" />
                    </div>
                </div>


            </div>

        </ShowItem>
    </ParentTransition>

    <!-- Filter Modal removed -->

</template>

<script>
export default {
    name: "ChildrenProfileShow",
    data() {
        return {
            openIndex: 0, // Default to open the first test
            activeTab: "Student Details",
            showDropdown: false,
            // This is original, Financial, Gradebook, Library, Transportation and Cafeteria is hidden
            // tabs: ["Demographics", "Family", "Medical/Health", "Cafeteria", "Transportation", "Library", "Career Planning", "Academic History", "Student Details", "Behavior/Incidents", "Attendance", "Schedule", "Gamification", "Gradebook", "Report Card", "Transcript"],
            tabs: ["Financial", "Progress Report", "Report Card", "Transcript", "Academic History", "Career Planning", "Student Details", "Demographics", "Behavior/Incidents", "Gamification", "Attendance", "Schedule", "Medical/Health"],
        };
    },
    mounted() {
        this.syncActiveTabFromRoute()
    },
    watch: {
        "$route.query.tab"() {
            this.syncActiveTabFromRoute()
        },
        async activeTab(newTab) {
            const uuid = this.$route.params.uuid
            if (!uuid) return

            if (newTab === "Progress Report") {
                try {
                    await this.$store.dispatch("student/studentProfile/markProgressReportSeen", {
                        uuid,
                    })
                    // Refresh nav badge counters
                    this.$store.dispatch("badges/fetch")
                } catch (e) {
                    console.error("Failed to mark progress reports as read for child:", e)
                }
            }

            if (newTab === "Report Card") {
                try {
                    await this.$store.dispatch("student/studentProfile/markReportCardSeen", {
                        uuid,
                    })
                    // Refresh nav badge counters
                    this.$store.dispatch("badges/fetch")
                } catch (e) {
                    console.error("Failed to mark report cards as read for child:", e)
                }
            }
        },
    },
    methods: {
        syncActiveTabFromRoute() {
            const requestedTab = typeof this.$route.query.tab === "string" ? this.$route.query.tab : ""
            if (requestedTab && this.tabs.includes(requestedTab)) {
                this.activeTab = requestedTab
            }
        },
        toggleAccordion(index) {
            this.openIndex = this.openIndex === index ? null : index;
        }
    }
}
</script>

<script setup>
import { reactive, ref, inject, computed, watch, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useToast } from "vue-toastification"

import { useStore } from "vuex"
import { perform } from "@core/helpers/action"
// import FilterForm from "./Filter.vue"
// import BaseModal from '@core/components/Ui/BaseModal.vue'
// import BaseSelect from '@core/components/Forms/BaseSelect.vue'
// import BaseButton from '@core/components/Forms/BaseButton.vue'
// import DatePicker from '@core/components/Forms/DatePicker.vue'

import StudentDetailsTab from './Tabs/StudentDetailsTab.vue'
import DemographicsTab from './Tabs/DemographicsTab.vue'
import FamilyTab from './Tabs/FamilyTab.vue'
import HealthRecordTab from './Tabs/HealthRecordTab.vue'
import IncidentTab from './Tabs/IncidentTab.vue'
import CareerPlanningTab from './Tabs/CareerPlanningTab.vue'
import AcademicHistoryTab from './Tabs/AcademicHistoryTab.vue'
import FinancialTab from './Tabs/FinancialTab.vue'
import AttendanceTab from './Tabs/AttendanceTab.vue'
import ScheduleTab from './Tabs/ScheduleTab.vue'
import ProgressReportTab from './Tabs/ProgressReportTab.vue'
import ReportCardTab from './Tabs/ReportCardTab.vue'
import CafeteriaTab from './Tabs/CafeteriaTab.vue'
import LibraryTab from './Tabs/LibraryTab.vue'
import TransportationTab from './Tabs/TransportationTab.vue'
import GamificationTab from './Tabs/GamificationTab.vue'
import TranscriptTab from './Tabs/TranscriptTab.vue'
import StudentNotes from './Tabs/StudentNotes.vue'

const showFilter = ref(false)
// const showFilterModal = ref(false) // Removed - filter functionality removed

const store = useStore()
const route = useRoute()
const router = useRouter()

const emitter = inject("emitter")
const $trans = inject("$trans")
const toast = useToast()

const initialStudentProfile = {}
const initialStudents = {}
const initialSportAndActivities = {}

const initUrl = "student/studentProfile/"

const refreshItem = ref(false)
const student = reactive({ ...initialStudentProfile })
const students = reactive({ ...initialStudents })
const gradeLevels = ref([])
const academicCourses = ref([])
const schoolYears = ref([])
const postsecondaryChoices = ref([])
const careerPathways = ref([])
const sportAndActivities = ref([])
const gradingSystems = ref([])

const setItem = (data) => {
    Object.assign(student, data['student']);
    Object.assign(students, data['students']);
    gradeLevels.value = data['gradeLevels'] || []
    schoolYears.value = data['schoolYears'] || []
    postsecondaryChoices.value = data['postsecondaryChoices'] || []
    careerPathways.value = data['careerPathways'] || []
    sportAndActivities.value = data['sportAndActivities'];
    academicCourses.value = data['academicCourses'] || []
    gradingSystems.value = data['gradingSystems'] || []

    // Load report card, progress report and transcript when student data loads
    if (student.uuid) {
        loadReportCard()
        loadProgressReport()
        loadTranscript()
    }
}

// Report Card, Progress Report & Transcript Data
const reportBlocks = ref([])
const progressBlocks = ref([])
const transcriptBlocks = ref([])
const loadingReportCard = ref(false)
const loadingTranscript = ref(false)

async function loadReportCard() {
    loadingReportCard.value = true
    try {
        const response = await store.dispatch('student/studentProfile/getReportCard', {
            uuid: student.uuid,
            publishedOnly: true // Parent side shows only published report cards
        })
        console.log('[Show.vue] Report card API response:', response)
        reportBlocks.value = response.reports || []

    } catch (error) {
        console.error('Failed to load report card:', error)
    } finally {
        loadingReportCard.value = false
    }
}

async function loadProgressReport() {
    try {
        const response = await store.dispatch('student/studentProfile/getProgressReport', {
            uuid: student.uuid,
            publishedOnly: true, // Parent side shows only published progress reports
        })
        progressBlocks.value = response?.reports || []
    } catch (error) {
        console.error('Failed to load progress report:', error)
        progressBlocks.value = []
    }
}

// Listen for refresh events to reload report card, progress report and transcript
emitter.on('refreshItem', () => {
    if (student.uuid) {
        loadReportCard()
        loadProgressReport()
        loadTranscript()
    }
})

async function loadTranscript() {
    loadingTranscript.value = true
    try {
        const response = await store.dispatch('student/studentProfile/getTranscript', {
            uuid: student.uuid,
            highSchoolOnly: true
        })
        transcriptBlocks.value = response.blocks || []
        console.log('Transcript Blocks')
        console.log(transcriptBlocks.value)
    } catch (error) {
        console.error('Failed to load transcript:', error)
    } finally {
        loadingTranscript.value = false
    }
}

const selectStudent = (studentUuid) => {
    router.replace({
        name: 'MyChildrenProfileShow',
        params: { uuid: studentUuid },
    })

    emitter.emit('refreshItem');
};

const headerTitle = computed(() => {
    const fullName = [student.firstName, student.middleName, student.lastName]
        .filter(Boolean)
        .join(' ');
    return fullName ? `${fullName}'s Profile` : $trans('student.student_profile');
});

// Search state
const studentSearch = ref('')

// Filter state - removed
// const filters = reactive({
//     gradeLevel: '',
//     gender: '',
//     startDate: '',
//     endDate: '',
// })

// Filter options - removed
// const genderOptions = computed(() => {
//     // Get genders from student data or use default
//     const genders = [
//         { value: 'Male', label: 'Male' },
//         { value: 'Female', label: 'Female' },
//     ]
//     return genders
// })

// const gradeLevelOptions = computed(() => {
//     return gradeLevels.value || []
// })

// Check if filters are active - removed
// const hasActiveFilters = computed(() => {
//     return !!(filters.gradeLevel || filters.gender || filters.startDate || filters.endDate)
// })

// Build a safe array from the reactive `students` object/array
const studentsArray = computed(() => {
    // If API gave an array, return it, else convert object map -> array
    return Array.isArray(students) ? students : Object.values(students)
})

// Helper to get latest academic history
const getLatestAcademicHistory = (student) => {
    const histories = student?.academicHistories ?? student?.academic_histories ?? []
    if (!histories.length) return null
    const sorted = [...histories].sort((a, b) => {
        const dateA = b.enrollmentDate?.value || b.enrollment_date?.value || 0
        const dateB = a.enrollmentDate?.value || a.enrollment_date?.value || 0
        return new Date(dateA) - new Date(dateB)
    })
    return sorted[0]
}

// Filter & sort (Last Name A→Z, then First Name) - filters removed, only search remains
const filteredSortedStudents = computed(() => {
    let result = studentsArray.value

    // Apply search filter
    const term = studentSearch.value.toLowerCase()
    if (term) {
        result = result.filter(s => {
            const haystack = [
                s.firstName, s.middleName, s.lastName, s.studentId, s.rollNo
            ].filter(Boolean).join(' ').toLowerCase()
            return haystack.includes(term)
        })
    }

    // Sort by lastName, then firstName (case-insensitive)
    const sorted = [...result].sort((a, b) => {
        const aLast = (a.lastName || '').toString()
        const bLast = (b.lastName || '').toString()
        const byLast = aLast.localeCompare(bLast, undefined, { sensitivity: 'base' })
        if (byLast !== 0) return byLast
        const aFirst = (a.firstName || '').toString()
        const bFirst = (b.firstName || '').toString()
        return aFirst.localeCompare(bFirst, undefined, { sensitivity: 'base' })
    })

    // Move selected student to the top if it exists in the filtered results
    const selectedUuid = route.params.uuid
    if (selectedUuid) {
        const selectedIndex = sorted.findIndex(s => s.uuid === selectedUuid)
        if (selectedIndex > 0) {
            const selectedStudent = sorted.splice(selectedIndex, 1)[0]
            sorted.unshift(selectedStudent)
        }
    }

    return sorted
})

// Filter functions - removed
// const applyFilters = () => {
//     showFilterModal.value = false
// }

// const resetFilters = () => {
//     filters.gradeLevel = ''
//     filters.gender = ''
//     filters.startDate = ''
//     filters.endDate = ''
// }

// Helper functions for active filters display - removed
// const getGradeLevelName = (uuid) => {
//     const gradeLevel = gradeLevelOptions.value.find(g => g.uuid === uuid)
//     return gradeLevel?.name || uuid
// }

// const getGenderLabel = (value) => {
//     const gender = genderOptions.value.find(g => g.value === value)
//     return gender?.label || value
// }

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
        'Cafeteria': 'fa-solid fa-utensils',
        'Transportation': 'fa-solid fa-bus',
        'Library': 'fa-solid fa-book-open',
    }
    return iconMap[tabName] || 'fa-solid fa-circle'
}

</script>


<style scoped>
/* Optional: ensure checkboxes align */
.form-checkbox {
    flex-shrink: 0;
}

.table-auto th,
.table-auto td {
    border-bottom: 1px solid #e5e7eb;
}
</style>
