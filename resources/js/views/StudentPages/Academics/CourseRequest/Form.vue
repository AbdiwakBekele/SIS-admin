<template>
    <div class="mx-1 p-3 space-y-4 bg-white rounded-lg shadow-md">
        <div>
            <h1 class="text-3xl font-bold text-center">Course Planner & Graduation Audit</h1>
        </div>

        <div v-if="isLoading" class="p-6 bg-white rounded-lg shadow-lg text-sm text-gray-600">
            Loading graduation audit…
        </div>

        <div v-else-if="!audit" class="p-6 bg-white rounded-lg shadow-lg text-sm text-gray-600">
            <div class="mb-2">No planner found for your current school year/division context yet.</div>
            <div class="text-xs">Tip: Make sure you have at least one academic history and a matching course planner.
            </div>
        </div>

        <div v-else class="p-3 bg-white rounded-lg shadow-lg overflow-x-auto">
            <span class="mb-5 text-sm text-gray-600">
                <span class="mx-4"><strong>School Year:</strong> {{ audit.schoolYear?.name || '-' }}</span>
                <span class="mx-4"><strong>Division:</strong> {{ audit.division?.name || '-' }}</span>
                <span v-if="audit.planner?.title" class="mx-4"><strong>Planner:</strong>
                    {{ audit.planner.title }}</span>
            </span>

            <table class="w-full table-fixed border border-gray-300 mt-3">
                <colgroup>
                    <col style="width: 10rem;" />
                    <col v-for="gh in audit.gradeHeaders" :key="'col-' + gh" style="width: 10rem;" />
                    <col style="width: 8rem;" />
                    <col style="width: 4rem;" />
                </colgroup>

                <thead class="bg-gray-100 text-[11px] text-gray-600 uppercase tracking-wide">
                    <tr>
                        <th class="border px-3 py-2 text-left">Subject Name</th>
                        <th v-for="gh in audit.gradeHeaders" :key="gh" class="border px-3 py-2 text-center">
                            {{ gh }}
                        </th>
                        <th class="border px-3 py-2 text-left">Credits</th>
                        <th class="border px-2 py-2 text-center">On Track</th>
                    </tr>
                </thead>

                <tbody class="align-top">
                    <tr v-for="row in audit.subjects" :key="row.subject.uuid || row.subject" class="border-t">
                        <td class="border px-3 py-2 font-medium text-sm break-words hyphens-auto">
                            {{ row.subject.name || row.subject }}
                        </td>

                        <td v-for="gh in audit.gradeHeaders" :key="`${row.subject.name || row.subject}-${gh}`"
                            class="border px-2 py-2 align-top">
                            <!-- add (+) button (unchanged) -->
                            <div class="flex items-center justify-end -mt-1 -mr-1 mb-1">
                                <button
                                    class="w-5 h-5 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center text-xs"
                                    title="Request New Course" @click="openRequest('new', row, gh, null)">+</button>
                            </div>

                            <!-- grouped content -->
                            <div v-if="row.grades?.[gh]?.length">
                                <template
                                    v-for="(ctype, idx) in auditNonEmptyTypes(cellGroups = auditGroupByType(row.grades[gh]))"
                                    :key="ctype">
                                    <!-- Group header -->
                                    <div class="text-[10px] font-semibold uppercase text-gray-600 mb-1">
                                        {{ AUDIT_COURSE_TYPE_LABEL[ctype] || ctype }}
                                    </div>

                                    <!-- Required / Academic Core: list inline as before -->
                                    <div v-if="ctype !== 'electives'" class="space-y-1">
                                        <div v-for="item in cellGroups[ctype]"
                                            :key="`${row.subject.name || row.subject}-${gh}-${keyOf(item)}`"
                                            class="text-[11px] leading-tight break-words hyphens-auto flex items-start gap-1">
                                            <div class="flex-1">
                                                <span class="font-medium">
                                                    {{ item.name }}
                                                    <template v-if="item._requested === 'new'">
                                                        <em class="text-gray-500"></em>
                                                    </template>
                                                    <template v-else-if="item._requested === 'withdrawal'">
                                                        <em class="text-gray-500"> (withdrawal requested)</em>
                                                    </template>
                                                </span>
                                                <br />
                                                <span class="text-[10px] text-gray-500">({{ item.credit }})</span>
                                            </div>

                                            <button
                                                class="w-5 h-5 rounded-full bg-gray-100 hover:bg-red-100 text-red-600 flex items-center justify-center text-xs shrink-0"
                                                title="Request Withdrawal"
                                                @click="openRequest('withdrawal', row, gh, item)">×</button>
                                        </div>
                                    </div>

                                    <!-- Electives: show hint that opens the picker modal -->
                                    <div v-else class="mt-1">
                                        <button type="button"
                                            class="text-[10px] text-blue-600 italic underline hover:no-underline"
                                            @click="openElectivesPicker(row, gh)">
                                            Click here to select your course
                                        </button>
                                    </div>


                                    <!-- Divider between groups -->
                                    <div v-if="idx < auditNonEmptyTypes(cellGroups).length - 1"
                                        class="h-px bg-gray-300 my-2"></div>
                                </template>
                            </div>

                            <div v-else class="text-[11px] text-gray-400">—</div>
                        </td>




                        <td class="border px-2 py-2 align-top">
                            <div class="text-[11px] leading-4">✅ {{ fmt(row.credits.completed) }} Completed</div>
                            <div class="text-[11px] leading-4">📘 {{ fmt(row.credits.planned) }} Planned</div>
                            <div class="text-[11px] leading-4">📘 {{ fmt(row.credits.enrolled) }} Enrolled</div>
                            <div class="text-[11px] leading-4">🎯 {{ fmt(row.credits.required) }} Required</div>
                        </td>

                        <td class="border px-1 py-2 text-center align-top">
                            <span v-if="row.onTrack"
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-600 text-xs font-bold"
                                title="On Track">✔</span>
                            <span v-else
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-100 text-red-600 text-xs font-bold"
                                title="Off Track">✘</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Request Modal (unchanged, still using BaseModal/BaseSelect) -->
        <div class="gaudit--compact-modal">
            <BaseModal :visibility="showRequestModal" @close="showRequestModal = false" :title="modalTitle">

                <div class="grid grid-cols-3 gap-4">

                    <div class="col-span-1">
                        <BaseSelect v-model="requestForm.schoolYear" name="schoolYear" :label="`School Year`"
                            :options="[audit.schoolYear]" label-prop="name" value-prop="uuid" :disabled="true" />
                    </div>

                    <div class="col-span-1">
                        <BaseSelect v-model="requestForm.division" name="division" :label="`Division`"
                            :options="[audit.division]" label-prop="name" value-prop="uuid" :disabled="true" />
                    </div>

                    <div class="col-span-1">
                        <BaseSelect v-model="requestForm.gradeLevel" name="gradeLevel" :label="`Grade Level`"
                            :options="audit.gradeLevels || []" label-prop="name" value-prop="uuid" :disabled="true" />
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
                        {{ submitting ? 'Saving…' : 'Save' }}
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
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useStore } from 'vuex'

