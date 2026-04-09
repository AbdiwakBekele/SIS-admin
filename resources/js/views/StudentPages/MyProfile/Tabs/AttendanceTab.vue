<template>
    <div class="bg-white rounded-lg border-r px-4 py-5 text-center mt-2">
        <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Attendance To View</p>
            <p class="text-xs text-gray-500 mt-1">Attendance information is not available at this time, coming soon!</p>
        </div>
    </div>

    <div v-if="false" class="hidden">
        <div class="bg-white rounded-lg border px-4 py-5 mt-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="flex flex-col gap-3 min-w-[220px]">
                    <p class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-emerald-500"></i>
                        <span>{{ $trans('student.attendance.attendance') || 'Attendance Overview' }}</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ overviewSubtitle }}
                    </p>
                    <div class="w-full max-w-xs">
                        <BaseSelect
                            v-model="selectedPeriodKey"
                            :options="periodOptions"
                            :placeholder="periodPlaceholder"
                            :searchable="periodOptions.length > 7"
                            :disabled="!periodOptions.length"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <BaseButton
                        v-for="option in RANGE_OPTIONS"
                        :key="option.value"
                        :design="activeRange === option.value ? 'primary' : 'white'"
                        class="px-3 py-1 text-xs font-semibold"
                        @click="activeRange = option.value"
                    >
                        {{ option.label }}
                    </BaseButton>
                </div>
            </div>

            <div v-if="state.isLoading" class="mt-6 py-16 text-center text-gray-500">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl mb-3"></i>
                <p>Loading attendance…</p>
            </div>

            <div v-else-if="state.error" class="mt-6">
                <div class="py-12 text-center text-red-500 border border-red-200 bg-red-50 rounded-lg">
                    <p class="font-semibold">{{ state.error }}</p>
                    <BaseButton class="mt-3" design="primary" @click="fetchAttendance({ force: true })">
                        Retry
                    </BaseButton>
                </div>
            </div>

            <div v-else-if="!hasData" class="mt-6">
                <div class="py-12 text-center text-gray-500 border border-dashed rounded-lg">
                    <i class="fa-solid fa-calendar-xmark text-2xl mb-2"></i>
                    <p>{{ $trans('student.attendance.no_records') || 'No attendance recorded yet.' }}</p>
                </div>
            </div>

            <div v-else class="space-y-6 mt-6">
                <template v-if="!isDailyRange">
                    <div class="grid gap-3 md:grid-cols-5">
                        <div
                            v-for="card in summaryCards"
                            :key="card.key"
                            class="rounded-xl border-2 bg-white px-4 py-4 shadow-sm"
                            :style="{ borderColor: card.color }"
                        >
                            <p class="text-xs uppercase tracking-wide font-semibold" :style="{ color: card.color }">
                                {{ card.label }}
                            </p>
                            <p class="text-2xl font-semibold mt-2" :style="{ color: card.color }">
                                {{ card.value }}
                            </p>
                            <p class="text-[11px] text-gray-500 mt-1">{{ card.percent }}% of total</p>
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-3">
                        <div class="lg:col-span-2 border rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-gray-700">
                                    Trend by {{ activeRangeLabel }}
                                </h4>
                                <span class="text-xs text-gray-400">
                                    Present vs other statuses
                                </span>
                            </div>
                            <div class="h-72">
                                <Line v-if="lineChartData" :data="lineChartData" :options="lineChartOptions" />
                                <div v-else class="text-sm text-gray-500 py-12 text-center">
                                    Not enough data to render chart.
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-gray-700">
                                    Distribution
                                </h4>
                                <span class="text-xs text-gray-400">
                                    {{ totalDays }} total days
                                </span>
                            </div>
                            <div class="h-64">
                                <Doughnut v-if="doughnutData" :data="doughnutData" :options="doughnutOptions" />
                                <div v-else class="text-sm text-gray-500 py-12 text-center">
                                    Not enough data to render chart.
                                </div>
                            </div>
                            <ul class="mt-4 space-y-2 text-xs">
                                <li
                                    v-for="item in distributionLegend"
                                    :key="item.code"
                                    class="flex items-center justify-between"
                                >
                                    <span class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full inline-block" :style="{ backgroundColor: item.color }"></span>
                                        {{ item.label }}
                                    </span>
                                    <span class="font-semibold text-gray-700">
                                        {{ item.percent }}% ({{ item.count }})
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </template>

                <div class="border rounded-xl overflow-hidden">
                    <div v-if="isDailyRange" class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3 p-8">
                        <div
                            v-for="row in activeBreakdown"
                            :key="row.label"
                            class="rounded-2xl border-2 px-8 py-8 shadow-lg bg-white text-center space-y-3"
                            :style="{ borderColor: dailyTypeColor(row) }"
                        >
                            <div class="text-base uppercase tracking-wide text-gray-500">
                                {{ row.label }}
                            </div>
                            <div class="text-4xl font-bold" :style="{ color: dailyTypeColor(row) }">
                                {{ dailyTypeLabel(row) }}
                            </div>
                        </div>
                    </div>
                    <template v-else>
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wide">
                                <tr>
                                    <th class="px-3 py-2 text-left">Period</th>
                                    <th
                                        v-for="meta in legendMeta"
                                        :key="meta.code"
                                        class="px-3 py-2 text-center"
                                    >
                                        {{ meta.label }}
                                    </th>
                                    <th class="px-3 py-2 text-center">Attendance %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="row in activeBreakdown"
                                    :key="row.label"
                                    class="odd:bg-white even:bg-gray-50"
                                >
                                    <td class="px-3 py-2 text-left font-medium text-gray-700">
                                        {{ row.label }}
                                        <span
                                            v-if="row.months.length && activeRange !== 'daily'"
                                            class="block text-[11px] text-gray-500"
                                        >
                                            Includes: {{ row.months.join(', ') }}
                                        </span>
                                    </td>
                                    <td
                                        v-for="meta in legendMeta"
                                        :key="meta.code"
                                        class="px-3 py-2 text-center font-semibold"
                                        :style="{ color: meta.color }"
                                    >
                                        {{ displayCellValue(meta, row) }}
                                    </td>
                                    <td class="px-3 py-2 text-center font-semibold text-gray-800">
                                        {{ row.attendanceRate }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { inject, reactive, ref, computed, watch } from 'vue'
import { useToast } from 'vue-toastification'
import * as Api from '@core/apis'
import BaseSelect from '@core/components/Forms/BaseSelect.vue'
import BaseButton from '@core/components/Forms/BaseButton.vue'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement,
    Filler,
    Tooltip,
    Legend,
} from 'chart.js'
import { Line, Doughnut } from 'vue-chartjs'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement,
    Filler,
    Tooltip,
    Legend,
)

