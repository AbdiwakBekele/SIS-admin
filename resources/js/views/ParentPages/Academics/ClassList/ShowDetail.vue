<template>
    <PageHeader :title="course.name || 'Class Detail'" :navs="[
        { label: $trans('school_setup.class.class'), path: 'ParentClassListList' }
    ]">
        <PageHeaderAction name="ParentClassList" :title="$trans('school_setup.class.class')" :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentClassListList' })" @refresh="emitter.emit('listItems')">

            <div class="p-3 shadow-md">
                <!-- Tabs Section -->
                <div class="rounded-lg">
                    <!-- Tab Headers -->
                    <div class="w-full flex gap-2">
                        <div v-for="(tab, idx) in tabs" :key="'r1-' + idx" @click="activeTab = tab" :class="[
                            'flex-1 min-w-max whitespace-nowrap cursor-pointer px-2 pt-3 pb-1 text-sm text-center transition-all duration-150',
                            activeTab === tab
                                // pressed-in for active
                                ? 'bg-blue-200 shadow-inner border border-blue-400 border-b-0 rounded-t-lg'
                                // lifted for inactive
                                : 'bg-white border border-gray-300 border-b-0 rounded-t-lg -mb-px z-20 shadow-lg relative -top-1'
                        ]">
                            {{ tab }}
                    </div>
                            </div>



                    <!-- Tab Content -->
                    <div class="mt-2">
                        <!-- Overview Tab -->
                        <OverviewTab v-if="activeTab === 'Overview'" :course="course" :instructors="instructors" />

                        <!-- Curriculum Tab -->
                        <CurriculumTab v-if="activeTab === 'Curriculum'" :class-uuid="route.params.uuid" :terms="terms"
                            :categories="baseCategories" @assessment-created="handleCurriculumAssessmentCreated" />

                        <!-- Discussion Tab -->
                        <DiscussionTab v-if="activeTab === 'Discussion'" :discussions="discussions"
                            @new-thread="openReplyModal(null)" @add-reply="openReplyModal" />

                        <!-- Resources Tab -->
                        <ResourcesTab v-if="activeTab === 'Resources'" :resources="resources"
                            :class-uuid="route.params.uuid" @refresh-resources="handleResourcesRefresh" />

                        <!-- Gamification Tab -->
                        <div v-if="activeTab === 'Gamification'">
                            <ClassGamificationTab :class-uuid="route.params.uuid" :class-name="course.title" />
                        </div>

                        <!-- Attendance Tab -->
                        <AttendanceTab v-if="activeTab === 'Attendance'" :attendance="attendance"
                            @record-attendance="recordAttendance" />

                        <!-- Progress Tab -->
                        <ProgressTab v-if="activeTab === 'Progress'" :class-data="gradebookClassData"
                            @update-grades="updateGrades" @term-changed="handleProgressTermChanged" />

                        <!-- Gradebook Tab -->
                        <GradebookTab v-show="activeTab === 'Gradebook'" ref="gradebookTabRef"
                            :class-uuid="route.params.uuid" :manual-term-overrides="manualTermOverrides"
                            @terms-loaded="handleGradebookTerms" @gradebook-loaded="handleGradebookLoaded"
                            @manual-term-flag="handleManualTermFlag" />

                        <!-- Students Tab -->
                        <StudentsTab v-if="activeTab === 'Students'" :students="filteredStudents" @send-email="sendEmail"
                            @view-profile="viewStudentProfile" @unenroll-student="unenrollStudent"
                            @enroll-students="enrollStudents" />
                                                </div>
                                            </div>
                                        </div>
        </ShowItem>
    </ParentTransition>
</template>

<script setup>
import { ref, reactive, computed, inject, watch, nextTick, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useStore } from 'vuex'

// Import Tab Components
import OverviewTab from './Tabs/OverviewTab.vue'
import GradebookTab from './Tabs/GradebookTab.vue'
import ProgressTab from './Tabs/ProgressTab.vue'
import CurriculumTab from './Tabs/CurriculumTab.vue'
import DiscussionTab from './Tabs/DiscussionTab.vue'
import AttendanceTab from './Tabs/AttendanceTab.vue'
import ResourcesTab from './Tabs/ResourcesTab.vue'
import ClassGamificationTab from '@views/Pages/Academics/LMS/Tabs/ClassGamificationTab.vue'
import StudentsTab from './Tabs/StudentsTab.vue'