const store = useStore()

const isLoading = ref(false)
const submitting = ref(false)
const audit = ref(null)

/* utils */
const fmt = (v) => (v === null || v === undefined) ? '0' : (isNaN(Number(v)) ? v : Number(v).toFixed(2))
const slug = (s = '') => String(s).toLowerCase().trim().replace(/\s+/g, '-')
const keyOf = (item) => item?.course?.uuid || item?.uuid || slug(item?.name)

/* load audit + then overlay requests from Vuex module */
const refreshAudit = async () => {
    isLoading.value = true
    try {
        const res = await store.dispatch('student/studentProfile/getMyGraduationAudit', { params: {} })
        audit.value = res?.audit || null
        if (audit.value) await overlayRequests()
    } catch (e) {
        console.error('[getMyGraduationAudit] failed', e)
        audit.value = null
    } finally {
        isLoading.value = false
    }
}

const overlayRequests = async () => {
    // fetch via the new module (paginated or plain)
    const resp = await store.dispatch('student/studentProfile/getMyPlannerCourseRequests', {
        per_page: 1000,
        // you can also pass division/school_year to filter server-side if your endpoint supports it
        // division: audit.value.division?.uuid,
        // school_year: audit.value.schoolYear?.uuid,
    })

    const list = (resp?.data?.data || resp?.data || resp || [])
    if (!Array.isArray(list) || !audit.value) return

    // map: gradeLevel.uuid -> name
    const gradeNameByUuid = Object.fromEntries(
        (audit.value.gradeLevels || []).map(gl => [gl.uuid, gl.name])
    )

    // iterate requests (camelCased by axios init interceptor)
    for (const r of list) {
        // supports either academicSubject/academicCourse OR subject/course keys
        const subjUuid = r?.academicSubject?.uuid || r?.subject?.uuid || r?.academicSubjectUuid || r?.subjectUuid
        const course = r?.academicCourse || r?.course
        const courseUuid = course?.uuid || r?.academicCourseUuid || r?.courseUuid
        const glUuid = r?.gradeLevel?.uuid || r?.gradeLevelUuid
        const glName = gradeNameByUuid[glUuid]
        if (!subjUuid || !glName || !courseUuid) continue

        const row = audit.value.subjects.find(s => (s.subject?.uuid || s.subject) === subjUuid)
        if (!row) continue
        row.grades[glName] ??= []

        if (r.requestType === 'withdrawal') {
            const idx = row.grades[glName].findIndex(i => keyOf(i) === courseUuid)
            if (idx !== -1) {
                row.grades[glName][idx] = { ...row.grades[glName][idx], _requested: 'withdrawal' }
            }
        } else if (r.requestType === 'new') {
            const exists = row.grades[glName].some(i => keyOf(i) === courseUuid)
            if (!exists) {
                const credit = course?.courseCredit != null
                    ? `${Number(course.courseCredit).toFixed(2)} Cr (requested)`
                    : 'requested'
                row.grades[glName].push({
                    name: course?.name || 'New Course',
                    credit,
                    _requested: 'new',
                    course: { uuid: courseUuid },
                })
            }
        }
    }
}

