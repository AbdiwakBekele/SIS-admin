<template>

  <div class="mb-4">
    <div class="flex items-center justify-between mb-2">
      <label class="block text-sm font-medium text-gray-700">Select Sport & Activity</label>
      <div class="flex items-center space-x-2">
        <button type="button" @click="refreshActivities"
          class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
          title="Refresh Activities" :disabled="isRefreshing">
          <i class="fa-solid fa-sync-alt mr-1" :class="{ 'fa-spin': isRefreshing }"></i>
          Refresh
        </button>
        <a :href="manageActivitiesUrl" target="_blank" rel="noopener noreferrer"
          class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          title="Manage Activity Types (opens in new tab)">
          <i class="fa-solid fa-cog mr-1"></i>
          Manage Activities
          <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-xs"></i>
        </a>
      </div>
    </div>

    <BaseSelect v-model="form.activity" name="activity" :label="`Select Sport & Activity`"
      :options="props.sportAndActivities" label-prop="title" value-prop="uuid" :error="formErrors.activity"
      :placeholder="sportAndActivities.length === 0 ? 'No activities available. Click Manage Activities to create new activities.' : 'Select an activity...'" />

    <!-- Help text when no activities are available -->
    <div v-if="sportAndActivities.length === 0" class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
      <div class="flex items-start">
        <i class="fa-solid fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
        <div class="text-sm text-yellow-800">
          <p class="font-medium">No activities configured</p>
          <p class="mt-1">Click "Manage Activities" to create new activity types that students can join.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-6 text-right">
    <BaseButton design="primary" @click="submit" :loading="isSubmitting" class="mt-4" :disabled="!form.activity">
      Submit Activity
    </BaseButton>
  </div>

</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const props = defineProps({
  studentUUID: {
    type: String,
    required: true
  },
  sportAndActivities: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['submitted', 'refresh-activities'])

const store = useStore()
const router = useRouter()
const isSubmitting = ref(false)
const isRefreshing = ref(false)
const formErrors = ref({})

// Local reactive form state
const form = reactive({
  activity: '',
  student: props.studentUUID,
})

// Compute the absolute URL for the SchoolActivityList route
const manageActivitiesUrl = computed(() => {
  // This assumes the route is /app/school-activity or similar
  // Adjust if your base path is different
  return router.resolve({ name: 'SchoolActivityList' }).href
})

const refreshActivities = async () => {
  isRefreshing.value = true
  try {
    // Emit event to parent to refresh activities
    emit('refresh-activities')
  } catch (error) {
    console.error('Failed to refresh activities:', error)
  } finally {
    isRefreshing.value = false
  }
}

const submit = async () => {
  // Clear previous errors
  formErrors.value = {}

  // Validate form
  if (!form.activity) {
    formErrors.value.activity = 'Please select an activity'
    return
  }

  isSubmitting.value = true
  try {
    await store.dispatch('student/studentProfile/addActivity', {
      form: form
    })
    emit('submitted') // Tell parent to refresh list / close modal
  } catch (error) {
    console.error(error)
    // Handle validation errors from backend
    if (error.response?.data?.errors) {
      formErrors.value = error.response.data.errors
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>