const props = defineProps({
    student: {
        type: Object,
        required: true,
    },
})

const $trans = inject('$trans', (key) => key)
const toast = useToast()

const RANGE_OPTIONS = [
    { value: 'daily', label: 'Daily' },
    { value: 'weekly', label: 'Weekly' },
    { value: 'monthly', label: 'Monthly' },
    { value: 'yearly', label: 'Yearly' },
]

const STATUS_COLORS = {
    present: '#22c55e',
    absent: '#ef4444',
    late: '#f59e0b',
    halfDay: '#0ea5e9',
    holiday: '#7c3aed',
}

const activeRange = ref('daily')
const selectedPeriodKey = ref(null)

const state = reactive({
    isLoading: false,
    error: null,
    payload: null,
})

const isDailyRange = computed(() => activeRange.value === 'daily')

const lastFetchedUuid = ref(null)
let requestCounter = 0

const legendMeta = computed(() => {
    const base = Array.isArray(state.payload?.legend) ? state.payload.legend : []

    if (base.length) {
        return base.map((item) => {
            const code = (item.code || '').toUpperCase()
            return {
                code,
                label: item.label || item.code || code,
                color: item.color || STATUS_COLORS[code.toLowerCase()] || '#6b7280',
                isPresent: Boolean(item.isPresent),
                isHoliday: Boolean(item.isHoliday),
            }
        })
    }

    const datasets = Array.isArray(state.payload?.monthly?.datasets)
        ? state.payload.monthly.datasets
        : []

    const fallback = new Map()
    datasets.forEach((dataset) => {
        const code = resolveDatasetKey(dataset?.label, dataset?.code)
        if (!code || fallback.has(code)) return

        fallback.set(code, {
            code,
            label: dataset.label || code,
            color:
                dataset.borderColor ||
                dataset.backgroundColor ||
                STATUS_COLORS[code.toLowerCase()] ||
                '#6b7280',
            isPresent: looksLikePresent(code, dataset.label),
            isHoliday: looksLikeHoliday(code, dataset.label),
        })
    })

    return Array.from(fallback.values())
})

