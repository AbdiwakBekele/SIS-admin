<template>
    <div v-if="isLoading" class="w-full bg-white rounded-2xl shadow-lg p-16 text-center">
        <div class="inline-flex flex-col items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600 mb-4"></div>
            <p class="text-lg font-medium text-gray-700">Loading course catalog...</p>
        </div>
    </div>

    <div v-else class="course-catalog-container">
        <div class="flex items-center justify-between mb-4">
            <div class="text-sm text-gray-600">
                {{ headerStats }}
            </div>
            <div class="relative">
                <BaseButton 
                    ref="filterButton" 
                    id="course-catalog-filter-button"
                    design="white" 
                    size="sm" 
                    @click="toggleFilter"
                >
                    <i class="fa-solid fa-filter mr-2"></i>
                    Filter
                </BaseButton>
                <FilterPopover
                    v-if="showFilter"
                    :key="showFilter"
                    :init-form="initForm"
                    :form="form"
                    :multiple="[]"
                    @afterFilter="showFilter = false"
                    @hide="showFilter = false"
                    v-slot="{ apply, reset }"
                >
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <BaseSelect
                                v-model="form.division"
                                name="division"
                                label="Division"
                                :options="stateData.divisions"
                                label-prop="name"
                                value-prop="name"
                                placeholder="All Divisions"
                            />
                        </div>

                        <div v-if="form.division">
                            <BaseSelect
                                v-model="form.subject"
                                name="subject"
                                label="Subject"
                                :options="stateData.subjects"
                                label-prop="name"
                                value-prop="name"
                                placeholder="All Subjects"
                            />
                        </div>

                        <div v-if="form.subject && gradeLevels.length">
                            <BaseSelect
                                v-model="form.gradeLevel"
                                name="gradeLevel"
                                label="Grade Level"
                                :options="gradeLevels"
                                label-prop="name"
                                value-prop="name"
                                placeholder="All Grades"
                            />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <BaseButton design="secondary" @click="reset">
                            {{ $trans('general.reset') }}
                        </BaseButton>
                        <BaseButton design="primary" @click="apply">
                            {{ $trans('general.apply') }}
                        </BaseButton>
                    </div>
                </FilterPopover>
            </div>
        </div>

        <div class="course-catalog-scroll">
            <table class="course-catalog-table">
                <thead>
                    <tr>
                        <th class="sticky-column sticky-header">Subject</th>
                        <th 
                            v-for="grade in visibleGrades" 
                            :key="grade"
                            class="sticky-header"
                        >
                            {{ grade }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="subject in visibleSubjects" :key="subject">
                        <td class="sticky-column">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full mr-3"></div>
                                <span class="font-bold text-gray-800">{{ subject }}</span>
                            </div>
                        </td>
                        <td
                            v-for="grade in visibleGrades"
                            :key="grade"
                            class="text-center"
                        >
                            <template v-if="visibleCourses[subject] && visibleCourses[subject][grade]?.length">
                                <div class="space-y-2">
                                    <div
                                        v-for="course in visibleCourses[subject][grade]"
                                        :key="course.uuid"
                                        class="group flex items-center p-2 rounded-lg hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 transition-all cursor-default"
                                    >
                                        <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2.5 flex-shrink-0"></div>
                                        <span class="text-gray-700 font-medium text-xs truncate">{{ course.name }}</span>
                                    </div>
                                </div>
                            </template>
                            <span v-else class="text-gray-400 italic text-xs block text-center py-2">â€” none â€”</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch, inject, nextTick } from 'vue'
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import FilterPopover from "@core/components/Forms/FilterPopover.vue"

const router = useRouter()
const store = useStore()
const $trans = inject("$trans")

const showFilter = ref(false)
const filterButton = ref(null)

const toggleFilter = async () => {
  if (showFilter.value) {
    showFilter.value = false
  } else {
    showFilter.value = true
    await nextTick()
    await new Promise(resolve => setTimeout(resolve, 50))
    const popover = document.querySelector('.filter-popover')
    const button = document.getElementById('course-catalog-filter-button')
    if (popover && button) {
      const buttonRect = button.getBoundingClientRect()
      popover.style.right = `${window.innerWidth - buttonRect.right}px`
    }
  }
}

