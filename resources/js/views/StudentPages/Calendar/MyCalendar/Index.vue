<template>
    <ParentTransition appear :visibility="true">
        <div>
            <PageHeader
                :title="$trans('calendar.my_calendar.my_calendar')"
                :navs="[
                    { label: $trans('calendar.calendar'), path: 'StudentCalendar' },
                ]"
            >
                <PageHeaderAction
                    name="StudentCalendarMyCalendar"
                    :title="$trans('calendar.my_calendar.my_calendar')"
                    :actions="[]"
                >
                    <template #leading>
                        <div class="flex flex-wrap items-center gap-2">
                            <BaseButton
                                v-if="viewMode === 'week'"
                                design="white"
                                @click="previousWeek"
                            >
                                <i class="fas fa-chevron-left mr-2"></i>
                                Previous Week
                            </BaseButton>
                            <BaseButton
                                v-if="viewMode === 'month'"
                                design="white"
                                @click="previousMonth"
                            >
                                <i class="fas fa-chevron-left mr-2"></i>
                                Previous Month
                            </BaseButton>
                            <BaseButton
                                design="white"
                                @click="goToToday"
                            >
                                <i class="fas fa-calendar-day mr-2"></i>
                                Today
                            </BaseButton>
                            <BaseButton
                                v-if="viewMode === 'week'"
                                design="white"
                                @click="nextWeek"
                            >
                                <i class="fas fa-chevron-right mr-2"></i>
                                Next Week
                            </BaseButton>
                            <BaseButton
                                v-if="viewMode === 'month'"
                                design="white"
                                @click="nextMonth"
                            >
                                <i class="fas fa-chevron-right mr-2"></i>
                                Next Month
                            </BaseButton>
                            <DatePicker
                                v-model="selectedDate"
                                @update:modelValue="onDateChange"
                                as="date"
                                :no-clear="false"
                                placeholder="Select date"
                                class="h-9"
                            />
                            <div
                                class="flex items-center gap-1 ml-2 rounded-md border border-blue-200 bg-white overflow-hidden"
                            >
                                <BaseButton
                                    size="sm"
                                    :design="viewMode === 'week' ? 'primary' : 'white'"
                                    class="px-3 py-1 text-xs"
                                    @click="switchToWeek"
                                >
                                    Week
                                </BaseButton>
                                <BaseButton
                                    size="sm"
                                    :design="viewMode === 'month' ? 'primary' : 'white'"
                                    class="px-3 py-1 text-xs"
                                    @click="switchToMonth"
                                >
                                    Month
                                </BaseButton>
                            </div>
                        </div>
                    </template>
                </PageHeaderAction>
            </PageHeader>

            <BaseLoader :is-loading="isLoading">
                <div class="mt-6">
                    <div class="mb-4 text-center">
                        <h3 class="text-lg font-semibold text-blue-900">
                            <span v-if="viewMode === 'week'">{{ weekRange }}</span>
                            <span v-else>{{ monthLabel }}</span>
                        </h3>
                    </div>

                    <div v-if="viewMode === 'week'" class="overflow-x-auto">
                        <div class="min-w-full bg-white dark:bg-neutral-800 rounded-lg border border-blue-200 shadow-sm">
                            <div
                                class="grid grid-cols-8 border-b border-blue-200 min-w-0"
                                style="grid-template-columns: 0.7fr repeat(7, minmax(0, 1fr));"
                            >
                                <div class="p-3 font-semibold text-blue-900 dark:text-blue-100 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/30 min-w-0">
                                    <div>{{ $trans('calendar.my_calendar.props.week') }}</div>
                                    <div class="text-xs font-normal mt-1 text-blue-700 dark:text-blue-300 truncate">
                                        {{ formatWeekRange(weekRange) }}
                                    </div>
                                </div>
                                <div
                                    v-for="(day, index) in days"
                                    :key="day.date || index"
                                    class="p-3 text-center font-semibold text-blue-900 dark:text-blue-100 border-l border-blue-200 min-w-0 overflow-hidden"
                                    :class="{
                                        'bg-blue-100 dark:bg-blue-900/20': day.is_today || day.isToday,
                                        'bg-blue-50 dark:bg-neutral-700': !(day.is_today || day.isToday),
                                    }"
                                >
                                    <div class="text-sm font-bold uppercase truncate">
                                        {{ day.day_name || weekDayNames[index] }}
                                    </div>
                                    <div
                                        class="text-xs mt-1 font-normal text-blue-700 dark:text-blue-300 truncate"
                                        :class="{
                                            'font-semibold text-blue-900 dark:text-blue-100': day.is_today || day.isToday,
                                        }"
                                    >
                                        {{ formatDate(day.date) }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-8 min-w-0"
                                style="grid-template-columns: 0.7fr repeat(7, minmax(0, 1fr));"
                            >
                                <div class="p-2 text-xs font-medium text-blue-700 dark:text-blue-300 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/30 border-r border-blue-200 sticky left-0 z-10 min-w-0">
                                    <div>{{ $trans('calendar.my_calendar.props.week') }}</div>
                                    <div class="text-[10px] font-normal mt-1 text-blue-600 dark:text-blue-400">
                                        {{ formatWeekRange(weekRange) }}
                                    </div>
                                </div>
                                <div
                                    v-for="day in days"
                                    :key="day.date"
                                    class="min-h-[400px] min-w-0 p-2 border-r border-blue-200 last:border-r-0 overflow-hidden"
                                    :class="{
                                        'bg-blue-50 dark:bg-blue-900/10': day.is_today || day.isToday,
                                    }"
                                >
                                    <div class="space-y-2 overflow-hidden">
                                        <div
                                            v-for="event in day.events"
                                            :key="event.uuid"
                                            class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30 border border-blue-300 dark:border-blue-700 cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors min-w-0 overflow-hidden"
                                            @click="viewEvent(event)"
                                        >
                                            <div class="flex-1 min-w-0 overflow-hidden">
                                                <div
                                                    v-if="event.type"
                                                    class="text-[11px] font-semibold text-blue-800 dark:text-blue-200 truncate"
                                                    :title="event.type.name"
                                                >
                                                    {{ event.type.name }}
                                                </div>
                                                <div
                                                    v-if="event.startTime?.formatted || event.startTime?.formattedWithTimezone || event.startTime?.formatted_with_timezone"
                                                    class="text-[11px] text-blue-700 dark:text-blue-300 mt-0.5 flex items-center gap-1"
                                                >
                                                    <i class="fas fa-clock"></i>
                                                    <span class="truncate">
                                                        {{ event.startTime?.formattedWithTimezone || event.startTime?.formatted_with_timezone || event.startTime?.formatted }}
                                                        <span v-if="event.endTime?.formatted || event.endTime?.formattedWithTimezone || event.endTime?.formatted_with_timezone">
                                                            - {{ event.endTime?.formattedWithTimezone || event.endTime?.formatted_with_timezone || event.endTime?.formatted }}
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="text-xs font-semibold text-blue-900 dark:text-blue-100 mt-1" :title="event.title">
                                                    {{ event.title }}
                                                </div>
                                                <div v-if="event.venue || event.virtualLink" class="text-xs text-blue-600 dark:text-blue-400 mt-1 flex flex-col gap-0.5 min-w-0">
                                                    <div v-if="event.venue" class="flex items-center gap-1 min-w-0">
                                                        <i class="fas fa-map-marker-alt shrink-0"></i>
                                                        <span class="truncate min-w-0">{{ event.venue }}</span>
                                                    </div>
                                                    <div v-if="event.virtualLink" class="flex items-center gap-1 min-w-0">
                                                        <i class="fas fa-link shrink-0"></i>
                                                        <a
                                                            :href="normalizeUrl(event.virtualLink)"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="underline hover:opacity-80 whitespace-nowrap"
                                                            @click.stop
                                                        >Link - Click Here</a>
                                                    </div>
                                                    <div v-else-if="!event.virtualLink && event.venue && isUrl(event.venue)" class="flex items-center gap-1 min-w-0">
                                                        <i class="fas fa-map-marker-alt shrink-0"></i>
                                                        <a
                                                            :href="normalizeUrl(event.venue)"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="underline hover:opacity-80 whitespace-nowrap"
                                                            @click.stop
                                                        >Click Here</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            v-for="meeting in day.meetings"
                                            :key="meeting.uuid"
                                            class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 cursor-pointer hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors min-w-0 overflow-hidden"
                                            @click="viewMeeting(meeting)"
                                        >
                                            <div class="flex-1 min-w-0 overflow-hidden">
                                                <div
                                                    v-if="meeting.type"
                                                    class="text-[11px] font-semibold text-green-800 dark:text-green-200 truncate"
                                                    :title="meeting.type.name"
                                                >
                                                    {{ meeting.type.name }}
                                                </div>
                                                <div
                                                    v-if="meeting.startTime?.formatted || meeting.startTime?.formattedWithTimezone || meeting.startTime?.formatted_with_timezone"
                                                    class="text-[11px] text-green-700 dark:text-green-300 mt-0.5 flex items-center gap-1"
                                                >
                                                    <i class="fas fa-clock"></i>
                                                    <span class="truncate">
                                                        {{ meeting.startTime?.formattedWithTimezone || meeting.startTime?.formatted_with_timezone || meeting.startTime?.formatted }}
                                                        <span v-if="meeting.endTime?.formatted || meeting.endTime?.formattedWithTimezone || meeting.endTime?.formatted_with_timezone">
                                                            - {{ meeting.endTime?.formattedWithTimezone || meeting.endTime?.formatted_with_timezone || meeting.endTime?.formatted }}
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="text-xs font-semibold text-green-900 dark:text-green-100 mt-1" :title="meeting.title">
                                                    <i class="fas fa-users mr-1 shrink-0"></i>
                                                    {{ meeting.title }}
                                                </div>
                                                <div v-if="meeting.venue || meeting.virtualLink" class="text-xs text-green-600 dark:text-green-400 mt-1 flex flex-col gap-0.5 min-w-0">
                                                    <div v-if="meeting.venue" class="flex items-center gap-1 min-w-0">
                                                        <i class="fas fa-map-marker-alt shrink-0"></i>
                                                        <span class="truncate min-w-0">{{ meeting.venue }}</span>
                                                    </div>
                                                    <div v-if="meeting.virtualLink" class="flex items-center gap-1 min-w-0">
                                                        <i class="fas fa-link shrink-0"></i>
                                                        <a
                                                            :href="normalizeUrl(meeting.virtualLink)"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="underline hover:opacity-80 whitespace-nowrap"
                                                            @click.stop
                                                        >Click Here</a>
                                                    </div>
                                                    <div v-else-if="!meeting.virtualLink && meeting.venue && isUrl(meeting.venue)" class="flex items-center gap-1 min-w-0">
                                                        <i class="fas fa-map-marker-alt shrink-0"></i>
                                                        <a
                                                            :href="normalizeUrl(meeting.venue)"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="underline hover:opacity-80 whitespace-nowrap"
                                                            @click.stop
                                                        >Click Here</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            v-for="announcement in day.announcements"
                                            :key="announcement.uuid"
                                            class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30 border border-blue-300 dark:border-blue-700 cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors min-w-0 overflow-hidden"
                                            @click="viewAnnouncement(announcement)"
                                        >
                                            <div class="flex-1 min-w-0 overflow-hidden">
                                                <div
                                                    v-if="announcement.type"
                                                    class="text-[11px] font-semibold text-blue-800 dark:text-blue-200 truncate"
                                                    :title="announcement.type.name"
                                                >
                                                    {{ announcement.type.name }}
                                                </div>
                                                <div
                                                    v-if="announcement.startDate?.formatted || announcement.publishedAt?.formatted"
                                                    class="text-[11px] text-blue-700 dark:text-blue-300 mt-0.5 flex items-center gap-1"
                                                >
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <span v-if="announcement.startDate?.formatted">
                                                        {{ announcement.startDate.formatted }}
                                                        <span v-if="announcement.endDate?.formatted && announcement.endDate.formatted !== announcement.startDate.formatted">
                                                            - {{ announcement.endDate.formatted }}
                                                        </span>
                                                    </span>
                                                    <span v-else-if="announcement.publishedAt?.formatted">
                                                        {{ announcement.publishedAt.formatted }}
                                                    </span>
                                                </div>
                                                <div class="text-xs font-semibold text-blue-900 dark:text-blue-100 mt-1" :title="announcement.title">
                                                    <i class="fas fa-bullhorn mr-1 shrink-0"></i>
                                                    {{ announcement.title }}
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            v-if="day.events.length === 0 && day.meetings.length === 0 && day.announcements.length === 0"
                                            class="text-xs text-gray-400 dark:text-gray-500 text-center py-4"
                                        >
                                            {{ $trans('calendar.my_calendar.props.no_events') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <div class="min-w-full bg-white dark:bg-neutral-800 rounded-lg border border-blue-200 shadow-sm">
                            <div class="grid grid-cols-7 border-b border-blue-200 bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white">
                                <div
                                    v-for="(name, index) in weekDayNames"
                                    :key="index"
                                    class="p-2 text-center text-xs font-bold uppercase tracking-wide"
                                >
                                    {{ name.substring(0, 3) }}
                                </div>
                            </div>

                            <div class="grid grid-cols-7 min-w-0 bg-white dark:bg-neutral-900">
                                <div
                                    v-for="day in monthDays"
                                    :key="day.date"
                                    class="min-h-[100px] min-w-0 p-2 border-r border-b border-blue-100 last:border-r-0 overflow-hidden"
                                    :class="{
                                        'bg-white dark:bg-neutral-900 text-blue-900 dark:text-blue-100': day.in_current_month,
                                        'bg-gray-50 dark:bg-neutral-800 text-gray-400 dark:text-gray-500': !day.in_current_month,
                                        'ring-2 ring-blue-500 ring-offset-1 ring-offset-white dark:ring-offset-neutral-900': day.is_today || day.isToday,
                                    }"
                                >
                                    <div class="flex items-center justify-between text-xs font-medium">
                                        <span>{{ new Date(day.date).getDate() }}</span>
                                    </div>
                                    <div class="mt-1 space-y-1 overflow-hidden min-w-0">
                                        <div
                                            v-for="event in (day.events || []).slice(0, 2)"
                                            :key="event.uuid"
                                            class="text-[11px] px-1 py-0.5 rounded bg-blue-100 text-blue-800 truncate dark:bg-blue-900/40 dark:text-blue-100 cursor-pointer min-w-0"
                                            @click="viewEvent(event)"
                                            :title="event.title"
                                        >
                                            {{ event.title }}
                                        </div>
                                        <div
                                            v-for="meeting in (day.meetings || []).slice(0, 2)"
                                            :key="meeting.uuid"
                                            class="text-[11px] px-1 py-0.5 rounded bg-green-100 text-green-800 truncate dark:bg-green-900/40 dark:text-green-100 cursor-pointer min-w-0"
                                            @click="viewMeeting(meeting)"
                                            :title="meeting.title"
                                        >
                                            <i class="fas fa-users mr-1"></i>{{ meeting.title }}
                                        </div>
                                        <div
                                            v-for="announcement in (day.announcements || []).slice(0, 2)"
                                            :key="announcement.uuid"
                                            class="text-[11px] px-1 py-0.5 rounded bg-blue-100 text-blue-800 truncate dark:bg-blue-900/40 dark:text-blue-100 cursor-pointer min-w-0"
                                            @click="viewAnnouncement(announcement)"
                                            :title="announcement.title"
                                        >
                                            <i class="fas fa-bullhorn mr-1"></i>{{ announcement.title }}
                                        </div>
                                        <div
                                            v-if="(day.events?.length || 0) + (day.meetings?.length || 0) + (day.announcements?.length || 0) > 2"
                                            class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 truncate"
                                        >
                                            + {{ (day.events?.length || 0) + (day.meetings?.length || 0) + (day.announcements?.length || 0) - 2 }} more
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </BaseLoader>
        </div>
    </ParentTransition>
</template>

<script>
export default {
    name: "StudentCalendarMyCalendar",
}
</script>

<script setup>
import { ref, onMounted, inject } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import { trans } from "@core/helpers/trans"
import DatePicker from "@core/components/Forms/DatePicker.vue"

const router = useRouter()
const store = useStore()
const $trans = inject("$trans")

const isLoading = ref(false)
const viewMode = ref("week")
const days = ref([])
const weekRange = ref("")
const selectedDate = ref("")
const currentWeekStart = ref(null)
const monthDays = ref([])
const monthLabel = ref("")

const weekDayNames = [
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday",
]

const toYmd = (date) => {
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, "0")
    const day = String(date.getDate()).padStart(2, "0")
    return `${year}-${month}-${day}`
}

