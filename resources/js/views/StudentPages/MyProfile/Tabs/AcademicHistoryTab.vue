<template>
    <!-- Standardized Assessments -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-2">
            <p class="text-sm font-semibold mx-4">Standardized Assessments</p>
        </div>
        <hr class="mx-4" />

        <div v-if="assessments && assessments.length > 0" class="overflow-x-auto mx-3">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-200 text-xs">
                        <th class="px-3 py-2">Test Title</th>
                        <th class="px-3 py-2">Date</th>
                        <th class="px-3 py-2">Total Score</th>
                        <th class="px-3 py-2">Score 1</th>
                        <th class="px-3 py-2">Score 2</th>
                        <th class="px-3 py-2">Score 3</th>
                        <th class="px-3 py-2">Score 4</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="a in assessments" :key="a.uuid" class="border-t text-sm">
                        <td class="px-3 py-1"><strong>{{ a.title }}</strong></td>
                        <td class="px-3 py-1">{{ a.date?.formatted || a.date || '-' }}</td>
                        <td class="px-3 py-1">{{ a.total || '-' }}</td>
                        <td class="px-3 py-1">{{ a.score1 || '-' }}</td>
                        <td class="px-3 py-1">{{ a.score2 || '-' }}</td>
                        <td class="px-3 py-1">{{ a.score3 || '-' }}</td>
                        <td class="px-3 py-1">{{ a.score4 || '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="bg-gray-50 border border-dashed border-gray-300 rounded-lg py-8 text-center">
            <i class="fa-solid fa-file-circle-exclamation text-2xl mb-2 text-gray-500"></i>
            <p class="text-sm text-gray-500">No Standardized Assessment item currently</p>
        </div>
    </div>

    <!-- Current School Academic History -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-2">
            <p class="text-sm font-semibold mx-4">Current School Academic History</p>
        </div>
        <hr class="mx-4" />

        <div v-if="currentSchoolView && currentSchoolView.length > 0">
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
                    <div class="flex items-center gap-3">
                        <span :class="[
                            'text-xs px-2 py-0.5 rounded',
                            h.isPublished ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'
                        ]">
                            {{ h.isPublished ? 'Published' : 'Draft' }}
                        </span>
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
                                <td class="px-3 py-1">{{ formatCredit(c.creditEarned) }}</td>
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
            <i class="fa-solid fa-file-circle-exclamation text-2xl mb-2 text-gray-500"></i>
            <p class="text-sm text-gray-500">No Current School Academic History</p>
            <p class="text-xs text-gray-400">Academic history information is not available at this time.</p>
        </div>
    </div>

    <!-- Previous School Academic History -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-2">
            <p class="text-sm font-semibold mx-4">Previous School Academic History</p>
        </div>
        <hr class="mx-4" />

        <div v-if="previousSchoolsView && previousSchoolsView.length > 0">
            <div v-for="ps in previousSchoolsView" :key="ps.uuid"
                class="bg-gray-100 border border-gray-300 mx-3 mb-3 rounded-lg shadow-md">
                <div class="flex items-center justify-between px-4 py-2">
                    <div class="text-left">
                        <p class="text-sm">
                            <strong>{{ ps.previousSchoolName }}</strong>
                            <span class="text-gray-500"> • Grade Level:</span>
                            <span class="text-gray-700">{{ findGradeLevelName(ps.gradeLevel) }}</span>
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ ps.rows?.length || 0 }} course(s) mapped
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
                                    {{ findCourseName(r.auditCourseEquivalency) || '-' }}
                                </td>
                                <td class="px-3 py-1">{{ r.auditCreditEquivalency ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-else class="bg-gray-50 border border-dashed border-gray-300 rounded-lg py-8 text-center">
            <i class="fa-solid fa-school-flag text-2xl mb-2 text-gray-500"></i>
            <p class="text-sm text-gray-500">No Previous School Academic History item currently</p>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue"

const props = defineProps({
    student: Object,
    schoolYears: Array,
    gradeLevels: Array,
    academicCourses: Array,
    gradingSystems: Array,
})

const currentSchoolView = computed(() => {
    const list = props.student?.currentSchoolHistories || props.student?.current_school_histories || []
    
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
                schoolName: h.schoolName || h.school_name || 'DSA',
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
            
            return {
                uuid: c.uuid,
                title: c.courseTitle ?? c.course_title ?? fallbackTitle,
                finalGrade: c.finalGrade ?? c.final_grade ?? null,
                creditEarned:
                    c.creditEarned ??
                    c.credit_earned ??
                    c.meta?.creditEarned ??
                    c.meta?.credit_earned ??
                    null,
                completionDate: c.completionDate ?? c.completion_date ?? null,
                academicCourseUuid: c.academicCourseUuid ?? c.academic_course_uuid ?? null,
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
        }
    })
})

const assessments = computed(() => {
    return props.student?.standardizedAssessments || props.student?.assessments || []
})

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

const formatCredit = (credit) => {
    if (credit === null || credit === undefined) return '-'
    return Number(credit).toFixed(2)
}

const formatLocalDate = (date) => {
    if (!date) return null
    if (typeof date === 'object' && date.formatted) {
        return date.formatted
    }
    if (typeof date === 'string') {
        return date
    }
    return null
}

const findGradeLevelName = (gradeLevelUuid) => {
    if (!gradeLevelUuid) return '-'
    const gradeLevel = props.gradeLevels?.find(g => g.uuid === gradeLevelUuid)
    return gradeLevel?.name || '-'
}

const findCourseName = (courseUuid) => {
    if (!courseUuid) return '-'
    const course = props.academicCourses?.find(c => c.uuid === courseUuid)
    return course?.name || course?.title || '-'
}
</script>

