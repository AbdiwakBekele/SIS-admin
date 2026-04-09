<template>
    <!-- Standardized Assessments (still static for now) -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 mt-2">
        <div class="flex justify-between items-center mx-4 mb-4">
            <p class="text-sm text-left font-semibold">Standardized Assessments</p>

        </div>

        <div v-if="assessments.length === 0" class="pr-6 py-5 text-center">
            <i class="fa-solid fa-file-circle-exclamation text-2xl mb-2 text-gray-500"></i>
            <p class="text-gray-500">No Standardized Assessment Recorded Yet.</p>
            <p class="text-xs text-gray-400">Click "Add Assessment" to add the first record</p>
        </div>

        <div v-else class="overflow-x-auto">
            <table class="w-full text-left mx-4">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left text-sm">Test Title</th>
                        <th class="p-2 text-left text-sm">Date</th>
                        <th class="p-2 text-left text-sm">Total Score</th>
                        <th class="p-2 text-left text-sm">Score 1</th>
                        <th class="p-2 text-left text-sm">Score 2</th>
                        <th class="p-2 text-left text-sm">Score 3</th>
                        <th class="p-2 text-left text-sm">Score 4</th>
                        <th class="p-2 text-left text-sm"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(a, idx) in assessments" :key="a.uuid" class="border-t hover:bg-gray-50">
                        <td class="p-2 text-sm text-left"><strong>{{ a.title }}</strong></td>
                        <td class="p-2 text-sm text-left">{{ a.date }}</td>
                        <td class="p-2 text-sm text-left">{{ a.total }}</td>
                        <td class="p-2 text-sm text-left">{{ a.score1 }}</td>
                        <td class="p-2 text-sm text-left">{{ a.score2 }}</td>
                        <td class="p-2 text-sm text-left">{{ a.score3 }}</td>
                        <td class="p-2 text-sm text-left">{{ a.score4 }}</td>
                        <td class="p-2 text-sm text-left">

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Current School Academic History -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-2">
            <p class="text-sm font-semibold mx-4">Current School Academic History</p>
            <div>

            </div>


        </div>
        <hr class="mx-4" />

        <div v-if="false">
            <div v-if="student?.academicHistories && student.academicHistories.length > 0">
                <div v-for="academicHistory in student.academicHistories" :key="academicHistory.uuid"
                    class="bg-gray-100 border border-gray-300 mx-3 mb-3 rounded-lg shadow-md">
                    <!-- Academics Info -->
                    <div class="grid grid-cols-12 text-left gap-y-2">
                        <!-- Header: title (left) + actions (right) -->
                        <div class="col-span-12 flex items-center justify-between px-4 pt-2">
                            <h3 class="text-sm font-semibold">{{ academicHistory.schoolYear?.name }}</h3>

                        </div>

                        <!-- Student Grade Level -->
                        <span class="col-span-1 flex items-center justify-center ml-4">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </span>
                        <div class="col-span-2">
                            <p class="text-sm"><strong>Grade Level (Current)</strong></p>
                            <span class="text-gray-600 text-sm">{{ academicHistory.gradeLevel?.name }}</span>
                        </div>

                        <!-- Start Date -->
                        <span class="col-span-1 flex items-center justify-center ml-4">
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                        <div class="col-span-2">
                            <p class="text-sm"><strong>Start Date</strong></p>
                            <span class="text-gray-600 text-sm">{{ academicHistory.enrollmentDate?.formatted }}</span>
                        </div>

                        <!-- End Date -->
                        <span class="col-span-1 flex items-center justify-center ml-4">
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                        <div class="col-span-2">
                            <p class="text-sm"><strong>End Date</strong></p>
                            <span class="text-gray-600 text-sm">-</span>
                        </div>

                        <!-- Withdrawal -->
                        <span class="col-span-1 flex items-center justify-center ml-4">
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                        <div class="col-span-2">
                            <p class="text-sm"><strong>Withdrawal Date</strong></p>
                            <span class="text-gray-600 text-sm">
                                {{ academicHistory.withdrawalDate?.formatted ? academicHistory.withdrawalDate.formatted :
                                    '-' }}
                            </span>
                        </div>
                    </div>


                </div>
            </div>

            <div v-else>
                <i class="fa-solid fa-file-circle-exclamation text-2xl mb-2"></i>
                <p class="text-sm">No Academic History Recorded Yet</p>
                <p class="text-xs text-gray-400">Click the "+" to add academic history</p>
            </div>
        </div>


        <div>
            <div v-if="currentSchoolView.length">
                <div v-for="h in currentSchoolView" :key="h.uuid"
                    class="bg-gray-100 border border-gray-300 mb-3 rounded-lg shadow-md">
                    <div class="px-4 py-2 flex items-center justify-between">
                        <div class="text-left">
                            <p class="text-sm">
                                <strong>{{ h.schoolName }}</strong>
                                <span class="text-gray-500"> • {{ h.schoolYearName }}</span>
                            </p>
                            <p class="text-xs text-gray-500">Grade Level: {{ h.gradeLevelName }}</p>
                        </div>

                    </div>

                    <!-- Courses table -->
                    <div class="overflow-x-auto pb-3">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-200 text-xs">
                                    <th class="px-3 py-2">Course Title</th>
                                    <th class="px-3 py-2">Final Grade</th>
                                    <th class="px-3 py-2">Credit Earned</th>
                                    <th class="px-3 py-2">Date Earned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="c in h.courses" :key="c.uuid" class="border-t text-sm">
                                    <td class="px-3 py-1">{{ c.title || '-' }}</td>
                                    <td class="px-3 py-1">{{ c.finalGrade ?? '-' }}</td>
                                    <td class="px-3 py-1">{{ c.creditEarned ?? c.catalogCredit ?? '-' }}</td>
                                    <td class="px-3 py-1">{{ formatLocalDate(c.completionDate) || '-' }}</td>
                                </tr>
                                <tr v-if="!h.courses?.length">
                                    <td colspan="4" class="px-3 py-2 text-xs text-gray-500">No courses recorded.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div v-else class="bg-gray-50 border border-dashed border-gray-300 rounded-lg py-8 text-center">
                <i class="fa-solid fa-file-circle-exclamation text-2xl mb-2"></i>
                <p class="text-sm">No Current School Academic History</p>
                <p class="text-xs text-gray-400">Click the “+” to add current school academic history</p>
            </div>
        </div>


    </div>

    <!-- Previous School Academic History (DB-connected) -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-2">
            <p class="text-sm font-semibold mx-4">Previous School Academic History</p>

        </div>
        <hr class="mx-4" />

        <!-- Empty -->
        <div v-if="previousSchoolsView.length === 0" class="pr-6 py-5 text-center">
            <i class="fa-solid fa-school-flag text-2xl mb-2 text-gray-500"></i>
            <p class="text-gray-500">No Previous School Recorded Yet.</p>
            <p class="text-xs text-gray-400">Click "+" to add the first record</p>
        </div>

        <!-- List -->
        <div v-else class="space-y-3 mx-3">
            <div v-for="(ps, idx) in previousSchoolsView" :key="ps.uuid"
                class="bg-gray-100 border border-gray-300 rounded-lg shadow-md">
                <div class="flex items-center justify-between px-4 py-2">
                    <div class="text-left">
                        <p class="text-sm">
                            <strong>{{ ps.previousSchoolName }}</strong>
                            <span class="text-gray-500"> • Grade Level:</span>
                            <span class="text-gray-700">{{ findGradeLevelName(ps.gradeLevel) }}</span>
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ ps.rows.length }} course(s) mapped
                        </p>
                    </div>

                </div>

                <!-- Rows preview -->
                <div class="overflow-x-auto pb-3">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-200 text-xs">
                                <th class="px-3 py-2">Previous Course Title</th>
                                <th class="px-3 py-2">Prev. School Credit</th>
                                <th class="px-3 py-2">Completion Date</th>
                                <th class="px-3 py-2">Audit Course Equivalency</th>
                                <th class="px-3 py-2">Audit Credit Equivalency</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(r, i) in ps.rows" :key="i" class="border-t text-sm">
                                <td class="px-3 py-1">{{ r.previousCourseTitle || '-' }}</td>
                                <td class="px-3 py-1">{{ r.previousSchoolCredit ?? '-' }}</td>
                                <td class="px-3 py-1">{{ formatLocalDate(r.completionDate) || '-' }}</td>
                                <td class="px-3 py-1">
                                    {{academicCourses.find(c => c.uuid === r.auditCourseEquivalency)?.name || '-'}}
                                </td>
                                <td class="px-3 py-1">{{ r.auditCreditEquivalency ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed } from "vue"

/** PROPS */
const props = defineProps({
    student: Object,
    schoolYears: Array,
    gradeLevels: Array,
    academicCourses: Array,
})

// Group by SchoolYear + GradeLevel
const currentSchoolView = computed(() => {
    const list = props.student?.currentSchoolHistories ?? []
    
    // Group by school year uuid + grade level uuid
    const grouped = {}
    
    list.forEach(h => {
        const syUuid = h.schoolYear?.uuid || h.school_year?.uuid || 'unknown'
        const glUuid = h.gradeLevel?.uuid || h.grade_level?.uuid || 'unknown'
        const key = `${syUuid}_${glUuid}`
        
        if (!grouped[key]) {
            grouped[key] = {
                schoolYearUuid: syUuid,
                gradeLevelUuid: glUuid,
                schoolYearName: h.schoolYear?.name || h.school_year?.name || '-',
                gradeLevelName: h.gradeLevel?.name || h.grade_level?.name || '-',
                schoolName: h.schoolName || 'DSA',
                histories: [],
                isPublished: false,
            }
        }
        
        // Use the most recent history's published status (or any published one)
        if (h.isPublished || h.is_published) {
            grouped[key].isPublished = true
        }
        
        // Map courses with term data
        const courses = (h.courses ?? []).map(c => {
            const fallbackTitle =
                props.academicCourses?.find(ac => ac.uuid === (c.academicCourseUuid ?? c.academic_course_uuid))?.name ?? '-'
            
            // Get term grades from course enrollment terms
            const terms = {}
            const enrollmentTerms = c.courseEnrollmentTerms || c.course_enrollment_terms || []
            enrollmentTerms.forEach(term => {
                const termNo = term.termNo ?? term.term_no ?? term.school_term?.term_no
                if (termNo !== null && termNo !== undefined) {
                    terms[termNo] = {
                        grade: term.termGradeLetter ?? term.term_grade_letter ?? term.termGradeNumeric ?? term.term_grade_numeric ?? null
                    }
                }
            })
            
            return {
                uuid: c.uuid,
                title: c.courseTitle ?? fallbackTitle,
                finalGrade: c.finalGrade ?? null,
                creditEarned:
                    c.creditEarned ??
                    c.credit_earned ??
                    c.meta?.creditEarned ??
                    c.meta?.credit_earned ??
                    null,
                completionDate: c.completionDate ?? null,
                academicCourseUuid: c.academicCourseUuid ?? c.academic_course_uuid ?? null,
                terms: terms,
                courseEnrollmentTerms: enrollmentTerms,
                course_enrollment_terms: enrollmentTerms,
                meta: c.meta || {},
            }
        })
        
        grouped[key].histories.push({
            uuid: h.uuid,
            courses: courses,
            isPublished: h.isPublished ?? h.is_published ?? false,
            schoolYear: h.schoolYear || h.school_year,
            gradeLevel: h.gradeLevel || h.grade_level,
        })
    })
    
    // Convert to array and merge all courses from all histories in the same group
    return Object.values(grouped).map(group => {
        // Merge all courses from all histories in this group
        const allCourses = []
        group.histories.forEach(hist => {
            allCourses.push(...hist.courses)
        })
        
        // Use the first history's uuid as the primary one (for edit/delete)
        const primaryHistory = group.histories[0]
        
        return {
            uuid: primaryHistory.uuid,
            schoolYearUuid: group.schoolYearUuid,
            gradeLevelUuid: group.gradeLevelUuid,
            schoolName: group.schoolName,
            schoolYearName: group.schoolYearName,
            gradeLevelName: group.gradeLevelName,
            isPublished: group.isPublished,
            courses: allCourses,
            schoolYear: primaryHistory.schoolYear,
            gradeLevel: primaryHistory.gradeLevel,
            // Keep all histories for reference
            allHistories: group.histories,
        }
    })
})



const courseByUuid = computed(() => {
    const map = new Map()
        ; (props.academicCourses || []).forEach(c => {
            // assume `uuid`, `name`, `course_credit`
            map.set(c.uuid, c)
        })
    return map
})


/** ===== Standardized Assessments ===== */
const assessments = ref([])

/**
 * Use the student payload (from StudentController@show) if it includes:
 * previousSchoolHistories -> previousSchoolCourses (+ academicCourse)
 * Map defensively to the UI shape.
 */
const previousSchoolsView = computed(() => {
    const list =
        props.student?.previousSchoolHistories ||
        props.student?.previous_school_histories ||
        []

    return list.map((h) => {
        const rowsRaw =
            h.previousSchoolCourses ||
            h.previous_school_courses ||
            h.rows ||
            []

        return {
            uuid: h.uuid,
            previousSchoolName:
                h.previousSchoolName ?? h.previous_school_name ?? "-",
            gradeLevel:
                h.gradeLevel?.uuid ??
                h.grade_level?.uuid ??
                h.grade_level_uuid ??
                h.gradeLevel ??
                null,
            rows: rowsRaw.map((r) => ({
                uuid: r.uuid,
                previousCourseTitle:
                    r.previousCourseTitle ?? r.previous_course_title ?? "",
                previousSchoolCredit:
                    r.previousSchoolCredit ?? r.previous_school_credit ?? null,
                completionDate:
                    r.completionDate ?? r.completion_date ?? null,
                auditCourseEquivalency:
                    r.auditCourseEquivalency ??
                    r.academicCourse?.uuid ??
                    r.academic_course?.uuid ??
                    null,
                auditCreditEquivalency:
                    r.auditCreditEquivalency ?? r.audit_credit_equivalency ?? null,
            })),
        }
    })
})


/** ===== Helpers ===== */
function findGradeLevelName(uuid) {
    return props.gradeLevels?.find((g) => g.uuid === uuid)?.name || "-"
}

function formatLocalDate(val) {
    try {
        const d = val ? new Date(val) : null
        if (!d || isNaN(d.getTime())) return ""
        return d.toLocaleDateString("en-US", { year: "numeric", month: "long", day: "2-digit" })
    } catch {
        return ""
    }
}



</script>