const codeMetaMap = computed(() => {
    const map = {}
    legendMeta.value.forEach((meta) => {
        map[meta.code] = meta
    })
    return map
})

const overviewSubtitle = computed(() => {
    const label = RANGE_OPTIONS.find((opt) => opt.value === activeRange.value)?.label || 'Monthly'
    const name = props.student?.displayName || props.student?.name || ''
    if (!name) {
        return `View attendance by ${label.toLowerCase()}.`
    }
    return `Viewing ${name.split(' ')[0] || name}'s attendance by ${label.toLowerCase()}.`
})

const activeRangeLabel = computed(() => {
    return RANGE_OPTIONS.find((opt) => opt.value === activeRange.value)?.label || 'Monthly'
})

async function fetchAttendance({ uuid = props.student?.uuid, force = false } = {}) {
    if (!uuid) return

    if (!force && lastFetchedUuid.value === uuid && state.payload) {
        return
    }

    const requestId = ++requestCounter
    state.isLoading = true
    state.error = null

    try {
        const response = await Api.custom({
            url: `/app/students/${uuid}/attendance`,
            method: 'GET',
        })

        if (requestId !== requestCounter) {
            return
        }

        state.payload = response || null
        lastFetchedUuid.value = uuid
    } catch (error) {
        if (requestId !== requestCounter) {
            return
        }

        const message = error?.message || 'Unable to load attendance.'
        state.error = message
        if (toast) {
            toast.error(message)
        }
    } finally {
        if (requestId === requestCounter) {
            state.isLoading = false
        }
    }
}

watch(
    () => props.student?.uuid,
    (uuid) => {
        if (!uuid) {
            return
        }
        fetchAttendance({ uuid })
    },
    { immediate: true },
)

const normalizedDaily = computed(() => {
    const chartData = state.payload?.daily
    if (!chartData?.labels?.length) return []

    const datasets = Array.isArray(chartData.datasets) ? chartData.datasets : []
    const datasetMap = {}

    datasets.forEach((dataset) => {
        const key = resolveDatasetKey(dataset?.label, dataset?.code)
        if (!key) return
        datasetMap[key] = (dataset.data || []).map((value) => Number(value ?? 0))
    })

    return chartData.labels
        .map((label, index) => {
            const dateObj = parseDayLabel(label)
            if (!dateObj) return null

            return {
                label: formatDayLabel(dateObj),
                sortKey: dateObj.getTime(),
                dateObj,
                counts: buildCounts(datasetMap, index),
            }
        })
        .filter(Boolean)
        .sort((a, b) => a.sortKey - b.sortKey)
})

const rangeBuckets = computed(() => {
    const base = normalizedDaily.value
    return {
        daily: aggregateBuckets(base, 'daily'),
        weekly: aggregateBuckets(base, 'weekly'),
        monthly: aggregateBuckets(base, 'monthly'),
        yearly: aggregateBuckets(base, 'yearly'),
    }
})

const periodOptions = computed(() => {
    const buckets = rangeBuckets.value[activeRange.value] || []
    const options = buckets
        .map((bucket) => ({
            value: bucket.key,
            label: bucket.label,
            sortKey: bucket.sortKey,
            startIso: bucket.startIso,
            endIso: bucket.endIso,
        }))

    const currentDescriptor = buildPeriodDescriptor(new Date(), activeRange.value)
    if (currentDescriptor && !options.some((option) => option.value === currentDescriptor.key)) {
        options.push({
            value: currentDescriptor.key,
            label: currentDescriptor.label,
            sortKey: currentDescriptor.sortKey,
            startIso: currentDescriptor.startIso,
            endIso: currentDescriptor.endIso,
        })
    }

    return options.sort((a, b) => b.sortKey - a.sortKey)
})

const periodPlaceholder = computed(() => {
    if (!periodOptions.value.length) {
        return `No ${activeRangeLabel.value.toLowerCase()} data`
    }
    switch (activeRange.value) {
        case 'daily':
            return 'Select day'
        case 'weekly':
            return 'Select week'
        case 'monthly':
            return 'Select month'
        case 'yearly':
            return 'Select year'
        default:
            return 'Select period'
    }
})

