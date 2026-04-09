<template>
  <div class="space-y-4">
    <div>
      <div class="flex items-center justify-between mb-2">
        <label for="documentType" class="block text-sm font-medium text-gray-700">Document Type *</label>
        <a
          :href="manageDocumentTypesUrl"
          target="_blank"
          rel="noopener noreferrer"
          class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          title="Manage Document Types (opens in new tab)"
        >
          <i class="fa-solid fa-cog mr-1"></i>
          Manage Document Types
          <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-xs"></i>
        </a>
      </div>
      <select
        id="documentType"
        v-model="localDocument.type"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': showErrors && !localDocument.type }"
        required
      >
        <option value="">{{ documentTypes.length === 0 ? 'No document types available. Click Manage Document Types to create new types.' : 'Select document type...' }}</option>
        <option v-for="type in documentTypes" :key="type.uuid" :value="type.uuid">
          {{ type.name }}
        </option>
      </select>
      
      <!-- Error message for document type -->
      <div v-if="showErrors && !localDocument.type" class="mt-1 text-sm text-red-600">
        <i class="fa-solid fa-exclamation-circle mr-1"></i>
        Please select a document type
      </div>
      
      <!-- Help text when no document types are available -->
      <div v-if="documentTypes.length === 0" class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
        <div class="flex items-start">
          <i class="fa-solid fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
          <div class="text-sm text-yellow-800">
            <p class="font-medium">No document types configured</p>
            <p class="mt-1">Click "Manage Document Types" to create new document types that can be assigned to students.</p>
          </div>
        </div>
      </div>
    </div>
    
    <div>
      <label for="documentTitle" class="block text-sm font-medium text-gray-700">Document Title *</label>
      <input
        id="documentTitle"
        v-model="localDocument.title"
        type="text"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': showErrors && !localDocument.title }"
        placeholder="e.g., Birth Certificate"
        required
      />
      <!-- Error message for title -->
      <div v-if="showErrors && !localDocument.title" class="mt-1 text-sm text-red-600">
        <i class="fa-solid fa-exclamation-circle mr-1"></i>
        Please enter a document title
      </div>
    </div>
    
    <div>
      <label for="documentDescription" class="block text-sm font-medium text-gray-700">Description</label>
      <textarea
        id="documentDescription"
        v-model="localDocument.description"
        rows="3"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
        placeholder="Optional description..."
      ></textarea>
    </div>
    
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Upload Document *</label>
      <MediaUpload
        multiple
        :label="$trans('general.file')"
        module="document"
        :media="localDocument.media"
        :media-token="localDocument.media_token"
        @isUpdated="localDocument.mediaUpdated = true"
        @setHash="(hash) => localDocument.media_hash.push(hash)"
        @setToken="(token) => localDocument.media_token = token"
      />
      <!-- Error message for file upload -->
      <div v-if="showErrors && (!localDocument.media || localDocument.media.length === 0)" class="mt-1 text-sm text-red-600">
        <i class="fa-solid fa-exclamation-circle mr-1"></i>
        Please upload at least one document file
      </div>
    </div>

    <!-- Validation summary -->
    <div v-if="showErrors && !isFormValid" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
      <div class="flex items-start">
        <i class="fa-solid fa-exclamation-triangle text-red-600 mt-0.5 mr-2"></i>
        <div class="text-sm text-red-800">
          <p class="font-medium">Please fix the following errors:</p>
          <ul class="mt-1 list-disc list-inside space-y-1">
            <li v-if="!localDocument.type">Select a document type</li>
            <li v-if="!localDocument.title">Enter a document title</li>
            <li v-if="!localDocument.media || localDocument.media.length === 0">Upload at least one document file</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useRouter } from 'vue-router';
import { v4 as uuidv4 } from 'uuid';
import MediaUpload from '@core/components/Forms/MediaUpload.vue';

const router = useRouter();

const props = defineProps({
  document: {
    type: Object,
    default: () => ({ 
      type: '', 
      title: '', 
      description: '', 
      media: [],
      media_token: uuidv4(),
      media_hash: [],
      mediaUpdated: false
    }),
  },
  documentTypes: {
    type: Array,
    default: () => []
  },
  showErrors: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:document', 'validation-change']);

const localDocument = ref({ ...props.document });

// Computed property to check if form is valid
const isFormValid = computed(() => {
  return localDocument.value.type && 
         localDocument.value.title && 
         localDocument.value.media && 
         localDocument.value.media.length > 0;
});

// Debug: Watch documentTypes prop
watch(() => props.documentTypes, (newTypes) => {
  console.log('AddDocumentForm: documentTypes prop changed:', newTypes);
}, { deep: true });

// Watch form validity and emit to parent
watch(isFormValid, (valid) => {
  emit('validation-change', valid);
}, { immediate: true });

// Compute the absolute URL for the StudentConfigDocumentType route
const manageDocumentTypesUrl = computed(() => {
  return router.resolve({ name: 'StudentConfigDocumentType' }).href
});

watch(localDocument, (newVal) => {
  emit('update:document', { ...newVal });
}, { deep: true });
</script>