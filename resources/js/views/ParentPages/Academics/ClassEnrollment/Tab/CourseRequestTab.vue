<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between mb-4">
      <div class="text-sm text-gray-600">
        {{ groupedRequests.length }} request group{{ groupedRequests.length !== 1 ? 's' : '' }} found
      </div>
    </div>

    <div class="rounded-xl border border-blue-200/50 bg-white shadow-lg overflow-hidden">
      <div class="p-6">
      <!-- Loading -->
      <div v-if="isLoading" class="w-full bg-white rounded-xl shadow-lg p-16 text-center border border-blue-200/50">
        <div class="inline-flex flex-col items-center">
          <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600 mb-4"></div>
          <p class="text-lg font-medium text-gray-700">Loading requests...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="!groupedRequests.length" class="w-full bg-white rounded-xl shadow-lg p-16 text-center border border-blue-200/50">
        <div class="max-w-md mx-auto">
          <div class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-full w-28 h-28 mx-auto mb-6 flex items-center justify-center">
            <i class="fa-solid fa-clipboard-list text-5xl text-blue-600"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-3">No Requests Found</h3>
          <p class="text-gray-600">No course requests found for your children.</p>
        </div>
      </div>

      <!-- Requests List -->
      <div v-else class="w-full space-y-5">
        <div v-for="(request, reqIndex) in groupedRequests" :key="request.id"
          class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300">

          <!-- Student Header -->
          <div class="bg-gradient-to-br from-blue-50 to-white px-6 py-4 flex justify-between items-center cursor-pointer border-b border-blue-200/50"
            @click="toggleExpand(reqIndex)">
            <div>
              <p class="text-lg font-bold text-blue-900">{{ request.studentName }}</p>
              <p class="text-sm text-blue-700">
                {{ request.schoolYear }} • Grade {{ request.gradeLevel }}
              </p>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-medium text-blue-600 bg-blue-100 px-3 py-1 rounded-full">
                {{ request.requestedCourses.length }} course{{ request.requestedCourses.length !== 1 ? 's' : '' }}
              </span>
              <i class="fa-solid fa-chevron-down text-blue-600 transition-transform"
                :class="expandedIndex === reqIndex ? 'rotate-180' : ''"></i>
            </div>
          </div>

          <!-- Expanded Courses -->
          <Transition name="slide">
            <div v-if="expandedIndex === reqIndex" class="px-6 py-5 border-t border-gray-200">
              <div class="overflow-x-auto">
                <table class="w-full min-w-[600px]">
                  <thead>
                    <tr class="text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                      <th class="pb-3">Course</th>
                      <th class="pb-3">Type</th>
                      <th class="pb-3">Requested</th>
                      <th class="pb-3">Status</th>
                      <th class="pb-3">Reason (if Denied)</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr v-for="course in request.requestedCourses" :key="course.uuid"
                      class="hover:bg-blue-50/50 transition">
                      <td class="py-3 text-sm font-medium text-gray-900">{{ course.name }}</td>
                      <td class="py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="course.typeLabel === 'Withdrawal' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                          {{ course.typeLabel }}
                        </span>
                      </td>
                      <td class="py-3 text-xs text-gray-500">{{ course.createdAt || '—' }}</td>
                      <td class="py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="{
                            'bg-yellow-100 text-yellow-800': course.status === 'pending',
                            'bg-green-100 text-green-800': course.status === 'approved',
                            'bg-red-100 text-red-800': course.status === 'rejected'
                          }">
                          {{ course.status === 'pending' ? 'Pending' : course.status === 'approved' ? 'Approved' : 'Denied' }}
                        </span>
                      </td>
                      <td class="py-3">
                        <span v-if="course.status === 'rejected' && course.reason" class="text-sm text-gray-700">
                          {{ course.reason }}
                        </span>
                        <span v-else class="text-gray-400 text-xs">—</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>
<script setup>
import { ref, computed, onMounted, inject } from 'vue'
import { useStore } from 'vuex'

const store = useStore()
const $trans = inject("$trans")

// === State ===
const isLoading = ref(false)
const rawRows = ref([])
const expandedIndex = ref(null)

// Children's student IDs (to filter requests)
const childrenStudentIds = ref([])

// === Load Data ===
onMounted(async () => {
  await fetchChildrenStudentIds()
  await load()
})

