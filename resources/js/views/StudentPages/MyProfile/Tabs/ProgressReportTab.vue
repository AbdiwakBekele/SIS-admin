<template>
  <AdminProgressReportTab
    :student="student"
    :reports="reports"
    :school-years="schoolYears"
    :grade-levels="gradeLevels"
    :academic-courses="academicCourses"
    :read-only="true"
  />
</template>

<script setup>
import AdminProgressReportTab from "../../../Pages/Student/Profile/Tabs/ProgressReportTab.vue"

const props = defineProps({
  student: { type: Object, required: true },
  reports: { type: Array, default: () => [] },
  schoolYears: { type: Array, default: () => [] },
  gradeLevels: { type: Array, default: () => [] },
  academicCourses: { type: Array, default: () => [] },
})
</script>

