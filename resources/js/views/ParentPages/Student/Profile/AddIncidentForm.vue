<template>
  <div class="space-y-4">
    <!-- Category Selection with Management -->
    <div class="mb-4">
      <div class="flex items-center justify-between mb-2">
        <label class="block text-sm font-medium text-gray-700">Category</label>
        <div class="flex items-center space-x-2">
          <button type="button" @click="refreshCategories"
            class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
            title="Refresh Categories" :disabled="isRefreshing">
            <i class="fa-solid fa-sync-alt mr-1" :class="{ 'fa-spin': isRefreshing }"></i>
            Refresh
          </button>
          <button type="button" @click="openManageCategories"
            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            title="Manage Incident Categories (opens in new tab)">
            <i class="fa-solid fa-cog mr-1"></i>
            Manage Categories
            <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-xs"></i>
          </button>
        </div>
      </div>

      <BaseSelect v-model="localIncident.category" name="category" :label="`Select Incident Category`"
        :options="categories" label-prop="name" value-prop="uuid" :error="formErrors.category"
        :placeholder="categories.length === 0 ? 'No categories available. Click Manage Categories to create new categories.' : 'Select a category...'" />

      <!-- Help text when no categories are available -->
      <div v-if="categories.length === 0" class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
        <div class="flex items-start">
          <i class="fa-solid fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
          <div class="text-sm text-yellow-800">
            <p class="font-medium">No categories configured</p>
            <p class="mt-1">Click "Manage Categories" to create new incident categories.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Title -->
    <div>
      <label for="incidentTitle" class="block text-sm font-medium text-gray-700">Title</label>
      <input id="incidentTitle" v-model="localIncident.title" type="text"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': formErrors.title }"
        placeholder="Enter incident title" />
      <FormError :error="formErrors.title" />
    </div>

    <!-- Date -->
    <div>
      <label for="incidentDate" class="block text-sm font-medium text-gray-700">Date</label>
      <input id="incidentDate" v-model="localIncident.date" type="date"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': formErrors.date }" />
      <FormError :error="formErrors.date" />
    </div>

    <!-- Nature and Severity -->
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label for="incidentNature" class="block text-sm font-medium text-gray-700">Nature</label>
        <select id="incidentNature" v-model="localIncident.nature"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
          :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': formErrors.nature }">
          <option value="">Select nature</option>
          <option value="negative">Negative</option>
          <option value="positive">Positive</option>
        </select>
        <FormError :error="formErrors.nature" />
      </div>

      <div>
        <label for="incidentSeverity" class="block text-sm font-medium text-gray-700">Severity</label>
        <select id="incidentSeverity" v-model="localIncident.severity"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
          :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': formErrors.severity }">
          <option value="">Select severity</option>
          <option value="minor">Minor</option>
          <option value="major">Major</option>
        </select>
        <FormError :error="formErrors.severity" />
      </div>
    </div>

    <!-- Reported By -->
    <div>
      <label for="incidentReportedBy" class="block text-sm font-medium text-gray-700">Reported By</label>
      <input id="incidentReportedBy" v-model="localIncident.reported_by" type="text"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': formErrors.reported_by }"
        placeholder="Enter reporter name" />
      <FormError :error="formErrors.reported_by" />
    </div>

    <!-- Description -->
    <div>
      <label for="incidentDescription" class="block text-sm font-medium text-gray-700">Description</label>
      <textarea id="incidentDescription" v-model="localIncident.description" rows="4"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': formErrors.description }"
        placeholder="Describe the incident in detail"></textarea>
      <FormError :error="formErrors.description" />
    </div>

    <!-- Action -->
    <div>
      <label for="incidentAction" class="block text-sm font-medium text-gray-700">Action Taken</label>
      <textarea id="incidentAction" v-model="localIncident.action" rows="3"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': formErrors.action }"
        placeholder="Describe the action taken (e.g., warning issued, suspension, etc.)"></textarea>
      <FormError :error="formErrors.action" />
    </div>

    <!-- Remarks -->
    <div>
      <label for="incidentRemarks" class="block text-sm font-medium text-gray-700">Remarks</label>
      <textarea id="incidentRemarks" v-model="localIncident.remarks" rows="3"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': formErrors.remarks }"
        placeholder="Additional remarks or notes"></textarea>
      <FormError :error="formErrors.remarks" />
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-end space-x-3">
      <BaseButton design="secondary" @click="$emit('cancel')" :disabled="isSubmitting">
        Cancel
      </BaseButton>
      <BaseButton design="primary" @click="submit" :loading="isSubmitting"
        :disabled="!localIncident.category || !localIncident.title">
        Save Incident
      </BaseButton>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import BaseSelect from '@core/components/Forms/BaseSelect.vue';
