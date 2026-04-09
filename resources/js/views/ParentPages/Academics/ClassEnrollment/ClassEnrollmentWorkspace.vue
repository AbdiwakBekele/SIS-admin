<template>
  <PageHeader :title="$trans('academics.class_enrollment.class_enrollment')" :navs="[
    { label: $trans('academics.class_enrollment.class_enrollment'), path: 'ParentClassEnrollmentWorkspace' },
  ]">
  </PageHeader>

  <ParentTransition appear :visibility="true">
    <div class="rounded-xl shadow-lg overflow-hidden border border-blue-200/50">
      <!-- Tabs -->
      <div class="border-b-2 border-gray-200">
        <div class="w-full flex gap-1 pl-0">
          <RouterLink v-for="tab in tabs" :key="tab.route" :to="{ name: tab.route }" :class="[
            'flex-1 min-w-max whitespace-nowrap cursor-pointer px-3 pt-2.5 pb-1.5 text-sm text-center transition-all duration-150 flex items-center justify-center gap-2',
            route.name === tab.route
              ? 'bg-blue-200 shadow-inner border border-blue-400 border-b-0 rounded-t-lg text-blue-900 font-medium'
              : 'bg-white border border-gray-300 border-b-0 rounded-t-lg -mb-px z-20 shadow-lg relative -top-1 text-gray-700 hover:text-blue-600 hover:bg-gray-50'
          ]">
            <i :class="[
              tab.icon,
              route.name === tab.route ? 'text-blue-700' : 'text-gray-500',
              'text-sm'
            ]"></i>
            <span>{{ tab.label }}</span>
          </RouterLink>
        </div>
      </div>

      <!-- Tab Content -->
      <div class="p-6 bg-white min-h-[60vh]">
        <RouterView />
      </div>
    </div>
  </ParentTransition>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

// Define tabs relevant for parent-side class enrollment
// Order: Course Catalog, Graduation Audit & Class Enrollment, Course Requests (read-only)
const tabs = computed(() => {
  return [
    { label: 'Course Catalog', route: 'ParentCourseCatalog', icon: 'fa-solid fa-book' },
    { label: 'Graduation Audit & Class Enrollment', route: 'ParentGraduationAudit', icon: 'fa-solid fa-graduation-cap' },
    { label: 'Course Requests', route: 'ParentCourseRequest', icon: 'fa-solid fa-clipboard-list' },
  ]
})
</script>

<style scoped>
.router-link-active {
  font-weight: 600;
}
</style>

