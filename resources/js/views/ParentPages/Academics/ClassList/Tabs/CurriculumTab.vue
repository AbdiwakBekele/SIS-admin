<template>
    <div class="bg-white rounded-lg p-6 space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Curriculum</h3>
        </div>

        <div v-if="unitsLoading" class="text-center py-8 text-gray-500">
            <i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i>
            <div>Loading curriculum...</div>
        </div>

        <div v-else-if="!normalizedUnits.length" class="text-center py-8 text-gray-500">
            <i class="fa-solid fa-layer-group text-4xl mb-3"></i>
            <p class="text-sm">No units have been defined yet. Create a unit to get started.</p>
        </div>

        <div v-else class="space-y-4">
            <div v-for="unit in normalizedUnits" :key="unit.uuid" class="border rounded-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 bg-gray-100 cursor-pointer"
                    @click="toggleUnit(unit.uuid)">
                    <div class="flex items-center gap-2">
                        <i
                            :class="['fa-solid', expandedUnits.includes(unit.uuid) ? 'fa-chevron-down' : 'fa-chevron-right']"></i>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ unit.name || 'Untitled Unit' }}</p>
                            <p v-if="unit.description" class="text-xs text-gray-500">{{ unit.description }}</p>
                        </div>
                    </div>
                </div>


                <div v-if="expandedUnits.includes(unit.uuid)" class="p-4 space-y-6 bg-white">

                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Lessons & Assessments</h4>
                        <div v-if="!unit.lessons?.length" class="text-sm text-gray-500">
                            No lessons yet.
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="lesson in unit.lessons" :key="lesson.uuid" class="border rounded-lg">
                                <!-- Lesson header accordion -->
                                <div class="flex items-start justify-between gap-3 p-3 bg-gray-50">
                                    <button class="flex flex-1 items-start gap-3 text-left"
                                        @click="toggleLessonAccordion(lesson.uuid)">
                                        <i
                                            :class="['fa-solid mt-1 text-xs', lessonAccordionOpen(lesson.uuid) ? 'fa-chevron-down' : 'fa-chevron-right']"></i>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-700">
                                                {{ lesson.title || 'Lesson' }}
                                            </p>
                                            <p v-if="lesson.content" class="text-xs text-gray-500">
                                                {{ lesson.content }}
                                            </p>
                                        </div>
                                    </button>
                                    <div class="flex items-center gap-3 text-sm">

                                    </div>
                                </div>

                                <transition name="fade">
                                    <div v-if="lessonAccordionOpen(lesson.uuid)" class="p-3 space-y-3">
                                        <div class="text-xs text-gray-500 flex flex-wrap gap-3">
                                            <span>Total assessments: {{ lesson.assessments?.length || 0 }}</span>
                                            <span v-if="lesson.activities?.length">
                                                Activities linked: {{ lesson.activities.length }}
                                            </span>
                                        </div>

                                        <div class="space-y-2">
                                            <div v-if="!lesson.assessments?.length"
                                                class="text-xs text-gray-500 bg-white border border-dashed rounded px-3 py-2">
                                                No assessments have been recorded for this lesson yet.
                                            </div>
                                            <div v-for="assessment in lesson.assessments" :key="assessment.uuid"
                                                class="border border-dashed border-gray-200 rounded-md">
                                                <div class="flex items-center justify-between gap-3 px-3 py-2 bg-white">
                                                    <button class="flex items-center gap-2 text-left"
                                                        @click="toggleAssessmentAccordion(unit.uuid, lesson.uuid, assessment.uuid)">
                                                        <i
                                                            :class="['fa-solid text-xs', assessmentAccordionOpen(unit.uuid, lesson.uuid, assessment.uuid) ? 'fa-chevron-down' : 'fa-chevron-right']"></i>
                                                        <span class="text-sm font-semibold text-gray-700">
                                                            {{ assessment.title }}
                                                        </span>
                                                    </button>
                                                    <div class="flex items-center gap-3 text-xs">
                                                        <span class="text-gray-500">Max
                                                            {{ assessment.maxScore ?? assessment.max_score }}</span>
                                                    </div>
                                                </div>


                                                <transition name="fade">
                                                    <div v-if="assessmentAccordionOpen(unit.uuid, lesson.uuid, assessment.uuid)"
                                                        class="px-3 py-2 text-xs text-gray-600 bg-slate-50 space-y-1">
                                                        <div>Category: {{ assessment.category || '—' }}</div>
                                                        <div>Term: {{ assessment.term || 'No term' }}</div>
                                                        <div v-if="assessment.assigned_date">Assigned:
                                                            {{ assessment.assigned_date }}
                                                        </div>
                                                        <div v-if="assessment.due_date">Due: {{ assessment.due_date }}
                                                        </div>
                                                        <div
                                                            v-if="assessment.weight !== null && assessment.weight !== undefined">
                                                            Weight: {{ assessment.weight }}
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                            <span v-if="assessment.is_extra_credit">Marked as extra
                                                                credit</span>
                                                            <span v-if="assessment.is_published">Published</span>
                                                        </div>
                                                    </div>
                                                </transition>
                                            </div>
                                        </div>
                                    </div>
                                </transition>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Activities</h4>
                        <div v-if="!unit.activities?.length" class="text-sm text-gray-500">
                            No activities recorded for this unit.
                        </div>
                        <div v-else class="space-y-2">
                            <div v-for="activity in unit.activities" :key="activity.uuid"
                                class="border rounded-md p-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-700">{{ activity.name }}</p>
                                        <p v-if="activity.activity_type" class="text-xs text-gray-500">
                                            Type: {{ activity.activity_type }}
                                        </p>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <p v-if="activity.start_date">Start: {{ activity.start_date }}</p>
                                        <p v-if="activity.due_date">Due: {{ activity.due_date }}</p>
                                    </div>
                                </div>
                                <p v-if="activity.description" class="mt-2 text-gray-600 text-xs">
                                    {{ activity.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useStore } from 'vuex'

const props = defineProps({
    classUuid: { type: String, required: true },
    terms: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
})

const emit = defineEmits(['assessment-created'])

const store = useStore()

const units = ref([])
const unitsLoading = ref(false)
const expandedUnits = ref([])
const expandedLessons = ref([])
const expandedAssessments = ref([])

const normalizedUnits = computed(() =>
    (units.value || []).filter((unit) => unit && typeof unit === 'object')
)

const categoryOptions = computed(() => props.categories || [])

const termOptions = computed(() => {
    return (props.terms || []).map((term) => ({
        label: term.title || term.shortTitle || `Term ${term.termNo}`,
        value: term.uuid,
    }))
})

function toggleUnit(uuid) {
    if (expandedUnits.value.includes(uuid)) {
        expandedUnits.value = expandedUnits.value.filter((id) => id !== uuid)
    } else {
        expandedUnits.value = [...expandedUnits.value, uuid]
    }
}

// Maintain accordion state for lessons and assessments
function lessonAccordionOpen(uuid) {
    return expandedLessons.value.includes(uuid)
}

function toggleLessonAccordion(uuid) {
    if (lessonAccordionOpen(uuid)) {
        expandedLessons.value = expandedLessons.value.filter((id) => id !== uuid)
    } else {
        expandedLessons.value = [...expandedLessons.value, uuid]
    }
}

function assessmentAccordionOpen(unitUuid, lessonUuid, assessmentUuid) {
    const key = `${unitUuid}::${lessonUuid}::${assessmentUuid}`
    return expandedAssessments.value.includes(key)
}

function toggleAssessmentAccordion(unitUuid, lessonUuid, assessmentUuid) {
    const key = `${unitUuid}::${lessonUuid}::${assessmentUuid}`
    if (assessmentAccordionOpen(unitUuid, lessonUuid, assessmentUuid)) {
        expandedAssessments.value = expandedAssessments.value.filter((id) => id !== key)
    } else {
        expandedAssessments.value = [...expandedAssessments.value, key]
    }
}

async function loadUnits() {
    if (!props.classUuid) {
        units.value = []
        return
    }
    unitsLoading.value = true
    try {
        const response = await store.dispatch('schoolSetup/academicClass/getCurriculum', {
            classUuid: props.classUuid,
        })
        units.value = Array.isArray(response) ? response : []
    } catch (error) {
        console.error('Failed to load curriculum:', error)
        window.toast?.error?.('Failed to load curriculum')
    } finally {
        unitsLoading.value = false
    }
}

watch(
    () => props.classUuid,
    () => {
        loadUnits()
    },
    { immediate: true }
)

</script>