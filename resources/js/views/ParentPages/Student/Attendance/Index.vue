<template>
    <PageHeader :title="$trans(route.meta.label)" :navs="[{ label: $trans('student.student'), path: '' }]">
        <PageHeaderAction url="student/attendance/" name="MyChildrenAttendance"
            :title="$trans('student.attendance.attendance')" :actions="userActions"
            :dropdown-actions="dropdownActions" />
    </PageHeader>

    <!-- Filter -->
    <!-- <ParentTransition appear :visibility="true">
        <FilterForm v-if="preRequisites.types.length" @afterFilter="onAfterFilter" :pre-requisites="preRequisites"
            :init-url="initUrl" />
    </ParentTransition> -->

    <ParentTransition appear :visibility="true">
        <BaseCard no-padding no-content-padding :is-loading="isLoading">
                <div class="p-6">
                    <div class="grid grid-cols-4">
                        <div class="col-span-1">
                            <span class="mb-4 p-3 items-center border border-gray-300 rounded-lg">
                                <span class="text-sm mx-3">{{ weekRange }}</span>
                            </span>
                        </div>

                        <div class="col-span-2">
                            <div class="mt-4 gap-4 text-sm text-right flex flex-wrap items-center justify-end">
                                <!-- Dynamic from DB -->
                                <span v-for="t in legendTypes" :key="t.id" class="items-center gap-1 mx-1 inline-flex">
                                    <span class="w-4 h-4 rounded-full inline-block"
                                        :style="{ backgroundColor: t.color || '#ccc' }"></span>
                                    {{ t.name }}
                                </span>

                                <!-- Static Holiday -->
                                <span class="items-center gap-1 mx-1 inline-flex">
                                    <span class="bg-white border border-black w-4 h-4 rounded-full inline-block"></span>
                                    Holiday
                                </span>
                            </div>


                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-center border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 border">ID</th>
                                    <th class="px-4 py-2 border">Student Name</th>
                                    <th v-for="day in weekDays" :key="day.dateRaw" :class="[
                                        'px-2 py-2 border text-center transition-all duration-300',
                                        isToday(day.dateRaw) ? 'border-gray-300 bg-green-100 rounded-md' : ''
                                    ]">
                                        <div>{{ day.dayName }}</div>
                                        <div class="text-xs text-gray-500">{{ day.dateFormatted }}</div>
                                        <div v-if="isToday(day.dateRaw)"
                                            class="text-green-500 text-xs font-semibold mt-1">(Today)</div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="student in studentsData" :key="student.student_id" class="hover:bg-gray-50">
                                    <td class="border px-2 py-1">{{ student.idNumber }}</td>
                                    <td class="border px-2 py-1 text-left">{{ student.name }}</td>

                                    <td v-for="day in weekDays" :key="day.dateRaw" :class="[
                                        'border px-2 py-1 transition-all duration-300',
                                        isToday(day.dateRaw) ? 'border-gray-300 bg-green-100 rounded-md' : ''
                                    ]">
                                        <span
                                            :style="statusStyle(student.attendance[day.dateRaw], day)"
                                            class="w-6 h-6 inline-flex items-center justify-center rounded-full text-xs select-none">
                                            {{ isSunday(day.dateRaw) ? 'H' : (student.attendance[day.dateRaw] || '-') }}
                                        </span>

                                        <span v-if="student.descriptions[day.dateRaw]" 
                                            @click="showCommentModal(student, day)"
                                            class="ml-3 cursor-pointer">
                                            <i class="fa-solid fa-comment text-blue-500" />
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
        </BaseCard>
    </ParentTransition>

    <!-- Comment View Modal (Read-only) -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
            <button @click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">✖</button>
            <h2 class="text-lg font-semibold mb-4">Comment</h2>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2">Description</label>
                <div class="w-full px-3 py-2 border rounded bg-gray-50 text-gray-700 min-h-[100px]">
                    {{ selectedStudent?.descriptions[selectedDay?.dateRaw] || 'No comment available' }}
                </div>
            </div>

            <div class="text-right">
                <BaseButton design="secondary" @click="closeModal">Close</BaseButton>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "StudentAttendance",
}
</script>

