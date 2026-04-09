<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center">
        <p class="text-sm text-left mx-4">
            <strong> Resources</strong>
        </p>
        <hr class="mx-4 my-3" />


        <!-- List -->
        <div class="grid grid-cols-12">
            <div v-if="displayResources && displayResources.length > 0" v-for="resource in displayResources" :key="resource.uuid"
                class="grid grid-cols-6 col-span-6 text-left bg-gray-100 border border-gray-300 rounded-lg p-3 mx-4 mt-2">
                <span class="col-span-1 mt-2 ml-4">
                    <i :class="getFileIcon(resource)"></i>
                </span>
                <div class="col-span-4 mt-2">
                    <p class="text-sm">{{ resource.title }}</p>
                    <span v-if="resource.type" class="text-xs text-gray-600">
                        {{ resource.type.title || resource.type.name || resource.type.label }}
                    </span>
                    <div class="text-xs text-gray-500" v-if="resource.start_date || resource.end_date">
                        {{ resource.start_date?.formatted || resource.start_date || 'â€”' }} â†’
                        {{ resource.end_date?.formatted || resource.end_date || 'â€”' }}
                    </div>
                </div>
                <div class="col-span-1 mt-2 text-right">
                    <button class="mx-1" title="Download" @click="downloadResource(resource)"
                        :disabled="!hasDownloadableMedia(resource)">
                        <i class="fa-solid fa-download"></i>
                    </button>
                </div>
            </div>

            <div v-else class="col-span-12 text-center py-4 text-gray-500">
                <i class="fa-solid fa-file-circle-exclamation text-2xl mb-2"></i>
                <p class="text-sm">No resources uploaded yet</p>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, inject, onMounted, watch } from "vue"
import { useStore } from "vuex"
import { useToast } from "vue-toastification"

const props = defineProps({
    resources: { type: Array, default: () => [] },
    classUuid: { type: String, required: true },
})

const emit = defineEmits(['refresh-resources'])

const store = useStore()
const toast = useToast()
const $trans = inject('$trans')
const emitter = inject('emitter')

const resourceTypes = ref([])

const displayResources = computed(() => {
    // Use localResources if available, otherwise fall back to props.resources
    return localResources.value.length > 0 ? localResources.value : (props.resources || [])
})

const noop = () => { }


function hasDownloadableMedia(resource) {
    return !!(resource?.media?.length && resource.media[0]?.status) || !!resource?.mediaToken
}

function getFileIcon(resource) {
    if (!resource?.media?.length) return 'fa-solid fa-file'
    const media = resource.media[0]
    const mime = media?.meta?.mime || ''

    if (mime.includes('pdf')) return 'fa-solid fa-file-pdf text-red-600'
    if (mime.includes('word') || mime.includes('document')) return 'fa-solid fa-file-word text-blue-600'
    if (mime.includes('excel') || mime.includes('spreadsheet')) return 'fa-solid fa-file-excel text-green-600'
    if (mime.includes('image')) return 'fa-solid fa-file-image text-blue-600'
    if (mime.includes('powerpoint') || mime.includes('presentation')) return 'fa-solid fa-file-powerpoint text-orange-600'
    return 'fa-solid fa-file'
}


const localResources = ref([])

async function refreshResources() {
    if (!props.classUuid) return
    try {
        const response = await store.dispatch("schoolSetup/academicClass/getClassResources", {
            classUuid: props.classUuid,
        })
        
        // Handle different response structures
        let resourcesData = []
        if (Array.isArray(response)) {
            resourcesData = response
        } else if (response?.data && Array.isArray(response.data)) {
            resourcesData = response.data
        } else if (response?.data?.data && Array.isArray(response.data.data)) {
            resourcesData = response.data.data
        }
        
        localResources.value = resourcesData
        emit('refresh-resources', resourcesData)
    } catch (error) {
        console.error("Error refreshing resources:", error)
        toast.error("Failed to refresh resources")
        localResources.value = []
    }
}

async function downloadResource(resource) {
    if (!props.classUuid) return
    if (!hasDownloadableMedia(resource)) {
        toast.error("No file available for download")
        return
    }
    const mediaUuid = resource.media?.[0]?.uuid
    await store.dispatch("schoolSetup/academicClass/downloadClassResource", {
        classUuid: props.classUuid,
        resourceUuid: resource.uuid,
        mediaUuid,
    })
}


onMounted(async () => {
    if (props.classUuid) {
        await refreshResources()
    }
})

watch(() => props.classUuid, async (newUuid) => {
    if (newUuid) {
        await refreshResources()
    }
})
</script>

<style scoped>
/* Add any component-specific styles if needed */
</style>