import BaseButton from '@core/components/Forms/BaseButton.vue';
import FormError from '@core/components/Forms/FormError.vue';

const props = defineProps({
  incident: {
    type: Object,
    default: () => ({
      category: '',
      title: '',
      date: '',
      nature: '',
      severity: '',
      reported_by: '',
      description: '',
      action: '',
      remarks: ''
    }),
  },
});

const emit = defineEmits(['update:incident', 'submitted', 'cancel', 'refresh-categories']);

const router = useRouter();
const localIncident = ref({ ...props.incident });
const categories = ref([]);
const formErrors = ref({});
const isSubmitting = ref(false);
const isRefreshing = ref(false);

// Open manage categories page
const openManageCategories = () => {
  const routeName = 'DisciplineConfigIncidentCategoryList';
  
  // Navigate using route name
  try {
    const route = router.resolve({ name: routeName });
    window.open(route.href, '_blank', 'noopener,noreferrer');
  } catch (error) {
    console.error('Error opening manage categories:', error);
  }
};

// Load categories on component mount
onMounted(async () => {
  await loadCategories();
});

// Set default date to today if not provided
if (!localIncident.value.date) {
  localIncident.value.date = new Date().toISOString().split('T')[0];
}

const loadCategories = async () => {
  try {
    const response = await axios.get('/api/v1/app/discipline/incidents/pre-requisite');
    categories.value = response.data.categories || [];
  } catch (error) {
    console.error('Error loading categories:', error);
  }
};

const refreshCategories = async () => {
  isRefreshing.value = true;
  try {
    await loadCategories();
    emit('refresh-categories');
  } catch (error) {
    console.error('Failed to refresh categories:', error);
  } finally {
    isRefreshing.value = false;
  }
};

const submit = async () => {
  // Clear previous errors
  formErrors.value = {};

  // Validate form
  if (!localIncident.value.category) {
    formErrors.value.category = 'Please select a category';
    return;
  }

  if (!localIncident.value.title) {
    formErrors.value.title = 'Please enter a title';
    return;
  }

  if (!localIncident.value.date) {
    formErrors.value.date = 'Please select a date';
    return;
  }

  if (!localIncident.value.nature) {
    formErrors.value.nature = 'Please select nature';
    return;
  }

  if (!localIncident.value.severity) {
    formErrors.value.severity = 'Please select severity';
    return;
  }

  if (!localIncident.value.reported_by) {
    formErrors.value.reported_by = 'Please enter who reported this incident';
    return;
  }

  if (!localIncident.value.description) {
    formErrors.value.description = 'Please provide a description';
    return;
  }

  isSubmitting.value = true;
  try {
    emit('submitted', { ...localIncident.value });
  } catch (error) {
    console.error(error);
    // Handle validation errors from backend
    if (error.response?.data?.errors) {
      formErrors.value = error.response.data.errors;
    }
  } finally {
    isSubmitting.value = false;
  }
};

watch(localIncident, (newVal) => {
  emit('update:incident', { ...newVal });
}, { deep: true });
</script>