<template>
    <div class="bg-white rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold">Progress</h3>
            <div class="flex items-center gap-2">
                <div class="hidden sm:flex items-center text-xs text-gray-600">
                    <span class="mr-2">Term:</span>
                    <div class="inline-flex border rounded overflow-hidden">
                        <button v-for="t in termOptions" :key="t" class="px-2 py-1"
                            :class="selectedTerm === t ? 'bg-blue-600 text-white' : 'bg-white'"
                            @click="selectedTerm = t">
                            {{ t }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <table class="w-full table-auto text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Student</th>
                    <th class="px-4 py-2 text-left">Current Term %</th>
                    <th class="px-4 py-2 text-left">Progress</th>
                    <th class="px-4 py-2 text-left">Notes</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in computedGrades" :key="row.sid" class="border-b">
                    <td class="px-4 py-2">{{ row.studentName }}</td>
                    <td class="px-4 py-2">
                        <span v-if="row.overrideActive" class="inline-flex items-center gap-1">
                            {{ row.termPercent.toFixed(1) }}%
                            <i class="fa-solid fa-key text-amber-600" title="Manual override applied"></i>
                        </span>
                        <span v-else>{{ row.termPercent.toFixed(1) }}%</span>
                    </td>
                    <td class="px-4 py-2">{{ row.coursePercent.toFixed(1) }}%</td>
                    <td class="px-4 py-2">
                        <div class="w-full bg-gray-200 h-2 rounded overflow-hidden">
                            <div class="h-2 bg-blue-600" :style="{ width: Math.min(100, row.coursePercent) + '%' }">
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-[11px] text-gray-500">
                        <span v-if="row.overrideActive">Term % is manually set in Gradebook.</span>
                        <span v-else>Calculated from enabled assessments.</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    classData: { type: Object, required: true },
})

const emit = defineEmits([])

const selectedTerm = ref('All')
const termOptions = ['All', 'T1', 'T2', 'T3', 'T4']

const students = computed(() => props.classData.students || [])
const assessments = computed(() => props.classData.assessments || [])
const config = computed(() => props.classData.config || { usedTypes: {}, weights: {}, manualTermEnabled: false })
const manualTermOverrides = computed(() => props.classData.manualTermOverrides || {})

function sid(student, index) {
    return `${student.uuid || student.name}#${index}`
}

function getScore(student, title) {
    const found = student.assessments.find(x => x.type === title)
    return found ? Number(found.score) : 0
}

function weightedPercent(student, termFilter = 'All') {
    const byCat = {}
    for (const a of assessments.value) {
        if (!config.value.usedTypes[a.category]) continue
        if (termFilter !== 'All' && a.term !== termFilter) continue
        if (!byCat[a.category]) byCat[a.category] = { sum: 0, max: 0 }
        byCat[a.category].sum += getScore(student, a.title)
        byCat[a.category].max += a.max
    }
    let percent = 0
    let activeWeight = 0
    for (const cat of Object.keys(byCat)) {
        const w = Number(config.value.weights[cat]) || 0
        if (byCat[cat].max <= 0 || w <= 0) continue
        const catPct = (byCat[cat].sum / byCat[cat].max) * 100
        percent += catPct * (w / 100)
        activeWeight += w
    }
    if (activeWeight > 0 && activeWeight !== 100) percent = percent * (100 / activeWeight)
    return percent
}

function termPercent(student, sIdx) {
    if (config.value.manualTermEnabled && selectedTerm.value !== 'All') {
        const key = sid(student, sIdx)
        const v = manualTermOverrides.value[key]
        if (v !== undefined && v !== null && v !== '') return Number(v)
    }
    return weightedPercent(student, selectedTerm.value)
}

function coursePercent(student) {
    return weightedPercent(student, 'All')
}

const computedGrades = computed(() => {
    return students.value.map((s, idx) => {
        const tp = termPercent(s, idx)
        const cp = coursePercent(s)
        return {
            sid: sid(s, idx),
            studentName: s.name,
            termPercent: tp,
            coursePercent: cp,
            overrideActive: config.value.manualTermEnabled && selectedTerm.value !== 'All' &&
                manualTermOverrides.value[sid(s, idx)] !== undefined &&
                manualTermOverrides.value[sid(s, idx)] !== null &&
                manualTermOverrides.value[sid(s, idx)] !== ''
        }
    })
})
</script>