const activeBreakdown = computed(() => {
    const buckets = rangeBuckets.value[activeRange.value] || []
    const key = pickDefaultKey(activeRange.value, buckets, selectedPeriodKey.value)

    if (!key) {
        return []
    }

    const existing = buckets.find((bucket) => bucket.key === key)
    if (existing) {
        return [existing]
    }

    const descriptor = buildPeriodDescriptor(new Date(), activeRange.value)
    if (descriptor && descriptor.key === key) {
        const emptyBucket = buildEmptyBucket(descriptor)
        return emptyBucket ? [emptyBucket] : []
    }

    return []
})

const hasData = computed(() => activeBreakdown.value.length > 0)

watch(
    [() => activeRange.value, () => periodOptions.value],
    () => {
        const buckets = rangeBuckets.value[activeRange.value] || []

        if (!buckets.length) {
            const descriptor = buildPeriodDescriptor(new Date(), activeRange.value)
            selectedPeriodKey.value = descriptor ? descriptor.key : null
            return
        }

        selectedPeriodKey.value = pickDefaultKey(activeRange.value, buckets, selectedPeriodKey.value)
    },
    { immediate: true, deep: true },
)

watch(
    () => state.payload,
    () => {
        const buckets = rangeBuckets.value[activeRange.value] || []
        selectedPeriodKey.value = pickDefaultKey(activeRange.value, buckets, selectedPeriodKey.value)
    },
)

const overallTotals = computed(() => {
    const totals = {
        byCode: {},
        totalDays: 0,
    }

    activeBreakdown.value.forEach((bucket) => {
        Object.entries(bucket.counts || {}).forEach(([code, count]) => {
            const normalized = code.toString().toUpperCase()
            totals.byCode[normalized] = (totals.byCode[normalized] || 0) + (count ?? 0)
        })

        totals.totalDays += bucket.totalDays ?? 0
    })

    legendMeta.value.forEach((meta) => {
        if (totals.byCode[meta.code] === undefined) {
            totals.byCode[meta.code] = 0
        }
    })

    return totals
})

const totalDays = computed(() => overallTotals.value.totalDays)

const summaryCards = computed(() => {
    const totals = overallTotals.value
    const total = totals.totalDays || 1

    return legendMeta.value.map((meta) => {
        const value = totals.byCode?.[meta.code] || 0
        return {
            key: meta.code,
            label: meta.label,
            value,
            color: meta.color,
            percent: total ? ((value / total) * 100).toFixed(1) : '0.0',
        }
    })
})

const distributionLegend = computed(() => {
    const totals = overallTotals.value
    const total = totals.totalDays || 1
    return legendMeta.value.map((meta) => ({
        ...meta,
        count: totals.byCode?.[meta.code] || 0,
        percent: total ? ((totals.byCode?.[meta.code] || 0) / total * 100).toFixed(1) : '0.0',
    }))
})

const lineChartData = computed(() => {
    if (isDailyRange.value) {
        return null
    }

    const buckets = activeBreakdown.value
    if (!buckets.length) return null

    const labels = buckets.map((bucket) => bucket.label)

    const datasets = legendMeta.value.map((meta) => {
        const series = buckets.map((bucket) => Number(bucket.counts?.[meta.code] ?? 0))

        return {
            label: meta.label,
            data: series,
            borderColor: meta.color,
            backgroundColor: withAlpha(meta.color, 0.1),
            tension: 0.35,
            fill: true,
            borderWidth: 2,
            code: meta.code,
        }
    })

    const hasSomeValues = datasets.some((dataset) => dataset.data.some((value) => value > 0))
    if (!hasSomeValues) {
        return null
    }

    return {
        labels,
        datasets,
    }
})

const lineChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        intersect: false,
        mode: 'index',
    },
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                usePointStyle: true,
            },
        },
        tooltip: {
            callbacks: {
                label(context) {
                    const label = context.dataset.label || ''
                    const value = context.parsed.y ?? 0
                    return `${label}: ${value}`
                },
            },
        },
    },
    scales: {
        x: {
            grid: {
                display: false,
            },
            ticks: {
                color: '#6b7280',
            },
        },
        y: {
            beginAtZero: true,
            grid: {
                color: '#e5e7eb',
            },
            ticks: {
                precision: 0,
                color: '#6b7280',
            },
        },
    },
}