const store = useStore()
const route = useRoute()
const router = useRouter()
const emitter = inject('emitter')
const $trans = inject('$trans')

// State
const initUrl = 'schoolSetup/academicClass/'
const activeTab = ref('Overview')

// Course Data
const course = reactive({
    name: '',
    title: '',
    code: '',
    section: '',
    category: '',
    credits: 0,
    description: '',
    sectionDescription: '',
    subjectTitle: '',
    subjectCode: '',
    gradeLevelTitle: '',
    image: '',
    maxEnrollment: null,
    currentEnrollment: null,
    schoolYearName: '',
    virtualLink: '',
    location: '',
    primaryInstructor: null,
    assistantInstructor: null,
    schedule: {
        time: '',
        day: '',
        location: '',
        items: [],
    },
    schoolTerms: [],
})

// Parent's children IDs/UUIDs
const childrenStudentIds = ref([])
const childrenStudentUuids = ref([])

// Fetch parent's children
async function fetchChildrenStudentIds() {
    try {
        const response = await store.dispatch("student/list", { params: { all: true } });
        const children = response?.data?.data || response?.data || [];
        childrenStudentIds.value = children.map(child => child.id).filter(Boolean);
        childrenStudentUuids.value = children.map(child => child.uuid).filter(Boolean);
    } catch (error) {
        console.error("Failed to fetch children student IDs:", error);
        childrenStudentIds.value = [];
        childrenStudentUuids.value = [];
    }
}

