<template>
    <div class="mx-1 p-3 space-y-8 bg-white rounded-lg">
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <!-- Title -->
                <h1 class="mx-2 text-2xl font-bold">
                    <span v-if="studentFirstName">
                        {{ studentFirstName }}'s
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
            <div class="mb-2">No planner found for your current school year/division context yet.</div>
            <div class="text-xs">Tip: Make sure you have at least one academic history and a matching course planner.</div>
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
                            <!-- add (+) button (top-right of cell) -->
                            <div class="flex items-center justify-end -mt-1 -mr-1 mb-1">
                                <button
                                    class="w-5 h-5 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center text-xs"
                                    title="Request New Course" @click="openRequest('new', row, gh, null)">+</button>
                            </div>

                            <!-- Read-only course list (no drag-and-drop on student side) -->
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
                                        <!-- Completed courses: no withdrawal X; match admin behavior -->
                                        <button
                                            v-if="!isItemCompleted(item)"
                                            class="w-5 h-5 rounded-full bg-gray-100 hover:bg-red-100 text-red-600 flex items-center justify-center text-xs shrink-0"
                                            title="Request Withdrawal"
                                            @click="openRequest('withdrawal', row, gh, item)">Ã—</button>
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

    <!-- Request Modal -->
    <div class="gaudit--compact-modal">
        <BaseModal :visibility="showRequestModal" @close="showRequestModal = false" :title="modalTitle">

            <div class="grid grid-cols-2 gap-4">

                <div class="col-span-1">
                    <BaseSelect v-model="requestForm.division" name="division" :label="`Division`"
                        :options="[audit?.division].filter(Boolean)" label-prop="name" value-prop="uuid" :disabled="true" />
                </div>

                <div class="col-span-1">
                    <BaseSelect v-model="requestForm.gradeLevel" name="gradeLevel" :label="`Grade Level`"
                        :options="audit?.gradeLevels || []" label-prop="name" value-prop="uuid" :disabled="true" />
                </div>

                <div class="col-span-1">
                    <BaseSelect v-model="requestForm.subject" name="subject" :label="`Subject`"
                        :options="[{ uuid: requestContext.subject.uuid, name: requestContext.subject.name }]"
                        label-prop="name" value-prop="uuid" :disabled="true" />
                </div>

                <div class="col-span-1">
                    <BaseSelect v-model="requestForm.course" name="course" :label="`Course`"
                        :options="courseOptions" label-prop="name" value-prop="uuid"
                        :disabled="requestForm.type === 'withdrawal' && !!requestContext.item" />
                </div>

            </div>
            <BaseTextarea v-model="requestForm.reason" name="reason" :label="`Reason`"
                placeholder="Brief reason for this request" rows="3" />

            <div class="mt-6 text-right">
                <button class="bg-blue-500 px-4 py-2 rounded text-white" @click="submitRequest"
                    :disabled="submitting">
                    {{ submitting ? 'Savingâ€¦' : 'Save' }}
                </button>
            </div>
        </BaseModal>
    </div>

    <!-- Electives Picker Modal -->
    <div class="gaudit--compact-modal">
        <BaseModal :visibility="electives.visible" @close="electives.visible = false" title="Select an Elective">

            <template #title>Choose Your Elective Course </template>
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
                    <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded"
                        @click="requestElective(c)">
                        Request
                    </button>
                </div>
            </div>
        </BaseModal>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, inject } from 'vue'
import { useStore } from 'vuex'
import * as Api from '@core/apis'
import BaseModal from '@core/components/Ui/BaseModal.vue'
import BaseSelect from '@core/components/Forms/BaseSelect.vue'
import BaseTextarea from '@core/components/Forms/BaseTextarea.vue'
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

const isLoading = ref(false)
const audit = ref(null)

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

const refreshAudit = async () => {
    isLoading.value = true
    try {
        const res = await store.dispatch('student/studentProfile/getMyGraduationAudit', {})
        
        audit.value = res?.audit || null
    } catch (e) {
        console.error('[getMyGraduationAudit] failed', e)
        audit.value = null
    } finally {
        isLoading.value = false
    }
}

