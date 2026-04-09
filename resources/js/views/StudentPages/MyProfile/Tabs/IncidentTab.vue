<template>
    <!-- Incidents -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong>Incidents</strong>
        </p>
        <hr class="mx-4 my-3">

        <!-- Display incidents dynamically -->
        <div v-if="incidents && incidents.length > 0">
            <div v-for="incident in incidents" :key="incident.uuid"
                class="bg-white rounded-lg border-r pr-6 py-5 text-center mb-4">
                <p class="text-sm text-left mx-4">
                    <strong>Date: {{ incident.incidentDate?.formatted || (incident.incidentDate ? new Date(incident.incidentDate).toLocaleDateString('en-US', {
                        year: 'numeric', month: 'long', day: '2-digit'
                    }) : 'N/A') }}</strong>
                    <span v-if="incident.incidentNumber" class="ml-4 text-gray-500">#{{ incident.incidentNumber }}</span>
                </p>
                <hr class="mx-4">
                <div class="text-left px-4 py-2">
                    <h4 class="font-semibold mb-2" v-if="incident.incidentType">
                        {{ incident.incidentType?.incidentName || incident.incidentType?.name }}
                        <span v-if="incident.incidentSubcategory" class="text-gray-500 text-sm">
                            - {{ incident.incidentSubcategory?.incidentSubcategory || incident.incidentSubcategory?.subCategoryName || incident.incidentSubcategory?.name }}
                        </span>
                    </h4>
                    <p class="text-sm mb-2" v-if="incident.description">{{ stripHtml(incident.description) }}</p>
                    <div class="flex flex-wrap gap-4 text-xs text-gray-600 mb-2">
                        <span v-if="incident.nature">
                            <strong>Nature:</strong> {{ incident.nature?.label || incident.nature }}
                        </span>
                        <span v-if="incident.severity">
                            <strong>Severity:</strong> {{ incident.severity?.label || incident.severity }}
                        </span>
                        <span v-if="incident.incidentReportedBy">
                            <strong>Reported by:</strong> {{ incident.incidentReportedBy }}
                        </span>
                        <span v-if="incident.incidentLocation">
                            <strong>Location:</strong> {{ incident.incidentLocation }}
                        </span>
                    </div>
                    <div v-if="incident.solution" class="text-sm mt-2">
                        <strong>Solution:</strong> {{ stripHtml(incident.solution) }}
                    </div>
                    <div v-if="incident.media && incident.media.length > 0" class="mt-3 pt-3 border-t">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold">Attachments</p>
                        </div>
                        <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                            <li v-for="(media, i) in incident.media" :key="'media-' + i" class="flex items-center gap-2">
                                <i class="fas text-gray-500" :class="media.icon || 'fa-file'"></i>
                                <button
                                    type="button"
                                    class="text-blue-600 hover:underline"
                                    @click="openIncidentFile(incident, media)"
                                >
                                    {{ media.name || media.file?.name || 'Document' }}
                                </button>
                                <span class="text-xs text-gray-400" v-if="media.size || media.file?.size">
                                    ({{ media.size || media.file?.size }})
                                </span>
                                <button
                                    type="button"
                                    class="ml-auto text-gray-500 hover:text-blue-600 transition-colors"
                                    @click.stop="downloadIncidentFile(incident, media)"
                                    title="Download file"
                                >
                                    <i class="fa-solid fa-download text-xs"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Show message when no incidents -->
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Incidents</p>
            <p class="text-xs text-gray-500 mt-1">Incidents information is not available at this time.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue"
import { useStore } from "vuex"
import { useToast } from "vue-toastification"

const props = defineProps({
    student: Object
})

const store = useStore()
const toast = useToast()

const incidents = ref([])

function stripHtml(html) {
    if (!html) return ''
    const div = document.createElement('div')
    div.innerHTML = html
    return (div.textContent || div.innerText || '').trim()
}

async function loadIncidents() {
    try {
        if (!props.student || !props.student.uuid) {
            console.warn('Student data not available for loading incidents')
            return
        }
        const response = await store.dispatch('student/studentProfile/getStudentIncidents', {
            studentUuid: props.student.uuid
        })
        incidents.value = Array.isArray(response) ? response : (response?.data || [])
    } catch (error) {
        console.error('Error loading incidents:', error)
        incidents.value = []
    }
}

function openIncidentFile(incident, media) {
    if (!media || !media.uuid || !incident?.uuid) {
        console.error('Missing required data:', { media, incident: incident?.uuid })
        return
    }
    
    const baseUrl = window.location.origin
    const mediaUrl = `${baseUrl}/api/v1/app/discipline/incidents/${incident.uuid}/media/${media.uuid}`
    
    if (media.isPreviewable || media.is_previewable || media.mime === 'application/pdf' || media.mime_type === 'application/pdf') {
        window.open(mediaUrl, '_blank')
    } else {
        window.open(mediaUrl + '?action=download', '_blank')
    }
}

async function downloadIncidentFile(incident, media) {
    if (!media || !media.uuid || !incident?.uuid) {
        console.error('Missing required data:', { media, incident: incident?.uuid })
        toast.error('Cannot download file. Missing required information.')
        return
    }
    
    try {
        const downloadUrl = `/api/v1/app/discipline/incidents/${incident.uuid}/media/${media.uuid}?action=download`
        
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
        }
        
        const authToken = localStorage.getItem('auth_token')
        if (authToken) {
            headers['Authorization'] = `Bearer ${authToken}`
        }
        
        const response = await fetch(downloadUrl, {
            method: 'GET',
            credentials: 'include',
            headers: headers,
        })

        if (!response.ok) {
            throw new Error(`Download failed: ${response.status} ${response.statusText}`)
        }

        const blob = await response.blob()
        const url = window.URL.createObjectURL(blob)
        const linkElement = document.createElement('a')
        linkElement.href = url
        
        const contentDisposition = response.headers.get('Content-Disposition')
        let fileName = 'download'
        if (contentDisposition) {
            const fileNameMatch = contentDisposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/)
            if (fileNameMatch && fileNameMatch[1]) {
                fileName = fileNameMatch[1].replace(/['"]/g, '')
            }
        } else {
            fileName = media.fileName || media.name || media.file?.name || 'download'
        }
        
        linkElement.setAttribute('download', fileName)
        document.body.appendChild(linkElement)
        linkElement.click()
        linkElement.remove()
        window.URL.revokeObjectURL(url)
    } catch (error) {
        console.error('Failed to download file:', error)
        toast.error('Failed to download file. Please try again.')
    }
}

onMounted(() => {
    if (props.student?.uuid) {
        loadIncidents()
    }
})

watch(() => props.student?.uuid, (uuid) => {
    if (uuid) {
        loadIncidents()
    }
}, { immediate: true })
</script>