const setItem = (data) => {
    if (data) {
        course.name = data.name || data.title || ''
        course.title = data.title || data.name || ''
        course.code = data.code || data.course_code || ''
        course.section = data.section || ''
        course.category =
            data.category ||
            data.academic_course?.category?.title ||
            data.academic_subject?.title ||
            data.academic_course?.subject?.title ||
            ''
        course.subjectTitle = data.academic_course?.subject?.title || data.academic_subject?.title || ''
        course.subjectCode = data.academic_course?.subject?.code || data.academic_subject?.code || ''
        course.credits = Number(data.credits ?? data.credit ?? data.academic_course?.course_credit ?? 0)
        course.description = data.description || data.academic_course?.description || ''
        course.sectionDescription = data.section_description || data.sectionDescription || ''
        course.gradeLevelTitle =
            data.grade_level?.name ||
            data.gradeLevel?.name ||
            data.academic_course?.grade_levels?.map((level) => level.name).join(', ') ||
            ''
        course.image = data.cover_image || data.image || data.banner_image || ''
        course.maxEnrollment = data.enrollmentSize || data.enrollment_size || data.max_enrollment || data.maxEnrollment || null
        course.currentEnrollment = data.enrollment_count || data.enrollmentCount || null
        course.schoolYearName = data.school_year?.name || data.schoolYear?.name || ''
        course.virtualLink = data.virtual_link || data.virtualLink || ''
        course.location = data.location || ''

        // Handle class_schedule (snake_case) or classSchedule (camelCase)
        const scheduleData = data.class_schedule || data.classSchedule || data.academic_course?.class_schedule || []
        const schedule = Array.isArray(scheduleData) ? scheduleData : []

        // Store schedule items array
        course.schedule.items = schedule.map((item) => ({
            day: item.day || '',
            startTime: item.start_time || item.startTime || '',
            endTime: item.end_time || item.endTime || '',
        }))

        // For backward compatibility, also set day and time as strings
        if (typeof data.class_schedule === 'object' && data.class_schedule !== null && !Array.isArray(data.class_schedule)) {
            const scheduleObj = data.class_schedule
            const days = []
            const times = []

            Object.keys(scheduleObj).forEach((dayKey) => {
                if (dayKey.startsWith('_')) {
                    const dayName = dayKey.replace('_', '').charAt(0).toUpperCase() + dayKey.replace('_', '').slice(1)
                    const daySchedules = Array.isArray(scheduleObj[dayKey]) ? scheduleObj[dayKey] : []
                    daySchedules.forEach((slot) => {
                        if (slot.start_time && slot.end_time) {
                            days.push(dayName)
                            times.push(`${slot.start_time} - ${slot.end_time}`)
                        }
                    })
                }
            })

            if (days.length) {
                course.schedule.day = [...new Set(days)].join(', ')
                course.schedule.time = [...new Set(times)].join(', ')
            }
        } else if (schedule.length > 0) {
            // Parse all schedule items
            const days = []
            const times = []
            schedule.forEach((item) => {
                const day = item.day || ''
                const startTime = item.start_time || item.startTime || ''
                const endTime = item.end_time || item.endTime || ''
                if (day) {
                    days.push(day)
                }
                if (startTime && endTime) {
                    times.push(`${startTime} - ${endTime}`)
                }
            })
            course.schedule.day = [...new Set(days)].join(', ')
            course.schedule.time = [...new Set(times)].join(', ')
        } else {
            course.schedule.time = data.schedule?.time || ''
            course.schedule.day = data.schedule?.day || ''
        }

        course.schedule.location = course.location || data.schedule?.location || data.academic_course?.location || ''

        const rawTerms = data.school_year?.school_terms || data.schoolYear?.schoolTerms || data.schoolTerms || data.school_terms || []
        course.schoolTerms = Array.isArray(rawTerms)
            ? rawTerms.map((term) => ({
                uuid: term.uuid || term.id || term.academic_class_term_uuid || term.school_term_uuid || null,
                title: term.title || term.name || '',
                startDate: term.start_date?.formatted || term.start_date?.value || term.startDate || term.period_start || '',
                endDate: term.end_date?.formatted || term.end_date?.value || term.endDate || term.period_end || '',
            }))
            : []

        const instructorList = []
        const primary = data.instructor || data.primaryInstructor || data.primary_instructor || data.academic_class_instructors?.primary || null
        if (primary) {
            const contact = primary.contact || {}
            instructorList.push({
                name: primary.name || contact.name || [contact.first_name, contact.last_name].filter(Boolean).join(' ') || '',
                role: 'Primary Instructor',
                email: contact.email || primary.email || '',
                phone: contact.contact_number || contact.phone || contact.mobile_phone || primary.phone || '',
                avatar: contact.photo_url || contact.photo || primary.avatar || primary.photo_url || '',
                photoUrl: contact.photo_url || contact.photo || primary.photo_url || primary.avatar || '',
                codeNumber: primary.code_number || primary.codeNumber || '',
            })
        }
        const assistant = data.assistant_instructor || data.assistantInstructor || data.academic_class_instructors?.assistant || null
        if (assistant) {
            const contact = assistant.contact || {}
            instructorList.push({
                name: assistant.name || contact.name || [contact.first_name, contact.last_name].filter(Boolean).join(' ') || '',
                role: 'Assistant Instructor',
                email: contact.email || assistant.email || '',
                phone: contact.contact_number || contact.phone || contact.mobile_phone || assistant.phone || '',
                avatar: contact.photo_url || contact.photo || assistant.avatar || assistant.photo_url || '',
                photoUrl: contact.photo_url || contact.photo || assistant.photo_url || assistant.avatar || '',
                codeNumber: assistant.code_number || assistant.codeNumber || '',
            })
        }
        if (Array.isArray(data.instructors)) {
            data.instructors.forEach((inst) => {
                const contact = inst.contact || {}
                instructorList.push({
                    name: inst.name || contact.name || [contact.first_name, contact.last_name].filter(Boolean).join(' ') || '',
                    role: inst.role || 'Instructor',
                    email: contact.email || inst.email || '',
                    phone: contact.contact_number || contact.phone || contact.mobile_phone || inst.phone || '',
                    avatar: contact.photo_url || contact.photo || inst.avatar || inst.photo_url || '',
                    photoUrl: contact.photo_url || contact.photo || inst.photo_url || inst.avatar || '',
                    codeNumber: inst.code_number || inst.codeNumber || '',
                })
            })
        }
        instructors.value = instructorList

        course.primaryInstructor = primary
        course.assistantInstructor = assistant
    }
    nextTick(() => {
        loadAssessmentData()
        refreshGradebook()
        loadResources()
    })
}

async function loadResources() {
    try {
        const classUuid = route.params.uuid
        if (!classUuid) return
        const response = await store.dispatch('schoolSetup/academicClass/getClassResources', {
            classUuid
        })
        resources.value = Array.isArray(response) ? response : []
    } catch (error) {
        console.error('Failed to load resources:', error)
    }
}

