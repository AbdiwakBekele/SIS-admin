<template>
    <!-- Gradebook tab root container -->
    <div class="bg-white rounded-lg p-6 space-y-4">
        <!-- Header: term selector and update button -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-semibold text-gray-700">Gradebook</h3>
                <p v-if="activeTerm" class="text-xs text-gray-500 mt-1">
                    {{ activeTerm.title }}
                    <span v-if="activeTerm.startDate && activeTerm.endDate">
                        • {{ formatDate(activeTerm.startDate) }} – {{ formatDate(activeTerm.endDate) }}
                    </span>
                </p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center text-xs text-gray-600" v-if="terms.length">
                    <span class="mr-2">Term:</span>
                    <div class="inline-flex border border-blue-200 rounded overflow-hidden">
                        <button v-for="term in terms" :key="term.academicClassTermUuid"
                            class="px-3 py-1 transition-colors"
                            :class="term.academicClassTermUuid === selectedTermUuid ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-blue-50'"
                            @click="selectTerm(term.academicClassTermUuid)">
                            {{ term.shortTitle }}
                        </button>
                        <button class="px-3 py-1 transition-colors"
                            :class="selectedTermUuid === 'final' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-blue-50'"
                            @click="selectTerm('final')">
                            Final Grades
                        </button>
                    </div>
                </div>

                <div class="text-xs text-gray-500" v-else>
                    No terms configured for this class.
                </div>

                <!-- Assessment Category Filter -->
                <div v-if="selectedTermUuid !== 'final' && termAssessments.length && !disableAssessmentsInGradebook"
                    class="relative">
                    <Popover v-slot="{ open }" class="relative">
                        <PopoverButton
                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm rounded border border-blue-200 bg-white text-gray-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors"
                            :class="{ 'bg-blue-50 border-blue-300': open || selectedCategoryFilter }">
                            <i class="fa-solid fa-filter text-blue-600"></i>
                            <span>Filter</span>
                            <span v-if="selectedCategoryFilter"
                                class="ml-1 px-1.5 py-0.5 text-xs bg-blue-500 text-white rounded-full">
                                {{ selectedCategoryFilter }}
                            </span>
                        </PopoverButton>

                        <transition enter-active-class="transition duration-200 ease-out"
                            enter-from-class="translate-y-1 opacity-0" enter-to-class="translate-y-0 opacity-100"
                            leave-active-class="transition duration-150 ease-in"
                            leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
                            <PopoverPanel class="absolute right-0 z-50 mt-2 w-56 transform">
                                <div class="relative">
                                    <!-- Pointer/Arrow -->
                                    <div
                                        class="absolute -top-2 right-4 w-4 h-4 bg-white border-l border-t border-gray-300 transform rotate-45">
                                    </div>

                                    <!-- Popover Content -->
                                    <div
                                        class="relative overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white">
                                        <div class="p-3 space-y-2">
                                            <div class="text-xs font-semibold text-gray-700 mb-2">Filter by Category
                                            </div>
                                            <button @click="setCategoryFilter(null)"
                                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-blue-50 transition-colors"
                                                :class="!selectedCategoryFilter ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700'">
                                                All Categories
                                            </button>
                                            <div v-for="category in availableCategories" :key="category"
                                                class="border-t border-gray-100">
                                                <button @click="setCategoryFilter(category)"
                                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-blue-50 transition-colors"
                                                    :class="selectedCategoryFilter === category ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700'">
                                                    {{ category }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </PopoverPanel>
                        </transition>
                    </Popover>
                </div>

            </div>
        </div>

        <!-- Loading state -->
        <div v-if="loading" class="text-center py-12">
            <i class="fa-solid fa-spinner fa-spin text-3xl text-gray-400"></i>
            <p class="mt-2 text-gray-500">Loading gradebook data...</p>
        </div>

        <!-- Empty state: no students -->
        <div v-else-if="!students.length" class="text-center py-12 text-gray-400">
            <i class="fa-solid fa-users text-4xl mb-3"></i>
            <p class="text-lg font-medium">No students enrolled</p>
            <p class="text-sm mt-1">Enroll students to start tracking grades</p>
        </div>

        <!-- Empty state: no assessments -->
        <div v-else-if="selectedTermUuid !== 'final' && !termAssessments.length && !disableAssessmentsInGradebook"
            class="text-center py-12 text-gray-400">
            <i class="fa-solid fa-list-check text-4xl mb-3"></i>
            <p class="text-lg font-medium">No assessments for this term</p>
            <p class="text-sm mt-1">Create curriculum assessments to begin scoring.</p>
        </div>

        <!-- Final Grades View -->
        <div v-else-if="selectedTermUuid === 'final'"
            class="rounded-xl overflow-hidden shadow-lg ring-1 ring-blue-300/50 dark:ring-white/10">
            <table class="min-w-[600px] w-full divide-y-2 divide-blue-400 dark:divide-blue-300 bg-white dark:bg-neutral-900">
                <thead class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-md">
                    <tr>
                        <th scope="col" class="sm:px-2 xl:px-4 py-4 text-left text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 sticky left-0 z-40 last:border-r-0">
                            Student
                        </th>
                        <th v-for="term in terms" :key="term.academicClassTermUuid" scope="col"
                            class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 min-w-[120px] last:border-r-0">
                            <div class="font-semibold text-white">{{ term.shortTitle }}</div>
                            <div class="text-[10px] text-white/90 font-normal mt-0.5">Final</div>
                        </th>
                        <th scope="col"
                            class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 sticky right-0 z-40 min-w-[120px] last:border-r-0">
                            <div class="font-semibold text-white">Course Final</div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-blue-400 dark:divide-blue-300 striped-rows">
                    <tr v-for="student in students" :key="student.uuid" class="data-row">
                        <td
                            class="py-2 sm:px-2 xl:px-4 text-sm border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400 sticky left-0 z-20 font-semibold">
                            {{ student.name }}
                        </td>
                        <td v-for="term in terms" :key="`${student.uuid}-${term.academicClassTermUuid}`"
                            class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-sm font-medium">
                                    {{ formatTermGrade(getTermFinalGrade(student, term.academicClassTermUuid)?.numeric) }}
                                </span>
                                <span class="text-xs font-semibold text-blue-600">
                                    {{ getTermFinalGrade(student, term.academicClassTermUuid)?.letter ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td
                            class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400 sticky right-0 z-20 last:border-r-0">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-sm font-medium">
                                    {{ formatTermGrade(student.finalGradeNumeric ?? student.final_grade_numeric) }}
                                </span>
                                <span class="text-xs font-semibold text-blue-600">
                                    {{ student.finalGradeLetter ?? student.final_grade_letter ?? '-' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Gradebook table (with assessments) -->
        <div v-else-if="!disableAssessmentsInGradebook"
            class="rounded-xl overflow-hidden shadow-lg ring-1 ring-blue-300/50 dark:ring-white/10">
            <table class="min-w-[900px] w-full divide-y-2 divide-blue-400 dark:divide-blue-300 bg-white dark:bg-neutral-900">
                <thead class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-md">
                    <tr>
                        <th scope="col" class="sm:px-2 xl:px-4 py-4 text-left text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 sticky left-0 z-40 last:border-r-0">
                            Student
                        </th>
                        <th v-for="assessment in termAssessments" :key="assessment.id" scope="col"
                            class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 whitespace-normal leading-tight min-w-[140px] last:border-r-0">
                            <div class="font-semibold text-white text-xs mb-1">{{ assessment.title }}</div>
                            <div class="grid grid-cols-2 gap-0.5 text-[10px] text-white/90">
                                <div class="border-r border-white/30 pr-0.5">Score</div>
                                <div class="pl-0.5">Letter</div>
                            </div>
                        </th>
                        <th v-if="selectedCategoryFilter" scope="col"
                            class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 sticky right-[160px] top-0 z-40 border-l-2 border-r-2 border-blue-700 dark:border-blue-500 min-w-[120px] last:border-r-0">
                            Category Avg
                        </th>
                        <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 sticky top-0 z-40 border-l-2 border-r-2 border-blue-700 dark:border-blue-500 min-w-[120px]"
                            :class="selectedCategoryFilter ? 'right-[80px]' : 'right-[80px]'">
                            {{ manualTermEnabled ? 'Manual Term ' : 'Term Average ' }}
                        </th>
                        <th scope="col"
                            class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 sticky right-0 top-0 z-40 border-l-2 border-blue-700 dark:border-blue-500 min-w-[80px] last:border-r-0">
                            Letter
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-blue-400 dark:divide-blue-300 striped-rows">
                    <tr v-for="student in students" :key="student.uuid" class="data-row">
                        <td class="py-2 sm:px-2 xl:px-4 text-sm border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400 sticky left-0 z-20 font-semibold">
                            {{ student.name }}
                        </td>

                        <td v-for="assessment in termAssessments" :key="`${student.uuid}-${assessment.id}`"
                            class="py-2 sm:px-2 xl:px-4 text-sm border-r-2 border-blue-400 dark:border-blue-300 align-top p-0">
                            <div class="grid grid-cols-2 gap-0.5 h-full">
                                <div class="flex items-center justify-center border-r border-blue-400 dark:border-blue-300 pr-0.5 py-1">
                                    <template v-if="assessmentLetterEnabled">
                                        <select :value="getScoreValue(student, assessment)"
                                            @change="handleScoreInput(student, assessment, $event)"
                                            :disabled="!courseEnrollmentTermId(student) || !gradeScaleOptions.length"
                                            class="w-full border border-blue-300 rounded px-1 py-0.5 text-[10px] text-center focus:ring-1 focus:ring-blue-400 focus:border-blue-500 disabled:opacity-60 disabled:bg-gray-100">
                                            <option value="">-</option>
                                            <option v-for="option in gradeScaleOptions" :key="option.value"
                                                :value="option.value">
                                                {{ option.label }}
                                            </option>
                                        </select>
                                    </template>
                                    <template v-else>
                                        <input type="number" :value="getNumericScore(student, assessment) ?? ''"
                                            @input="handleScoreInput(student, assessment, $event)" :max="assessment.max"
                                            min="0" step="0.5" :disabled="!courseEnrollmentTermId(student)"
                                            class="w-full border border-blue-300 rounded px-1 py-0.5 text-[10px] text-center focus:ring-1 focus:ring-blue-400 focus:border-blue-500 disabled:opacity-60 disabled:bg-gray-100" />
                                    </template>
                                </div>
                                <div class="flex items-center justify-center pl-0.5 py-1 bg-blue-100/60 dark:bg-blue-900/30 rounded-sm min-h-full">
                                    <span class="text-[10px] font-semibold text-blue-700 dark:text-blue-400">
                                        {{ getAssessmentLetterGrade(student, assessment) }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td v-if="selectedCategoryFilter"
                            class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400 sticky right-[160px] z-20 border-l-2 border-r-2 border-blue-400 dark:border-blue-300">
                            {{ formatPercent(categoryAveragePercent(student, selectedCategoryFilter)) }}
                        </td>
                        <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400 sticky z-20 border-l-2 border-r-2 border-blue-400 dark:border-blue-300"
                            :class="selectedCategoryFilter ? 'right-[80px]' : 'right-[80px]'">
                            <template v-if="manualTermEnabled">
                                <input type="number" min="0" max="100" step="0.1" :value="manualOverrideValue(student)"
                                    @input="setManualOverride(student, $event.target.value)"
                                    :disabled="!courseEnrollmentTermId(student)"
                                    class="w-24 border border-blue-300 rounded px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-400 focus:border-blue-500 disabled:opacity-60 disabled:bg-gray-100" />
                            </template>
                            <template v-else>
                                {{ formatPercent(termAveragePercent(student)) }}
                            </template>
                        </td>

                        <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400 sticky right-0 z-20 border-l-2 border-blue-400 dark:border-blue-300 last:border-r-0">
                            <span class="font-semibold text-blue-600">{{ letterFor(student) }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Gradebook table (assessments disabled - final grades only) -->
        <div v-else class="rounded-xl overflow-hidden shadow-lg ring-1 ring-blue-300/50 dark:ring-white/10">
            <table class="min-w-[600px] w-full divide-y-2 divide-blue-400 dark:divide-blue-300 bg-white dark:bg-neutral-900">
                <thead class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-md">
                    <tr>
                        <th scope="col" class="sm:px-2 xl:px-4 py-4 text-left text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 sticky left-0 z-40 last:border-r-0">
                            Student
                        </th>
                        <th scope="col"
                            class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 sticky top-0 z-40 border-l-2 border-r-2 border-blue-700 dark:border-blue-500 min-w-[120px] last:border-r-0">
                            {{ manualTermEnabled ? 'Manual Term ' : 'Term Average ' }}
                        </th>
                        <th scope="col"
                            class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 sticky right-0 top-0 z-40 border-l-2 border-blue-700 dark:border-blue-500 min-w-[80px] last:border-r-0">
                            Letter
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-blue-400 dark:divide-blue-300 striped-rows">
                    <tr v-for="student in students" :key="student.uuid" class="data-row">
                        <td class="py-2 sm:px-2 xl:px-4 text-sm border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400 sticky left-0 z-20 font-semibold">
                            {{ student.name }}
                        </td>
                        <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400 sticky z-20 border-l-2 border-r-2 border-blue-400 dark:border-blue-300">
                            <input type="number" min="0" max="100" step="0.1"
                                :value="manualTermEnabled ? manualOverrideValue(student) : (termAveragePercent(student) || '')"
                                @input="setManualOverride(student, $event.target.value)"
                                :disabled="!courseEnrollmentTermId(student)"
                                class="w-24 border border-blue-300 rounded px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-400 focus:border-blue-500 disabled:opacity-60 disabled:bg-gray-100" />
                        </td>
                        <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400 sticky right-0 z-20 border-l-2 border-blue-400 dark:border-blue-300 last:border-r-0">
                            <span class="font-semibold text-blue-600">{{ letterFor(student) }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
// Core utilities
import { computed, reactive, ref, watch } from 'vue'
import { useStore } from 'vuex'
import { useToast } from 'vue-toastification'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'

// Component props
const props = defineProps({
    classUuid: { type: String, required: true },
    manualTermOverrides: {
        type: Object,
        default: () => ({}),
    },
})

// Events emitted to parent components
const emit = defineEmits(['terms-loaded', 'gradebook-loaded', 'manual-term-flag'])

// Store and toast instances
const store = useStore()
const toast = useToast()

// Loading state flags
const loading = ref(false)
const calculating = ref(false)

// Primary data collections
const terms = ref([])
const assessments = ref([])
const students = ref([])
const scores = ref([])
const gradeScales = ref([])
const categoryConfig = ref([])
const manualTermEnabled = ref(false)
const assessmentLetterEnabled = ref(false)
const disableAssessmentsInGradebook = ref(false)
const manualOverrides = props.manualTermOverrides || reactive({})
const dirtyManualOverrideKeys = new Set()

// Active term tracking
const selectedTermUuid = ref(null)

// Derived state: active term
const activeTerm = computed(() => {
    if (selectedTermUuid.value === 'final') {
        return null
    }
    return terms.value.find(term => term.academicClassTermUuid === selectedTermUuid.value) || null
})

// Category filter state
const selectedCategoryFilter = ref(null)

// Available categories for filtering
const availableCategories = computed(() => {
    if (selectedTermUuid.value === 'final') {
        return []
    }
    const categories = new Set()
    assessments.value
        .filter(assessment => assessment.academicClassTermUuid === selectedTermUuid.value)
        .forEach(assessment => {
            if (assessment.category) {
                categories.add(assessment.category)
            }
        })
    return Array.from(categories).sort()
})

// Derived state: assessments for selected term (filtered by category)
const termAssessments = computed(() => {
    if (selectedTermUuid.value === 'final') {
        return []
    }
    let filtered = assessments.value.filter(assessment => assessment.academicClassTermUuid === selectedTermUuid.value)

    if (selectedCategoryFilter.value) {
        filtered = filtered.filter(assessment => assessment.category === selectedCategoryFilter.value)
    }

    return filtered
})

// Lookup map for student-assessment scores
const scoreMap = computed(() => {
    const map = new Map()
    scores.value.forEach(score => {
        const studentUuid = score.studentUuid || score.student_uuid
        const assessmentId = score.classAssessmentId || score.class_assessment_id
        const key = `${studentUuid}|${assessmentId}`
        map.set(key, {
            value: score.scoreEarned ?? score.score_earned ?? null,
            courseEnrollmentTermId: score.courseEnrollmentTermId || score.course_enrollment_term_id,
        })
    })
    return map
})

const weightedCategoryConfig = computed(() =>
    categoryConfig.value.filter(
        (config) => config.isEnabled && Number.isFinite(config.weightPercent) && config.weightPercent > 0
    )
)

const normalizedGradeScales = computed(() =>
    (gradeScales.value || [])
        .map((scale) => ({
            name: scale.name,
            min: Number(scale.minScore ?? scale.min_score ?? 0),
            max: Number(scale.maxScore ?? scale.max_score ?? scale.min_score ?? 0),
            isFailing: !!(scale.isFailing ?? scale.is_failing ?? false),
        }))
        .sort((a, b) => b.min - a.min)
)

const gradeScaleOptions = computed(() =>
    normalizedGradeScales.value.map((scale) => ({
        value: scale.name,
        label: scale.name,
        min: scale.min,
        max: scale.max,
        isFailing: scale.isFailing,
    }))
)

// Auto-refresh when class changes
watch(
    () => props.classUuid,
    (uuid) => {
        if (uuid) {
            refresh()
        }
    },
    { immediate: true }
)

// Normalizers: assessments, scores, students, and terms
function normalizeAssessments(collection) {
    return (collection || []).map((assessment) => ({
        id: assessment.id,
        uuid: assessment.uuid,
        title: assessment.title,
        category: assessment.category?.title || assessment.categoryName || 'Other',
        categoryId: assessment.category?.id || assessment.categoryId,
        categoryUuid: assessment.category?.uuid || assessment.categoryUuid || assessment.assessment_category_uuid,
        academicClassTermUuid: assessment.academicClassTermUuid || assessment.academic_class_term_uuid,
        schoolTermUuid: assessment.schoolTermUuid || assessment.school_term_uuid,
        termTitle: assessment.term || assessment.term_title || '',
        termNo: assessment.termNo || assessment.term_no,
        max: assessment.maxScore || assessment.max_score || 100,
        due: assessment.dueDate || assessment.due_date || '',
    }))
}

function normalizeScores(collection) {
    return (collection || []).map((score) => ({
        id: score.id,
        studentUuid: score.studentUuid || score.student_uuid,
        classAssessmentId: score.classAssessmentId || score.class_assessment_id,
        courseEnrollmentTermId: score.courseEnrollmentTermId || score.course_enrollment_term_id,
        scoreEarned: score.scoreEarned ?? score.score_earned ?? null,
    }))
}

function normalizeStudents(collection, normalizedScores) {
    const students = (collection || []).map((student) => {
        const enrollments = (student.enrollments || []).map((enrollment) => ({
            id: enrollment.id,
            uuid: enrollment.uuid,
            academicClassTermUuid: enrollment.academicClassTermUuid || enrollment.academic_class_term_uuid,
            schoolTermUuid: enrollment.schoolTermUuid || enrollment.school_term_uuid,
            termNo: enrollment.termNo || enrollment.term_no,
            termTitle: enrollment.termTitle || enrollment.term_title,
            termGradeLetter: enrollment.termGradeLetter || enrollment.term_grade_letter,
            termGradeNumeric: enrollment.termGradeNumeric || enrollment.term_grade_numeric,
        }))

        return {
            uuid: student.uuid,
            idNumber: student.idNumber || student.id_number,
            name: student.name,
            firstName: student.firstName || student.first_name,
            lastName: student.lastName || student.last_name,
            enrollmentId: student.enrollmentId || student.enrollment_id,
            enrollmentUuid: student.enrollmentUuid || student.enrollment_uuid,
            enrollmentStatus: student.enrollmentStatus || student.enrollment_status,
            enrollments,
            assessments: [],
            finalGradeLetter: student.finalGradeLetter || student.final_grade_letter,
            finalGradeNumeric: student.finalGradeNumeric || student.final_grade_numeric,
        }
    })

    const studentMap = new Map(students.map(student => [student.uuid, student]))

    normalizedScores.forEach((score) => {
        const student = studentMap.get(score.studentUuid)
        if (!student) return

        student.assessments.push({
            assessmentId: score.classAssessmentId,
            score: score.scoreEarned,
        })
    })

    return students
}

function normalizeTerms(collection, activeClassTermUuid) {
    return (collection || []).map((term) => {
        const academicClassTermUuid = term.academicClassTermUuid || term.academic_class_term_uuid || term.uuid

        return {
            academicClassTermUuid,
            uuid: academicClassTermUuid,
            schoolTermUuid: term.schoolTermUuid || term.school_term_uuid,
            termNo: term.termNo || term.term_no,
            title: term.title,
            shortTitle: term.shortTitle || term.short_title || term.title,
            startDate: term.startDate || term.start_date || null,
            endDate: term.endDate || term.end_date || null,
            isActive: activeClassTermUuid
                ? activeClassTermUuid === academicClassTermUuid
                : !!(term.isActive || term.is_active),
        }
    })
}

function normalizeCategoryConfig(collection) {
    return (collection || []).map((config) => ({
        id: config.id,
        categoryId: config.assessmentCategoryId || config.assessment_category_id || config.categoryId || config.category_id,
        categoryUuid:
            config.assessment_category_uuid ||
            config.categoryUuid ||
            config.category_uuid ||
            config.uuid,
        weightPercent: Number(config.weightPercent ?? config.weight_percent ?? 0),
        isEnabled: !(config.is_enabled === false || config.isEnabled === false),
    }))
}

function resolveSelectedTermUuid(collection, activeUuid, requestedUuid) {
    if (!collection.length) return null

    if (requestedUuid) {
        const byClassTerm = collection.find(term => term.academicClassTermUuid === requestedUuid)
        if (byClassTerm) return byClassTerm.academicClassTermUuid

        const bySchoolTerm = collection.find(term => term.schoolTermUuid === requestedUuid)
        if (bySchoolTerm) return bySchoolTerm.academicClassTermUuid
    }

    if (activeUuid) {
        const active = collection.find(term => term.academicClassTermUuid === activeUuid)
        if (active) return active.academicClassTermUuid
    }

    const flagged = collection.find(term => term.isActive)
    if (flagged) return flagged.academicClassTermUuid

    return collection[0].academicClassTermUuid
}

// Refresh gradebook data
async function refresh(termUuid = selectedTermUuid.value) {
    if (!props.classUuid) {
        return
    }

    loading.value = true
    try {
        const response = await store.dispatch('schoolSetup/academicClass/getGradebook', {
            classUuid: props.classUuid,
            termUuid: termUuid ?? null,
        })

        const normalizedScores = normalizeScores(response.scores || [])
        const normalizedStudents = normalizeStudents(response.students || [], normalizedScores)
        const normalizedAssessments = normalizeAssessments(response.assessments || [])
        const normalizedTerms = normalizeTerms(
            response.terms || [],
            response.active_class_term_uuid || response.activeClassTermUuid
        )
        const rawCategoryConfig = response.category_config || response.categoryConfig || []

        manualTermEnabled.value = !!(response.manualTermEnabled ?? response.manual_term_enabled)
        disableAssessmentsInGradebook.value = !!(response.disableAssessmentsInGradebook ?? response.disable_assessments_in_gradebook)

        terms.value = normalizedTerms
        assessments.value = normalizedAssessments
        students.value = normalizedStudents
        scores.value = normalizedScores
        gradeScales.value = response.gradeScales || response.grade_scales || []
        categoryConfig.value = normalizeCategoryConfig(rawCategoryConfig)
        assessmentLetterEnabled.value = !!(response.assessmentLetterEnabled ?? response.assessment_letter_enabled)
        syncManualOverridesFromStudents(normalizedStudents)

        const resolved = resolveSelectedTermUuid(
            normalizedTerms,
            response.active_class_term_uuid || response.activeClassTermUuid,
            termUuid
        )
        selectedTermUuid.value = resolved

        emit('terms-loaded', normalizedTerms)
        emit('gradebook-loaded', {
            assessments: normalizedAssessments,
            students: normalizedStudents,
            scores: normalizedScores,
            categoryConfig: categoryConfig.value,
            selectedTermUuid: selectedTermUuid.value,
            assessmentLetterEnabled: assessmentLetterEnabled.value,
        })
        emit('manual-term-flag', manualTermEnabled.value)
    } catch (error) {
        console.error('Failed to load gradebook:', error)
        toast.error('Failed to load gradebook data')
        throw error
    } finally {
        loading.value = false
    }
}

// Term selection handler
function selectTerm(termUuid) {
    selectedTermUuid.value = termUuid
    selectedCategoryFilter.value = null // Reset filter when changing terms
    if (termUuid !== 'final') {
        refresh(termUuid).catch(() => { })
    }
}

// Formatting helpers
function formatDate(date) {
    if (!date) return ''
    return new Date(date).toLocaleDateString(undefined, {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })
}

function gradeLetterForScore(score) {
    if (score === null || score === undefined) {
        return ''
    }

    const numeric = Number(score)
    if (!Number.isFinite(numeric)) {
        return ''
    }

    for (const scale of normalizedGradeScales.value) {
        const min = Number.isFinite(scale.min) ? scale.min : 0
        if (numeric >= min) {
            return scale.name
        }
    }

    if (!normalizedGradeScales.value.length) {
        return ''
    }

    return normalizedGradeScales.value[normalizedGradeScales.value.length - 1]?.name ?? ''
}

function scoreForLetter(letter) {
    if (!letter) {
        return null
    }

    const normalizedLetter = String(letter).trim().toLowerCase()

    const matching = normalizedGradeScales.value.find(
        (scale) => scale.name?.toLowerCase() === normalizedLetter
    )

    if (!matching) {
        return null
    }

    if (matching.isFailing || normalizedLetter === 'f') {
        if (Number.isFinite(matching.min)) {
            return Math.max(0, matching.min)
        }
        return 0
    }

    if (Number.isFinite(matching.max)) {
        return matching.max
    }

    if (Number.isFinite(matching.min)) {
        return matching.min
    }

    return null
}

function scoreKey(student, assessment) {
    return `${student.uuid}|${assessment.id}`
}

// Score helpers
function getScoreValue(student, assessment) {
    const numeric = getNumericScore(student, assessment)
    if (assessmentLetterEnabled.value) {
        return gradeLetterForScore(numeric) || ''
    }
    if (numeric === null) {
        return ''
    }
    return numeric
}

function getAssessmentLetterGrade(student, assessment) {
    const numeric = getNumericScore(student, assessment)
    if (numeric === null || numeric === undefined) {
        return '-'
    }
    return gradeLetterForScore(numeric) || '-'
}

function getTermFinalGrade(student, termUuid) {
    const enrollment = (student.enrollments || []).find(
        e => (e.academicClassTermUuid || e.academic_class_term_uuid) === termUuid
    )
    if (!enrollment) {
        return null
    }
    return {
        numeric: enrollment.termGradeNumeric ?? enrollment.term_grade_numeric ?? null,
        letter: enrollment.termGradeLetter ?? enrollment.term_grade_letter ?? null,
    }
}

function getNumericScore(student, assessment) {
    const studentAssessment = (student.assessments || []).find(
        item => (item.assessmentId || item.assessment_id) === assessment.id
    )
    if (studentAssessment) {
        const value = studentAssessment.score
        if (value === null || value === undefined || value === '') {
            return null
        }
        const numeric = Number(value)
        return Number.isNaN(numeric) ? null : numeric
    }

    const existing = scoreMap.value.get(scoreKey(student, assessment))
    if (existing && existing.value !== null && existing.value !== undefined && existing.value !== '') {
        const numeric = Number(existing.value)
        return Number.isNaN(numeric) ? null : numeric
    }

    return null
}

function courseEnrollmentTermId(student) {
    if (!selectedTermUuid.value) return null
    const enrollment = (student.enrollments || []).find(
        e => (e.academicClassTermUuid || e.academic_class_term_uuid) === selectedTermUuid.value
    )
    return enrollment?.id ?? null
}

// Score input handler
async function handleScoreInput(student, assessment, event) {
    const enrollmentTermId = courseEnrollmentTermId(student)

    if (!enrollmentTermId) {
        toast.error('Unable to locate enrollment term for this student.')
        return
    }

    let scoreValue = null
    let status = 'graded'

    if (assessmentLetterEnabled.value) {
        const letter = event.target.value

        if (!letter) {
            scoreValue = null
            status = 'not_submitted'
        } else {
            if (!gradeScaleOptions.value.length) {
                toast.error('No grading scale configured for this class.')
                return
            }

            const numeric = scoreForLetter(letter)
            if (numeric === null || Number.isNaN(numeric)) {
                toast.error('Unable to convert selected grade to numeric value.')
                return
            }

            const maxScore = Number(assessment.max) || 100
            scoreValue = Math.min(maxScore, numeric)
        }
    } else {
        const rawValue = event.target.value
        const parsed = rawValue === '' ? null : Number(rawValue)
        if (parsed !== null && Number.isNaN(parsed)) {
            return
        }
        scoreValue = parsed
        status = parsed === null ? 'not_submitted' : 'graded'
    }

    try {
        await store.dispatch('schoolSetup/academicClass/bulkSaveScores', {
            classUuid: props.classUuid,
            scores: [{
                course_enrollment_term_id: enrollmentTermId,
                class_assessment_id: assessment.id,
                score_earned: scoreValue,
                status,
            }],
        })

        updateScoreState(student.uuid, assessment.id, enrollmentTermId, scoreValue)
    } catch (error) {
        console.error('Failed to save score:', error)
        toast.error('Failed to save score')
    }
}

function updateScoreState(studentUuid, assessmentId, enrollmentTermId, newScore) {
    const existing = scores.value.find(
        (score) =>
            (score.studentUuid || score.student_uuid) === studentUuid &&
            (score.classAssessmentId || score.class_assessment_id) === assessmentId
    )

    if (existing) {
        existing.scoreEarned = newScore
        existing.score_earned = newScore
        existing.courseEnrollmentTermId = enrollmentTermId
        existing.course_enrollment_term_id = enrollmentTermId
    } else {
        scores.value.push({
            id: null,
            studentUuid,
            student_uuid: studentUuid,
            classAssessmentId: assessmentId,
            class_assessment_id: assessmentId,
            courseEnrollmentTermId: enrollmentTermId,
            course_enrollment_term_id: enrollmentTermId,
            scoreEarned: newScore,
            score_earned: newScore,
        })
    }

    const student = students.value.find(s => s.uuid === studentUuid)
    if (!student) return

    const assessmentEntry = student.assessments.find(a => a.assessmentId === assessmentId)
    if (assessmentEntry) {
        assessmentEntry.score = newScore
    } else {
        student.assessments.push({
            assessmentId,
            score: newScore,
        })
    }
}

// Manual override helpers
function manualOverrideKey(student) {
    const termId = courseEnrollmentTermId(student)
    if (!termId) {
        return null
    }
    return String(termId)
}

function manualOverrideValue(student) {
    const key = manualOverrideKey(student)
    if (!key) {
        return ''
    }
    const value = manualOverrides[key]
    return value ?? ''
}

function setManualOverride(student, value) {
    const key = manualOverrideKey(student)
    if (!key) {
        toast.error('Unable to locate enrollment term for this student.')
        return
    }

    if (value === '') {
        manualOverrides[key] = null
        dirtyManualOverrideKeys.add(key)
        return
    }

    const numeric = Number(value)
    if (Number.isNaN(numeric)) {
        return
    }

    manualOverrides[key] = numeric
    dirtyManualOverrideKeys.add(key)
}

function manualOverrideNumeric(student) {
    const key = manualOverrideKey(student)
    if (!key) {
        return undefined
    }
    const value = manualOverrides[key]
    if (value === undefined || value === null || value === '') {
        return undefined
    }
    const numeric = Number(value)
    return Number.isNaN(numeric) ? undefined : numeric
}

function resetManualOverrides() {
    Object.keys(manualOverrides).forEach((key) => {
        delete manualOverrides[key]
    })
    dirtyManualOverrideKeys.clear()
}

function syncManualOverridesFromStudents(studentCollection) {
    if (!manualTermEnabled.value) {
        resetManualOverrides()
        return
    }

    const validKeys = new Set()

    studentCollection.forEach((student) => {
        (student.enrollments || []).forEach((enrollment) => {
            const termId = enrollment.id
            if (!termId) {
                return
            }
            const key = String(termId)
            validKeys.add(key)
            if (dirtyManualOverrideKeys.has(key)) {
                return
            }
            const rawValue = enrollment.termGradeNumeric ?? enrollment.term_grade_numeric
            if (rawValue === null || rawValue === undefined) {
                delete manualOverrides[key]
                return
            }
            const numeric = Number(rawValue)
            if (Number.isNaN(numeric)) {
                return
            }
            manualOverrides[key] = numeric
        })
    })

    Object.keys(manualOverrides).forEach((key) => {
        if (!validKeys.has(key) && !dirtyManualOverrideKeys.has(key)) {
            delete manualOverrides[key]
        }
    })
}

function buildManualOverridePayload() {
    if (!manualTermEnabled.value || dirtyManualOverrideKeys.size === 0) {
        return []
    }

    const payload = []

    dirtyManualOverrideKeys.forEach((key) => {
        const termId = Number(key)
        if (!Number.isFinite(termId)) {
            return
        }

        const stored = manualOverrides[key]
        if (stored === undefined) {
            return
        }

        if (stored === null || stored === '') {
            payload.push({
                course_enrollment_term_id: termId,
                term_grade_numeric: null,
                term_grade_letter: null,
            })
            return
        }

        const numeric = Number(stored)
        if (Number.isNaN(numeric)) {
            return
        }

        payload.push({
            course_enrollment_term_id: termId,
            term_grade_numeric: numeric,
            term_grade_letter: gradeLetterForScore(numeric) || null,
        })
    })

    return payload
}

async function persistManualOverrides() {
    if (!manualTermEnabled.value || !props.classUuid) {
        return
    }

    const payload = buildManualOverridePayload()
    if (!payload.length) {
        return
    }

    await store.dispatch('schoolSetup/academicClass/updateTermGrades', {
        classUuid: props.classUuid,
        grades: payload,
    })

    dirtyManualOverrideKeys.clear()
}

function calculatedTermAveragePercent(student) {
    const configs = weightedCategoryConfig.value
    if (!configs.length) {
        return simpleAveragePercent(student)
    }

    let weightedSum = 0
    let totalWeight = 0

    configs.forEach((config) => {
        const weight = config.weightPercent
        if (weight <= 0) {
            return
        }

        const categoryAssessments = termAssessments.value.filter((assessment) =>
            matchesCategory(assessment, config)
        )

        totalWeight += weight

        if (!categoryAssessments.length) {
            return
        }

        let earned = 0
        let possible = 0

        categoryAssessments.forEach((assessment) => {
            const max = Number(assessment.max) || 0
            if (max <= 0) {
                return
            }

            const score = getNumericScore(student, assessment)
            if (score !== null) {
                earned += score
            }
            possible += max
        })

        if (possible <= 0) {
            return
        }

        const categoryPercent = (earned / possible) * 100
        weightedSum += categoryPercent * weight
    })

    if (totalWeight <= 0) {
        return simpleAveragePercent(student)
    }

    return weightedSum / totalWeight
}

function termAveragePercent(student) {
    if (manualTermEnabled.value) {
        const overrideValue = manualOverrideNumeric(student)
        if (overrideValue !== undefined) {
            return overrideValue
        }
    }

    const calculated = calculatedTermAveragePercent(student)
    if (calculated === null || calculated === undefined || Number.isNaN(calculated)) {
        return 0
    }

    return calculated
}

function simpleAveragePercent(student) {
    const assessmentsForTerm = termAssessments.value
    if (!assessmentsForTerm.length) return 0

    let earned = 0
    let possible = 0

    assessmentsForTerm.forEach((assessment) => {
        const max = Number(assessment.max) || 0
        if (max <= 0) {
            return
        }

        const score = getNumericScore(student, assessment)
        if (score !== null) {
            earned += score
        }
        possible += max
    })

    if (possible <= 0) return 0
    return (earned / possible) * 100
}

function letterFor(student) {
    if (!gradeScales.value || gradeScales.value.length === 0) {
        return '-'
    }

    const overrideKey = manualOverrideKey(student)
    if (manualTermEnabled.value && overrideKey) {
        const manualValue = manualOverrideNumeric(student)
        if (manualValue !== undefined) {
            const manualLetter = gradeLetterForScore(manualValue)
            return manualLetter || '-'
        }
    }

    const percent = calculatedTermAveragePercent(student)

    if (percent === null || percent === undefined || Number.isNaN(percent)) {
        return '-'
    }

    if (percent === 0 && !hasAnyScore(student)) {
        return '-'
    }

    return gradeLetterForScore(percent) || '-'
}

function hasAnyScore(student) {
    return (student.assessments || []).some((assessment) => {
        const score = assessment.score
        return score !== null && score !== undefined && score !== '' && !Number.isNaN(Number(score))
    })
}

function formatPercent(value) {
    if (value === null || value === undefined || Number.isNaN(value)) return '0.0'
    return Number(value).toFixed(1)
}

function formatTermGrade(value) {
    if (value === null || value === undefined || Number.isNaN(value)) return '-'
    return Number(value).toFixed(1) + '%'
}

// Category filter functions
function setCategoryFilter(category) {
    selectedCategoryFilter.value = category
}

// Calculate category average for a student
function categoryAveragePercent(student, categoryName) {
    if (!categoryName) return 0

    const categoryAssessments = termAssessments.value.filter(
        assessment => assessment.category === categoryName
    )

    if (!categoryAssessments.length) return 0

    let earned = 0
    let possible = 0

    categoryAssessments.forEach((assessment) => {
        const max = Number(assessment.max) || 0
        if (max <= 0) {
            return
        }

        const score = getNumericScore(student, assessment)
        if (score !== null) {
            earned += score
        }
        possible += max
    })

    if (possible <= 0) return 0
    return (earned / possible) * 100
}

function matchesCategory(assessment, config) {
    if (config.categoryId && assessment.categoryId && config.categoryId === assessment.categoryId) {
        return true
    }
    if (config.categoryUuid && assessment.categoryUuid && config.categoryUuid === assessment.categoryUuid) {
        return true
    }
    return false
}

// Trigger backend grade recalculation for the class
async function calculateGrades() {
    if (!props.classUuid) return

    calculating.value = true
    try {
        if (manualTermEnabled.value || disableAssessmentsInGradebook.value) {
            await persistManualOverrides()
        }

        await store.dispatch('schoolSetup/academicClass/calculateGrades', {
            classUuid: props.classUuid,
        })
        await refresh(selectedTermUuid.value)
    } catch (error) {
        console.error('Failed to calculate grades:', error)
        toast.error('Failed to calculate grades')
    } finally {
        calculating.value = false
    }
}

// Make refresh and calculate actions available to parents
defineExpose({
    refresh,
    calculateGrades,
})
</script>

<style scoped>
/* Alternating stripes (light mode) using nth-child(of ...) so only data rows are counted */
tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        odd of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(252 253 255) !important; /* custom very light */
}

tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        even of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(222 235 255) !important; /* custom softer blue */
}

/* Alternating stripes (dark mode) */
.dark
    tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        odd of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(18 24 37) !important; /* slightly lighter dark */
}

.dark
    tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        even of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(59 130 246 / 0.25) !important; /* blue-500 @ 25% */
}

/* Remove right border from last column in header */
thead tr th:last-child {
    border-right: none !important;
}

/* Remove right border from last column in body cells */
tbody tr td:last-child {
    border-right: none !important;
}

/* Sticky column background for striped rows */
tbody.striped-rows > tr.data-row > td.sticky,
tbody.striped-rows > tr.data-row > th.sticky {
    background-color: inherit;
}

tbody.striped-rows > tr.data-row:nth-child(odd of tr.data-row) > td.sticky,
tbody.striped-rows > tr.data-row:nth-child(odd of tr.data-row) > th.sticky {
    background-color: rgb(252 253 255) !important;
}

tbody.striped-rows > tr.data-row:nth-child(even of tr.data-row) > td.sticky,
tbody.striped-rows > tr.data-row:nth-child(even of tr.data-row) > th.sticky {
    background-color: rgb(222 235 255) !important;
}

.dark tbody.striped-rows > tr.data-row:nth-child(odd of tr.data-row) > td.sticky,
.dark tbody.striped-rows > tr.data-row:nth-child(odd of tr.data-row) > th.sticky {
    background-color: rgb(18 24 37) !important;
}

.dark tbody.striped-rows > tr.data-row:nth-child(even of tr.data-row) > td.sticky,
.dark tbody.striped-rows > tr.data-row:nth-child(even of tr.data-row) > th.sticky {
    background-color: rgb(59 130 246 / 0.25) !important;
}

/* Letter column overlay styling */
tbody.striped-rows > tr.data-row > td > div.grid > div:last-child {
    background-color: rgb(219 234 254 / 0.6) !important; /* blue-100 at 60% opacity */
}

.dark tbody.striped-rows > tr.data-row > td > div.grid > div:last-child {
    background-color: rgb(30 58 138 / 0.3) !important; /* blue-900 at 30% opacity */
}
</style>