const parseYmd = (dateString) => {
    if (!dateString) return null
    const parts = dateString.split("-")
    if (parts.length !== 3) return null
    const [year, month, day] = parts.map(Number)
    if (!year || !month || !day) return null
    return new Date(year, month - 1, day)
}

const formatDate = (dateString) => {
    if (!dateString) return ""
    const date = parseYmd(dateString) || new Date(dateString)
    const months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ]
    const month = months[date.getMonth()]
    const day = String(date.getDate()).padStart(2, "0")
    // Only show month and day for week header (no year)
    return `${month} ${day}`
}

const formatWeekRange = (range) => {
    if (!range || typeof range !== "string") return ""
    return range.replace(/,\s*\d{4}/g, "")
}

const isUrl = (str) => {
    if (!str || typeof str !== "string") return false
    const s = str.trim()
    return /^https?:\/\//i.test(s) || /^[a-z][a-z0-9+.-]*:\/\//i.test(s) || /^[a-z0-9][a-z0-9.-]*\.[a-z]{2,}(\/.*)?$/i.test(s)
}

const normalizeUrl = (str) => {
    if (!str || typeof str !== "string") return "#"
    const s = str.trim()
    if (/^https?:\/\//i.test(s)) return s
    if (/^[a-z][a-z0-9+.-]*:\/\//i.test(s)) return s
    return "https://" + s
}

const loadWeeklyCalendar = async (startDate = null) => {
    isLoading.value = true
    try {
        const params = startDate ? { start_date: startDate } : {}
        const response = await store.dispatch("calendar/myCalendar/fetchWeekly", {
            params,
        })

        const responseDays = response.days || []

        if (responseDays.length === 7) {
            days.value = responseDays
        } else {
            const weekStart = response.weekStart ? new Date(response.weekStart) : new Date()
            const monday = new Date(weekStart)
            monday.setDate(monday.getDate() - (monday.getDay() === 0 ? 6 : monday.getDay() - 1))

            days.value = []
            for (let i = 0; i < 7; i++) {
                const currentDate = new Date(monday)
                currentDate.setDate(monday.getDate() + i)
                const dateString = toYmd(currentDate)
                const todayString = toYmd(new Date())

                const apiDay = responseDays.find((d) => d.date === dateString)

                days.value.push({
                    date: dateString,
                    day_name: weekDayNames[i],
                    day_short: weekDayNames[i].substring(0, 3),
                    day_number: currentDate.getDate(),
                    is_today: dateString === todayString,
                    events: apiDay?.events || [],
                    meetings: apiDay?.meetings || [],
                    announcements: apiDay?.announcements || [],
                })
            }
        }

        weekRange.value = response.weekRange || ""
        currentWeekStart.value = response.weekStart

        if (!selectedDate.value && response.weekStart) {
            selectedDate.value = response.weekStart
        }
    } catch (error) {
        console.error("Error loading weekly calendar:", error)
        const today = new Date()
        const monday = new Date(today)
        monday.setDate(today.getDate() - (today.getDay() === 0 ? 6 : today.getDay() - 1))

        days.value = []
        for (let i = 0; i < 7; i++) {
            const currentDate = new Date(monday)
            currentDate.setDate(monday.getDate() + i)
            const dateString = toYmd(currentDate)
            const todayString = toYmd(new Date())

            days.value.push({
                date: dateString,
                day_name: weekDayNames[i],
                day_short: weekDayNames[i].substring(0, 3),
                day_number: currentDate.getDate(),
                is_today: dateString === todayString,
                events: [],
                meetings: [],
                announcements: [],
            })
        }
    } finally {
        isLoading.value = false
    }
}

const loadMonthlyCalendar = async (startDate = null) => {
    isLoading.value = true
    try {
        const anchorDate =
            parseYmd(startDate) ||
            parseYmd(selectedDate.value) ||
            new Date()

        const year = anchorDate.getFullYear()
        const month = anchorDate.getMonth() 

        const firstOfMonth = new Date(year, month, 1)
        const lastOfMonth = new Date(year, month + 1, 0)

        const gridStart = new Date(firstOfMonth)
        const startDay = gridStart.getDay() === 0 ? 7 : gridStart.getDay() // Mon=1..Sun=7
        gridStart.setDate(gridStart.getDate() - (startDay - 1))

        const gridEnd = new Date(lastOfMonth)
        const endDay = gridEnd.getDay() === 0 ? 7 : gridEnd.getDay()
        gridEnd.setDate(gridEnd.getDate() + (7 - endDay))

        const weeks = []
        const weekCursor = new Date(gridStart)
        while (weekCursor <= gridEnd) {
            weeks.push(toYmd(weekCursor))
            weekCursor.setDate(weekCursor.getDate() + 7)
        }

        const todayStr = toYmd(new Date())
        const monthDaysMap = {}

        for (const weekStartStr of weeks) {
            const response = await store.dispatch("calendar/myCalendar/fetchWeekly", {
                params: { start_date: weekStartStr },
            })

            const responseDays = response.days || []
            for (const day of responseDays) {
                const dateStr = day.date
                if (!dateStr) continue

                const dateObj = parseYmd(dateStr) || new Date(dateStr)
                const inCurrentMonth = dateObj.getMonth() === month

                if (!monthDaysMap[dateStr]) {
                    monthDaysMap[dateStr] = {
                        date: dateStr,
                        in_current_month: inCurrentMonth,
                        is_today: dateStr === todayStr,
                        events: [],
                        meetings: [],
                        announcements: [],
                    }
                }

                monthDaysMap[dateStr].events = day.events || []
                monthDaysMap[dateStr].meetings = day.meetings || []
                monthDaysMap[dateStr].announcements = day.announcements || []
            }
        }

        const monthCells = []
        const cursor = new Date(gridStart)
        while (cursor <= gridEnd) {
            const dateStr = toYmd(cursor)
            const base = monthDaysMap[dateStr]
            const inCurrentMonth = cursor.getMonth() === month

            monthCells.push(
                base || {
                    date: dateStr,
                    in_current_month: inCurrentMonth,
                    is_today: dateStr === todayStr,
                    events: [],
                    meetings: [],
                    announcements: [],
                }
            )

            cursor.setDate(cursor.getDate() + 1)
        }

        monthDays.value = monthCells
        monthLabel.value = firstOfMonth.toLocaleDateString(undefined, {
            month: "long",
            year: "numeric",
        })
    } catch (error) {
        console.error("Error loading monthly calendar:", error)
        monthDays.value = []
        monthLabel.value = ""
    } finally {
        isLoading.value = false
    }
}

const getWeekStartYmd = () => {
    if (currentWeekStart.value) {
        const d = parseYmd(currentWeekStart.value) || new Date(currentWeekStart.value)
        return toYmd(d)
    }
    const anchor = parseYmd(selectedDate.value) || new Date()
    const monday = new Date(anchor)
    monday.setDate(anchor.getDate() - (anchor.getDay() === 0 ? 6 : anchor.getDay() - 1))
    return toYmd(monday)
}

const previousWeek = () => {
    const weekStartYmd = getWeekStartYmd()
    const date = parseYmd(weekStartYmd) || new Date()
    date.setDate(date.getDate() - 7)
    const ymd = toYmd(date)
    selectedDate.value = ymd
    loadWeeklyCalendar(ymd)
}

const nextWeek = () => {
    const weekStartYmd = getWeekStartYmd()
    const date = parseYmd(weekStartYmd) || new Date()
    date.setDate(date.getDate() + 7)
    const ymd = toYmd(date)
    selectedDate.value = ymd
    loadWeeklyCalendar(ymd)
}

const previousMonth = () => {
    const anchor = parseYmd(selectedDate.value) || new Date()
    const prev = new Date(anchor.getFullYear(), anchor.getMonth() - 1, 1)
    selectedDate.value = toYmd(prev)
    loadMonthlyCalendar(selectedDate.value)
}

const nextMonth = () => {
    const anchor = parseYmd(selectedDate.value) || new Date()
    const next = new Date(anchor.getFullYear(), anchor.getMonth() + 1, 1)
    selectedDate.value = toYmd(next)
    loadMonthlyCalendar(selectedDate.value)
}

const goToToday = () => {
    const today = toYmd(new Date())
    selectedDate.value = today
    if (viewMode.value === "week") {
        loadWeeklyCalendar(today)
    } else {
        loadMonthlyCalendar(today)
    }
}

const onDateChange = (date) => {
    if (!date) return
    selectedDate.value = date
    if (viewMode.value === "week") {
        loadWeeklyCalendar(date)
    } else {
        loadMonthlyCalendar(date)
    }
}

const switchToWeek = () => {
    viewMode.value = "week"
    const anchor = selectedDate.value || toYmd(new Date())
    loadWeeklyCalendar(anchor)
}

const switchToMonth = () => {
    viewMode.value = "month"
    const anchor = selectedDate.value || toYmd(new Date())
    loadMonthlyCalendar(anchor)
}

const viewEvent = (event) => {
    router.push({
        name: "CalendarEventShow",
        params: { uuid: event.uuid },
    })
}

const viewMeeting = (meeting) => {
    router.push({
        name: "MeetingShow",
        params: { uuid: meeting.uuid },
    })
}

const viewAnnouncement = (announcement) => {
    router.push({
        name: "CommunicationAnnouncementShow",
        params: { uuid: announcement.uuid },
    })
}

onMounted(() => {
    const today = toYmd(new Date())
    selectedDate.value = today
    loadWeeklyCalendar(today)
})
</script>

