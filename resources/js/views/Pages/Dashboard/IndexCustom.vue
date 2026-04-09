<template>
    <div v-if="isTeacherDashboardUser" class="p-6 space-y-6">
        <div class="flex items-center">
            <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
            <div class="xl:col-span-3">
                <AnnouncementsCard :style="{ height: topBlockHeight + 'px' }" class="w-full" />
            </div>

            <div class="xl:col-span-2">
                <GamificationCard
                    key="teacher-staff-gamification-card"
                    :style="{ height: topBlockHeight + 'px' }"
                    heading-text="Staff Gamification"
                    source="staff"
                    :gradient="{ from: 'from-orange-700', via: 'via-orange-500', to: 'to-orange-400' }"
                    class="h-full"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
            <div class="xl:col-span-3 min-h-[520px]">
                <div
                    class="bg-white dark:bg-neutral-800 shadow-2xl rounded-2xl overflow-hidden h-full flex flex-col"
                    :style="{ height: topBlockHeight + 'px' }"
                >
                    <div class="relative bg-gradient-to-br from-cyan-900 via-cyan-700 to-cyan-400 text-white px-5 sm:px-6 py-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                                    <i class="fa-solid fa-briefcase text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-semibold">Productivity</h2>
                                    <p class="text-xs opacity-90">My schedule and tasks in one place.</p>
                                </div>
                            </div>
                            <div class="text-right text-xs opacity-90">
                                <div>{{ teacherWeekRangeLabel }}</div>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-white/10"></div>
                    </div>

                    <div class="flex w-full border-b border-gray-200 dark:border-neutral-700">
                        <button
                            v-for="tab in teacherTabs"
                            :key="tab.key"
                            :class="[
                                'flex-1 py-3 px-2 sm:px-3 text-xs sm:text-sm font-semibold transition focus:outline-none',
                                activeTeacherTab === tab.key
                                    ? 'bg-cyan-50 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-300 border-b-2 border-cyan-600 dark:border-cyan-400'
                                    : 'hover:bg-gray-50 dark:hover:bg-neutral-700 text-gray-500 dark:text-neutral-300',
                            ]"
                            @click="activeTeacherTab = tab.key"
                        >
                            <i :class="tab.icon" class="mr-1.5 sm:mr-2"></i>{{ tab.label }}
                        </button>
                    </div>

                    <div class="p-5 sm:p-6 flex-1 overflow-y-auto min-h-0">
                        <transition name="fade-soft" appear>
                            <div v-show="activeTeacherTab === 'schedule'">
                                <ul v-if="teacherScheduleItems.length" class="space-y-3">
                                    <li
                                        v-for="item in teacherScheduleItems"
                                        :key="item.date"
                                        class="rounded-lg border border-gray-200 dark:border-neutral-700 p-3 bg-gray-50/70 dark:bg-neutral-900/40"
                                    >
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="font-semibold text-sm text-gray-900 dark:text-neutral-100">
                                                    {{ item.dayLabel }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-neutral-400 mt-1">
                                                    {{ item.dateLabel }}
                                                </p>
                                            </div>
                                            <span
                                                class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold"
                                                :class="teacherStatusClass(item.status)"
                                            >
                                                {{ item.statusLabel }}
                                            </span>
                                        </div>

                                        <div v-if="item.shiftSummary.length" class="mt-3 space-y-2">
                                            <div
                                                v-for="shift in item.shiftSummary"
                                                :key="shift.key"
                                                class="rounded-lg border border-cyan-200 bg-cyan-50 px-3 py-2 text-xs text-cyan-900"
                                            >
                                                <div class="font-semibold">{{ shift.name }}</div>
                                                <div class="text-cyan-700">{{ shift.timeLabel }}</div>
                                            </div>
                                        </div>

                                        <p v-else-if="item.note" class="mt-3 text-xs text-gray-500 dark:text-neutral-400">
                                            {{ item.note }}
                                        </p>
                                    </li>
                                </ul>
                                <p v-else class="text-sm text-gray-500 dark:text-neutral-400 italic">
                                    No personal schedule available right now.
                                </p>
                            </div>
                        </transition>

                        <transition name="fade-soft" appear>
                            <div v-show="activeTeacherTab === 'todo'">
                                <ul v-if="teacherTodoItems.length" class="space-y-3">
                                    <li
                                        v-for="item in teacherTodoItems"
                                        :key="item.id"
                                        class="rounded-lg border border-gray-200 dark:border-neutral-700 p-3 bg-gray-50/70 dark:bg-neutral-900/40"
                                    >
                                        <p class="font-medium text-sm text-gray-900 dark:text-neutral-100">
                                            {{ item.title }}
                                        </p>
                                        <p v-if="item.due" class="text-xs text-gray-500 dark:text-neutral-400 mt-1">
                                            Due: {{ item.due }}
                                        </p>
                                    </li>
                                </ul>
                                <p v-else class="text-sm text-gray-500 dark:text-neutral-400 italic">
                                    No tasks right now.
                                </p>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-2 min-h-[520px]">
                <CalendarCard
                    :events="calendarItems.events"
                    :meetings="calendarItems.meetings"
                    :style="{ height: topBlockHeight + 'px' }"
                    class="h-full"
                />
            </div>
        </div>
    </div>

    <div v-else class="p-6 space-y-6">
        <!-- Page Title -->
        <div class="grid grid-cols-1 xl:grid-cols-9 gap-6">
            <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
            <div class="col-span-2">
                <KpiTile title="Total Students" :value="studentCount" icon="fa-solid fa-user-graduate" subnote=""
                    class="bg-gradient-to-br from-amber-700 to-yellow-400" />
            </div>

            <div class="col-span-2">
                <KpiTile title="New Students" :value="newStudentCount" icon="fa-solid fa-user-graduate" subnote=""
                    class="bg-gradient-to-br from-green-800 to-green-400" />
            </div>

            <div class="col-span-2">
                <KpiTile title="Student Attendance" :value="studentAttendancePercentage" icon="fa-solid fa-calendar-check"
                    class="bg-gradient-to-br from-cyan-700 to-cyan-400" />
            </div>

            <div class="col-span-2">
                <KpiTile title="Staff Attendance" value="0%" icon="fa-solid fa-calendar-check"
                class="bg-gradient-to-br from-orange-800 to-orange-400" />
            </div>

        </div>


        <!-- ROW 1: Left = Announcements (fixed height). Right = KPI tiles + Gamification (same height) -->
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
            <div class="xl:col-span-3">
                <AnnouncementsCard class="w-full" />
            </div>

            <div class="xl:col-span-2 flex flex-col gap-6">
                <AttendanceCard v-if="canViewAttendance" :data="attendanceData" />
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <GamificationCard
                key="student-gamification-card"
                class="min-h-[480px]"
                heading-text="Student Gamification"
                source="student"
                :gradient="{ from: 'from-amber-700', via: 'via-amber-500', to: 'to-yellow-400' }"
            />
            <GamificationCard
                key="staff-gamification-card"
                class="min-h-[480px]"
                heading-text="Staff Gamification"
                source="staff"
                :gradient="{ from: 'from-orange-700', via: 'via-orange-500', to: 'to-orange-400' }"
            />
            <TodoCard
                class="min-h-[480px]"
                @toggle="onToggleTodo"
                @remove="onRemoveTodo"
            />
        </div>

        <!-- ROW: Calendar + Grade distribution + Student Enrollment Trend -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
            <div class="min-h-[280px]">
                <CalendarCard :events="calendarItems.events" :meetings="calendarItems.meetings" class="h-full" />
            </div>

            <div class="min-h-[280px]">
                <GradeDistributionChart
                    :labels="gradeLabels"
                    :data="gradeData"
                    :colors="['#10b981', '#14b8a6', '#f59e0b', '#ec4899', '#8b5cf6']"
                    class="h-full"
                />
            </div>

            <div class="min-h-[280px]">
                <EnrollmentTrendChart
                    title="Student Enrollment Trend"
                    badge="Last 6 months"
                    :labels="enrollmentLabels"
                    :data="enrollmentData"
                    color="#3b82f6"
                    fillColor="rgba(59,130,246,0.15)"
                    class="h-full"
                />
            </div>
        </div>

        <!-- ROW 4: Enrollment Trend + Donuts (unchanged, aligned below) -->
        <div class="grid grid-cols-2 gap-5">

            <DonutChart title="Student Type Breakdown" :labels="studentTypeLabels"
                :data="studentTypeData" :colors="['#3b82f6', '#10b981', '#f59e0b']"
                headerGradient="bg-gradient-to-br from-emerald-700 to-emerald-400" />
            <DonutChart title="Staff Type Breakdown" :labels="staffTypeLabels" :data="staffTypeData"
                :colors="['#d946ef', '#8b5cf6']"
                headerGradient="bg-gradient-to-br from-rose-700 to-rose-400" />
        </div>
    </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { perform } from '@core/helpers/action'
import AnnouncementsCard from './Cards/AnnouncementsCard.vue'
import GamificationCard from './Cards/GamificationCard.vue'
import KpiTile from './Cards/KpiTile.vue'
import EnrollmentTrendChart from './Cards/EnrollmentTrendChart.vue'
import GradeDistributionChart from './Cards/GradeDistributionChart.vue'
import DonutChart from './Cards/DonutChart.vue'
import CalendarCard from './Cards/CalendarCard.vue'
import AttendanceCard from './Cards/AttendanceCard.vue'
import TodoCard from './Cards/TodoCard.vue'
import { storeKey, useStore } from 'vuex/dist/vuex.cjs.js'

const store = useStore(storeKey)
const isStudent = computed(() => store.getters["auth/user/hasRole"]('student'))
const isParent = computed(() => store.getters["auth/user/hasAnyRole"](['parent-profile', 'guardian']))
const isAdmin = computed(() => store.getters["auth/user/hasRole"]('admin'))
const isTeacherDashboardUser = computed(
    () => !isStudent.value && !isParent.value && !isAdmin.value
)
const canViewAttendance = computed(() =>
    perform('student_attendance:read') ||
    perform('student_attendance_report:read') ||
    perform('staff_attendance:read') ||
    perform('staff_attendance_report:read')
)
const studentCount = ref(0)
const newStudentCount = ref(0)
const calendarItems = ref([])
const gradeLabels = ref([])
const gradeData = ref([])
const enrollmentLabels = ref([])
const enrollmentData = ref([])
const studentTypeLabels = ref([])
const studentTypeData = ref([])
const staffTypeLabels = ref([])
const staffTypeData = ref([])
const teacherEmployee = ref(null)
const teacherScheduleDays = ref([])
const teacherScheduleRow = ref(null)
const teacherTodoRows = ref([])
const activeTeacherTab = ref('schedule')
const teacherTabs = [
    { key: 'schedule', label: 'My Schedule', icon: 'fa-regular fa-clock' },
    { key: 'todo', label: 'Tasks', icon: 'fa-solid fa-list-check' },
]

// calendarItems = await store.dispatch('dashboard/getCalendarItems');
// console.log('calendarItems after dispatch', calendarItems);

onMounted(async () => {
    if (isTeacherDashboardUser.value) {
        return
    }
    try {
        const requests = [
            store.dispatch('dashboard/getStudents'),
            store.dispatch('dashboard/getNewStudents'),
            store.dispatch('dashboard/getCalendarItems'),
        ]

        if (canViewAttendance.value) {
            requests.push(store.dispatch('dashboard/getTodayAttendance'))
        }

        const [totalResponse, newResponse, calendarResponse, attendanceResponse] = await Promise.all(requests)

        studentCount.value = totalResponse.studentCount || 0
        newStudentCount.value = newResponse.newStudentCount || 0
        calendarItems.value = calendarResponse || []

        if (canViewAttendance.value && attendanceResponse) {
            attendanceData.value = {
                legend: attendanceResponse.legend || [],
                students: attendanceResponse.students || { total: 0, countsByCode: {} },
                teachers: attendanceResponse.teachers || { total: 0, countsByCode: {} },
                staff: attendanceResponse.staff || { total: 0, countsByCode: {} },
            }
        }
    } catch (error) {
        console.error('Failed to fetch dashboard data:', error)
        studentCount.value = 0
        newStudentCount.value = 0

    }
})

onMounted(async () => {
    if (isTeacherDashboardUser.value) {
        return
    }
    try {
        const response = await store.dispatch('dashboard/getGradeDistribution')
        const grades = response.grades || []

        gradeLabels.value = grades.map(g => g.gradeLevelName || g.gradeLevelCode || 'Unknown')
        gradeData.value = grades.map(g => g.studentCount || 0)
    } catch (error) {
        console.error('Failed to fetch grade distribution:', error)
        gradeLabels.value = []
        gradeData.value = []
    }
})

onMounted(async () => {
    if (isTeacherDashboardUser.value) {
        return
    }
    try {
        const response = await store.dispatch('dashboard/getEnrollmentTrend')
        const labels = response.labels || []
        const data = response.data || []

        enrollmentLabels.value = labels
        enrollmentData.value = data
    } catch (error) {
        console.error('Failed to fetch enrollment trend:', error)
        enrollmentLabels.value = []
        enrollmentData.value = []
    }
})

onMounted(async () => {
    if (isTeacherDashboardUser.value) {
        return
    }
    try {
        const response = await store.dispatch('dashboard/getStudentTypeBreakdown')
        const labels = response.labels || []
        const data = response.data || []

        studentTypeLabels.value = labels
        studentTypeData.value = data
    } catch (error) {
        console.error('Failed to fetch student type breakdown:', error)
        studentTypeLabels.value = []
        studentTypeData.value = []
    }
})

onMounted(async () => {
    if (isTeacherDashboardUser.value) {
        return
    }
    try {
        const response = await store.dispatch('dashboard/getStaffTypeBreakdown')
        const labels = response.labels || []
        const data = response.data || []

        staffTypeLabels.value = labels
        staffTypeData.value = data
    } catch (error) {
        console.error('Failed to fetch staff type breakdown:', error)
        staffTypeLabels.value = []
        staffTypeData.value = []
    }
})

onMounted(async () => {
    try {
        const calendarResponse = await store.dispatch('dashboard/getCalendarItems')
        calendarItems.value = calendarResponse || []
        console.log('calendarItems', calendarItems.value)
        console.log('calendarItems.events', calendarItems.value.events)
    } catch (error) {
        console.error('Failed to fetch calendar items:', error)
        calendarItems.value = []
    }
})

onMounted(async () => {
    if (!isTeacherDashboardUser.value) {
        return
    }

    try {
        const [profileResponse, todoResponse] = await Promise.all([
            store.dispatch('employee/myProfile'),
            store.dispatch('utility/todo/list', { archived: false }),
        ])

        teacherEmployee.value = profileResponse?.employee || null
        teacherTodoRows.value = todoResponse?.data?.data || todoResponse?.data || []

        await loadTeacherSchedule()
    } catch (error) {
        console.error('Failed to load teacher dashboard data:', error)
        teacherEmployee.value = null
        teacherTodoRows.value = []
        teacherScheduleDays.value = []
        teacherScheduleRow.value = null
    }
})
const now = computed(() =>
    new Date().toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })
)