// Student first name for title
const studentFirstName = computed(() => {
    // Try to get from store or audit response
    const user = store.getters['auth/user']
    return user?.firstName || user?.first_name || ''
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
    // Only show required in the cell;
    // all electives (planner-only or otherwise) live in the Elective Courses modal.
    return [...(groups.required || [])]
}

const isItemCompleted = (item) => {
    return item && (item.status === 'completed' || item.meta?.status === 'completed')
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

/* Request modal state */
const showRequestModal = ref(false)
const submitting = ref(false)
const modalTitle = computed(() =>
    requestForm.value.type === 'withdrawal' ? 'Request Course Withdrawal' : 'Request New Course'
)
const requestContext = ref({
    subject: { uuid: null, name: '' },
    gradeHeader: '',
    gradeLevelUuid: null,
    item: null,
})
const requestForm = ref({
    type: 'new',
    schoolYear: null,
    division: null,
    gradeLevel: null,
    subject: null,
    course: null,
    reason: '',
})

const gradeHeaderToUuid = (name) => {
    const gl = (audit.value?.gradeLevels || []).find(g => g.name === name)
    return gl?.uuid || null
}

/* new-course dropdown: union across grades for subject, excluding current cell */
const courseOptions = computed(() => {
    if (!audit.value || !requestContext.value.subject?.uuid || !requestContext.value.gradeHeader) return []
    const row = audit.value.subjects.find(r => {
        const subjectUuid = r.subject?.uuid || r.subject
        return subjectUuid === requestContext.value.subject.uuid
    })
    if (!row) return []

    if (requestForm.value.type === 'withdrawal') {
        const items = row?.grades?.[requestContext.value.gradeHeader] || []
        return dedupeByKey(items.map(i => ({ uuid: keyOf(i), name: i.name })))
    }

    const allItems = Object.values(row.grades || {}).flat()
    const allOptions = dedupeByKey(allItems.map(i => ({ uuid: keyOf(i), name: i.name })))
    const inCell = row?.grades?.[requestContext.value.gradeHeader] || []
    const exclude = new Set(inCell.map(i => keyOf(i)))
    return allOptions.filter(o => !exclude.has(o.uuid))
})

function dedupeByKey(arr) {
    const map = new Map()
    for (const it of arr) if (it?.uuid && !map.has(it.uuid)) map.set(it.uuid, it)
    return Array.from(map.values())
}

function openRequest(type, row, gradeHeader, item) {
    const divisionUuid = audit.value?.division?.uuid || null
    const gradeLevelUuid = gradeHeaderToUuid(gradeHeader)

    // Handle subject - can be string or object
    const subjectUuid = row.subject?.uuid || row.subject
    const subjectName = row.subject?.name || row.subject

    requestContext.value = {
        subject: { uuid: subjectUuid, name: subjectName },
        gradeHeader,
        gradeLevelUuid,
        item,
    }

    requestForm.value = {
        type,
        schoolYear: null, // Not needed anymore
        division: divisionUuid,
        gradeLevel: gradeLevelUuid,
        subject: subjectUuid,
        course: item?.course?.uuid || item?.uuid || null,
        reason: '',
    }

    showRequestModal.value = true
}

async function submitRequest() {
    if (!requestForm.value.course && requestForm.value.type === 'withdrawal' && requestContext.value.item?.course?.uuid) {
        requestForm.value.course = requestContext.value.item.course.uuid
    }

    submitting.value = true
    try {
        const payload = {
            request_type: requestForm.value.type,
            school_year: requestForm.value.schoolYear,
            division: requestForm.value.division,
            grade_level: requestForm.value.gradeLevel,
            subject: requestForm.value.subject,
            course: requestForm.value.course,
            reason: requestForm.value.reason,
        }

        await Api.custom({
            url: '/app/students/my-planner-course-requests',
            method: 'POST',
            data: payload,
        })

        // Optimistic UI, server merge will persist on refresh
        const row = audit.value.subjects.find(r => {
            const subjectUuid = r.subject?.uuid || r.subject
            return subjectUuid === requestContext.value.subject.uuid
        })
        const gh = requestContext.value.gradeHeader

        if (requestForm.value.type === 'new') {
            const sel = (courseOptions.value || []).find(o => o.uuid === requestForm.value.course)
            const name = sel?.name || 'New Course'
            row.grades[gh] ??= []
            row.grades[gh].push({
                name,
                credit: 'requested',
                _requested: 'new',
                course: { uuid: requestForm.value.course }
            })
        } else {
            const arr = row?.grades?.[gh] || []
            const idx = arr.findIndex(i => keyOf(i) === (requestContext.value.item && keyOf(requestContext.value.item)))
            if (idx !== -1) arr[idx] = { ...arr[idx], _requested: 'withdrawal' }
        }

        showRequestModal.value = false
        await refreshAudit()
    } catch (e) {
        console.error('[createPlannerCourseRequest] failed', e)
    } finally {
        submitting.value = false
    }
}

// Electives picker modal state
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
        gradeLevelUuid: gradeHeaderToUuid(gradeHeader),
    }
}

async function requestElective(course) {
    const ctx = electives.value.context
    if (!ctx?.row || !ctx.gradeHeader) return

    const divisionUuid = audit.value?.division?.uuid || null
    const gradeLevelUuid = ctx.gradeLevelUuid || gradeHeaderToUuid(ctx.gradeHeader)

    const subjectUuid = ctx.row?.subject?.uuid || ctx.row?.subject || null
    const courseUuid = course?.course?.uuid || course?.uuid || null

    if (!divisionUuid || !gradeLevelUuid || !subjectUuid || !courseUuid) {
        console.warn('[elective-request] missing required uuid(s)', {
            divisionUuid, gradeLevelUuid, subjectUuid, courseUuid
        })
        return
    }

    submitting.value = true
    try {
        const payload = {
            request_type: 'new',
            school_year: null, // Not needed anymore
            division: divisionUuid,
            grade_level: gradeLevelUuid,
            subject: subjectUuid,
            course: courseUuid,
            reason: '',
        }

        await Api.custom({
            url: '/app/students/my-planner-course-requests',
            method: 'POST',
            data: payload,
        })

        const gh = ctx.gradeHeader
        const row = ctx.row

        row.grades[gh] ??= []
        const k = (i) => i?.course?.uuid || i?.uuid || slug(i?.name)
        const exists = row.grades[gh].some(i => k(i) === courseUuid)

        if (!exists) {
            row.grades[gh].push({
                name: course?.name ?? 'New Course',
                credit: 'requested',
                _requested: 'new',
                course: { uuid: courseUuid },
            })
        }

        electives.value.visible = false
        await refreshAudit()
    } catch (e) {
        console.error('[createPlannerCourseRequest] direct elective request failed', e?.response?.data || e)
    } finally {
        submitting.value = false
    }
}

onMounted(refreshAudit)
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
