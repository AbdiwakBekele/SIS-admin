<template>
    <div class="bg-white rounded-lg border px-4 py-5 mt-2">
        <p class="text-sm text-left mx-2 flex items-center justify-between">
            <span>
                <i class="fa-solid fa-calendar-days mx-2"></i>
                <strong>Report card Info</strong>
            </span>
        </p>

        <hr class="my-3 mx-2" />

        <div v-if="items.length === 0" class="pr-6 py-5 text-center">
            <i class="fa-solid fa-file-circle-exclamation text-2xl mb-2 text-gray-500"></i>
            <p class="text-gray-500">No Report Card Yet.</p>
        </div>

        <div v-else class="space-y-8">
            <div v-for="(blk, idx) in items" :key="blk.key || idx" class="rounded-xl border overflow-hidden mb-6 shadow-md hover:shadow-lg transition-shadow">
                <!-- Header -->
                <div
                    class="px-4 py-3 bg-gray-200 border-b flex items-center justify-between cursor-pointer select-none hover:bg-gray-100"
                    @click="toggle(blk, idx)"
                >
                    <div class="text-left flex-1">
                        <div class="flex items-center gap-2">
                            <button
                                class="text-gray-700 mr-1"
                                @click.stop="toggle(blk, idx)"
                                :title="isOpen(blk, idx) ? 'Collapse' : 'Expand'"
                            >
                                <i :class="['fa-solid', isOpen(blk, idx) ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
                            </button>
                            <div>
                                <div class="font-bold text-lg text-gray-900">
                                    {{ getStudentName() }} · {{ blk.gradeLabel }}
                                </div>
                                <div class="text-sm text-gray-600 mt-0.5 ml-0 space-y-0.5">
                                    <span>School Year: {{ blk.yearLabel || 'Academic Year' }}</span>
                                    <span class="text-gray-500 ml-2">{{ blk.courses.length }} course(s)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Body -->
                <div v-if="isOpen(blk, idx)">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-md">
                                    <th class="px-3 py-2 text-left border-b border-blue-700 dark:border-blue-500">Course Title</th>

                                    <!-- Dynamic term headers -->
                                    <th v-for="(term, termIdx) in (blk.terms || [])" :key="term.termNo" :class="[
                                            'px-3 py-2 text-center border-b border-blue-700 dark:border-blue-500 relative',
                                            termIdx % 2 === 0 ? 'bg-blue-600/90' : 'bg-blue-500/90'
                                        ]" colspan="2">
                                        <div>{{ term.shortTitle || term.title }}</div>
                                        <div v-if="termIdx < (blk.terms?.length || 0) - 1"
                                            class="absolute top-2 bottom-2 right-0 w-px bg-gray-200/40"></div>
                                    </th>

                                    <th class="px-3 py-2 text-center border-b border-green-800 bg-green-800 text-white shadow-sm uppercase font-semibold"
                                        colspan="2">
                                        Final
                                    </th>
                                </tr>
                                <tr class="bg-blue-500/80 text-white text-xs">
                                    <th class="px-3 py-2 text-left border-b border-blue-700 dark:border-blue-500"></th>

                                    <!-- Dynamic term sub-headers -->
                                    <template v-for="(term, termIdx) in (blk.terms || [])" :key="'sub-' + term.termNo">
                                        <th :class="[
                                            'px-3 py-2 text-left border-b border-blue-700 dark:border-blue-500 relative',
                                            termIdx % 2 === 0 ? 'bg-blue-500/80' : 'bg-blue-400/80'
                                        ]">
                                            Grade
                                            <div v-if="termIdx < (blk.terms?.length || 0) - 1"
                                                class="absolute top-2 bottom-2 right-0 w-px bg-blue-600/60"></div>
                                        </th>
                                        <th :class="[
                                            'px-3 py-2 text-center border-b border-blue-700 dark:border-blue-500 relative',
                                            termIdx % 2 === 0 ? 'bg-blue-500/80' : 'bg-blue-400/80'
                                        ]">
                                            Numeric Grade
                                            <div v-if="termIdx < (blk.terms?.length || 0) - 1"
                                                class="absolute top-2 bottom-2 right-0 w-px bg-blue-600/60"></div>
                                        </th>
                                    </template>

                                    <th class="px-3 py-2 text-left border-b border-green-800 bg-green-700 text-white shadow-sm uppercase font-semibold">Final Grade</th>
                                    <th class="px-3 py-2 text-center border-b border-green-800 bg-green-700 text-white shadow-sm uppercase font-semibold">Numeric Grade</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(course, i) in blk.courses" :key="course.title + i" :class="[
                                    'transition-colors',
                                    i % 2 === 0 ? 'bg-white hover:bg-gray-50' : 'bg-gray-50 hover:bg-gray-100'
                                ]">
                                    <td :class="[
                                        'px-3 py-2 border-b text-gray-800',
                                        i % 2 === 0 ? 'bg-white' : 'bg-gray-50'
                                    ]">
                                        <div class="font-medium">{{ course.title }}</div>
                                        <div class="text-xs text-gray-500" v-if="course.courseCode">
                                            {{ course.courseCode }}
                                        </div>
                                    </td>

                                    <!-- Dynamic term data -->
                                    <template v-for="(term, termIdx) in blk.terms" :key="'data-' + term.termNo">
                                        <td :class="[
                                            'px-3 py-2 border-b text-left font-medium relative',
                                            termIdx % 2 === 0
                                                ? (i % 2 === 0 ? 'bg-white' : 'bg-gray-50')
                                                : (i % 2 === 0 ? 'bg-gray-100/80' : 'bg-gray-200/70')
                                        ]">
                                            {{ getTermGrade(course, term.termNo) }}
                                            <div v-if="termIdx < (blk.terms?.length || 0) - 1"
                                                class="absolute top-1 bottom-1 right-0 w-px bg-gray-200/60"></div>
                                        </td>
                                        <td :class="[
                                            'px-3 py-2 border-b text-center relative',
                                            termIdx % 2 === 0
                                                ? (i % 2 === 0 ? 'bg-white' : 'bg-gray-50')
                                                : (i % 2 === 0 ? 'bg-gray-100/80' : 'bg-gray-200/70')
                                        ]">
                                            {{ getTermNumericGrade(course, term.termNo) }}
                                            <div v-if="termIdx < (blk.terms?.length || 0) - 1"
                                                class="absolute top-1 bottom-1 right-0 w-px bg-gray-200/60"></div>
                                        </td>
                                    </template>

                                    <!-- Final Grade Column (Green) -->
                                    <td :class="[
                                        'px-3 py-2 border-b border-green-700 text-left font-semibold bg-green-200 shadow-sm'
                                    ]">
                                        {{ (course.final?.letter && course.final.letter.trim() !== '') ? course.final.letter : (course.final?.grade || '-') }}
                                    </td>
                                    <!-- Numeric Grade column for both high school and non-high school -->
                                    <td :class="[
                                        'px-3 py-2 border-b border-green-700 text-center font-semibold bg-green-200 shadow-sm'
                                    ]">
                                        <template v-if="course.final?.numeric != null">
                                            {{ toFixedOrBlank(course.final.numeric) !== '-' ? toFixedOrBlank(course.final.numeric) : '-' }}
                                        </template>
                                        <template v-else>
                                            -
                                        </template>
                                    </td>
                                </tr>

                                <tr v-if="blk.courses.length === 0">
                                    <td :colspan="(blk.terms?.length || 0) * 2 + 3"
                                        class="px-3 py-3 text-center text-xs text-gray-500">
                                        No courses recorded.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Attendance Section - Only if config enabled -->
                    <div v-if="false" class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fa-solid fa-calendar-check text-blue-600"></i>
                            <span class="text-sm font-semibold text-gray-700">Attendance</span>
                        </div>
                        <div v-if="blk.attendance && (blk.attendance.byType || blk.attendance.by_type) && Object.keys(blk.attendance.byType || blk.attendance.by_type || {}).length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                            <div v-for="(count, typeName) in (blk.attendance.byType || blk.attendance.by_type || {})" :key="typeName">
                                <span class="text-gray-600">{{ typeName }}:</span>
                                <span class="ml-1 font-semibold text-gray-800">{{ count }}</span>
                            </div>
                        </div>
                        <div v-else class="grid grid-cols-2 md:grid-cols-6 gap-3 text-xs">
                            <div>
                                <span class="text-gray-600">Total Days:</span>
                                <span class="ml-1 font-semibold text-gray-800">{{ blk.attendance?.totalDays ?? blk.attendance?.total_days ?? 0 }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Present:</span>
                                <span class="ml-1 font-semibold text-green-700">{{ blk.attendance?.present ?? 0 }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Absent:</span>
                                <span class="ml-1 font-semibold text-red-700">{{ blk.attendance?.absent ?? 0 }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Late:</span>
                                <span class="ml-1 font-semibold text-yellow-700">{{ blk.attendance?.late ?? 0 }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Excused:</span>
                                <span class="ml-1 font-semibold text-blue-700">{{ blk.attendance?.excused ?? 0 }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Unexcused:</span>
                                <span class="ml-1 font-semibold text-orange-700">{{ blk.attendance?.unexcused ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <div v-if="blk.comments || getCourseCommentRows(blk).length" class="px-4 py-3 bg-gray-50 border-t text-sm text-gray-700">
                        <p class="font-semibold mb-2">Comments</p>
                        <div v-if="getCourseCommentRows(blk).length" class="space-y-2 mb-3">
                            <div v-for="(row, idx) in getCourseCommentRows(blk)" :key="idx" class="text-gray-800">
                                <div class="font-medium text-gray-700">{{ row.courseName }}</div>
                                <div class="ml-0 mt-0.5 text-gray-600">
                                    <template v-for="(tc, ti) in row.termComments" :key="ti">
                                        <span v-if="ti > 0" class="mx-2 text-gray-400">·</span>
                                        <span><span class="font-bold text-gray-700">{{ tc.termLabel }}:</span> {{ tc.comment }}</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div v-if="blk.comments" :class="getCourseCommentRows(blk).length ? 'pt-2 border-t border-gray-200' : ''">
                            <span class="whitespace-pre-wrap">{{ blk.comments }}</span>
                        </div>
                    </div>
                </div>
                <!-- /Body -->
            </div>
        </div>

        <!-- Report Card Comments Modal (View Only) -->
        <ReportCardCommentsModal
            :visibility="showCommentsModal"
            :history-uuid="commentHistoryUuid"
            :comments="commentContent"
            :readonly="true"
            @close="showCommentsModal = false"
        />
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue"
import { useRouter } from "vue-router"
import BaseButton from "@core/components/Forms/BaseButton.vue"
import ReportCardCommentsModal from "@core/components/Student/ReportCardCommentsModal.vue"
import { getConfig } from "@core/helpers/action"

const props = defineProps({
    student: Object,
    reports: Array,
    schoolYears: Array,
    gradeLevels: Array,
    academicCourses: Array,
    gradingSystems: Array,
})

const router = useRouter()

const showCommentsModal = ref(false)
const commentHistoryUuid = ref(null)
const commentContent = ref('')

// Check if attendance should be displayed
const shouldDisplayAttendance = computed(() => {
    return false // Hidden for now
})

function getCurrentSchoolHistoryForReport(report, schoolYearUuid, gradeLevelUuid) {
    if (!props.student?.currentSchoolHistories || !Array.isArray(props.student.currentSchoolHistories)) {
        return null
    }

    if (!schoolYearUuid || !gradeLevelUuid) {
        return null
    }

    return props.student.currentSchoolHistories.find(h => {
        const syUuid = h.schoolYear?.uuid || h.school_year?.uuid
        const glUuid = h.gradeLevel?.uuid || h.grade_level?.uuid
        return syUuid === schoolYearUuid && glUuid === gradeLevelUuid
    }) || null
}

function handleViewComments(blk) {
    commentHistoryUuid.value = blk.historyUuid || null
    commentContent.value = blk.comments || ''
    showCommentsModal.value = true
}

// Transform API data to UI format - Group by SchoolYear + GradeLevel
const items = computed(() => {
    if (!Array.isArray(props.reports)) {
        return []
    }

    if (props.reports.length === 0) {
        return []
    }

    // First, process all reports and group by SchoolYear + GradeLevel
    const grouped = {}
    
    props.reports.forEach((report) => {
        const schoolYear = report.schoolYear || report.school_year || {}
        const gradeLevel = report.gradeLevel || report.grade_level || {}
        const terms = Array.isArray(report.terms) ? report.terms : []
        
        // Create unique key for grouping
        const groupKey = `${schoolYear.uuid || 'unknown'}_${gradeLevel.uuid || 'unknown'}`
        
        if (!grouped[groupKey]) {
            const startDate = schoolYear.startDate ?? schoolYear.start_date
            const sortKey = startDate ? Date.parse(startDate) : 0

            grouped[groupKey] = {
                key: groupKey,
                sortKey,
                yearLabel: schoolYear.name || 'School Year',
                gradeLabel: gradeLevel.name || 'Grade Level',
                terms: terms.map((term) => ({
                    uuid: term.uuid,
                    termNo: term.termNo ?? term.term_no,
                    title: term.title,
                    shortTitle: term.shortTitle ?? term.short_title ?? term.title,
                })),
                courses: [],
                meta: {
                    schoolYearUuid: schoolYear.uuid || null,
                    gradeLevelUuid: gradeLevel.uuid || null,
                },
                historyUuid: null,
                isPublished: true, // All reports from API are published (filtered by backend)
                summary: report.summary || null,
                attendance: report.attendance || null,
                comments: report.comments || null,
            }
        }
        
        // Process courses from this report
        const courses = (report.courses || []).map((course) => {
            const termMap = {}
            const courseTerms = course.terms || []
            
            // First, create entries for all terms in the report (to ensure all terms are displayed)
            const reportTerms = terms || []
            reportTerms.forEach((reportTerm) => {
                const termNo = reportTerm.termNo ?? reportTerm.term_no
                if (termNo != null) {
                termMap[termNo] = {
                    grade: '-',
                    credits: null,
                    creditsAttempted: null,
                    numeric: null,
                    comment: null,
                }
                }
            })
            
            // Then, update with actual course term data
            courseTerms.forEach((term) => {
                const termNo = term.termNo ?? term.term_no
                if (termNo == null) {
                    return
                }

                // Prioritize letter grade - get from both camelCase and snake_case
                const termLetter = term.gradeLetter ?? term.grade_letter ?? null
                const termNumeric = term.gradeNumeric ?? term.grade_numeric ?? null
                
                // Use formatGrade which prioritizes letter over numeric
                const termGrade = formatGrade(termLetter, termNumeric)

                termMap[termNo] = {
                    grade: termGrade,
                    credits: term.creditsEarned ?? term.credits_earned ?? null,
                    creditsAttempted: term.creditsAttempted ?? term.credits_attempted ?? null,
                    numeric: termNumeric,
                    numericGrade: termNumeric,
                    comment: term.comment ?? null,
                }
            })

            const final = course.finalGrade || course.final_grade || {}
            // Prioritize letter grade if available, otherwise use numeric
            const finalGradeString = formatGrade(final.letter, final.numeric)
            
            // Get credits earned from multiple possible locations (raw value)
            // Priority: final_grade.credits_earned > course.credits_earned
            // This value comes from either current_school_courses.credit_earned or course_enrollments.credits_earned
            const rawCreditsEarned = toNumberOrNull(
                final.creditsEarned ?? 
                final.credits_earned ?? 
                course.creditsEarned ?? 
                course.credits_earned
            )

            // Create course object - use the credits_earned value directly from API
            // No recalculation needed - backend already provides the correct value
            const courseObj = {
                title: course.courseName || course.course_name || 'Course',
                courseCode: course.courseCode || course.course_code || '',
                enrollmentUuid: course.enrollmentUuid || course.enrollment_uuid || null,
                academicCourseUuid: course.academicCourseUuid || course.academic_course_uuid || null,
                creditsAttempted: toNumberOrNull(course.creditsAttempted ?? course.credits_attempted),
                creditsEarned: rawCreditsEarned,
                terms: termMap,
                final: {
                    letter: final.letter || '',
                    numeric: toNumberOrNull(final.numeric),
                    grade: finalGradeString,
                    credits: rawCreditsEarned, // Use value directly from API (from current_school_courses or course_enrollments)
                },
            }

            return courseObj
        })
        
        // Merge courses into the group
        grouped[groupKey].courses.push(...courses)
        
        // Merge terms (use the longest terms array or most complete one)
        if (terms.length > grouped[groupKey].terms.length) {
            grouped[groupKey].terms = terms.map((term) => ({
                uuid: term.uuid,
                termNo: term.termNo ?? term.term_no,
                title: term.title,
                shortTitle: term.shortTitle ?? term.short_title ?? term.title,
            }))
        }
        
        // Preserve summary from report (should be the same for all reports in a group)
        if (report.summary && !grouped[groupKey].summary) {
            grouped[groupKey].summary = report.summary
        }
        
        // Preserve attendance and comments
        if (report.attendance && !grouped[groupKey].attendance) {
            grouped[groupKey].attendance = report.attendance
        }
        if (report.comments && !grouped[groupKey].comments) {
            grouped[groupKey].comments = report.comments
        }
    })
    
    // Now calculate credits attempted and populate comments/historyUuid from student histories
    Object.values(grouped).forEach(group => {
        // Get history for this group to populate comments and historyUuid
        const history = getCurrentSchoolHistoryForReport(
            { schoolYear: { uuid: group.meta.schoolYearUuid }, gradeLevel: { uuid: group.meta.gradeLevelUuid } },
            group.meta.schoolYearUuid,
            group.meta.gradeLevelUuid
        )
        
        if (history) {
            group.historyUuid = history.uuid
            // Populate comments from history (prioritize history comments over report comments)
            if (history.comments) {
                group.comments = history.comments
            }
        }
        
        // Calculate credits attempted based only on terms that were actually taken
        let calculatedCreditsAttempted = 0.0
        group.courses.forEach(course => {
            if (!course.terms) return
            
            // Sum credits attempted from only terms that have actual grade data (not just '-')
            Object.values(course.terms).forEach(term => {
                // Check if term has actual grade data (was taken)
                const hasGrade = term.grade && term.grade !== '-' && term.grade.trim() !== ''
                if (hasGrade && term.creditsAttempted != null) {
                    calculatedCreditsAttempted += Number(term.creditsAttempted) || 0
                }
            })
        })
        
        // Update summary with calculated credits attempted (only from taken terms)
        if (group.summary) {
            group.summary.creditsAttempted = calculatedCreditsAttempted
            group.summary.credits_attempted = calculatedCreditsAttempted
        } else {
            group.summary = {
                creditsAttempted: calculatedCreditsAttempted,
                credits_attempted: calculatedCreditsAttempted,
            }
        }
    })
    
    // Helper function to get grade level sort value (extract numeric part from code or name)
    function getGradeLevelSortValue(gradeLevelUuid) {
        if (!gradeLevelUuid) return 0
        
        const gradeLevel = props.gradeLevels.find(gl => gl.uuid === gradeLevelUuid)
        if (!gradeLevel) return 0
        
        // Try to extract numeric part from code (e.g., "G12" -> 12, "G01" -> 1)
        const code = gradeLevel.code || ''
        const codeMatch = code.match(/\d+/)
        if (codeMatch) {
            return parseInt(codeMatch[0], 10)
        }
        
        // Fallback: try to extract numeric part from name (e.g., "12th Grade" -> 12, "1st Grade" -> 1)
        const name = gradeLevel.name || ''
        const nameMatch = name.match(/\d+/)
        if (nameMatch) {
            return parseInt(nameMatch[0], 10)
        }
        
        return 0
    }
    
    // Convert to array and sort
    // Sort by grade level (highest first), then by school year (most recent first)
    const result = Object.values(grouped)
        .map(group => ({
            ...group,
            rows: group.courses,
            gradeLevelSortValue: getGradeLevelSortValue(group.meta?.gradeLevelUuid),
        }))
        .sort((a, b) => {
            // First sort by grade level (descending - highest first)
            const gradeDiff = (b.gradeLevelSortValue ?? 0) - (a.gradeLevelSortValue ?? 0)
            if (gradeDiff !== 0) {
                return gradeDiff
            }
            // Then sort by school year (descending - most recent first)
            return (b.sortKey ?? 0) - (a.sortKey ?? 0)
        })
    
    return result
})

/* Accordion */
const openSet = ref(new Set())
const firstKey = computed(() => keyFor(items.value[0], 0))
watch(firstKey, (k) => {
    if (k && openSet.value.size === 0) openSet.value.add(k)
}, { immediate: true })

function keyFor(blk, idx) {
    return (blk && blk.key) != null ? blk.key : idx
}
function isOpen(blk, idx) {
    return openSet.value.has(keyFor(blk, idx))
}
function toggle(blk, idx) {
    const k = keyFor(blk, idx)
    if (openSet.value.has(k)) openSet.value.delete(k)
    else openSet.value.add(k)
}

/* Helpers */
function toFixedOrBlank(v) {
    if (v === null || v === undefined || v === '') return '-'
    const n = Number(v)
    return Number.isFinite(n) ? n.toFixed(2).replace(/\.00$/, '') : '-'
}

function formatGpaValue(value) {
    if (value === null || value === undefined || value === '') return '-'
    const n = Number(value)
    if (!Number.isFinite(n)) return '-'
    // Format GPA to 2 decimal places, but show more precision if needed
    if (n % 1 === 0) {
        return n.toFixed(0)
    }
    return n.toFixed(2).replace(/\.00$/, '')
}

function formatUnweightedGpa(value) {
    if (value === null || value === undefined || value === '') return '-'
    const n = Number(value)
    if (!Number.isFinite(n)) return '-'
    // Format GPA with at least 1 decimal, allow more precision
    // Remove trailing zeros but keep at least one decimal place
    const formatted = n.toFixed(3).replace(/\.?0+$/, '')
    // Ensure at least one decimal place (e.g., 3.0 instead of 3)
    if (!formatted.includes('.')) {
        return n.toFixed(1)
    }
    // If it ends with .0, keep one decimal (e.g., 3.0)
    if (formatted.endsWith('.0')) {
        return formatted
    }
    // Otherwise return with precision (e.g., 3.124)
    return formatted
}

function getTermComment(course, termNo) {
    const comment = course.terms?.[termNo]?.comment
    return comment && String(comment).trim() ? String(comment).trim() : null
}

function getCourseCommentRows(blk) {
    const rows = []
    ;(blk.courses || []).forEach((course) => {
        const termComments = []
        ;(blk.terms || []).forEach((term) => {
            const comment = getTermComment(course, term.termNo)
            if (comment) {
                const termLabel = term.shortTitle || term.title || `Term ${term.termNo}`
                termComments.push({ termLabel, comment })
            }
        })
        if (termComments.length) {
            rows.push({
                courseName: course.title || 'Course',
                termComments,
            })
        }
    })
    return rows
}

function getTermGrade(course, termNo) {
    // Get the grade from the termMap (already formatted by formatGrade which prioritizes letter)
    const grade = course.terms?.[termNo]?.grade
    // Return the grade if it exists and is not empty, otherwise show dash
    return (grade != null && grade !== '' && grade !== '-') ? grade : '-'
}

function getTermCredits(course, termNo) {
    const value = course.terms?.[termNo]?.credits
    return value != null && value !== '' ? toFixedOrBlank(value) : '-'
}

function calculateFinalCredits(course, blk) {
    // Use credits_earned directly from the API response
    // Backend provides this from either current_school_courses.credit_earned or course_enrollments.credits_earned
    const credits = course.final?.credits ?? course.creditsEarned ?? course.credits_earned
    
    if (credits != null && credits !== undefined && credits !== '') {
        return toFixedOrBlank(credits)
    }
    
    // Fallback: return dash if no credits available
    return '-'
}

function getTermNumericGrade(course, termNo) {
    // Get numeric grade from term data
    const term = course.terms?.[termNo]
    if (!term) return '-'
    
    // Check if term has numeric grade stored separately
    const numericGrade = term.numericGrade ?? term.numeric_grade ?? term.numeric
    
    if (numericGrade != null && numericGrade !== '') {
        const formatted = toFixedOrBlank(numericGrade)
        return formatted !== '-' ? formatted : '-'
    }
    
    // If no numeric grade, try to extract from the grade string if it's numeric
    const grade = term.grade
    if (grade && grade !== '-') {
        const num = Number(grade)
        if (Number.isFinite(num)) {
            const formatted = toFixedOrBlank(num)
            return formatted !== '-' ? formatted : '-'
        }
    }
    
    return '-'
}

function isHighSchoolGradeLevel(blk) {
    if (!blk || !blk.meta || !blk.meta.gradeLevelUuid) {
        return false
    }
    
    const gradeLevelUuid = blk.meta.gradeLevelUuid
    const gradeLevel = props.gradeLevels.find(gl => gl.uuid === gradeLevelUuid)
    
    if (!gradeLevel) {
        // Fallback: check gradeLabel
        const gradeLabel = blk.gradeLabel || ''
        const gradeNumber = extractGradeNumber(gradeLabel)
        return gradeNumber !== null && gradeNumber >= 9 && gradeNumber <= 12
    }
    
    // Extract grade number from grade level
    const gradeNumber = extractGradeNumber(gradeLevel.name) || 
                       extractGradeNumber(gradeLevel.shortName) || 
                       extractGradeNumber(gradeLevel.code) ||
                       extractGradeNumber(gradeLevel.displayName) ||
                       toNumberOrNull(gradeLevel.sequence) ||
                       toNumberOrNull(gradeLevel.level) ||
                       toNumberOrNull(gradeLevel.grade) ||
                       toNumberOrNull(gradeLevel.numeric)
    
    return gradeNumber !== null && gradeNumber >= 9 && gradeNumber <= 12
}

function extractGradeNumber(value) {
    if (value === null || value === undefined || value === '') {
        return null
    }
    const match = String(value).match(/\d+/)
    return match ? Number(match[0]) : null
}

/* Total credits per block - use credits_earned from CourseEnrollment table */
function totalCredits(courses = []) {
    const rows = Array.isArray(courses) ? courses : []
    let sum = 0
    for (const r of rows) {
        // Use credits_earned from CourseEnrollment (from final_grade.credits_earned or top-level credits_earned)
        const creditsEarned = r?.final?.creditsEarned ?? 
                              r?.final?.credits_earned ?? 
                              r?.creditsEarned ?? 
                              r?.credits_earned ?? 
                              0
        const value = Number(creditsEarned)
        sum += Number.isFinite(value) ? value : 0
    }
    return Number(sum).toFixed(2).replace(/\.00$/, '')
}

function formatGrade(letter, numeric) {
    if (letter && String(letter).trim() !== '') {
        return letter
    }
    if (numeric !== null && numeric !== undefined && numeric !== '') {
        return Number(numeric).toFixed(2).replace(/\.00$/, '')
    }
    return '-' // Display dash for missing data
}

function toNumberOrNull(value) {
    if (value === null || value === undefined || value === '') {
        return null
    }
    const n = Number(value)
    return Number.isFinite(n) ? n : null
}

function getStudentName() {
    const student = props.student
    if (!student) return ''
    
    // Try multiple possible name fields
    const firstName = student.firstName || student.first_name || ''
    const middleName = student.middleName || student.middle_name || ''
    const lastName = student.lastName || student.last_name || ''
    
    const fullName = [firstName, middleName, lastName].filter(Boolean).join(' ')
    if (fullName) return fullName
    
    // Fallback to contact name if available
    if (student.contact) {
        return `${student.contact.firstName || ''} ${student.contact.lastName || ''}`.trim() || 'Student'
    }
    
    // Fallback to name field
    return student.name || 'Student'
}

/* Print handler */
function printItem(blk) {
    const cacheKey = cacheKeyFor(props.student.uuid)
    sessionStorage.setItem(cacheKey, JSON.stringify(items.value))

    const studentPayload = {
        name:
            [props.student?.firstName, props.student?.middleName, props.student?.lastName]
                .filter(Boolean)
                .join(' ') ||
            props.student?.name ||
            '',
        id:
            props.student?.studentId ||
            props.student?.student_id ||
            props.student?.idNumber ||
            props.student?.id_number ||
            props.student?.admissionNumber ||
            props.student?.admission_number ||
            '',
        birthDate: props.student?.birthDate || props.student?.dob || props.student?.date_of_birth || '',
        address:
            props.student?.address ||
            props.student?.addressLine1 ||
            props.student?.address_line_1 ||
            props.student?.homeAddress ||
            '',
    }

    router.push({
        name: 'StudentReportCardPrintPreview',
        params: { uuid: props.student.uuid },
        query: {
            sy: blk.meta?.schoolYearUuid || '',
            gl: blk.meta?.gradeLevelUuid || '',
        },
        state: { block: blk, cacheKey, student: studentPayload },
    })
}
function cacheKeyFor(uuid) {
    return `rc_blocks_${uuid}`
}
</script>
