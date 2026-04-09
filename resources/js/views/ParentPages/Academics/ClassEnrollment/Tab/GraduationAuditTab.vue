<template>
    <div class="grid grid-cols-11 gap-2 p-1 shadow-md">

        <!-- Left Section -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg border pt-1 pb-2">
                <!-- Header -->
                <p class="text-sm text-left mx-4 mt-1">
                    <strong>Students</strong>
                    <span class="text-gray-500 ml-1">({{ filteredSortedStudents.length }})</span>
                </p>
                <hr class="mx-4 mb-2">

                <!-- Scrollable list -->
                <div class="px-2">
                    <div class="max-h-[70vh] overflow-y-auto pr-1">
                        <div v-if="isLoadingInit" class="mx-2 my-3 text-xs text-gray-500 text-center">
                            Loading studentsâ€¦
                        </div>
                        <div v-else>
                            <div v-for="stu in filteredSortedStudents" :key="stu.uuid"
                                @click="selectStudent(stu.uuid)" :class="[
                                    'cursor-pointer px-2 py-2 text-sm sm:w-auto text-left mx-1 rounded',
                                    selectedStudent === stu.uuid
                                        ? 'border border-blue-400 bg-blue-50 text-blue-900 font-semibold'
                                        : 'border border-transparent hover:border-gray-200'
                                ]">
                                <div class="flex flex-col leading-tight">
                                    <span>{{ (stu.firstName || '') + ' ' + (stu.lastName || '') }}</span>
                                </div>
                            </div>

                            <div v-if="!filteredSortedStudents.length" class="text-xs text-gray-400 px-2 py-4 text-center">
                                No students match your search.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-span-9">

            <div class="mx-1 p-3 space-y-8 bg-white rounded-lg">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <!-- Title -->
                        <h1 class="mx-2 text-2xl font-bold">
                            <span v-if="selectedStudentFirstName">
                                {{ selectedStudentFirstName }}'s
                            </span>
                            Graduation Audit
                        </h1>
                    </div>

                    <!-- Assigned Course Planners -->
                    <div v-if="audit?.planners && audit.planners.length > 0" class="mx-2">
                        <h2 class="text-sm font-semibold text-gray-700 mb-2">Assigned Course Planners</h2>
                        <div class="flex flex-wrap gap-2">
                            <div 
                                v-for="(planner, index) in audit.planners" 
                                :key="planner.uuid"
                                :class="['inline-flex items-center gap-2 px-3 py-1.5 border rounded-full', getPlannerColorClasses(planner, index).bg]"
                            >
                                <!-- Colored bullet matching planner -->
                                <span
                                    class="flex-shrink-0 inline-block rounded-full"
                                    :style="{
                                        width: '8px',
                                        height: '8px',
                                        minWidth: '8px',
                                        minHeight: '8px',
                                        backgroundColor: getCoursePlannerBulletColor(planner.uuid)
                                    }"
                                ></span>
                                <span :class="['text-sm font-medium', getPlannerColorClasses(planner, index).text]">
                                    <span v-if="planner.isPrimary" class="font-bold">Primary: </span>
                                    {{ planner.title }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading / Empty States -->
                <div v-if="isLoading" class="p-6 bg-white rounded-lg shadow-lg text-sm text-gray-600">
                    Loading graduation auditâ€¦
                </div>

                <div v-else-if="!audit" class="p-6 bg-white rounded-lg shadow-lg text-sm text-gray-600">
                    <div class="mb-2">No planner found for the selected context yet.</div>
                    <div class="text-xs">
                        Tip: Choose a student and (optionally) select School Year / Division / Grade Level to
                        refresh.
                    </div>
                </div>

                <!-- Audit Table -->
                <div v-else class="p-2 bg-white rounded-lg shadow-lg">
                    <!-- Overall credits summary -->
                    <div class="flex flex-wrap items-center justify-end gap-x-4 mb-2 text-xs text-gray-700">
                        <span class="font-semibold">Total Credits Overview:</span>
                        <span class="inline-flex items-center gap-1">
                            <CheckCircleIcon class="h-4 w-4 text-green-600" />
                            Completed: {{ fmt(overallCredits.completed) }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <StarIcon class="h-4 w-4 text-yellow-500" />
                            Enrolled: {{ fmt(overallCredits.enrolled) }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <CalendarDaysIcon class="h-4 w-4 text-blue-700" />
                            Planned: {{ fmt(overallCredits.planned) }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <CalculatorIcon class="h-4 w-4 text-gray-600" />
                            Total: {{ fmt(overallCredits.progress) }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <AcademicCapIcon class="h-4 w-4 text-blue-600" />
                            Required: {{ fmt(overallCredits.required) }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <ExclamationCircleIcon class="h-4 w-4 text-red-600" />
                            Missing: {{ fmt(overallCredits.missing) }}
                        </span>
                    </div>

                    <table class="w-full text-sm text-left border border-gray-400">
                        <thead class="bg-gray-200 text-xs text-gray-600 uppercase">
                            <tr>
                                <th class="border px-4 py-2">Subject Name</th>
                                <th v-for="gh in audit.gradeHeaders" :key="gh" class="border px-4 py-2">
                                    {{ gh }}
                                </th>
                                <th class="border px-4 py-2">Credits</th>
                                <th class="border px-4 py-2">On Track</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in audit.subjects" :key="row.subject"
                                class="border-t odd:bg-white even:bg-gray-100">

                                <td class="border px-1 font-medium text-xs text-gray-600 uppercase break-words hyphens-auto">
                                    {{ row.subject?.name || row.subject }}
                                </td>

                                <td v-for="gh in audit.gradeHeaders" :key="`${row.subject}-${gh}`"
                                    class="border px-2 py-1 align-top text-xs">
                                    <!-- Read-only course list (no drag-and-drop on parent side) -->
                                    <div v-if="visibleItemsForCell(row, gh).length">
                                        <div class="space-y-1.5">
                                            <div
                                                v-for="item in visibleItemsForCell(row, gh)"
                                                :key="itemKeyForDrag(item)"
                                                class="text-[11px] leading-tight break-words hyphens-auto flex items-start gap-1.5 rounded-lg border border-gray-200 px-2 py-1.5 hover:border-gray-300 transition-colors duration-150 course-item-bg"
                                                :style="{ backgroundColor: getCoursePlannerBgColor((sortedPlannerUuidsForItem(item)[0] ?? null)) }"
                                            >
                                                <span
                                                    v-for="(puuid, bi) in (sortedPlannerUuidsForItem(item).length ? sortedPlannerUuidsForItem(item) : [null])"
                                                    :key="`bullet-${bi}-${puuid || 'gray'}`"
                                                    class="flex-shrink-0 inline-block rounded-full mr-0.5 mt-1.5"
                                                    :style="{
                                                        width: '10px',
                                                        height: '10px',
                                                        minWidth: '10px',
                                                        minHeight: '10px',
                                                        backgroundColor: getCoursePlannerBulletColor(puuid)
                                                    }"
                                                    :title="puuid ? getPlannerTitle(puuid) : 'Not in a planner'"
                                                ></span>
                                                <div class="flex-1 min-w-0">
                                                    <span v-if="isOptionalCourseType(item)" class="text-[9px] text-gray-500 uppercase mr-1">Optional</span>
                                                    <span class="font-medium">
                                                        {{ item.name }}
                                                        <template v-if="item._requested === 'new'"><em class="text-gray-500"></em></template>
                                                        <template v-else-if="item._requested === 'withdrawal'"><em class="text-gray-500"> (withdrawal requested)</em></template>
                                                    </span>
                                                    <br />
                                                    <span class="text-[10px] text-gray-500">({{ fmt(item.credit) }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Elective Courses link (bottom-left of cell, only if electives exist) -->
                                    <div v-if="hasElectives(row, gh)" class="mt-1">
                                        <button
                                            type="button"
                                            class="text-[10px] text-blue-600 hover:text-blue-800 underline"
                                            @click="openElectivesPicker(row, gh)"
                                        >
                                            Elective Courses
                                        </button>
                                    </div>
                                </td>

                                <td class="border px-1 py-2 align-top min-w-[130px]">
                                    <div class="text-xs flex items-center gap-1"><CheckCircleIcon class="h-4 w-4 text-green-600 shrink-0" />{{ fmt(row.credits.completed) }} Completed</div>
                                    <div class="text-xs flex items-center gap-1"><StarIcon class="h-4 w-4 text-yellow-500 shrink-0" />{{ fmt(row.credits.enrolled) }} Enrolled</div>
                                    <div class="text-xs flex items-center gap-1"><CalendarDaysIcon class="h-4 w-4 text-blue-700 shrink-0" />{{ fmt(row.credits.planned) }} Planned</div>
                                    <div class="text-xs flex items-center gap-1"><AcademicCapIcon class="h-4 w-4 text-blue-600 shrink-0" />{{ fmt(row.credits.required) }} Required</div>
                                    <div class="text-xs flex items-center gap-1"><ExclamationCircleIcon class="h-4 w-4 text-red-600 shrink-0" />{{ fmt(getMissingCredits(row.credits)) }} Missing</div>
                                </td>

                                <td class="border px-4 py-2 text-center text-lg align-top">
                                    <span v-if="row.onTrack" class="text-green-600 font-bold">âœ”</span>
                                    <span v-else class="text-red-500 font-bold">âœ˜</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Electives Picker Modal (read-only for parent) -->
    <div class="gaudit--compact-modal">
        <BaseModal :visibility="electives.visible" @close="electives.visible = false" title="Select an Elective">
            <template #title>Elective Courses</template>
            <div v-if="!electives.list.length" class="text-sm text-gray-500">
                No electives available in this cell.
            </div>

            <div v-else class="space-y-2">
                <div v-for="c in electives.list" :key="c.course?.uuid || c.uuid || keyOf(c)"
                    class="flex items-start justify-between gap-3 border rounded px-3 py-2">
                    <div class="text-sm">
                        <div class="font-medium">{{ c.name }}</div>
                        <div class="text-[11px] text-gray-500">({{ c.credit }})</div>
                    </div>
                </div>
            </div>
        </BaseModal>
    </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted, nextTick, inject } from 'vue'
import { useStore } from 'vuex'
import * as Api from '@core/apis'
import BaseModal from '@core/components/Ui/BaseModal.vue'
import {
    CheckCircleIcon,
    AcademicCapIcon,
    CalendarDaysIcon,
    CalculatorIcon,
    ExclamationCircleIcon,
    StarIcon,
} from '@heroicons/vue/20/solid'

const store = useStore()
const $trans = inject('$trans')

const isLoadingInit = ref(true)
const isLoading = ref(false)

const students = ref([])
const gradeLevels = ref([])
const schoolYears = ref([])
const divisions = ref([])
const audit = ref(null)

const selectedStudent = ref(null)

// Form state (used for API calls)
const form = reactive({
    schoolYear: '',
    division: '',
    gradeLevel: '',
})

const fullName = (s) => `${(s.firstName || '').trim()} ${(s.lastName || '').trim()}`.trim()

// Sorted students
const filteredSortedStudents = computed(() => {
    let result = students.value || []

    // Sort by lastName, then firstName (case-insensitive)
    return [...result].sort((a, b) => {
        const aLast = (a.lastName || '').toString()
        const bLast = (b.lastName || '').toString()
        const byLast = aLast.localeCompare(bLast, undefined, { sensitivity: 'base' })
        if (byLast !== 0) return byLast
        const aFirst = (a.firstName || '').toString()
        const bFirst = (b.firstName || '').toString()
        return aFirst.localeCompare(bFirst, undefined, { sensitivity: 'base' })
    })
})

const fmt = (v) => {
    if (v === null || v === undefined) return '0'
    const n = Number(v)
    return isNaN(n) ? v : n.toFixed(2)
}

// Calculate missing credits for a row (min = 0)
const getMissingCredits = (credits) => {
    if (!credits) return 0
    const required = Number(credits.required || 0)
    const completed = Number(credits.completed || 0)
    const enrolled = Number(credits.enrolled || 0)
    const planned = Number(credits.planned || 0)
    const progress = completed + enrolled + planned
    return Math.max(0, required - progress)
}

// Aggregate overall credits across all subjects
const overallCredits = computed(() => {
    const rows = audit.value?.subjects || []
    let required = 0
    let completed = 0
    let enrolled = 0
    let planned = 0
    let missing = 0

    rows.forEach((row) => {
        const c = row.credits || {}
        required += Number(c.required || 0)
        completed += Number(c.completed || 0)
        enrolled += Number(c.enrolled || 0)
        planned += Number(c.planned || 0)
        missing += getMissingCredits(c)
    })

    const progress = completed + enrolled + planned

    return {
        required,
        completed,
        enrolled,
        planned,
        progress,
        missing,
    }
})

/* utils */
const slug = (s = '') => String(s).toLowerCase().trim().replace(/\s+/g, '-')
const keyOf = (item) => item?.course?.uuid || item?.uuid || item?.course_uuid || slug(item?.name)

/** Deduplicate course items in a cell (same course from different planners: show only one) */
const auditDeduplicateCellItems = (items = []) => {
    if (!items.length) return items
    const seen = new Map()
    return items.filter((it) => {
        const k = it?.course_uuid || it?.course?.uuid || it?.uuid || keyOf(it)
        if (!k || seen.has(k)) return false
        seen.set(k, true)
        return true
    })
}

/** Group cell items by course and attach plannerUuids (all planners this course appears in) for multiple bullets */
const auditGroupByCourseWithPlanners = (items = []) => {
    if (!items?.length) return []
    const byCourse = new Map()
    for (const it of items) {
        const k = it?.course_uuid || it?.course?.uuid || it?.uuid || keyOf(it)
        if (!k) continue
        const plannerUuids = it?.plannerUuids ?? it?.planner_uuids
        const arr = Array.isArray(plannerUuids) ? [...plannerUuids] : []
        if (!arr.length) {
            const single = it?.planner_uuid ?? it?.plannerUuid
            if (single) arr.push(single)
        }
        if (!byCourse.has(k)) {
            byCourse.set(k, { ...it, plannerUuids: [] })
        }
        const merged = byCourse.get(k)
        for (const u of arr) {
            if (u && !merged.plannerUuids.includes(u)) merged.plannerUuids.push(u)
        }
    }
    return [...byCourse.values()]
}

/** Return planner UUIDs for an item (for bullet list); empty => show one gray bullet */
const itemPlannerUuids = (item) => {
    const uuids = item?.plannerUuids ?? item?.planner_uuids
    if (Array.isArray(uuids) && uuids.length) return uuids
    const single = item?.planner_uuid ?? item?.plannerUuid
    return single ? [single] : []
}

const itemKeyForDrag = (item) => item?.course_uuid ?? item?.courseUuid ?? keyOf(item)

// Ensure consistent dot order in the table: primary planner first, then others in assigned order
const sortedPlannerUuidsForItem = (item) => {
    const uuids = itemPlannerUuids(item)
    if (!uuids.length || !audit.value?.planners?.length) return uuids

    const uuidSet = new Set(uuids)
    const ordered = []

    // Primary planner(s) first, in planners array order
    audit.value.planners.forEach((p) => {
        if (p.isPrimary && uuidSet.has(p.uuid)) {
            ordered.push(p.uuid)
        }
    })

    // Then non-primary planners, in planners array order
    audit.value.planners.forEach((p) => {
        if (!p.isPrimary && uuidSet.has(p.uuid) && !ordered.includes(p.uuid)) {
            ordered.push(p.uuid)
        }
    })

    return ordered
}

async function refreshRoster() {
    isLoadingInit.value = true
    try {
        // Use store dispatch which automatically filters to parent's children
        const response = await store.dispatch("student/list", {
            params: {
                all: true,
                ...(form.schoolYear ? { schoolYear: form.schoolYear } : {}),
                ...(form.division ? { division: form.division } : {}),
                ...(form.gradeLevel ? { gradeLevel: form.gradeLevel } : {}),
            }
        })
        
        // Handle both paginated and non-paginated responses
        let data = []
        if (response?.data) {
            data = Array.isArray(response.data) ? response.data : []
            // If data is empty, check if it's nested in data.data (paginated structure)
            if (data.length === 0 && response.data?.data) {
                data = Array.isArray(response.data.data) ? response.data.data : []
            }
        }

        students.value = data.map(s => ({
            uuid: s.uuid,
            firstName: s.firstName ?? s.contact?.firstName ?? '',
            lastName: s.lastName ?? s.contact?.lastName ?? '',
            studentId: s.studentId || s.codeNumber || '',
            rollNo: s.rollNo || '',
            middleName: s.middleName ?? s.contact?.middleName ?? '',
        }))

        // If current selection is now outside the filtered list, clear selection & audit
        if (selectedStudent.value && !students.value.some(x => x.uuid === selectedStudent.value)) {
            selectedStudent.value = null
            audit.value = null
        }
    } catch (e) {
        console.error('[roster] failed', e)
    } finally {
        isLoadingInit.value = false
    }
}

const selectStudent = async (uuid) => {
    selectedStudent.value = uuid
    await nextTick()
    refreshAudit()
}

const refreshAudit = async () => {
    if (!selectedStudent.value) return
    isLoading.value = true
    try {
        const res = await store.dispatch('student/studentProfile/getGraduationAudit', {
            uuid: selectedStudent.value,
            params: {
                ...(form.schoolYear ? { schoolYear: form.schoolYear } : {}),
                ...(form.division ? { division: form.division } : {}),
                ...(form.gradeLevel ? { gradeLevel: form.gradeLevel } : {}),
            },
        })

        students.value = (res?.students || []).map(s => ({
            uuid: s.uuid, 
            firstName: s.firstName, 
            lastName: s.lastName,
        }))
        // Only update gradeLevels if we don't have them yet, or merge new ones
        const newGradeLevels = (res?.gradeLevels || []).map(gl => ({
            uuid: gl.uuid,
            name: gl.name,
            code: gl.code,
            division: gl.division ? { uuid: gl.division.uuid, name: gl.division.name, code: gl.division.code } : null,
        }))
        
        if (!gradeLevels.value.length) {
            gradeLevels.value = newGradeLevels
        } else {
            // Merge new grade levels with existing ones (avoid duplicates)
            const existingUuids = new Set(gradeLevels.value.map(gl => gl.uuid))
            const toAdd = newGradeLevels.filter(gl => !existingUuids.has(gl.uuid))
            gradeLevels.value = [...gradeLevels.value, ...toAdd]
        }
        
        // Extract school years from audit response or students' academic histories
        if (res?.audit?.schoolYear) {
            const sy = res.audit.schoolYear
            const exists = schoolYears.value.some(s => s.uuid === sy.uuid)
            if (!exists) {
                schoolYears.value.push({
                    uuid: sy.uuid,
                    name: sy.name,
                })
            }
        }
        
        // Extract divisions from audit response and grade levels
        if (res?.audit?.division) {
            const div = res.audit.division
            const exists = divisions.value.some(d => d.uuid === div.uuid)
            if (!exists) {
                divisions.value.push({
                    uuid: div.uuid,
                    name: div.name,
                    code: div.code || '',
                })
            }
        }
        
        // Extract divisions from grade levels (grade levels have division relationship)
        if (newGradeLevels.length > 0) {
            const divisionMap = new Map(divisions.value.map(d => [d.uuid, d]))
            for (const gl of newGradeLevels) {
                if (gl.division && gl.division.uuid && !divisionMap.has(gl.division.uuid)) {
                    divisionMap.set(gl.division.uuid, {
                        uuid: gl.division.uuid,
                        name: gl.division.name,
                        code: gl.division.code || '',
                    })
                }
            }
            divisions.value = Array.from(divisionMap.values())
        }

        audit.value = res?.audit || null
    } catch (e) {
        console.error('[graduationAudit] dispatch failed', e)
    } finally {
        isLoading.value = false
        isLoadingInit.value = false
    }
}

// Load filter options dynamically
const loadFilterOptions = async () => {
    try {
        // Load grade levels
        if (!gradeLevels.value.length) {
            await loadGradeLevels()
        }
    } catch (e) {
        console.error('[loadFilterOptions] failed', e)
    }
}

const loadGradeLevels = async () => {
    try {
        // Use preRequisite from academicCourse to get grade levels (same as Show.vue pattern)
        const preReqRes = await store.dispatch('schoolSetup/academicCourse/preRequisite', { data: [] })
        const preReq = preReqRes?.data || preReqRes || {}
        if (preReq.gradeLevels) {
            gradeLevels.value = preReq.gradeLevels.map(gl => ({
                uuid: gl.uuid,
                name: gl.name,
                code: gl.code,
                division: gl.division ? { uuid: gl.division.uuid, name: gl.division.name, code: gl.division.code } : null,
            }))
        }
    } catch (e) {
        console.error('[loadGradeLevels] failed', e)
    }
}

const loadFirstStudentAndAudit = async () => {
    try {
        // Filter students to only parent's children
        const list = students.value
        if (!list.length) {
            isLoadingInit.value = false
            return
        }

        const first = list[0]
        if (!first?.uuid) {
            isLoadingInit.value = false
            return
        }

        selectedStudent.value = first.uuid
        await nextTick()
        await refreshAudit()
    } catch (e) {
        console.error('[bootstrap] failed to fetch first student', e)
    } finally {
        isLoadingInit.value = false
    }
}

// Friendly student name (for the modal header)
const selectedStudentName = computed(() => {
    const s = students.value.find(x => x.uuid === selectedStudent.value)
    return s ? `${s.firstName} ${s.lastName}` : ''
})

// Student first name only (for title)
const selectedStudentFirstName = computed(() => {
    const s = students.value.find(x => x.uuid === selectedStudent.value)
    return s?.firstName || ''
})

// --- grouping by course type for audit cells ---
const AUDIT_COURSE_TYPE_ORDER = ['required', 'electives']
const AUDIT_COURSE_TYPE_LABEL = {
    required: 'Required',
    electives: 'Electives',
}
const isOptionalCourseType = (item) => {
    const t = (item?.course_type ?? item?.courseType ?? item?.type ?? '').toString().toLowerCase()
    return t === 'electives' || t === 'optional'
}
const auditGetCourseType = (it) =>
    (it && String(it.courseType ?? it.type ?? it.course_type ?? '').toLowerCase()) || 'required'

const auditGroupByType = (items = []) => {
    const buckets = { required: [], electives: [] }
    items.forEach((it) => {
        const t = auditGetCourseType(it)
            ; (buckets[t] ?? buckets.required).push(it)
    })
    return buckets
}

const auditNonEmptyTypes = (groups) =>
    AUDIT_COURSE_TYPE_ORDER.filter((t) => (groups?.[t] || []).length)

const hasElectives = (row, gradeHeader) => {
    const inCell = Array.isArray(row?.grades?.[gradeHeader]) ? row.grades[gradeHeader] : []
    const { electives: cellElectives = [] } = auditGroupByType(inCell)
    return cellElectives.length > 0
}

const visibleItemsForCell = (row, gradeHeader) => {
    const inCell = Array.isArray(row?.grades?.[gradeHeader]) ? row.grades[gradeHeader] : []
    if (!inCell.length) return []
    const groups = auditGroupByType(inCell)
    // Only show required + academic core in the cell;
    // all electives (planner-only or otherwise) live in the Elective Courses modal.
    return [...(groups.required || []), ...(groups.academic_core || [])]
}

const isItemCompleted = (item) => {
    return item && (item.status === 'completed' || item.meta?.status === 'completed')
}

// Electives picker modal state (read-only list for parent)
const electives = ref({
    visible: false,
    list: [],
    context: { row: null, gradeHeader: '', gradeLevelUuid: null },
})

function openElectivesPicker(row, gradeHeader) {
    const inCell = Array.isArray(row?.grades?.[gradeHeader]) ? row.grades[gradeHeader] : []
    const { electives: cellElectives = [] } = auditGroupByType(inCell)

    const seen = new Set()
    const deduped = []
    for (const it of cellElectives) {
        const k = keyOf(it)
        if (!k || seen.has(k)) continue
        seen.add(k)
        deduped.push(it)
    }

    electives.value.visible = true
    electives.value.list = deduped
    electives.value.context = {
        row,
        gradeHeader,
    }
}

// Helper function to get planner bubble color classes
const getPlannerColorClasses = (planner, index) => {
    if (planner.isPrimary) {
        // Primary planner: GREEN
        return {
            bg: 'bg-green-100 border-green-300',
            text: 'text-green-900',
            button: 'text-green-700 hover:text-green-900 hover:bg-green-200'
        }
    }
    
    // Count non-primary planners before this one
    const nonPrimaryIndex = audit.value?.planners?.slice(0, index).filter(p => !p.isPrimary).length || 0
    
    if (nonPrimaryIndex === 0) {
        // Second planner (first non-primary): ORANGE
        return {
            bg: 'bg-orange-100 border-orange-300',
            text: 'text-orange-900',
            button: 'text-orange-700 hover:text-orange-900 hover:bg-orange-200'
        }
    }
    
    if (nonPrimaryIndex === 1) {
        // Third planner (second non-primary): PURPLE
        return {
            bg: 'bg-blue-100 border-blue-300',
            text: 'text-blue-900',
            button: 'text-blue-700 hover:text-blue-900 hover:bg-blue-200'
        }
    }
    
    // Fallback for any additional planners (shouldn't happen with max 3)
    return {
        bg: 'bg-gray-100 border-gray-300',
        text: 'text-gray-900',
        button: 'text-gray-700 hover:text-gray-900 hover:bg-gray-200'
    }
}

// Bullet color: only Green, Orange, Purple (by planner), or Gray (no planner)
const getCoursePlannerBulletColor = (plannerUuid) => {
    const GRAY = '#9ca3af'   // course not from any planner
    const GREEN = '#16a34a'  // primary planner
    const ORANGE = '#ea580c' // second planner
    const PURPLE = '#a855f7' // third planner

    if (!audit.value?.planners || !plannerUuid) return GRAY
    const planner = audit.value.planners.find(p => p.uuid === plannerUuid)
    if (!planner) return GRAY

    const index = audit.value.planners.findIndex(p => p.uuid === plannerUuid)
    const colorClasses = getPlannerColorClasses(planner, index)
    if (colorClasses.bg.includes('green')) return GREEN
    if (colorClasses.bg.includes('orange')) return ORANGE
    if (colorClasses.bg.includes('blue')) return PURPLE
    return GRAY
}

// Very light background color for course item (matches planner: green / orange / blue / gray)
const getCoursePlannerBgColor = (plannerUuid) => {
    const GRAY = '#f9fafb'   // gray-50
    const GREEN = '#f0fdf4'  // green-50
    const ORANGE = '#fff7ed' // orange-50
    const PURPLE = '#faf5ff' // blue-50

    if (!audit.value?.planners || !plannerUuid) return GRAY
    const planner = audit.value.planners.find(p => p.uuid === plannerUuid)
    if (!planner) return GRAY

    const index = audit.value.planners.findIndex(p => p.uuid === plannerUuid)
    const colorClasses = getPlannerColorClasses(planner, index)
    if (colorClasses.bg.includes('green')) return GREEN
    if (colorClasses.bg.includes('orange')) return ORANGE
    if (colorClasses.bg.includes('blue')) return PURPLE
    return GRAY
}

const getPlannerTitle = (plannerUuid) => {
    if (!audit.value?.planners || !plannerUuid) return ''
    const planner = audit.value.planners.find(p => p.uuid === plannerUuid)
    return planner?.title || ''
}

onMounted(async () => {
    // Load filter options first
    await loadFilterOptions()
    // Load roster (automatically filtered to parent's children via store dispatch)
    await refreshRoster()
    // Then load first student and audit
    await loadFirstStudentAndAudit()
})
</script>

<style scoped>
.hyphens-auto {
    hyphens: auto;
}

/* Course item: subtle hover darkening while keeping planner tint */
.course-item-bg:hover {
    filter: brightness(0.97);
}

/* Graduation Audit ONLY â€” compact modal overrides */
:deep(.gaudit--compact-modal .sm\:max-w-3\/4) {
    max-width: 28rem !important;
}

:deep(.gaudit--compact-modal .sm\:w-3\/4) {
    width: auto !important;
}

:deep(.gaudit--compact-modal .inline-block.w-full) {
    border-radius: 0.75rem;
}

/* Tighten header */
:deep(.gaudit--compact-modal h3) {
    padding: .5rem .75rem !important;
}

:deep(.gaudit--compact-modal h3 .text-lg) {
    font-size: .95rem !important;
    line-height: 1.25rem !important;
}

/* Remove/trim modal body padding */
:deep(.gaudit--compact-modal > * .p-4) {
    padding: 20px !important;
}
</style>