/** Keep both sides perfectly aligned; tweak once and both update */
const topBlockHeight = 520 // px

const markedDates = ['2025-08-12', '2025-08-20', '2025-08-21', '2025-08-28']

const attendanceData = ref({
    legend: [],
    students: { total: 0, countsByCode: {} },
    teachers: { total: 0, countsByCode: {} },
    staff: { total: 0, countsByCode: {} },
})

const studentAttendancePercentage = computed(() => {
    const students = attendanceData.value.students
    if (students?.presentPercentage !== undefined) {
        return `${students.presentPercentage}%`
    }
    return '0%'
})

const teacherWeekDates = computed(() => {
    const today = new Date()
    const day = today.getDay()
    const diffToMonday = day === 0 ? -6 : 1 - day
    const start = new Date(today)
    start.setDate(today.getDate() + diffToMonday)

    return Array.from({ length: 5 }, (_, index) => {
        const current = new Date(start)
        current.setDate(start.getDate() + index)
        return current
    })
})

const teacherWeekRangeLabel = computed(() => {
    const dates = teacherWeekDates.value
    if (!dates.length) {
        return ''
    }

    return `${formatShortDisplayDate(dates[0])} - ${formatShortDisplayDate(dates[dates.length - 1])}`
})

const teacherScheduleItems = computed(() =>
    teacherScheduleDays.value.map((date) => {
        const day = teacherScheduleRow.value?.days?.[date] || {}
        const shifts = Array.isArray(day.shifts) ? day.shifts : []

        return {
            date,
            dayLabel: formatWeekdayLabel(date),
            dateLabel: formatShortDisplayDate(date),
            status: day.status || 'off',
            statusLabel: day.status_label || 'Off',
            shiftSummary: shifts.map((shift) => ({
                key: shift.uuid || `${date}-${shift.name}`,
                name: shift.is_primary ? shift.name : `${shift.name} - Secondary`,
                timeLabel: shift.time_label || 'Time not set',
            })),
            note: day.leave?.reason || day.override?.reason || day.calendar_context?.label || '',
        }
    })
)