/* modal state (unchanged) */
const showRequestModal = ref(false)
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
    const row = audit.value.subjects.find(r => (r.subject.uuid || r.subject) === requestContext.value.subject.uuid)
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
    const schoolYearUuid = audit.value?.schoolYear?.uuid || null
    const divisionUuid = audit.value?.division?.uuid || null
    const gradeLevelUuid = gradeHeaderToUuid(gradeHeader)

    requestContext.value = {
        subject: { uuid: row.subject.uuid || row.subject, name: row.subject.name || row.subject },
        gradeHeader,
        gradeLevelUuid,
        item,
    }

    requestForm.value = {
        type,
        schoolYear: schoolYearUuid,
        division: divisionUuid,
        gradeLevel: gradeLevelUuid,
        subject: row.subject.uuid || row.subject,
        course: item?.course?.uuid || null,
        reason: '',
    }

    showRequestModal.value = true
}

async function submitRequest() {

    console.log(requestForm)
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

        console.log('payload (json):', JSON.stringify(payload, null, 2))
        await store.dispatch('student/studentProfile/createPlannerCourseRequest', payload)

        // Optimistic UI, server merge will persist on refresh
        const row = audit.value.subjects.find(r => (r.subject.uuid || r.subject) === requestContext.value.subject.uuid)
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
    } catch (e) {
        console.error('[createPlannerCourseRequest] failed', e)
    } finally {
        submitting.value = false
    }
}