<script setup>
import { ref, reactive, inject, computed, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform, actingAs } from "@core/helpers/action"

const route = useRoute()
const router = useRouter()
const store = useStore()
const $trans = inject("$trans")

/** Header Actions (keep simple; replace with perform() if you prefer) */
// let userActions = ["config"]
// Conditionally show export actions: allow if user has permission OR is a parent
let dropdownActions = []
const isParent = actingAs(['parent-profile', 'guardian'], 'any')
if (perform("student:list-attendance") || isParent) {
    dropdownActions = ["print", "pdf", "excel"]
}

/** IMPORTANT: points to your Vuex module + API base */
const moduleNs = "student/attendance/"
const initUrl = "/app/student/attendance" // matches your module's initURL

const isLoading = ref(false)

/** PreRequisites (must include types; students, etc.) */
const preRequisites = reactive({
    types: [],          // [{id, name, code}]
    divisions: [],
    courses: [],
    grade_levels: [],
    classes: [],
    students: [],       // [{id, first_name, last_name, id_number}]
})

/** Current scope (date + context) */
const form = reactive({
    date: "",                 // YYYY-MM-DD
    context_type: "school",   // 'division'|'course'|'grade'|'class'|'school' - default to 'school' for parents
    context_id: null,         // null when 'school'
})

/** Table data */
const studentsData = ref([])  // [{student_id, idNumber, name, attendance{}, descriptions{}, visibility{}}]

/** Week helpers */
function getMonday(date) {
    const day = date.getDay()
    const diff = day === 0 ? -6 : 1 - day
    const monday = new Date(date)
    monday.setDate(date.getDate() + diff)
    return monday
}
function formatWithSuffix(date) {
    const month = date.toLocaleDateString('en-US', { month: 'short' })
    const day = date.getDate()
    const suf = (d) => (d > 3 && d < 21) ? 'th' : (['st', 'nd', 'rd'][((d % 10) - 1)] || 'th')
    return `${month} ${day}${suf(day)}`
}
const selectedDate = computed(() => {
    const dateString = route.query.date || new Date().toISOString().split('T')[0]
    return new Date(dateString)
})
const mondayDate = computed(() => getMonday(selectedDate.value))
const weekDays = computed(() => {
    const week = []
    for (let i = 0; i < 5; i++) {
        const d = new Date(mondayDate.value)
        d.setDate(mondayDate.value.getDate() + i)
        week.push({
            dayName: d.toLocaleDateString('en-US', { weekday: 'short' }),
            dateFormatted: d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }),
            dateRaw: d.toISOString().split('T')[0]
        })
    }
    return week
})
const weekRange = computed(() => {
    const start = mondayDate.value
    const end = new Date(start)
    end.setDate(start.getDate() + 6)
    return `${formatWithSuffix(start)} – ${formatWithSuffix(end)} (This week)`
})
const isToday = (dateRaw) => new Date().toISOString().split('T')[0] === dateRaw
const isSunday = (iso) => new Date(iso).getDay() === 0

/** Color map from DB (code -> hex) and style helper */
const typeColorByCode = computed(() => {
    const out = {}
    for (const t of preRequisites.types || []) {
        const code = String(t.code || '').toUpperCase()
        if (code) out[code] = t.color || '#e5e7eb' // fallback gray
    }
    return out
})

function hexToRgb(hex) {
    if (!hex) return { r: 229, g: 231, b: 235 } // gray-200 fallback
    let h = hex.replace('#', '')
    if (h.length === 3) h = h.split('').map(c => c + c).join('')
    const num = parseInt(h, 16)
    return { r: (num >> 16) & 255, g: (num >> 8) & 255, b: num & 255 }
}
function isDark(hex) {
    const { r, g, b } = hexToRgb(hex)
    // perceived luminance (WCAG-ish)
    const L = 0.2126 * (r / 255) + 0.7152 * (g / 255) + 0.0722 * (b / 255)
    return L < 0.6
}