const teacherTodoItems = computed(() => {
    return (teacherTodoRows.value || [])
        .filter((item) => item?.column !== 'completed')
        .slice(0, 8)
        .map((item) => ({
            id: item.uuid || item.id,
            title: item.title || '(untitled)',
            due: item?.dueDate?.formatted?.formatted || item?.dueDate?.formatted || item?.due || '',
        }))
})

async function loadTeacherSchedule() {
    const employeeUuid = teacherEmployee.value?.uuid

    if (!employeeUuid) {
        teacherScheduleDays.value = []
        teacherScheduleRow.value = null
        return
    }

    const dates = teacherWeekDates.value
    const startDate = toQueryDate(dates[0])
    const endDate = toQueryDate(dates[dates.length - 1])

    const response = await store.dispatch('employee/attendance/workShift/dashboard', {
        params: {
            start_date: startDate,
            end_date: endDate,
            focus_date: toQueryDate(new Date()),
            employee_uuid: employeeUuid,
        },
    })

    teacherScheduleDays.value = response?.days?.slice(0, 5) || []
    teacherScheduleRow.value = response?.data?.[0] || null
}

function teacherStatusClass(status) {
    return {
        'border border-blue-200 bg-blue-50 text-blue-800': status === 'scheduled',
        'border border-cyan-200 bg-cyan-50 text-cyan-800': status === 'covering',
        'border border-amber-200 bg-amber-50 text-amber-800': status === 'holiday',
        'border border-rose-200 bg-rose-50 text-rose-800': ['leave', 'absent'].includes(status),
        'border border-emerald-200 bg-emerald-50 text-emerald-800': status === 'working_break',
        'border border-slate-200 bg-slate-50 text-slate-700': !status || status === 'off',
    }
}