const initForm = {
  division: '',
  subject: '',
  gradeLevel: '',
}

const form = reactive({ ...initForm })

const stateData = reactive({
  divisions: [],
  subjects: [],
})

const coursesBySubject = reactive({})
const isLoading = ref(false)

const gradeLevels = computed(() => {
  if (form.division) {
    const division = stateData.divisions.find(d => d.name === form.division)
    return division?.gradeLevels || []
  }

  const map = new Map()
  stateData.divisions.forEach(division => {
    ;(division.gradeLevels || []).forEach(grade => {
      if (!map.has(grade.name)) {
        map.set(grade.name, grade)
      }
    })
  })

  return Array.from(map.values())
})

const subjects = computed(() => stateData.subjects.map(s => s.name))

const visibleSubjects = computed(() =>
  form.subject ? [form.subject] : subjects.value
)

const visibleGrades = computed(() => {
  const allGrades = form.gradeLevel
    ? [form.gradeLevel]
    : gradeLevels.value.map(g => g.name)

  const sorted = allGrades.sort((a, b) => {
    const numA = parseInt(a.match(/\d+/)?.[0] || '0')
    const numB = parseInt(b.match(/\d+/)?.[0] || '0')
    return numA - numB
  })

  return sorted
})

const visibleCourses = computed(() => {
  const map = {}
  visibleSubjects.value.forEach(subj => {
    map[subj] = {}
    visibleGrades.value.forEach(grade => {
      map[subj][grade] = (coursesBySubject[subj]?.[grade] || [])
    })
  })
  return map
})

const headerStats = computed(() => {
  const subjectCount = visibleSubjects.value.length
  const gradeCount = visibleGrades.value.length

  const subjectLabel = subjectCount === 1 ? 'Subject' : 'Subjects'
  const gradeLabel = gradeCount === 1 ? 'Grade' : 'Grades'

  return `${subjectCount} ${subjectLabel} Â· ${gradeCount} ${gradeLabel} Available`
})

watch(() => form.division, () => {
  form.gradeLevel = ''
  form.subject = ''
})

const getCourseCatalog = async () => {
  isLoading.value = true
  try {
    const response = await store.dispatch("schoolSetup/academicCourse/courseCatalog")

    stateData.divisions = response.divisions.map(division => ({
      name: division.name,
      uuid: division.uuid,
      gradeLevels: division.gradeLevels || []
    }))

    stateData.subjects = response.subjects.map(subject => ({
      name: subject.name,
      uuid: subject.uuid,
      courses: subject.courses
    }))

    for (const subject of stateData.subjects) {
      coursesBySubject[subject.name] = {}
      for (const division of stateData.divisions) {
        for (const grade of division.gradeLevels) {
          const matchingCourses = subject.courses.filter(
            course =>
              Array.isArray(course.gradeLevels) &&
              course.gradeLevels.some(gl => gl.uuid === grade.uuid)
          )

          if (!coursesBySubject[subject.name][grade.name]) {
            coursesBySubject[subject.name][grade.name] = []
          }

          coursesBySubject[subject.name][grade.name].push(
            ...matchingCourses.map(course => ({
              uuid: course.uuid,
              name: course.name,
            }))
          )
        }
      }
    }
  } catch (e) {
    router.push({ name: "Dashboard" })
  } finally {
    isLoading.value = false
  }
}

onMounted(async () => {
  await getCourseCatalog()
})
</script>

<style scoped>
.course-catalog-container {
    overflow-x: auto;
}

.course-catalog-scroll {
    max-height: 60vh;
    overflow-y: auto;
    overflow-x: auto;
    border: 1px solid rgb(147 197 253);
    border-radius: 0.75rem;
    background: white;
}

.course-catalog-table {
    width: 100%;
    min-width: max-content;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
}

/* Header row - sticky at top */
.course-catalog-table thead {
    position: sticky;
    top: 0;
    z-index: 20;
}

.course-catalog-table thead th {
    position: sticky;
    top: 0;
    background: linear-gradient(to bottom right, rgb(37 99 235), rgb(59 130 246), rgb(96 165 250));
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    padding: 1rem;
    text-align: left;
    border-bottom: 2px solid rgb(29 78 216);
    border-right: 1px solid rgb(147 197 253);
    white-space: nowrap;
}

