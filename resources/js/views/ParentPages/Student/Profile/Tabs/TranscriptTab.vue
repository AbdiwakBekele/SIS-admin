<template>
    <div class="bg-white rounded-lg border px-4 py-5 mt-2">
        <!-- Header -->
        <p class="text-sm text-left mx-2 flex items-center justify-between">
            <span>
                <i class="fa-solid fa-calendar-days mx-2"></i>
                <strong>Unofficial Transcript</strong>
            </span>

        <div class="flex items-center gap-2">
            <BaseButton design="primary" size="sm" @click="openHistoryModal"
                title="View official transcript requests">
                Official Transcript Requests
            </BaseButton>

            <BaseButton design="secondary" size="sm" @click="goToPrintPreview" title="Print Unofficial Transcript">
                <i class="fa-solid fa-print mr-1"></i> Print Unofficial Transcript
            </BaseButton>
        </div>
        </p>

        <hr class="my-3 mx-2" />

        <!-- Empty -->
        <div v-if="items.length === 0" class="px-4 py-10 text-center text-gray-500">
            <i class="fa-solid fa-file-circle-exclamation text-2xl mb-2"></i>
            <p>No Transcript Yet.</p>
        </div>

        <!-- List -->
        <div v-else class="space-y-8">
            <div v-for="(blk, idx) in items" :key="blk.key || idx" class="rounded-xl border overflow-hidden mb-6 shadow-md hover:shadow-lg transition-shadow">
                <!-- Block header -->
                <div class="px-4 py-3 bg-gray-200 border-b flex items-center justify-between cursor-pointer select-none hover:bg-gray-100"
                    @click="toggle(blk, idx)">
                    <div class="text-left flex-1">
                        <div class="flex items-center gap-2">
                            <button class="text-gray-700 mr-1" @click.stop="toggle(blk, idx)"
                                :title="isOpen(blk, idx) ? 'Collapse' : 'Expand'">
                                <i :class="['fa-solid', isOpen(blk, idx) ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
                            </button>
                            <div>
                                <div class="font-bold text-lg text-gray-900">
                                    {{ getStudentName() }} · {{ blk.gradeLabel || '—' }}
                                </div>
                                <div class="text-sm text-gray-600 mt-0.5 ml-0 space-y-0.5">
                                    <span>School Year: {{ blk.yearLabel || blk.dateLabel || 'Academic Year' }}</span>
                                    <span class="text-gray-500 ml-2">{{ blk.courses?.length || 0 }} course(s)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Block body -->
                <div v-if="isOpen(blk, idx)">
                    <div class="overflow-x-auto border-t border-gray-200">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-md">
                                    <th class="px-3 py-2 text-left border-b border-blue-700 dark:border-blue-500">Course Title</th>
                                    <th class="px-3 py-2 text-center border-b border-blue-700 dark:border-blue-500">Final Grade</th>
                                    <th class="px-3 py-2 text-center border-b border-blue-700 dark:border-blue-500">Credit Earned</th>
                                    <th class="px-3 py-2 text-center border-b border-blue-700 dark:border-blue-500">GPA Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, ri) in blk.courses" :key="ri" :class="[
                                    'transition-colors',
                                    ri % 2 === 0 ? 'bg-white hover:bg-gray-50' : 'bg-gray-50 hover:bg-gray-100'
                                ]">
                                    <td :class="[
                                        'px-3 py-2 border-b border-gray-200 text-gray-800',
                                        ri % 2 === 0 ? 'bg-white' : 'bg-gray-50'
                                    ]">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <div class="font-medium">{{ row.title }}</div>
                                            <!-- Term grade badges -->
                                            <div v-if="row.termGrades && row.termGrades.length > 0" class="flex items-center gap-1 flex-wrap">
                                                <span 
                                                    v-for="(termGrade, tgIdx) in row.termGrades" 
                                                    :key="tgIdx"
                                                    class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-medium border border-blue-200"
                                                    :title="`${termGrade.termTitle} Grade: ${termGrade.grade}`"
                                                >
                                                    {{ termGrade.termTitle }} Grade: {{ termGrade.grade }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1" v-if="row.courseCode">
                                            {{ row.courseCode }}
                                        </div>
                                    </td>
                                    <td :class="[
                                        'px-3 py-2 border-b border-gray-200 text-center font-semibold',
                                        ri % 2 === 0 ? 'bg-white' : 'bg-gray-50'
                                    ]">
                                        {{ row.final }}
                                    </td>
                                    <td :class="[
                                        'px-3 py-2 border-b border-gray-200 text-center',
                                        ri % 2 === 0 ? 'bg-white' : 'bg-gray-50'
                                    ]">
                                        {{ toFixedOrBlank(row.credits) }}
                                    </td>
                                    <td :class="[
                                        'px-3 py-2 border-b border-gray-200 text-center font-semibold',
                                        ri % 2 === 0 ? 'bg-white' : 'bg-gray-50'
                                    ]">
                                        {{ row.points === '-' ? '-' : toFixedOrBlank(row.points) }}
                                    </td>
                                </tr>

                                <tr v-if="blk.courses.length === 0">
                                    <td colspan="4" class="px-3 py-3 text-center text-xs text-gray-500">
                                        No courses recorded.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GPA Summary Section -->
                    <div v-if="blk.summaryDisplay" class="px-4 py-3 bg-blue-50 border-t border-blue-200">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 font-medium">Credits Earned:</span>
                                <span class="ml-2 font-semibold text-gray-800">{{ blk.summaryDisplay.creditsEarned ?? blk.summaryDisplay.credits_earned ?? '0.00' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">Unweighted GPA:</span>
                                <span class="ml-2 font-bold text-blue-700 text-lg">{{ blk.summaryDisplay.unweightedGpa ?? blk.summaryDisplay.unweighted_gpa ?? '0.00' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">Weighted GPA:</span>
                                <span class="ml-2 font-bold text-blue-800 text-lg">{{ blk.summaryDisplay.weightedGpa ?? blk.summaryDisplay.weighted_gpa ?? '0.00' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Official Transcript Requests History Modal -->
    <BaseModal :visibility="showHistoryModal" size="lg" @close="closeHistoryModal">
        <template #title>Official Transcript Requests</template>
        <div v-if="loadingHistory" class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
        </div>
        <div v-else>
            <div v-if="!requestHistory.length" class="text-center py-6 text-gray-500">
                <i class="fa-solid fa-file-circle-question text-2xl mb-2"></i>
                <p>No transcript requests yet.</p>
            </div>
            <div v-else class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-3 py-2 text-left">Request Date</th>
                            <th class="px-3 py-2 text-left">Recipient Name</th>
                            <th class="px-3 py-2 text-left">Organization</th>
                            <th class="px-3 py-2 text-left">Delivery Method</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="req in requestHistory" :key="req.uuid" class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-2">{{ formatRequestDate(req.requestDate ?? req.request_date ?? req.createdAt ?? req.created_at) }}</td>
                            <td class="px-3 py-2">{{ formatRecipientNames(req.recipients) }}</td>
                            <td class="px-3 py-2">{{ formatRecipientOrganizations(req.recipients) }}</td>
                            <td class="px-3 py-2">{{ formatDeliveryMethods(req.recipients) }}</td>
                            <td class="px-3 py-2">
                                <span :class="getStatusClass(req.status)">{{ statusLabel(req.status) }}</span>
                            </td>
                            <td class="px-3 py-2 text-right">
                                <BaseButton
                                    v-if="isApprovedOrCompleted(req.status)"
                                    design="primary"
                                    size="sm"
                                    @click="downloadTranscriptPdf"
                                    title="Download official transcript (PDF)"
                                    class="p-2"
                                >
                                    <i class="fa-solid fa-download"></i>
                                </BaseButton>
                                <span v-else class="text-gray-400">—</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                <BaseButton design="primary" @click="openCreateModal" title="Create new transcript request">
                    <i class="fa-solid fa-plus mr-1"></i> Create New Transcript Request
                </BaseButton>
            </div>
        </div>
    </BaseModal>

    <!-- Transcript Request Create/Edit Modal -->
    <ParentTranscriptRequestModal
        :visibility="showRequestModal"
        :student="student"
        :existing-request="existingRequest"
        @close="closeRequestModal"
        @saved="handleRequestSaved"
    />
</template>

<script setup>
import { computed, ref, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'
import BaseButton from "@core/components/Forms/BaseButton.vue"
import BaseModal from "@core/components/Ui/BaseModal.vue"
import ParentTranscriptRequestModal from "./ParentTranscriptRequestModal.vue"

const props = defineProps({
    student: { type: Object, required: true },
    reports: { type: Array, default: () => [] },
    gradeLevels: { type: Array, default: () => [] },
    academicCourses: { type: Array, default: () => [] },
    schoolYears: { type: Array, default: () => [] },
    gradingSystems: { type: Array, default: () => [] },
})

const router = useRouter()
const store = useStore()

const showRequestModal = ref(false)
const existingRequest = ref(null)
const showHistoryModal = ref(false)
const requestHistory = ref([])
const loadingHistory = ref(false)

onMounted(() => {
    loadExistingRequest()
})

async function loadExistingRequest() {
    try {
        const response = await store.dispatch("student/transcriptRequest/getByStudent", props.student.uuid)
        existingRequest.value = response.transcriptRequest
    } catch (error) {
        console.error('Failed to load transcript request:', error)
    }
}

function openHistoryModal() {
    showHistoryModal.value = true
    loadHistory()
}

function closeHistoryModal() {
    showHistoryModal.value = false
}

async function loadHistory() {
    loadingHistory.value = true
    requestHistory.value = []
    try {
        const response = await store.dispatch("student/transcriptRequest/getHistoryByStudent", props.student.uuid)
        requestHistory.value = response.transcriptRequests || response.transcript_requests || []
    } catch (error) {
        console.error('Failed to load transcript request history:', error)
    } finally {
        loadingHistory.value = false
    }
}

function openCreateModal() {
    closeHistoryModal()
    existingRequest.value = null
    showRequestModal.value = true
}

function openRequestModal() {
    showRequestModal.value = true
}

function closeRequestModal() {
    showRequestModal.value = false
}

async function handleRequestSaved() {
    await loadExistingRequest()
    await loadHistory()
}

function isApprovedOrCompleted(status) {
    const value = status?.value ?? status
    return value === 'approved' || value === 'completed'
}

function statusLabel(status) {
    if (!status) return '—'
    return status.label ?? status.value ?? status
}

function formatRequestDate(dateInput) {
    if (dateInput == null || dateInput === '') return '—'
    const dateStr = typeof dateInput === 'object' && dateInput !== null
        ? (dateInput.value ?? dateInput.formatted ?? '')
        : String(dateInput)
    if (!dateStr) return '—'
    try {
        const d = new Date(dateStr)
        if (!Number.isFinite(d.getTime())) return dateStr
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
    } catch {
        return dateStr
    }
}

function formatRecipientNames(recipients) {
    if (!Array.isArray(recipients) || recipients.length === 0) return '—'
    const names = recipients.map(r => (r.recipientName ?? r.recipient_name ?? '').trim()).filter(Boolean)
    return names.length ? names.join(', ') : '—'
}

function formatRecipientOrganizations(recipients) {
    if (!Array.isArray(recipients) || recipients.length === 0) return '—'
    const orgs = recipients.map(r => (r.organization ?? '').trim()).filter(Boolean)
    return orgs.length ? orgs.join(', ') : '—'
}

function formatDeliveryMethods(recipients) {
    if (!Array.isArray(recipients) || recipients.length === 0) return '—'
    const methods = recipients.map(r => (r.deliveryMethod ?? r.delivery_method ?? '').trim()).filter(Boolean)
    return methods.length ? [...new Set(methods)].join(', ') : '—'
}

function downloadTranscriptPdf() {
    const url = router.resolve({ name: 'MyChildrenTranscriptPrint', params: { uuid: props.student.uuid } }).href
    window.open(url, '_blank', 'noopener,noreferrer')
}

/** Filter grade levels to only show high school grades (9-12) */
const highSchoolGradeLevels = computed(() => {
    return (props.gradeLevels || []).filter(gl => {
        const gradeNumber = extractGradeNumber(gl.name) || 
                           extractGradeNumber(gl.shortName) || 
                           extractGradeNumber(gl.code) ||
                           extractGradeNumber(gl.displayName) ||
                           toNumberOrNull(gl.sequence) ||
                           toNumberOrNull(gl.level) ||
                           toNumberOrNull(gl.grade) ||
                           toNumberOrNull(gl.numeric)
        return gradeNumber !== null && gradeNumber >= 9 && gradeNumber <= 12
    })
})

/** Get published histories map for filtering */
function getPublishedHistoriesMap() {
    const histories = props.student?.currentSchoolHistories || 
                     props.student?.current_school_histories || 
                     []
    
    const publishedMap = {}
    histories.forEach(history => {
        if (history.isPublished ?? history.is_published) {
            const syUuid = history.schoolYear?.uuid || history.school_year?.uuid
            const glUuid = history.gradeLevel?.uuid || history.grade_level?.uuid
            if (syUuid && glUuid) {
                const key = `${syUuid}_${glUuid}`
                publishedMap[key] = true
            }
        }
    })
    return publishedMap
}

/** Transform API data to UI format (high school grades only) - from CourseEnrollment and PreviousSchool */
const items = computed(() => {
    if (!Array.isArray(props.reports)) {
        return []
    }

    // Get published histories map for filtering
    const publishedMap = getPublishedHistoriesMap()

    // Process reports from CourseEnrollment (same as ReportCardTab)
    const grouped = {}
    
    props.reports.forEach((report) => {
        const schoolYear = report.schoolYear || report.school_year || {}
        const gradeLevel = report.gradeLevel || report.grade_level || {}
        
        // Filter to only high school grades (9th-12th)
        const gradeLevelUuid = gradeLevel.uuid
        const gradeLevelModel = props.gradeLevels.find(gl => gl.uuid === gradeLevelUuid)
        if (!gradeLevelModel) return
        
        const gradeNumber = extractGradeNumber(gradeLevelModel.name) || 
                           extractGradeNumber(gradeLevelModel.shortName) || 
                           extractGradeNumber(gradeLevelModel.code) ||
                           extractGradeNumber(gradeLevelModel.displayName) ||
                           toNumberOrNull(gradeLevelModel.sequence) ||
                           toNumberOrNull(gradeLevelModel.level) ||
                           toNumberOrNull(gradeLevelModel.grade) ||
                           toNumberOrNull(gradeLevelModel.numeric)
        
        // Skip if not high school grade (9-12)
        if (gradeNumber === null || gradeNumber < 9 || gradeNumber > 12) {
            return
        }
        
        // Filter by published status - only include published transcripts
        const schoolYearUuid = schoolYear.uuid
        const groupKey = `${schoolYearUuid || 'unknown'}_${gradeLevelUuid || 'unknown'}`
        if (schoolYearUuid && gradeLevelUuid && !publishedMap[groupKey]) {
            return // Skip unpublished transcripts
        }
        
        if (!grouped[groupKey]) {
            const startDate = schoolYear.startDate ?? schoolYear.start_date
            const sortKey = startDate ? Date.parse(startDate) : 0
            
            grouped[groupKey] = {
                key: groupKey,
                sortKey,
                yearLabel: schoolYear.name || 'School Year',
                gradeLabel: gradeLevel.name || 'Grade Level',
                courses: [],
                meta: {
                    schoolYearUuid: schoolYear.uuid || null,
                    gradeLevelUuid: gradeLevel.uuid || null,
                },
                summary: report.summary || null,
            }
        }
        
        // Get report terms for looking up term titles
        const reportTerms = Array.isArray(report.terms) ? report.terms : []
        const reportTermsMap = {}
        reportTerms.forEach((rt) => {
            const termNo = rt.termNo ?? rt.term_no
            if (termNo !== null) {
                reportTermsMap[termNo] = {
                    title: rt.title || rt.shortTitle || rt.short_title || null,
                }
            }
        })
        
        // Process courses from this report
        const courses = (report.courses || []).map((course) => {
            const final = course.finalGrade || course.final_grade || {}
            const finalGradeString = formatGrade(final.letter, final.numeric)
            let numericGrade = toNumberOrNull(final.numeric ?? course.gradeNumeric ?? course.grade_numeric)
            let gradeLetter = final.letter || null
            
            // Collect term grades for display (as badges)
            const courseTerms = course.terms || []
            const termGradesForDisplay = []
            const termNumericGrades = []
            
            courseTerms.forEach((term) => {
                const termNo = term.termNo ?? term.term_no
                const termLetter = term.gradeLetter ?? term.grade_letter ?? null
                const termNumeric = term.gradeNumeric ?? term.grade_numeric ?? null
                
                if (termNo !== null && (termLetter || termNumeric !== null)) {
                    // Look up term title from report terms, then from course term, then fallback
                    const termTitle = term.termTitle ?? 
                                     term.term_title ?? 
                                     term.title ?? 
                                     reportTermsMap[termNo]?.title ?? 
                                     `Term ${termNo}`
                    
                    const termGradeString = formatGrade(termLetter, termNumeric)
                    termGradesForDisplay.push({
                        termNo: termNo,
                        termTitle: termTitle,
                        grade: termGradeString,
                        letter: termLetter,
                        numeric: termNumeric,
                    })
                    if (termNumeric !== null) {
                        termNumericGrades.push(termNumeric)
                    }
                }
            })
            
            // Sort term grades by term number
            termGradesForDisplay.sort((a, b) => (a.termNo || 0) - (b.termNo || 0))
            
            // Get course_weighting and grading system from academicCourses prop by matching course code or UUID
            // (Do this early so we can use grading system for letter grade conversion)
            const courseCode = course.courseCode || course.course_code || ''
            const courseUuid = course.academicCourseUuid || course.academic_course_uuid || null
            let courseWeighting = null
            let gradingSystem = null
            
            if (courseUuid) {
                const academicCourse = props.academicCourses.find(ac => ac.uuid === courseUuid)
                courseWeighting = academicCourse?.courseWeighting ?? academicCourse?.course_weighting ?? null
                // Get grading system UUID from academic course
                const gradingSystemUuid = academicCourse?.gradingSystemUuid ?? 
                                         academicCourse?.grading_system_uuid ?? 
                                         academicCourse?.gradingSystem?.uuid ??
                                         academicCourse?.grading_system?.uuid ??
                                         null
                if (gradingSystemUuid) {
                    gradingSystem = props.gradingSystems.find(gs => gs.uuid === gradingSystemUuid)
                }
            } else if (courseCode) {
                const academicCourse = props.academicCourses.find(ac => 
                    (ac.code === courseCode) || (ac.name === (course.courseName || course.course_name))
                )
                courseWeighting = academicCourse?.courseWeighting ?? academicCourse?.course_weighting ?? null
                // Get grading system UUID from academic course
                const gradingSystemUuid = academicCourse?.gradingSystemUuid ?? 
                                         academicCourse?.grading_system_uuid ?? 
                                         academicCourse?.gradingSystem?.uuid ??
                                         academicCourse?.grading_system?.uuid ??
                                         null
                if (gradingSystemUuid) {
                    gradingSystem = props.gradingSystems.find(gs => gs.uuid === gradingSystemUuid)
                }
            }
            
            // If final grade is not available, calculate from term grades for GPA calculation only
            // But don't replace the final grade display - keep it as '-' or empty
            if ((!finalGradeString || finalGradeString === '-' || (!final.letter && final.numeric === null)) && termNumericGrades.length > 0) {
                // Determine if quarterly (4 terms) or semester (2 terms) based on number of terms
                const isQuarterly = termGradesForDisplay.length === 4
                
                if (isQuarterly) {
                    // For quarterly: Average Q1+Q2 for first semester, Q3+Q4 for second semester
                    const q1Grade = termGradesForDisplay.find(t => t.termNo === 1)?.numeric
                    const q2Grade = termGradesForDisplay.find(t => t.termNo === 2)?.numeric
                    const q3Grade = termGradesForDisplay.find(t => t.termNo === 3)?.numeric
                    const q4Grade = termGradesForDisplay.find(t => t.termNo === 4)?.numeric
                    
                    const semester1Grades = [q1Grade, q2Grade].filter(g => g !== null && g !== undefined)
                    const semester2Grades = [q3Grade, q4Grade].filter(g => g !== null && g !== undefined)
                    
                    // Calculate average for each semester
                    const semester1Avg = semester1Grades.length > 0 
                        ? semester1Grades.reduce((sum, g) => sum + g, 0) / semester1Grades.length 
                        : null
                    const semester2Avg = semester2Grades.length > 0 
                        ? semester2Grades.reduce((sum, g) => sum + g, 0) / semester2Grades.length 
                        : null
                    
                    // Use the average of both semesters for overall numeric grade
                    const semesterAverages = [semester1Avg, semester2Avg].filter(g => g !== null && g !== undefined)
                    numericGrade = semesterAverages.length > 0 
                        ? semesterAverages.reduce((sum, g) => sum + g, 0) / semesterAverages.length 
                        : null
                } else {
                    // For semester: Calculate average from all term grades
                    numericGrade = termNumericGrades.reduce((sum, g) => sum + g, 0) / termNumericGrades.length
                }
                
                // Convert numeric to letter grade using grading system
                if (numericGrade !== null) {
                    gradeLetter = getLetterFromNumeric(numericGrade, gradingSystem)
                }
            }
            
            // Get credits earned - prioritize course-level credits, fallback to sum of term credits
            let creditsEarned = toNumberOrNull(
                final.creditsEarned ?? 
                final.credits_earned ?? 
                course.creditsEarned ?? 
                course.credits_earned
            )
            
            // If course-level credits not available, try summing term credits
            if ((creditsEarned === null || creditsEarned === 0) && course.terms && course.terms.length > 0) {
                const termCreditsSum = course.terms.reduce((sum, term) => {
                    const termCredits = toNumberOrNull(term.creditsEarned ?? term.credits_earned)
                    return sum + (termCredits || 0)
                }, 0)
                if (termCreditsSum > 0) {
                    creditsEarned = termCreditsSum
                }
            }
            
            // Calculate weighted points based on course_weighting if numeric grade >= 70
            // Only calculate when final grade is available - show "-" for term grades only
            let weightedPoints = null // null means show "-"
            const gradeForGpa = gradeLetter || finalGradeString
            
            // Only calculate GPA Points if final grade is available
            if (creditsEarned > 0 && finalGradeString && finalGradeString !== '-' && final.letter) {
                const baseGradePoints = getBasePointsFromGrade(gradeForGpa)
                const gradeStr = String(gradeForGpa).trim().toUpperCase()
                const countsTowardGpa = baseGradePoints !== null && gradeStr !== 'F'
                
                if (countsTowardGpa) {
                    let additionalWeight = 0
                    if (numericGrade !== null && numericGrade >= 70 && courseWeighting) {
                        if (courseWeighting === 'Honors') {
                            additionalWeight = 0.5
                        } else if (courseWeighting === 'DE' || courseWeighting === 'AP') {
                            additionalWeight = 1.0
                        }
                    }
                    
                    const weightedGradeValue = baseGradePoints + additionalWeight
                    weightedPoints = weightedGradeValue * creditsEarned
                }
            }

            return {
                title: course.courseName || course.course_name || 'Course',
                courseCode: courseCode,
                final: finalGradeString, // Keep original final grade (or '-' if not available)
                numericGrade: numericGrade,
                gradeLetter: gradeLetter, // Store calculated letter grade (from final or calculated from terms)
                courseWeighting: courseWeighting,
                credits: creditsEarned || 0,
                points: weightedPoints !== null ? weightedPoints : '-', // Show "-" if no final grade
                termGrades: termGradesForDisplay, // Store term grades for display as badges
            }
        })
        
        // Merge courses into the group
        grouped[groupKey].courses.push(...courses)
    })
    
    // Process PreviousSchool records (filtered to high school grades)
    const previousSchoolHistories = props.student?.previousSchoolHistories || 
                                    props.student?.previous_school_histories || 
                                    []
    
    previousSchoolHistories.forEach((history) => {
        const gradeLevelUuid = history.gradeLevel?.uuid || 
                               history.grade_level?.uuid || 
                               history.grade_level_uuid
        
        if (!gradeLevelUuid) return
        
        const gradeLevelModel = props.gradeLevels.find(gl => gl.uuid === gradeLevelUuid)
        if (!gradeLevelModel) return
        
        const gradeNumber = extractGradeNumber(gradeLevelModel.name) || 
                           extractGradeNumber(gradeLevelModel.shortName) || 
                           extractGradeNumber(gradeLevelModel.code) ||
                           extractGradeNumber(gradeLevelModel.displayName) ||
                           toNumberOrNull(gradeLevelModel.sequence) ||
                           toNumberOrNull(gradeLevelModel.level) ||
                           toNumberOrNull(gradeLevelModel.grade) ||
                           toNumberOrNull(gradeLevelModel.numeric)
        
        // Skip if not high school grade (9-12)
        if (gradeNumber === null || gradeNumber < 9 || gradeNumber > 12) {
            return
        }
        
        // Create a group key for previous school (use a placeholder school year or create one)
        // Since previous school doesn't have a school year, we'll use the grade level as the key
        const groupKey = `previous_${gradeLevelUuid}`
        
        if (!grouped[groupKey]) {
            grouped[groupKey] = {
                key: groupKey,
                sortKey: 0, // Previous school records sort to the end
                yearLabel: history.previousSchoolName || history.previous_school_name || 'Previous School',
                gradeLabel: gradeLevelModel.name || 'Grade Level',
                courses: [],
                meta: {
                    schoolYearUuid: null,
                    gradeLevelUuid: gradeLevelUuid,
                    isPreviousSchool: true,
                },
            }
        }
        
        // Process previous school courses
        const previousCourses = history.previousSchoolCourses || 
                               history.previous_school_courses || 
                               []
        
        const courses = previousCourses.map((course) => {
            const finalGrade = course.finalGrade || course.final_grade || ''
            const creditsEarned = toNumberOrNull(
                course.auditCreditEquivalency ?? 
                course.audit_credit_equivalency ?? 
                course.previousSchoolCredit ?? 
                course.previous_school_credit ??
                0
            )

                return {
                title: course.previousCourseTitle || course.previous_course_title || 'Course',
                courseCode: '',
                final: finalGrade || '-',
                credits: creditsEarned || 0,
                points: 0, // Previous school courses don't have GPA points
            }
        })
        
        grouped[groupKey].courses.push(...courses)
    })
    
    // Convert to array and calculate summary for each group
    const result = Object.values(grouped)
        .map(group => {
            // Use summary from report if available, otherwise calculate
            let summaryData = group.summary || {}
            
            // Calculate from courses if summary not available
            let totalCreditsEarned = 0
            let totalWeightedPoints = 0
            let totalUnweightedPoints = 0
            let gpaCredits = 0
            
            group.courses.forEach(course => {
                const credits = course.credits || 0
                
                totalCreditsEarned += credits
                
                // Include courses that count toward GPA (passing grades, not F)
                // Use final grade if available, otherwise calculate from term grades for GPA summary
                const hasFinalGrade = course.final && course.final !== '-'
                const hasTermGrades = course.termGrades && course.termGrades.length > 0
                const hasValidGrade = hasFinalGrade || (hasTermGrades && course.numericGrade !== null)
                
                if (credits > 0 && hasValidGrade) {
                    // Determine grade for GPA calculation - use final if available, otherwise use calculated from terms
                    let gradeForGpa = course.final
                    if (!hasFinalGrade && hasTermGrades && course.numericGrade !== null) {
                        // Use the stored gradeLetter (already calculated using grading system in course processing)
                        // Fallback to recalculating if not available
                        gradeForGpa = course.gradeLetter || getLetterFromNumeric(course.numericGrade) || String(course.numericGrade)
                    }
                    
                    const basePoints = getBasePointsFromGrade(gradeForGpa)
                    // Only count toward GPA if not F grade (F returns 0.0, but we want to exclude it)
                    const gradeStr = String(gradeForGpa).trim().toUpperCase()
                    const countsTowardGpa = basePoints !== null && gradeStr !== 'F'
                    
                    if (countsTowardGpa) {
                        // Calculate weighted points for GPA summary (even if GPA Points column shows "-")
                        let calculatedPoints = 0
                        if (hasFinalGrade && course.points !== '-' && course.points !== null) {
                            // Use the calculated points from final grade
                            calculatedPoints = Number(course.points) || 0
                        } else if (hasTermGrades && course.numericGrade !== null) {
                            // Calculate from term grades for GPA summary
                            const termNumericGrades = course.termGrades
                                .map(tg => tg.numeric)
                                .filter(n => n !== null && n !== undefined)
                            
                            if (termNumericGrades.length > 0) {
                                const isQuarterly = course.termGrades.length === 4
                                
                                if (isQuarterly) {
                                    // For quarterly: Average Q1+Q2 for semester 1, Q3+Q4 for semester 2
                                    const q1Grade = course.termGrades.find(t => t.termNo === 1)?.numeric
                                    const q2Grade = course.termGrades.find(t => t.termNo === 2)?.numeric
                                    const q3Grade = course.termGrades.find(t => t.termNo === 3)?.numeric
                                    const q4Grade = course.termGrades.find(t => t.termNo === 4)?.numeric
                                    
                                    const semester1Grades = [q1Grade, q2Grade].filter(g => g !== null && g !== undefined)
                                    const semester2Grades = [q3Grade, q4Grade].filter(g => g !== null && g !== undefined)
                                    
                                    const semester1Avg = semester1Grades.length > 0 
                                        ? semester1Grades.reduce((sum, g) => sum + g, 0) / semester1Grades.length 
                                        : null
                                    const semester2Avg = semester2Grades.length > 0 
                                        ? semester2Grades.reduce((sum, g) => sum + g, 0) / semester2Grades.length 
                                        : null
                                    
                                    const semesterCredits = credits / 2
                                    
                                    [semester1Avg, semester2Avg].forEach((semAvg) => {
                                        if (semAvg !== null && semAvg !== undefined) {
                                            const semLetter = getLetterFromNumeric(semAvg, null)
                                            if (semLetter) {
                                                const semBasePoints = getBasePointsFromGrade(semLetter)
                                                const semGradeStr = String(semLetter).trim().toUpperCase()
                                                if (semBasePoints !== null && semGradeStr !== 'F') {
                                                    let additionalWeight = 0
                                                    if (semAvg >= 70 && course.courseWeighting) {
                                                        if (course.courseWeighting === 'Honors') {
                                                            additionalWeight = 0.5
                                                        } else if (course.courseWeighting === 'DE' || course.courseWeighting === 'AP') {
                                                            additionalWeight = 1.0
                                                        }
                                                    }
                                                    const weightedGradeValue = semBasePoints + additionalWeight
                                                    calculatedPoints += weightedGradeValue * semesterCredits
                                                }
                                            }
                                        }
                                    })
                                } else {
                                    // For semester: Calculate for each term
                                    const termCredits = credits / course.termGrades.length
                                    
                                    course.termGrades.forEach((termGrade) => {
                                        if (termGrade.numeric !== null && termGrade.numeric !== undefined) {
                                            const termLetter = termGrade.letter || getLetterFromNumeric(termGrade.numeric, null)
                                            if (termLetter) {
                                                const termBasePoints = getBasePointsFromGrade(termLetter)
                                                const termGradeStr = String(termLetter).trim().toUpperCase()
                                                if (termBasePoints !== null && termGradeStr !== 'F') {
                                                    let additionalWeight = 0
                                                    if (termGrade.numeric >= 70 && course.courseWeighting) {
                                                        if (course.courseWeighting === 'Honors') {
                                                            additionalWeight = 0.5
                                                        } else if (course.courseWeighting === 'DE' || course.courseWeighting === 'AP') {
                                                            additionalWeight = 1.0
                                                        }
                                                    }
                                                    const weightedGradeValue = termBasePoints + additionalWeight
                                                    calculatedPoints += weightedGradeValue * termCredits
                                                }
                                            }
                                        }
                                    })
                                }
                            }
                        }
                        
                        totalWeightedPoints += calculatedPoints
                        totalUnweightedPoints += basePoints * credits
                        gpaCredits += credits
                    }
                }
            })
            
            // Always recalculate from courses to ensure course_weighting is included
            // Use frontend-calculated credits earned (can use backend as fallback if frontend is 0)
            const creditsEarned = totalCreditsEarned > 0 ? totalCreditsEarned : (summaryData.creditsEarned ?? summaryData.credits_earned ?? 0)
            // Always recalculate weighted GPA from frontend-calculated weighted points
            // Use gpaCredits (credits that count toward GPA - excluding F grades) as denominator
            const weightedGpa = gpaCredits > 0 ? (totalWeightedPoints / gpaCredits) : 0
            // Always recalculate unweighted GPA from frontend-calculated unweighted points
            const unweightedGpa = gpaCredits > 0 ? (totalUnweightedPoints / gpaCredits) : 0
            // Always use recalculated weighted grade points
            const weightedGradePoints = totalWeightedPoints

            return {
                ...group,
                summaryDisplay: {
                    creditsEarned: creditsEarned > 0 ? Number(creditsEarned).toFixed(2) : '0.00',
                    weightedGpa: Number(weightedGpa).toFixed(2),
                    unweightedGpa: Number(unweightedGpa).toFixed(2),
                    weightedGradePoints: weightedGradePoints > 0 ? Number(weightedGradePoints).toFixed(3) : '0.000',
                },
                gradeLevelSortValue: getGradeLevelSortValue(group.meta?.gradeLevelUuid),
            }
        })
        .sort((a, b) => {
            // First sort by grade level (descending - highest first)
            const gradeDiff = (b.gradeLevelSortValue ?? 0) - (a.gradeLevelSortValue ?? 0)
            if (gradeDiff !== 0) {
                return gradeDiff
            }
            // Then sort by school year (descending - most recent first)
            // Previous school records (sortKey = 0) go to the end
            return (b.sortKey ?? 0) - (a.sortKey ?? 0)
        })
    
    return result
})

function getGradeLevelSortValue(gradeLevelUuid) {
    if (!gradeLevelUuid) return 0
    
    const gradeLevel = props.gradeLevels.find(gl => gl.uuid === gradeLevelUuid)
    if (!gradeLevel) return 0
    
    const code = gradeLevel.code || ''
    const codeMatch = code.match(/\d+/)
    if (codeMatch) {
        return parseInt(codeMatch[0], 10)
    }
    
    const name = gradeLevel.name || ''
    const nameMatch = name.match(/\d+/)
    if (nameMatch) {
        return parseInt(nameMatch[0], 10)
    }
    
    return 0
}

function formatGrade(letter, numeric) {
    if (letter && String(letter).trim() !== '') {
        return letter
    }
    if (numeric !== null && numeric !== undefined && numeric !== '') {
        return Number(numeric).toFixed(2).replace(/\.00$/, '')
    }
    return '-'
}

function toNumberOrNull(value) {
    if (value === null || value === undefined || value === '') {
        return null
    }
    const n = Number(value)
    return Number.isFinite(n) ? n : null
}

/** Accordion open/close (default open first) */
const openSet = ref(new Set())
const firstKey = computed(() => (items.value[0] ? keyFor(items.value[0], 0) : null))
watch(
    firstKey,
    (k) => {
        if (k && openSet.value.size === 0) openSet.value.add(k)
    },
    { immediate: true },
)

function keyFor(blk, idx) {
    return blk?.key != null ? blk.key : idx
}
function isOpen(blk, idx) {
    return openSet.value.has(keyFor(blk, idx))
}
function toggle(blk, idx) {
    const k = keyFor(blk, idx)
    if (openSet.value.has(k)) openSet.value.delete(k)
    else openSet.value.add(k)
}

/** Helpers */
function toFixedOrBlank(v) {
    if (v === null || v === undefined || v === '') return ''
    const n = Number(v)
    return Number.isFinite(n) ? n.toFixed(2).replace(/\.00$/, '') : ''
}

function formatGpaValue(value) {
    if (value === null || value === undefined || value === '') return '-'
    const n = Number(value)
    if (!Number.isFinite(n)) return '-'
    if (n % 1 === 0) {
        return n.toFixed(0)
    }
    return n.toFixed(2).replace(/\.00$/, '')
}

function formatUnweightedGpa(value) {
    if (value === null || value === undefined || value === '') return '0.00'
    const n = Number(value)
    if (!Number.isFinite(n)) return '0.00'
    // Always format GPA with exactly 2 decimal places
    return n.toFixed(2)
}

function getBasePointsFromGrade(grade) {
    if (!grade) return null
    const gradeStr = String(grade).trim().toUpperCase()
    const gradeMap = {
        'A+': 4.0, 'A': 4.0, 'A-': 3.7,
        'B+': 3.3, 'B': 3.0, 'B-': 2.7,
        'C+': 2.3, 'C': 2.0, 'C-': 1.7,
        'D+': 1.3, 'D': 1.0, 'D-': 0.7,
        'F': 0.0
    }
    return gradeMap[gradeStr] ?? null
}

function getLetterFromNumeric(numeric, gradingSystem = null) {
    if (numeric === null || numeric === undefined) return null
    const num = Number(numeric)
    if (!Number.isFinite(num)) return null
    
    // If grading system is provided, use its grade scales
    if (gradingSystem) {
        const gradeScales = gradingSystem.gradeScales || gradingSystem.grade_scales || []
        if (gradeScales.length > 0) {
            // Sort scales by min_score ascending
            const sortedScales = [...gradeScales].sort((a, b) => {
                const minA = Number(a.min_score || a.minScore || 0)
                const minB = Number(b.min_score || b.minScore || 0)
                return minA - minB
            })
            
            // Find matching grade scale
            for (let i = 0; i < sortedScales.length; i++) {
                const scale = sortedScales[i]
                const minScore = Number(scale.min_score || scale.minScore || 0)
                const maxScore = Number(scale.max_score || scale.maxScore || 100)
                
                if (i < sortedScales.length - 1) {
                    // Not the last scale - check up to (but not including) next scale's min_score
                    const nextScale = sortedScales[i + 1]
                    const nextMinScore = Number(nextScale.min_score || nextScale.minScore || 0)
                    if (num >= minScore && num < nextMinScore) {
                        return scale.name || scale.letter || null
                    }
                } else {
                    // Last scale - includes max_score
                    if (num >= minScore && num <= maxScore) {
                        return scale.name || scale.letter || null
                    }
                }
            }
        }
    }
    
    // Fallback to hardcoded values if no grading system or no matching scale found
    if (num >= 97) return 'A+'
    if (num >= 93) return 'A'
    if (num >= 90) return 'A-'
    if (num >= 87) return 'B+'
    if (num >= 83) return 'B'
    if (num >= 80) return 'B-'
    if (num >= 77) return 'C+'
    if (num >= 73) return 'C'
    if (num >= 70) return 'C-'
    if (num >= 67) return 'D+'
    if (num >= 63) return 'D'
    if (num >= 60) return 'D-'
    return 'F'
}

function getStudentName() {
    const student = props.student
    if (!student) return ''
    const firstName = student.firstName || student.first_name || ''
    const middleName = student.middleName || student.middle_name || ''
    const lastName = student.lastName || student.last_name || ''
    const fullName = [firstName, middleName, lastName].filter(Boolean).join(' ')
    if (fullName) return fullName
    return student.name || ''
}

/** Actions */
function goToPrintPreview() {
    router.push({
        name: 'MyChildrenTranscriptPrint',
        params: { uuid: props.student.uuid },
    })
}

function printItem(blk) {
    const cacheKey = cacheKeyFor(props.student.uuid)
    // store current rendered blocks so Report.vue can optionally read them
    sessionStorage.setItem(cacheKey, JSON.stringify(items.value))

    router.push({
        name: 'StudentTranscriptReport',
        params: { uuid: props.student.uuid },
        query: {
            sy: blk.meta?.schoolYearUuid || '',
            gl: blk.meta?.gradeLevelUuid || '',
        },
        state: { block: blk, cacheKey },
    })
}

function getStatusClass(status) {
    if (!status) return 'text-gray-500'
    const statusValue = status.value || status
    const colorMap = {
        'requested': 'text-blue-600',
        'in_progress': 'text-yellow-600',
        'approved': 'text-green-600',
        'rejected': 'text-red-600',
        'completed': 'text-green-600',
    }
    return colorMap[statusValue] || 'text-gray-500'
}

function cacheKeyFor(uuid) {
    return `tr_blocks_${uuid}`
}

function extractGradeNumber(value) {
    if (value === null || value === undefined || value === '') {
        return null
    }
    const match = String(value).match(/\d+/)
    return match ? Number(match[0]) : null
}
</script>
