<template>
    <div class="p-6 space-y-6">
        <div class="flex items-center">
            <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
            <div class="xl:col-span-3">
                <AnnouncementsCard :style="{ height: topBlockHeight + 'px' }" class="w-full" />
            </div>

            <div class="xl:col-span-2">
                <GamificationCard 
                    :style="{ height: topBlockHeight + 'px' }" 
                    :gradient="{ from: 'from-amber-700', via: 'via-amber-500', to: 'to-yellow-400' }"
                    class="h-full" />
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
            <div class="xl:col-span-3 min-h-[520px]">
                <div
                    class="bg-white dark:bg-neutral-800 shadow-2xl rounded-2xl overflow-hidden h-full flex flex-col"
                    :style="{ height: topBlockHeight + 'px' }"
                >
                    <div class="relative bg-gradient-to-br from-blue-900 via-blue-700 to-blue-400 text-white px-5 sm:px-6 py-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                                    <i class="fa-solid fa-layer-group text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-semibold">Productivity</h2>
                                    <p class="text-xs opacity-90">Schedule, tasks, groups, and activities in one place.</p>
                                </div>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-white/10"></div>
                    </div>

                    <div class="flex w-full border-b border-gray-200 dark:border-neutral-700">
                        <button v-for="tab in studentTabs" :key="tab.key" :class="[
                            'flex-1 py-3 px-2 sm:px-3 text-xs sm:text-sm font-semibold transition focus:outline-none',
                            activeStudentTab === tab.key
                                ? 'bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-300 border-b-2 border-blue-600 dark:border-blue-400'
                                : 'hover:bg-gray-50 dark:hover:bg-neutral-700 text-gray-500 dark:text-neutral-300'
                        ]" @click="activeStudentTab = tab.key">
                            <i :class="tab.icon" class="mr-1.5 sm:mr-2"></i>{{ tab.label }}
                        </button>
                    </div>

                    <div class="p-5 sm:p-6 flex-1 overflow-y-auto min-h-0">
                    <transition name="fade-soft" appear>
                        <div v-show="activeStudentTab === 'schedule'">
                            <ul v-if="scheduleItems.length" class="space-y-3">
                                <li v-for="item in scheduleItems" :key="`${item.type}-${item.id || item.uuid}`"
                                    class="flex items-start border border-gray-100 dark:border-neutral-700 rounded-lg p-3 bg-gray-50/70 dark:bg-neutral-900/40">
                                    <i :class="item.type === 'meeting' ? 'fa-solid fa-users text-blue-500' : 'fa-solid fa-calendar-days text-emerald-500'"
                                        class="text-base mt-0.5 mr-3"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-sm text-gray-900 dark:text-neutral-100 truncate">
                                            {{ item.title || 'Untitled' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-neutral-400 mt-1">
                                            {{ item.dateLabel }}<span v-if="item.timeLabel"> â€¢ {{ item.timeLabel }}</span>
                                        </p>
                                    </div>
                                    <span class="text-[11px] font-medium px-2 py-0.5 rounded-full"
                                        :class="item.type === 'meeting'
                                            ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'
                                            : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'">
                                        {{ item.type === 'meeting' ? 'Meeting' : 'Event' }}
                                    </span>
                                </li>
                            </ul>
                            <p v-else class="text-sm text-gray-500 dark:text-neutral-400 italic">
                                No schedule items available right now.
                            </p>
                        </div>
                    </transition>

                    <transition name="fade-soft" appear>
                        <div v-show="activeStudentTab === 'todo'">
                            <ul v-if="todoItems.length" class="space-y-3">
                                <li v-for="item in todoItems" :key="item.id"
                                    class="rounded-lg border border-gray-200 dark:border-neutral-700 p-3 bg-gray-50/70 dark:bg-neutral-900/40">
                                    <p class="font-medium text-sm text-gray-900 dark:text-neutral-100">
                                        {{ item.title }}
                                    </p>
                                    <p v-if="item.due" class="text-xs text-gray-500 dark:text-neutral-400 mt-1">
                                        Due: {{ item.due }}
                                    </p>
                                </li>
                            </ul>
                            <p v-else class="text-sm text-gray-500 dark:text-neutral-400 italic">
                                No to-do items right now.
                            </p>
                        </div>
                    </transition>

                    <transition name="fade-soft" appear>
                        <div v-show="activeStudentTab === 'groupsActivities'" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden flex flex-col min-h-0 text-left transition hover:border-blue-300 hover:bg-gray-50 dark:hover:bg-neutral-900"
                                @click="router.push({ name: 'StudentMyGroupsList' })"
                            >
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400 px-4 pt-4 pb-2">
                                    Groups
                                </p>
                                <div class="w-full px-4 pb-4 pt-1 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-neutral-100">My Groups</p>
                                            <p class="text-xs text-gray-500 dark:text-neutral-400 mt-1">
                                                Join discussions and see your active groups.
                                            </p>
                                        </div>
                                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-300">
                                            View all
                                        </span>
                                    </div>

                                    <div class="mt-4 space-y-2">
                                        <template v-if="groupItems.length">
                                            <button
                                                v-for="group in groupItems"
                                                :key="group.uuid"
                                                type="button"
                                                class="w-full rounded-lg border border-blue-100 bg-blue-50 px-3 py-2 text-left transition hover:border-blue-300 hover:bg-blue-100 dark:border-blue-900/50 dark:bg-blue-950/40 dark:hover:bg-blue-950/60"
                                                @click.stop="openGroup(group)"
                                            >
                                                <p class="text-sm font-semibold text-gray-900 dark:text-neutral-100">
                                                    {{ group.groupName }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">
                                                    {{ group.groupType || 'School Group' }}
                                                </p>
                                            </button>
                                        </template>
                                        <p v-else class="text-xs text-gray-500 dark:text-neutral-400 italic">
                                            No groups yet.
                                        </p>
                                    </div>
                                </div>
                            </button>
                            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden flex flex-col min-h-0">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400 px-4 pt-4 pb-2">
                                    Activities
                                </p>
                                <button type="button"
                                    class="w-full text-left px-4 pb-4 pt-1 hover:bg-gray-50 dark:hover:bg-neutral-900 transition flex-1"
                                    @click="router.push({ name: 'StudentSchoolActivityList' })">
                                    <p class="font-semibold text-gray-900 dark:text-neutral-100">School Activities</p>
                                    <p class="text-xs text-gray-500 dark:text-neutral-400 mt-1">
                                        Browse sports, clubs, and other activities.
                                    </p>
                                </button>
                            </div>
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

        <div class="grid grid-cols-2 gap-5" v-if="false">
            <GradeDistributionChart :labels="gradeLabels" :data="gradeData"
                :colors="['#10b981', '#14b8a6', '#f59e0b', '#ec4899', '#8b5cf6']" />
            <EnrollmentTrendChart title="Student Enrollment Trend" badge="Last 6 months"
                :labels="enrollmentLabels" :data="enrollmentData"
                color="#3b82f6" fillColor="rgba(59,130,246,0.15)" />
        </div>

        <div class="grid grid-cols-2 gap-5" v-if="false">

            <DonutChart title="Student Type Breakdown" :labels="studentTypeLabels"
                :data="studentTypeData" :colors="['#3b82f6', '#10b981', '#f59e0b']" />
            <DonutChart title="Staff Type Breakdown" :labels="staffTypeLabels" :data="staffTypeData"
                :colors="['#d946ef', '#8b5cf6']" />
        </div>
    </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import AnnouncementsCard from '../../Pages/Dashboard/Cards/AnnouncementsCard.vue'
import GamificationCard from '../../Pages/Dashboard/Cards/GamificationCard.vue'
import EnrollmentTrendChart from '../../Pages/Dashboard/Cards/EnrollmentTrendChart.vue'
import GradeDistributionChart from '../../Pages/Dashboard/Cards/GradeDistributionChart.vue'
import DonutChart from '../../Pages/Dashboard/Cards/DonutChart.vue'
import { useRouter } from 'vue-router'
import CalendarCard from '../../Pages/Dashboard/Cards/CalendarCard.vue'
import { storeKey, useStore } from 'vuex/dist/vuex.cjs.js'

const store = useStore(storeKey)
const router = useRouter()
const studentCount = ref(0)
const newStudentCount = ref(0)
const calendarItems = ref({ events: [], meetings: [] })
const gradeLabels = ref([])
const gradeData = ref([])
const enrollmentLabels = ref([])
const enrollmentData = ref([])
const studentTypeLabels = ref([])
const studentTypeData = ref([])
const staffTypeLabels = ref([])
const staffTypeData = ref([])
const todoRows = ref([])
const groupRows = ref([])
const studentTabs = [
    { key: 'schedule', label: 'Schedule', icon: 'fa-regular fa-clock' },
    { key: 'todo', label: 'To-do', icon: 'fa-solid fa-list-check' },
    { key: 'groupsActivities', label: 'Groups & Activities', icon: 'fa-solid fa-people-group' },
]
const activeStudentTab = ref('schedule')

onMounted(async () => {
    try {
        const [totalResponse, newResponse, calendarResponse, groupResponse] = await Promise.all([
            store.dispatch('dashboard/getStudents'),
            store.dispatch('dashboard/getNewStudents'),
            store.dispatch('dashboard/getCalendarItems'),
            store.dispatch('communication/mygroups/list', {
                params: {
                    page_length: 3,
                    current_page: 1,
                },
            }),
        ])
        studentCount.value = totalResponse.studentCount || 0
        newStudentCount.value = newResponse.newStudentCount || 0
        calendarItems.value = calendarResponse || []
        groupRows.value = groupResponse?.data || []
    } catch (error) {
        console.error('Failed to fetch dashboard data:', error)
        studentCount.value = 0
        newStudentCount.value = 0
        groupRows.value = []
    }
})

onMounted(async () => {
    try {
        const response = await store.dispatch('utility/todo/list', { archived: false })
        todoRows.value = response?.data?.data || response?.data || []
    } catch (error) {
        console.error('Failed to fetch to-do items:', error)
        todoRows.value = []
    }
})

onMounted(async () => {
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

const topBlockHeight = 520
const scheduleItems = computed(() => {
    const events = Array.isArray(calendarItems.value?.events) ? calendarItems.value.events : []
    const meetings = Array.isArray(calendarItems.value?.meetings) ? calendarItems.value.meetings : []

    const mappedEvents = events.map(item => ({
        ...item,
        type: 'event',
        dateLabel: item?.startDate?.formatted || item?.start_date?.formatted || item?.startDate?.value || item?.start_date?.value || 'Date not available',
        timeLabel: [item?.startTime?.formatted, item?.endTime?.formatted].filter(Boolean).join(' - '),
        startValue: item?.startDate?.value || item?.start_date?.value || '',
    }))
    const mappedMeetings = meetings.map(item => ({
        ...item,
        type: 'meeting',
        dateLabel: item?.startDate?.formatted || item?.start_date?.formatted || item?.startDate?.value || item?.start_date?.value || 'Date not available',
        timeLabel: [item?.startTime?.formatted, item?.endTime?.formatted].filter(Boolean).join(' - '),
        startValue: item?.startDate?.value || item?.start_date?.value || '',
    }))

    return [...mappedMeetings, ...mappedEvents]
        .sort((a, b) => String(a.startValue || '').localeCompare(String(b.startValue || '')))
        .slice(0, 8)
})

const todoItems = computed(() => {
    return (todoRows.value || [])
        .filter(item => item?.column !== 'completed')
        .slice(0, 8)
        .map(item => ({
            id: item.uuid || item.id,
            title: item.title || '(untitled)',
            due: item?.dueDate?.formatted?.formatted || item?.dueDate?.formatted || item?.due || '',
        }))
})

const groupItems = computed(() => {
    return (groupRows.value || [])
        .slice(0, 3)
        .map((item) => ({
            uuid: item.uuid,
            groupName: item.groupName || 'My Group',
            groupType: item.groupType || '',
        }))
})

function openGroup(group) {
    if (!group?.uuid) {
        router.push({ name: 'StudentMyGroupsList' })
        return
    }

    router.push({
        name: 'StudentMyGroupsShow',
        params: { uuid: group.uuid },
    })
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