const config = reactive({
    gradingScheme: [
        { min: 90, letter: 'A+' },
        { min: 80, letter: 'A' },
        { min: 70, letter: 'B' },
        { min: 60, letter: 'C' },
        { min: 0, letter: 'F' },
    ],
    manualTermEnabled: false,
    assessmentLetterEnabled: false,
})

const baseCategories = ref([])
const categories = ref([])
const manualTermOverrides = reactive({})
const terms = ref([])

const gradebookSnapshot = reactive({
    assessments: [],
    students: [],
    scores: [],
    categoryConfig: [],
    selectedTermUuid: null,
    assessmentLetterEnabled: false,
})

const gradebookClassData = computed(() => {
    const usedTypes = {}
    const weights = {}

    gradebookSnapshot.categoryConfig.forEach((item) => {
        const key = item.category ?? item.category_name ?? item.title ?? `Category ${item.assessment_category_id}`
        const weight = Number(item.weight_percent ?? item.weightPercent ?? 0)
        usedTypes[key] = !!(item.is_enabled ?? item.isEnabled ?? weight > 0)
        weights[key] = weight
    })

    // Filter students to only show parent's children
    const filteredStudents = gradebookSnapshot.students.filter(student => {
        const studentId = student.id || student.studentId
        const studentUuid = student.uuid || student.studentUuid
        return childrenStudentIds.value.includes(studentId) || childrenStudentUuids.value.includes(studentUuid)
    })

    // Filter scores to only include parent's children
    const filteredScores = gradebookSnapshot.scores.filter(score => {
        const studentId = score.studentId || score.student?.id
        const studentUuid = score.studentUuid || score.student?.uuid
        return childrenStudentIds.value.includes(studentId) || childrenStudentUuids.value.includes(studentUuid)
    })

    return {
        assessments: gradebookSnapshot.assessments,
        students: filteredStudents,
        config: {
            usedTypes,
            weights,
            manualTermEnabled: config.manualTermEnabled,
        },
        manualTermOverrides,
        terms: terms.value,
        selectedTermUuid: gradebookSnapshot.selectedTermUuid,
        manualTermEnabled: config.manualTermEnabled,
        scores: filteredScores,
        assessmentLetterEnabled: gradebookSnapshot.assessmentLetterEnabled,
    }
})

// Filter students to only show parent's children
const filteredStudents = computed(() => {
    return gradebookSnapshot.students.filter(student => {
        const studentId = student.id || student.studentId
        const studentUuid = student.uuid || student.studentUuid
        return childrenStudentIds.value.includes(studentId) || childrenStudentUuids.value.includes(studentUuid)
    })
})

const gradebookTabRef = ref(null)

async function refreshGradebook(termUuid = null) {
    if (!gradebookTabRef.value?.refresh) {
        return
    }

    try {
        await gradebookTabRef.value.refresh(termUuid ?? undefined)
    } catch (error) {
        // The tab already surfaces its own toast/error handling.
    }
}

// Load assessment categories & class configuration
async function loadAssessmentData() {
    try {
        const classUuid = route.params.uuid

        const [categoryResponse, configResponse] = await Promise.all([
            store.dispatch('schoolSetup/assessmentCategory/list', {
                params: { all: true }
            }),
            store.dispatch('schoolSetup/academicClass/getClassAssessmentCategories', {
                classUuid
            })
        ])

        const rawCategories = Array.isArray(categoryResponse?.data)
            ? categoryResponse.data
            : Array.isArray(categoryResponse)
                ? categoryResponse
                : []

        baseCategories.value = rawCategories.map(cat => ({
            id: cat.id ?? null,
            uuid: cat.uuid ?? null,
            title: cat.title ?? '',
            code: cat.code ?? '',
        }))

        const configItems = configResponse?.categories ?? []
        const configMap = new Map(
            configItems
                .filter(item => item?.assessmentCategoryUuid || item?.assessment_category_uuid)
                .map(item => {
                    const uuid = item.assessmentCategoryUuid ?? item.assessment_category_uuid
                    return [uuid, item]
                })
        )

        const merged = baseCategories.value.map(cat => {
            const configItem = configMap.get(cat.uuid) ?? {}
            return {
                id: cat.id,
                uuid: cat.uuid,
                title: cat.title,
                code: cat.code,
                weight: Number(configItem.weightPercent ?? configItem.weight_percent ?? 0),
                isEnabled: !!(configItem.isEnabled ?? configItem.is_enabled ?? false),
                isNew: false,
            }
        })

        configItems.forEach(item => {
            const uuid = item.assessmentCategoryUuid ?? item.assessment_category_uuid

            if (!uuid || merged.some(cat => cat.uuid === uuid)) {
                return
            }

            merged.push({
                id: item.assessmentCategoryId ?? item.assessment_category_id ?? null,
                uuid,
                title: item.title ?? '',
                code: item.code ?? '',
                weight: Number(item.weightPercent ?? item.weight_percent ?? 0),
                isEnabled: !!(item.isEnabled ?? item.is_enabled ?? false),
                isNew: false,
            })
        })

        categories.value = merged

        const manualTermFlag = configResponse?.manualTermEnabled ?? configResponse?.manual_term_enabled
        if (typeof manualTermFlag === 'boolean') {
            config.manualTermEnabled = manualTermFlag
        }

        const letterFlag = configResponse?.assessmentLetterEnabled ?? configResponse?.assessment_letter_enabled
        if (typeof letterFlag === 'boolean') {
            config.assessmentLetterEnabled = letterFlag
        }
    } catch (error) {
        console.error('Failed to load assessment data:', error)
    }
}