function formatWeekdayLabel(value) {
    return new Date(value).toLocaleDateString('en-US', { weekday: 'long' })
}

function formatShortDisplayDate(value) {
    return new Date(value).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
    })
}

async function onToggleTodo(todoItem) {
    try {
        const raw = todoItem.raw
        if (!raw || !raw.uuid) return
        
        // Update status via API (this will move to 'completed' column if completing)
        await store.dispatch('utility/todo/status', { uuid: raw.uuid })
        
        // The TodoCard will refresh automatically via its internal refresh
    } catch (error) {
        console.error('Failed to toggle todo:', error)
    }
}

async function onRemoveTodo(todoItem) {
    try {
        const raw = todoItem.raw
        if (!raw || !raw.uuid) return
        
        // Archive via API
        await store.dispatch('utility/todo/archive', { uuid: raw.uuid })
        
        // The TodoCard will refresh automatically via its internal refresh
    } catch (error) {
        console.error('Failed to archive todo:', error)
    }
}
</script>

<style scoped>
.fade-soft-enter-active,
.fade-soft-leave-active {
    transition: opacity 220ms cubic-bezier(.2, .8, .2, 1),
        transform 220ms cubic-bezier(.2, .8, .2, 1);
}

.fade-soft-enter-from,
.fade-soft-leave-to {
    opacity: 0;
    transform: translateY(6px) scale(0.996);
}

.fade-soft-leave-active {
    pointer-events: none;
}

@media (prefers-reduced-motion: reduce) {
    .fade-soft-enter-active,
    .fade-soft-leave-active {
        transition: none !important;
    }

    .fade-soft-enter-from,
    .fade-soft-leave-to {
        transform: none !important;
    }
}
</style>