const doughnutData = computed(() => {
    if (isDailyRange.value) {
        return null
    }

    const totals = overallTotals.value
    if (totals.totalDays === 0) return null

    return {
        labels: legendMeta.value.map((meta) => meta.label),
        datasets: [
            {
                data: legendMeta.value.map((meta) => totals.byCode?.[meta.code] || 0),
                backgroundColor: legendMeta.value.map((meta) => meta.color),
                borderWidth: 0,
            },
        ],
    }
})

const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '60%',
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            callbacks: {
                label(context) {
                    const label = context.label || ''
                    const value = context.parsed ?? 0
                    return `${label}: ${value}`
                },
            },
        },
    },
}

function aggregateBuckets(entries, range) {
    if (!Array.isArray(entries) || !entries.length) {
        return []
    }

    const legendLookup = codeMetaMap.value || {}
    const legendCodes = Object.keys(legendLookup)
    const bucketMap = new Map()

    entries.forEach((entry) => {
        const date = entry.dateObj
        if (!date) return

        let startDate = stripTime(date)
        let endDate = stripTime(date)
        let groupKey
        let label
        let sortKey

        switch (range) {
            case 'weekly': {
                startDate = startOfWeek(date)
                endDate = endOfWeek(startDate)
                const { week, year } = getISOWeekParts(startDate)
                groupKey = `${year}-W${String(week).padStart(2, '0')}`
                label = formatWeekRange(startDate, endDate)
                sortKey = startDate.getTime()
                break
            }
            case 'monthly': {
                startDate = startOfMonth(date)
                endDate = endOfMonth(startDate)
                groupKey = `${startDate.getFullYear()}-${String(startDate.getMonth() + 1).padStart(2, '0')}`
                label = formatMonthLabel(startDate)
                sortKey = startDate.getFullYear() * 100 + (startDate.getMonth() + 1)
                break
            }
            case 'yearly': {
                startDate = startOfYear(date)
                endDate = endOfYear(date)
                groupKey = `${startDate.getFullYear()}`
                label = formatYearLabel(startDate)
                sortKey = startDate.getFullYear()
                break
            }
            case 'daily':
            default: {
                startDate = stripTime(date)
                endDate = startDate
                groupKey = isoFromDate(startDate)
                label = formatDayLabel(startDate)
                sortKey = startDate.getTime()
                break
            }
        }

        if (!bucketMap.has(groupKey)) {
            bucketMap.set(groupKey, {
                key: groupKey,
                label,
                sortKey,
                counts: {},
                dayLabels: new Set(),
                startDate,
                endDate,
            })
        }

        const bucket = bucketMap.get(groupKey)

        if (startDate < bucket.startDate) bucket.startDate = startDate
        if (endDate > bucket.endDate) bucket.endDate = endDate

        if (range !== 'daily') {
            bucket.dayLabels.add(formatDayLabel(date))
        }

        Object.entries(entry.counts || {}).forEach(([code, value]) => {
            const normalized = code.toString().toUpperCase()
            bucket.counts[normalized] = (bucket.counts[normalized] || 0) + (value ?? 0)
        })
    })

    return Array.from(bucketMap.values())
        .map((bucket) => {
            const lookup = legendLookup
            const codes = legendCodes.length ? legendCodes : Object.keys(bucket.counts)

            codes.forEach((code) => {
                bucket.counts[code] = bucket.counts[code] ?? 0
            })

            let nonHolidayTotal = 0
            let presentTotal = 0

            codes.forEach((code) => {
                const meta = lookup[code] || {}
                const value = bucket.counts[code] ?? 0
                if (!meta.isHoliday) {
                    nonHolidayTotal += value
                }
                if (meta.isPresent) {
                    presentTotal += value
                }
            })

            const attendanceRate = nonHolidayTotal
                ? ((presentTotal / nonHolidayTotal) * 100).toFixed(1)
                : '0.0'

            return {
                key: bucket.key,
                label: bucket.label,
                sortKey: bucket.sortKey,
                counts: bucket.counts,
                months: Array.from(bucket.dayLabels || []),
                totalDays: nonHolidayTotal,
                attendanceRate,
                startIso: isoFromDate(bucket.startDate),
                endIso: isoFromDate(bucket.endDate),
            }
        })
        .sort((a, b) => a.sortKey - b.sortKey)
}