// Fetch parent's children student IDs and UUIDs
async function fetchChildrenStudentIds() {
  try {
    const response = await store.dispatch("student/list", {
      params: { all: true }
    })
    
    // Handle both paginated and non-paginated responses
    let data = []
    if (response?.data) {
      data = Array.isArray(response.data) ? response.data : []
      if (data.length === 0 && response.data?.data) {
        data = Array.isArray(response.data.data) ? response.data.data : []
      }
    }
    
    // Extract student IDs and UUIDs for matching
    childrenStudentIds.value = data.map(student => ({
      id: student.id,
      uuid: student.uuid
    })).filter(s => s.id || s.uuid)
  } catch (error) {
    console.error("Failed to fetch children's student IDs:", error)
    childrenStudentIds.value = []
  }
}

async function load() {
  isLoading.value = true
  try {
    const resp = await store.dispatch('coursePlanning/plannerCourseRequest/listAll', {
      per_page: 1000,
      status: 'pending,approved,rejected',
    })
    const allRows = resp?.data?.data ?? resp?.data ?? []
    
    // Filter to only show requests for parent's children
    rawRows.value = allRows.filter(row => {
      const studentId = row?.student?.id || row?.student_id
      const studentUuid = row?.student?.uuid || row?.student_uuid
      
      // Match by ID or UUID
      return childrenStudentIds.value.some(child => 
        (child.id && child.id === studentId) || 
        (child.uuid && child.uuid === studentUuid)
      )
    })
  } catch (e) {
    console.error('[parent listAll] failed', e)
    rawRows.value = []
  } finally {
    isLoading.value = false
  }
}

/* === Helpers === */
const fullName = (r) => {
  const c = r?.student?.contact
  if (c?.firstName || c?.lastName) return [c?.firstName, c?.lastName].filter(Boolean).join(' ')
  return r?.studentName || '—'
}
const syName = (r) => r?.schoolYear?.name || '—'
const glName = (r) => r?.gradeLevel?.name || '—'
const courseId = (r) => r?.course?.uuid || r?.course?.uuid
const courseNm = (r) => r?.course?.name || r?.course?.name || '—'
const typeLbl = (r) => r?.request_type === 'withdrawal' ? 'Withdrawal' : 'New'
const created = (r) => r?.created_at ? new Date(r.created_at).toLocaleDateString() : ''

/* === Filter Options === */
const schoolYearOptions = computed(() => {
  const m = new Map()
  for (const r of rawRows.value) {
    if (r?.schoolYear?.uuid && !m.has(r.schoolYear.uuid)) {
      m.set(r.schoolYear.uuid, { uuid: r.schoolYear.uuid, name: syName(r) })
    }
  }
  return Array.from(m.values())
})

const schoolYearOptionsWithAll = computed(() => [
  { uuid: '', name: 'All Years' },
  ...schoolYearOptions.value
])

const gradeLevelOptions = computed(() => {
  const m = new Map()
  for (const r of rawRows.value) {
    if (r?.gradeLevel?.uuid && !m.has(r.gradeLevel.uuid)) {
      m.set(r.gradeLevel.uuid, { uuid: r.gradeLevel.uuid, name: glName(r) })
    }
  }
  return Array.from(m.values())
})

const gradeLevelOptionsWithAll = computed(() => [
  { uuid: '', name: 'All Grades' },
  ...gradeLevelOptions.value
])

const courseOptions = computed(() => {
  const m = new Map()
  for (const r of rawRows.value) {
    const uuid = courseId(r)
    if (uuid && !m.has(uuid)) m.set(uuid, { uuid, name: courseNm(r) })
  }
  return Array.from(m.values())
})

const courseOptionsWithAll = computed(() => [
  { uuid: '', name: 'All Courses' },
  ...courseOptions.value
])

/* === GROUPED (Real-time, like Invoices) === */
const groupedRequests = computed(() => {
  const buckets = new Map()
  for (const r of rawRows.value) {
    const sName = fullName(r)
    const sy = syName(r)
    const gl = glName(r)
    const key = `${sName}__${sy}__${gl}`
    if (!buckets.has(key)) {
      buckets.set(key, {
        id: key,
        studentName: sName,
        schoolYear: sy,
        gradeLevel: gl,
        requestedCourses: [],
      })
    }
    buckets.get(key).requestedCourses.push({
      uuid: r?.uuid,
      name: courseNm(r),
      status: r?.status || 'pending',
      reason: r?.reason || '',
      typeLabel: typeLbl(r),
      createdAt: created(r),
    })
  }
  return Array.from(buckets.values())
})

/* === Actions === */
function toggleExpand(i) {
  expandedIndex.value = expandedIndex.value === i ? null : i
}
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
