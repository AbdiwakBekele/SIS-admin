<template>
  <StudentFinancialTab :student="student" />
</template>

<script setup>
import StudentFinancialTab from "@views/Pages/Student/Profile/Tabs/FinancialTab.vue"

defineProps({
  student: {
    type: Object,
    default: () => ({}),
  },
})
</script>