function handleGradebookLoaded(payload) {
    gradebookSnapshot.assessments = payload?.assessments ?? []
    gradebookSnapshot.students = payload?.students ?? []
    gradebookSnapshot.scores = payload?.scores ?? []
    gradebookSnapshot.categoryConfig = payload?.categoryConfig ?? []
    gradebookSnapshot.selectedTermUuid = payload?.selectedTermUuid ?? null
    gradebookSnapshot.assessmentLetterEnabled = !!(payload?.assessmentLetterEnabled ?? payload?.assessment_letter_enabled)
}

function handleGradebookTerms(loadedTerms) {
    terms.value = Array.isArray(loadedTerms) ? loadedTerms : []
}

function handleManualTermFlag(flag) {
    if (typeof flag === 'boolean') {
        config.manualTermEnabled = flag
    }
}

function handleCurriculumAssessmentCreated() {
    refreshGradebook(gradebookSnapshot.selectedTermUuid)
}

async function updateGrades() {
    if (gradebookTabRef.value?.calculateGrades) {
        await gradebookTabRef.value.calculateGrades()
    }
}

function handleProgressTermChanged(payload) {
    if (payload === undefined) {
        return
    }

    if (!payload) {
        refreshGradebook(null)
        return
    }

    if (typeof payload === 'object') {
        const nextUuid = payload.classTermUuid ?? payload.termUuid ?? null
        refreshGradebook(nextUuid)
        return
    }

    if (typeof payload === 'string') {
        if (payload === 'All') {
            refreshGradebook(null)
            return
        }

        const termNo = Number(String(payload).replace('T', ''))
        if (Number.isNaN(termNo)) {
            return
        }

        const matched = terms.value.find(term => Number(term.termNo ?? term.term_no) === termNo)
        refreshGradebook(matched?.academicClassTermUuid ?? null)
        return
    }

    refreshGradebook(payload ?? null)
}

// Other Data (kept for other tabs)
const discussions = ref([])
const resources = ref([])
const attendance = ref([])
const participants = ref([])
const weekStart = ref('May 19, 2025')
const weekEnd = ref('May 25, 2025')
const instructors = ref([])

const tabs = ['Overview', 'Curriculum', 'Discussion', 'Resources', 'Gamification', 'Attendance', 'Progress', 'Gradebook', 'Students']

// Event handlers
function recordAttendance() { console.log('Record attendance') }
function sendEmail(student) {
    console.log('Send email to', student)
}
function viewStudentProfile(student) {
    router.push({ name: 'ParentStudentProfileShow', params: { uuid: student.uuid } })
}
function unenrollStudent(student) {
    // Read-only - no action
}
function enrollStudents() {
    // Read-only - no action
}
function openReplyModal(threadId) { console.log('Open reply modal', threadId) }

async function handleResourcesRefresh(newResources) {
    resources.value = Array.isArray(newResources) ? newResources : []
}

onMounted(async () => {
    await fetchChildrenStudentIds()
})
</script>

<style scoped>
/* Add any page-level styles if needed */
</style>