function stripTime(date) {
    const d = new Date(date.getFullYear(), date.getMonth(), date.getDate())
    d.setHours(0, 0, 0, 0)
    return d
}

function isoFromDate(date) {
    if (!date) return null
    const d = stripTime(date)
    const year = d.getFullYear()
    const month = String(d.getMonth() + 1).padStart(2, '0')
    const day = String(d.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
}

function endOfMonth(date) {
    const d = new Date(date.getFullYear(), date.getMonth() + 1, 0)
    d.setHours(0, 0, 0, 0)
    return d
}

function startOfYear(date) {
    const d = new Date(date.getFullYear(), 0, 1)
    d.setHours(0, 0, 0, 0)
    return d
}

function endOfYear(date) {
    const d = new Date(date.getFullYear(), 11, 31)
    d.setHours(0, 0, 0, 0)
    return d
}

function isIsoBetween(target, start, end) {
    if (!target || !start || !end) return false
    return target >= start && target <= end
}

function todayIsoString() {
    return isoFromDate(new Date())
}

function buildPeriodDescriptor(dateInput, range) {
    if (!dateInput) return null

    const base = stripTime(dateInput instanceof Date ? dateInput : new Date(dateInput))
    if (Number.isNaN(base.getTime())) {
        return null
    }

    let startDate = base
    let endDate = base
    let key
    let label
    let sortKey
    let dayLabels = []

    switch (range) {
        case 'weekly': {
            startDate = startOfWeek(base)
            endDate = endOfWeek(startDate)
            const { week, year } = getISOWeekParts(startDate)
            key = `${year}-W${String(week).padStart(2, '0')}`
            label = formatWeekRange(startDate, endDate)
            sortKey = startDate.getTime()
            dayLabels = Array.from({ length: 5 }, (_, index) => {
                const day = stripTime(startDate)
                day.setDate(startDate.getDate() + index)
                return formatDayLabel(day)
            })
            break
        }
        case 'monthly': {
            startDate = startOfMonth(base)
            endDate = endOfMonth(startDate)
            key = `${startDate.getFullYear()}-${String(startDate.getMonth() + 1).padStart(2, '0')}`
            label = formatMonthLabel(startDate)
            sortKey = startDate.getFullYear() * 100 + (startDate.getMonth() + 1)
            dayLabels = [label]
            break
        }
        case 'yearly': {
            startDate = startOfYear(base)
            endDate = endOfYear(base)
            key = `${startDate.getFullYear()}`
            label = formatYearLabel(startDate)
            sortKey = startDate.getFullYear()
            dayLabels = [label]
            break
        }
        case 'daily':
        default: {
            key = isoFromDate(base)
            label = formatDayLabel(base)
            sortKey = base.getTime()
            dayLabels = [label]
            break
        }
    }

    return {
        key,
        label,
        sortKey,
        startIso: isoFromDate(startDate),
        endIso: isoFromDate(endDate),
        dayLabels,
    }
}

function buildEmptyBucket(descriptor) {
    if (!descriptor) return null

    const legendCodes = Object.keys(codeMetaMap.value || {})
    const counts = {}
    legendCodes.forEach((code) => {
        counts[code] = 0
    })

    return {
        key: descriptor.key,
        label: descriptor.label,
        sortKey: descriptor.sortKey,
        counts,
        months: descriptor.dayLabels || [],
        totalDays: 0,
        attendanceRate: '0.0',
        startIso: descriptor.startIso,
        endIso: descriptor.endIso,
    }
}

function pickDefaultKey(range, buckets, currentKey = null) {
    const list = Array.isArray(buckets) ? buckets : []

    if (currentKey && list.some((bucket) => bucket.key === currentKey)) {
        return currentKey
    }

    if (!list.length) {
        const descriptor = buildPeriodDescriptor(new Date(), range)
        return descriptor ? descriptor.key : null
    }

    const descriptor = buildPeriodDescriptor(new Date(), range)
    if (!descriptor) {
        return list[list.length - 1].key
    }

    if (range === 'daily') {
        return descriptor.key
    }

    const todayIso = todayIsoString()
    const containing = list.find((bucket) =>
        isIsoBetween(todayIso, bucket.startIso, bucket.endIso) ||
        isIsoBetween(descriptor.startIso, bucket.startIso, bucket.endIso) ||
        isIsoBetween(descriptor.endIso, bucket.startIso, bucket.endIso),
    )

    if (containing) {
        return containing.key
    }

    return descriptor.key
}

function resolveDatasetKey(label, rawCode) {
    if (rawCode) {
        return rawCode.toString().toUpperCase()
    }
    if (!label) return null
    return label.toString().toUpperCase()
}

function parseDayLabel(label) {
    if (!label) return null
    const parts = label.split('-').map(Number)
    if (parts.length === 3 && parts.every((part) => !Number.isNaN(part))) {
        const [year, month, day] = parts
        return new Date(year, month - 1, day)
    }
    const direct = new Date(label)
    if (!Number.isNaN(direct.getTime())) {
        return direct
    }
    return null
}

function formatDayLabel(dateObj) {
    return dateObj.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })
}