// --- grouping by course type for grade cells ---
const AUDIT_COURSE_TYPE_ORDER = ['required', 'electives']
const AUDIT_COURSE_TYPE_LABEL = {
    required: 'Required',
    electives: 'Electives',
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


// Electives picker modal state (unchanged)
const electives = ref({
    visible: false,
    list: [],
    context: { row: null, gradeHeader: '', gradeLevelUuid: null },
})

// Only use electives that are already loaded IN THIS CELL
function openElectivesPicker(row, gradeHeader) {
    // Take the cell’s items
    const inCell = Array.isArray(row?.grades?.[gradeHeader]) ? row.grades[gradeHeader] : []

    // Filter to electives only using your existing helper
    const { electives: cellElectives = [] } = auditGroupByType(inCell)

    // (Optional) Dedupe by key if needed
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
    const ctx = electives.value.context;
    if (!ctx?.row || !ctx.gradeHeader) return;

    // Pull UUIDs from the audit/row context
    const schoolYearUuid = audit.value?.schoolYear?.uuid || null;
    const divisionUuid = audit.value?.division?.uuid || null;
    const gradeLevelUuid = ctx.gradeLevelUuid || gradeHeaderToUuid(ctx.gradeHeader);

    // Subject must be a UUID (not the name)
    const subjectUuid = ctx.row?.subject?.uuid || ctx.row?.subject || null;

    // Course UUID can be under course.uuid (planner/history rows) or item.uuid
    const courseUuid = course?.course?.uuid || course?.uuid || null;

    if (!schoolYearUuid || !divisionUuid || !gradeLevelUuid || !subjectUuid || !courseUuid) {
        console.warn('[elective-request] missing required uuid(s)', {
            schoolYearUuid, divisionUuid, gradeLevelUuid, subjectUuid, courseUuid
        });
        // optional: toast here
        return;
    }

    submitting.value = true;
    try {
        // Send directly — no second modal
        const payload = {
            request_type: 'new',
            school_year: schoolYearUuid,
            division: divisionUuid,
            grade_level: gradeLevelUuid,
            subject: subjectUuid,
            course: courseUuid,
            reason: '',           // no reason for direct request; add if you want
        };

        await store.dispatch('student/studentProfile/createPlannerCourseRequest', payload);

        // Optimistic UI: add to the current cell as "requested" if it isn't there yet
        const gh = ctx.gradeHeader;
        const row = ctx.row;

        row.grades[gh] ??= [];
        const k = (i) => i?.course?.uuid || i?.uuid || slug(i?.name);
        const exists = row.grades[gh].some(i => k(i) === courseUuid);

        if (!exists) {
            row.grades[gh].push({
                name: course?.name ?? 'New Course',
                credit: 'requested',
                _requested: 'new',
                course: { uuid: courseUuid },
            });
        }

        electives.value.visible = false;
        // optional: toast success
    } catch (e) {
        console.error('[createPlannerCourseRequest] direct elective request failed', e?.response?.data || e);
        // optional: toast error e?.response?.data?.message
    } finally {
        submitting.value = false;
    }
}





onMounted(refreshAudit)
</script>



<style scoped>
.hyphens-auto {
    hyphens: auto;
}

/* Graduation Audit ONLY — compact modal overrides */
:deep(.gaudit--compact-modal .sm\:max-w-3\/4) {
    max-width: 28rem !important;
    /* ~352px */
}

:deep(.gaudit--compact-modal .sm\:w-3\/4) {
    width: auto !important;
}

:deep(.gaudit--compact-modal .inline-block.w-full) {
    border-radius: 0.75rem;
    /* rounded-xl visual */
}

/* Tighten header */
:deep(.gaudit--compact-modal h3) {
    padding: .5rem .75rem !important;
    /* px-3 py-2 */
}

:deep(.gaudit--compact-modal h3 .text-lg) {
    font-size: .95rem !important;
    line-height: 1.25rem !important;
}

/* Remove/trim modal body padding (choose one) */
:deep(.gaudit--compact-modal > * .p-4) {
    padding: 20px !important;
    /* NO whitespace */
    /* or: padding: .5rem .75rem !important;  // slight padding */
}
</style>