/** Returns inline style for each chip based on code/day */
function statusStyle(code, day) {
    // Sunday/Holiday
    if (day && isSunday(day.dateRaw)) {
        return {
            backgroundColor: '#ffffff',
            color: '#111827',
            border: '1px solid #6b7280', // gray-500
        }
    }

    // Empty (no mark)
    if (!code) {
        return {
            backgroundColor: '#e5e7eb', // gray-200
            color: '#6b7280',           // gray-500
            border: '1px solid transparent',
        }
    }

    const hex = typeColorByCode.value[String(code).toUpperCase()] || '#e5e7eb'
    return {
        backgroundColor: hex,
        color: isDark(hex) ? '#ffffff' : '#111827',
        border: '1px solid transparent',
    }
}


/** Modal (comments - read-only) */
const showModal = ref(false)
const selectedStudent = ref(null)
const selectedDay = ref(null)
function showCommentModal(student, day) {
    selectedStudent.value = student
    selectedDay.value = day
    showModal.value = true
}
function closeModal() { 
    showModal.value = false
    selectedStudent.value = null
    selectedDay.value = null
}


/** Map id <-> code for fetch mapping */
const typeCodeById = computed(() => Object.fromEntries(preRequisites.types.map(t => [t.id, t.code])))

/** Load preRequisites */
async function loadPreRequisites() {
    isLoading.value = true
    const resp = await store.dispatch(`${moduleNs}preRequisite`)
    Object.assign(preRequisites, resp)
    isLoading.value = false

    console.log('preRequisites:', preRequisites.students)

}

/** Build the student rows for the week grid */
function hydrateStudentsForWeek() {
    studentsData.value = preRequisites.students.map(s => ({
        student_id: s.id,
        idNumber: s.idNumber,
        name: `${s.contact?.firstName || ""} ${s.contact?.lastName || ""}`.trim(),
        attendance: Object.fromEntries(weekDays.value.map(d => [d.dateRaw, ''])),
        descriptions: Object.fromEntries(weekDays.value.map(d => [d.dateRaw, ''])),
        visibility: {}
    }))
}

/** Fetch existing attendance for the selected date + context */
async function fetchExisting() {
    form.date = route.query.date || new Date().toISOString().slice(0, 10)
    form.context_type = route.query.context_type || 'school' // Default to 'school' for parents
    const queryContextId = route.query.context_id ? Number(route.query.context_id) : null
    
    // Only set context_id if context_type is not 'school' AND we have a valid context_id
    if (form.context_type === 'school') {
        form.context_id = null
    } else {
        form.context_id = queryContextId && queryContextId > 0 ? queryContextId : null
        // If no valid context_id and not 'school', default to 'school'
        if (!form.context_id) {
            form.context_type = 'school'
            form.context_id = null
        }
    }

    isLoading.value = true
    const params = {
        date: form.date,
        context_type: form.context_type,
    }
    
    // Only include context_id if context_type is not 'school'
    if (form.context_type !== 'school' && form.context_id) {
        params.context_id = form.context_id
    }
    
    const resp = await store.dispatch(`${moduleNs}fetch`, { params })
    isLoading.value = false

    // Process attendance for all days in the week
    // Service returns data with attendances object where keys are dates
    for (const row of (resp?.data || [])) {
        const s = studentsData.value.find(x => x.student_id === row.studentId)
        if (!s) continue
        
        // Process all attendances for this student (week data)
        if (row.attendances) {
            Object.keys(row.attendances).forEach(dateRaw => {
                const attendance = row.attendances[dateRaw]
                if (attendance.attendanceTypeCode) {
                    s.attendance[dateRaw] = attendance.attendanceTypeCode
                }
                if (attendance.comment) {
                    s.descriptions[dateRaw] = attendance.comment
                }
                if (attendance.visibleTo) {
                    s.visibility[dateRaw] = attendance.visibleTo
                }
            })
        }
    }
}


// All types except Holiday (exclude by code 'H' or name 'holiday')
const legendTypes = computed(() =>
    (preRequisites.types || []).filter(t => {
        const code = String(t.code || "").toUpperCase()
        const name = String(t.name || "").toLowerCase()
        return code !== "H" && name !== "holiday"
    })
)


/** Boot */
onMounted(async () => {
    await loadPreRequisites()
    hydrateStudentsForWeek()
    await fetchExisting()
})
</script>