function formatMonthLabel(dateObj) {
    return dateObj.toLocaleDateString('en-US', {
        month: 'short',
        year: 'numeric',
    })
}

function formatYearLabel(dateObj) {
    return dateObj.getFullYear().toString()
}

function formatWeekRange(start, end) {
    const startLabel = start.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
    const endLabel = end.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
    const startYear = start.getFullYear()
    const endYear = end.getFullYear()

    if (startYear === endYear) {
        return `${startLabel} – ${endLabel}, ${endYear}`
    }

    return `${startLabel}, ${startYear} – ${endLabel}, ${endYear}`
}

function startOfWeek(date) {
    const d = stripTime(date)
    const day = d.getDay()
    const diff = day === 0 ? -6 : 1 - day
    d.setDate(d.getDate() + diff)
    return stripTime(d)
}

function endOfWeek(start) {
    const d = stripTime(start)
    d.setDate(d.getDate() + 4)
    return stripTime(d)
}

function startOfMonth(date) {
    return stripTime(new Date(date.getFullYear(), date.getMonth(), 1))
}

function buildCounts(map, index) {
    const counts = {}
    Object.keys(map).forEach((key) => {
        const normalized = key.toString().toUpperCase()
        counts[normalized] = map[key]?.[index] ?? 0
    })
    return counts
}

function withAlpha(color, alpha = 0.2) {
    if (!color) return `rgba(107, 114, 128, ${alpha})`

    const hex = color.replace('#', '')
    if (hex.length === 3) {
        const r = parseInt(hex[0] + hex[0], 16)
        const g = parseInt(hex[1] + hex[1], 16)
        const b = parseInt(hex[2] + hex[2], 16)
        return `rgba(${r}, ${g}, ${b}, ${alpha})`
    }

    if (hex.length === 6) {
        const r = parseInt(hex.slice(0, 2), 16)
        const g = parseInt(hex.slice(2, 4), 16)
        const b = parseInt(hex.slice(4, 6), 16)
        return `rgba(${r}, ${g}, ${b}, ${alpha})`
    }

    return color
}

function looksLikePresent(code, label) {
    const value = `${code || ''} ${label || ''}`.toLowerCase()
    return value.includes('present') || value === 'p' || value.startsWith('pr')
}

function looksLikeHoliday(code, label) {
    const value = `${code || ''} ${label || ''}`.toLowerCase()
    return value.includes('holiday') || value === 'h' || value.startsWith('hol')
}

function getISOWeekParts(date) {
    const temp = new Date(date.getTime())
    temp.setUTCDate(temp.getUTCDate() + 4 - (temp.getUTCDay() || 7))
    const year = temp.getUTCFullYear()
    const startOfYear = new Date(Date.UTC(year, 0, 1))
    const diff = temp - startOfYear
    const week = Math.floor(diff / (7 * 24 * 60 * 60 * 1000)) + 1
    return { week, year }
}

function displayCellValue(meta, row) {
    const value = row.counts?.[meta.code] ?? 0
    if (isDailyRange.value) {
        return value > 0 ? meta.label : '—'
    }

    return value
}

function dailyTypeLabel(row) {
    const entry = legendMeta.value.find((meta) => (row.counts?.[meta.code] ?? 0) > 0)
    return entry ? entry.label : '—'
}

function dailyTypeColor(row) {
    const entry = legendMeta.value.find((meta) => (row.counts?.[meta.code] ?? 0) > 0)
    return entry ? entry.color : '#6b7280'
}
</script>