.course-catalog-table thead th:last-child {
    border-right: none;
}

.course-catalog-table thead th:first-child {
    text-align: left;
}

.course-catalog-table thead th:not(:first-child) {
    text-align: center;
}

/* Subject column - sticky on left */
.course-catalog-table th.sticky-column,
.course-catalog-table td.sticky-column {
    position: sticky;
    left: 0;
    z-index: 15;
    border-right: 2px solid rgb(147 197 253);
}

.course-catalog-table thead th.sticky-column {
    z-index: 25;
    background: linear-gradient(to bottom right, rgb(29 78 216), rgb(37 99 235), rgb(59 130 246));
}

/* Table body */
.course-catalog-table tbody tr {
    border-bottom: 1px solid rgb(229 231 235);
}

.course-catalog-table tbody tr:nth-child(odd) {
    background-color: white;
}

.course-catalog-table tbody tr:nth-child(even) {
    background-color: rgb(239 246 255);
}

/* Subject column - highlighted with primary color */
.course-catalog-table tbody tr:nth-child(odd) td.sticky-column {
    background-color: rgb(239 246 255);
}

.course-catalog-table tbody tr:nth-child(even) td.sticky-column {
    background-color: rgb(219 234 254);
}

.course-catalog-table tbody td {
    padding: 1rem;
    vertical-align: top;
    border-right: 1px solid rgb(191 219 254);
}

.course-catalog-table tbody td:last-child {
    border-right: none;
}

.course-catalog-table tbody td.text-center {
    text-align: center;
}

/* Dark mode support */
.dark .course-catalog-scroll {
    background: rgb(17 24 39);
    border-color: rgb(59 130 246);
}

.dark .course-catalog-table {
    background: rgb(17 24 39);
}

.dark .course-catalog-table thead th {
    background: linear-gradient(to bottom right, rgb(30 64 175), rgb(37 99 235), rgb(59 130 246));
    border-bottom-color: rgb(30 58 138);
    border-right-color: rgb(59 130 246 / 0.5);
}

.dark .course-catalog-table thead th.sticky-column {
    background: linear-gradient(to bottom right, rgb(30 58 138), rgb(29 78 216), rgb(37 99 235));
}

.dark .course-catalog-table th.sticky-column,
.dark .course-catalog-table td.sticky-column {
    border-right-color: rgb(59 130 246);
}

.dark .course-catalog-table tbody tr {
    border-bottom-color: rgb(55 65 81);
}

.dark .course-catalog-table tbody tr:nth-child(odd) {
    background-color: rgb(17 24 39);
}

.dark .course-catalog-table tbody tr:nth-child(even) {
    background-color: rgb(30 58 138 / 0.3);
}

/* Subject column - highlighted with primary color in dark mode */
.dark .course-catalog-table tbody tr:nth-child(odd) td.sticky-column {
    background-color: rgb(30 58 138 / 0.4);
}

.dark .course-catalog-table tbody tr:nth-child(even) td.sticky-column {
    background-color: rgb(30 58 138 / 0.5);
}

.dark .course-catalog-table tbody td {
    border-right-color: rgb(59 130 246 / 0.3);
}

/* Scrollbar styling */
.course-catalog-scroll {
    scrollbar-width: thin;
    scrollbar-color: rgb(203 213 225) rgb(241 245 249);
}

.course-catalog-scroll::-webkit-scrollbar {
    width: 12px;
    height: 12px;
}

.course-catalog-scroll::-webkit-scrollbar-track {
    background: rgb(241 245 249);
    border-radius: 6px;
}

.course-catalog-scroll::-webkit-scrollbar-thumb {
    background: rgb(203 213 225);
    border-radius: 6px;
    border: 2px solid rgb(241 245 249);
}

.course-catalog-scroll::-webkit-scrollbar-thumb:hover {
    background: rgb(148 163 184);
}

/* Override FilterPopover positioning - move it slightly to the left */
.relative :deep(.filter-popover) {
    right: 1rem !important;
    transform: translateX(0) !important;
}
</style>
