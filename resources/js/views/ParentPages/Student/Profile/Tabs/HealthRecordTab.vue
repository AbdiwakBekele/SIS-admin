<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 mt-2">
        <p class="text-sm text-left mx-4">
            <strong>Medical/Health Record Info</strong>
        </p>
        <hr class="mx-4" />
        <br />

        <!-- Empty state -->
        <div v-if="!hasSavedMedical" class="pr-6 py-5 text-center">
            <i class="fa-solid fa-notes-medical text-2xl mb-2 text-gray-500"></i>
            <p class="text-gray-500">No Medical Recorded yet.</p>
        </div>

        <!-- Rendered sections -->
        <div v-else class="grid grid-cols-2">
            <!-- Blood Type (top) -->
            <div class="col-span-2 m-3 rounded bg-gray-100 border border-gray-300 p-3">
                <p class="text-sm"><strong>Blood Type</strong></p>
                <span class="text-gray-600 text-sm">{{ medicalData.bloodType || '-' }}</span>
            </div>

            <!-- Medical/Health Documents -->
            <div class="col-span-2 m-3 rounded bg-gray-100 border border-gray-300 p-3">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm"><strong>Medical/Health Documents</strong></p>
                    <button
                        v-if="(medicalData.mediaFiles && medicalData.mediaFiles.length > 0) || (medicalData.documents && medicalData.documents.length > 0)"
                        type="button"
                        class="text-gray-500 hover:text-blue-600 transition-colors p-1"
                        @click="downloadAllMedicalFiles"
                        title="Download all medical documents"
                    >
                        <i class="fa-solid fa-download text-base"></i>
                    </button>
                </div>
                <!-- New media files (with download) -->
                <div v-if="medicalData.mediaFiles && medicalData.mediaFiles.length" class="mt-2">
                    <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                        <li v-for="(media, i) in medicalData.mediaFiles" :key="'media-' + i" class="flex items-center gap-2">
                            <i class="fas text-gray-500" :class="media.icon || 'fa-file'"></i>
                            <button
                                type="button"
                                class="text-blue-600 hover:underline"
                                @click="openMedicalFile(media)"
                            >
                                {{ media.name || media.file?.name || 'Document' }}
                            </button>
                            <span class="text-xs text-gray-400" v-if="media.size || media.file?.size">
                                ({{ formatFileSize(media.size || media.file?.size) }})
                            </span>
                            <button
                                type="button"
                                class="ml-auto text-gray-500 hover:text-blue-600 transition-colors"
                                @click.stop="downloadMedicalFile(media)"
                                title="Download file"
                            >
                                <i class="fa-solid fa-download text-xs"></i>
                            </button>
                        </li>
                    </ul>
                </div>
                <!-- Legacy documents (fallback) -->
                <div v-else-if="medicalData.documents?.length" class="mt-2">
                    <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                        <li v-for="(doc, i) in medicalData.documents" :key="'doc-' + i">
                            {{ doc.name }}
                            <span class="text-xs text-gray-400">({{ prettySize(doc.size) }})</span>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-gray-500 text-sm">No documents attached.</div>
            </div>

            <!-- Allergies -->
            <div class="col-span-1 m-3 rounded bg-gray-100 border border-gray-300 p-3">
                <p class="text-sm"><strong>Allergies</strong></p>
                <div v-if="medicalData.allergies?.length || medicalData.allergiesOther">
                    <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                        <li v-for="a in medicalData.allergies" :key="a">{{ a }}</li>
                        <li v-if="medicalData.allergiesOther"><strong>Other:</strong> {{ medicalData.allergiesOther }}
                        </li>
                    </ul>
                </div>
                <div v-else class="text-gray-500 text-sm">None recorded.</div>
            </div>

            <!-- Medical/Mental Health Conditions -->
            <div class="col-span-1 m-3 rounded bg-gray-100 border border-gray-300 p-3">
                <p class="text-sm"><strong>Medical/Mental Health Conditions</strong></p>
                <div v-if="medicalData.conditions?.length && medicalData.conditions.some(c => c.title?.trim())">
                    <ul class="text-gray-600 text-sm space-y-2">
                        <li v-for="(c, i) in medicalData.conditions" :key="i" v-if="c.title?.trim()">
                            <span>{{ c.title }}</span>
                            <span v-if="c.relatedToIEP504"
                                class="ml-2 px-2 py-0.5 text-xs rounded bg-blue-100 text-blue-700">
                                Related to IEP/504 Plan
                            </span>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-gray-500 text-sm">None recorded.</div>
            </div>

            <!-- Medications -->
            <div class="col-span-1 m-3 rounded bg-gray-100 border border-gray-300 p-3">
                <p class="text-sm"><strong>Medications</strong></p>
                <div v-if="medicalData.medications?.length && medicalData.medications.some(m => m.name?.trim())">
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li v-for="(m, i) in medicalData.medications" :key="i" v-if="m.name?.trim()">
                            - {{ m.name }}<span v-if="m.dosage"> ({{ m.dosage }})</span><span v-if="m.notes"> –
                                {{ m.notes }}</span>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-gray-500 text-sm">None recorded.</div>
            </div>

            <!-- Medical/Surgical Records -->
            <div class="col-span-1 m-3 rounded bg-gray-100 border border-gray-300 p-3">
                <p class="text-sm"><strong>Medical/Surgical Records</strong></p>
                <div v-if="medicalData.records?.length && medicalData.records.some(r => (r || '').trim())">
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li v-for="(r, i) in medicalData.records" :key="i" v-if="(r || '').trim()">- {{ r }}</li>
                    </ul>
                </div>
                <div v-else class="text-gray-500 text-sm">None recorded.</div>
            </div>

            <!-- Physicians/Providers -->
            <div class="col-span-1 m-3 rounded bg-gray-100 border border-gray-300 p-3">
                <p class="text-sm"><strong>Physicians/Providers</strong></p>
                <div v-if="medicalData.providers?.length && medicalData.providers.some(p => p.name?.trim())">
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li v-for="(p, i) in medicalData.providers" :key="i" v-if="p.name?.trim()">
                            - {{ p.name }} <span v-if="p.specialty">({{ p.specialty }})</span>
                            <span v-if="p.phone"> – {{ p.phone }}</span>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-gray-500 text-sm">None recorded.</div>
            </div>

            <!-- Insurance -->
            <div class="col-span-1 m-3 rounded bg-gray-100 border border-gray-300 p-3">
                <p class="text-sm"><strong>Insurance</strong></p>
                <div
                    v-if="medicalData.insurance?.provider || medicalData.insurance?.policyNumber || medicalData.insurance?.coverage">
                    <span class="text-gray-600 text-sm block" v-if="medicalData.insurance.provider">- Provider:
                        {{ medicalData.insurance.provider }}</span>
                    <span class="text-gray-600 text-sm block" v-if="medicalData.insurance.policyNumber">- Policy Number:
                        {{ medicalData.insurance.policyNumber }}</span>
                    <span class="text-gray-600 text-sm block" v-if="medicalData.insurance.coverage">- Coverage:
                        {{ medicalData.insurance.coverage }}</span>
                </div>
                <div v-else class="text-gray-500 text-sm">None recorded.</div>
            </div>

            <!-- Vaccinations -->
            <div class="col-span-2 m-3 rounded bg-gray-100 border border-gray-300 p-3">
                <p class="text-sm"><strong>Vaccinations</strong></p>
                <div
                    v-if="medicalData.vaccinations?.length && medicalData.vaccinations.some(v => v.type || v.date || v.dose)">
                    <div class="overflow-x-auto mt-2">
                        <table class="min-w-full text-left">
                            <thead>
                                <tr class="bg-gray-200 text-xs">
                                    <th class="px-3 py-2 w-1/3">Vaccination Type</th>
                                    <th class="px-3 py-2 w-1/3">Date</th>
                                    <th class="px-3 py-2 w-1/3">Dose</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(v, i) in medicalData.vaccinations" :key="'vacc-disp-' + i"
                                    class="border-t text-sm">
                                    <td class="px-3 py-2">{{ labelForVaccination(v.type) || '-' }}</td>
                                    <td class="px-3 py-2">{{ formatLocalDate(v.date) || '-' }}</td>
                                    <td class="px-3 py-2">{{ v.dose || '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-else class="text-gray-500 text-sm">None recorded.</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import { useToast } from 'vue-toastification'

const toast = useToast()

const props = defineProps({
    student: { type: Object, required: true },
})

const store = useStore()

/* ---------- Display State (after Save) ---------- */
const medicalData = reactive({
    bloodType: '',
    documents: [],
    mediaFiles: [],
    allergies: [],
    allergiesOther: '',
    conditions: [{ title: '', relatedToIEP504: false }],
    medications: [{ name: '', dosage: '', notes: '' }],
    records: [''],
    providers: [{ name: '', specialty: '', phone: '' }],
    insurance: { provider: '', policyNumber: '', coverage: '' },
    vaccinations: [{ type: '', date: '', dose: '' }],
})

const hasSavedMedical = computed(() => {
    return !!(
        medicalData.bloodType ||
        (medicalData.documents && medicalData.documents.length) ||
        (medicalData.mediaFiles && medicalData.mediaFiles.length) ||
        (medicalData.allergies && medicalData.allergies.length) ||
        medicalData.allergiesOther ||
        (medicalData.conditions && medicalData.conditions.some(c => c.title?.trim())) ||
        (medicalData.medications && medicalData.medications.some(m => m.name?.trim())) ||
        (medicalData.records && medicalData.records.some(r => (r || '').trim())) ||
        (medicalData.providers && medicalData.providers.some(p => p.name?.trim())) ||
        medicalData.insurance.provider || medicalData.insurance.policyNumber || medicalData.insurance.coverage ||
        (medicalData.vaccinations && medicalData.vaccinations.some(v => v.type || v.date || v.dose))
    )
})

// Provided vaccination list (value/label); used in BaseSelect
const vaccinationOptions = [
    { value: 'Hib', label: 'Hib' },
    { value: 'Pneumocaccal(PCV)', label: 'Pneumocaccal(PCV)' },
    { value: 'DTP, DTap, DT, Td', label: 'DTP, DTap, DT, Td' },
    { value: 'Poliomyelitis', label: 'Poliomyelitis' },
    { value: 'HepatitisB', label: 'Hepatitis B' },
    { value: 'HepatitisA', label: 'Hepatitis A' },
    { value: 'Measles', label: 'Measles' },
    { value: 'Mumps', label: 'Mumps' },
    { value: 'Rubella', label: 'Rubella' },
    { value: 'Varicella', label: 'Varicella' },
    { value: 'Tdap Booster', label: 'Tdap Booster' },
    { value: 'Rotavirus', label: 'Rotavirus' },
    { value: 'Influenza', label: 'Influenza' },
    { value: 'Meningococcal', label: 'Meningococcal' },
    { value: 'HPV', label: 'HPV' },
]

/* ---------- Load on mount / student change ---------- */
async function loadHealth() {
    try {
        if (!props.student || !props.student.uuid) {
            return
        }
        const payload = await store.dispatch('student/studentProfile/getHealthProfile', { uuid: props.student.uuid })
        copyIn(payload || {}, medicalData)
    } catch (error) {
        console.error('Failed to load health profile:', error)
    }
}
onMounted(() => {
    if (props.student?.uuid) {
        loadHealth()
    }
})
watch(() => props.student?.uuid, (uuid) => {
    if (uuid) {
        loadHealth()
    }
}, { immediate: true })

/* ---------- Helpers ---------- */
function copyIn(from, to) {
    to.bloodType = from.bloodType || ''
    to.documents = Array.isArray(from.documents) ? [...from.documents] : []
    to.mediaFiles = Array.isArray(from.mediaFiles) ? [...from.mediaFiles] : []
    to.allergies = Array.isArray(from.allergies) ? [...from.allergies] : []
    to.allergiesOther = from.allergiesOther || ''
    to.conditions = Array.isArray(from.conditions) && from.conditions.length
        ? from.conditions.map(c => ({ title: c.title || '', relatedToIEP504: !!c.relatedToIEP504 }))
        : [{ title: '', relatedToIEP504: false }]
    to.medications = Array.isArray(from.medications) && from.medications.length
        ? from.medications.map(m => ({ name: m.name || '', dosage: m.dosage || '', notes: m.notes || '' }))
        : [{ name: '', dosage: '', notes: '' }]
    to.records = Array.isArray(from.records) && from.records.length ? [...from.records] : ['']
    to.providers = Array.isArray(from.providers) && from.providers.length
        ? from.providers.map(p => ({ name: p.name || '', specialty: p.specialty || '', phone: p.phone || '' }))
        : [{ name: '', specialty: '', phone: '' }]
    to.insurance = {
        provider: from.insurance?.provider || '',
        policyNumber: from.insurance?.policyNumber || '',
        coverage: from.insurance?.coverage || ''
    }
    to.vaccinations = Array.isArray(from.vaccinations) && from.vaccinations.length
        ? from.vaccinations.map(v => ({ type: v.type || '', date: v.date || '', dose: v.dose || '' }))
        : [{ type: '', date: '', dose: '' }]
}

function prettySize(bytes) {
    if (!bytes && bytes !== 0) return ''
    const units = ['B', 'KB', 'MB', 'GB']
    let i = 0, val = bytes
    while (val >= 1024 && i < units.length - 1) { val /= 1024; i++ }
    return `${val.toFixed(val < 10 && i > 0 ? 1 : 0)} ${units[i]}`
}

function formatLocalDate(val) {
    try {
        const d = val ? new Date(val) : null
        if (!d || isNaN(d.getTime())) return ''
        return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: '2-digit' })
    } catch { return '' }
}

function labelForVaccination(value) {
    return vaccinationOptions.find(o => o.value === value)?.label || value
}

function formatFileSize(bytes) {
    if (!bytes && bytes !== 0) return ''
    if (typeof bytes === 'string') return bytes
    if (typeof bytes !== 'number') return String(bytes)
    const units = ['B', 'KB', 'MB', 'GB']
    let i = 0, val = bytes
    while (val >= 1024 && i < units.length - 1) { val /= 1024; i++ }
    return `${val.toFixed(val < 10 && i > 0 ? 1 : 0)} ${units[i]}`
}

/* ---------- Download Functions ---------- */
function openMedicalFile(media) {
    if (!media || !media.uuid || !props.student?.uuid) {
        console.error('Missing required data:', { media, student: props.student?.uuid })
        return
    }
    
    const baseUrl = window.location.origin
    const mediaUrl = `${baseUrl}/api/v1/app/students/${props.student.uuid}/media/${media.uuid}`
    
    if (media.isPreviewable || media.is_previewable || media.mime === 'application/pdf' || media.mime_type === 'application/pdf') {
        window.open(mediaUrl, '_blank')
    } else {
        window.open(mediaUrl + '?action=download', '_blank')
    }
}

async function downloadMedicalFile(media) {
    if (!media || !media.uuid || !props.student?.uuid) {
        console.error('Missing required data:', { media, student: props.student?.uuid })
        return
    }
    
    try {
        const downloadUrl = `/api/v1/app/students/${props.student.uuid}/media/${media.uuid}?action=download`
        
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

async function downloadAllMedicalFiles() {
    await loadHealth()
    
    await new Promise(resolve => setTimeout(resolve, 100))
    
    const mediaFilesArray = Array.isArray(medicalData.mediaFiles) 
        ? [...medicalData.mediaFiles] 
        : []
    const documentsArray = Array.isArray(medicalData.documents) 
        ? [...medicalData.documents] 
        : []
    
    const filesToDownload = mediaFilesArray.filter(f => f && f.uuid)
    
    if (filesToDownload.length === 0) {
        if (documentsArray.length > 0 && mediaFilesArray.length === 0) {
            toast.error('Files are in the legacy format and cannot be downloaded. Please contact the school administrator.')
        } else {
            toast.error('No downloadable files found.')
        }
        return
    }
    
    if (!props.student?.uuid) {
        toast.error('Student information not available.')
        return
    }
    
    toast.info(`Downloading ${filesToDownload.length} file(s)...`)
    
    let successCount = 0
    let failCount = 0
    
    for (let i = 0; i < filesToDownload.length; i++) {
        const media = filesToDownload[i]
        try {
            await downloadMedicalFile(media)
            successCount++
            if (i < filesToDownload.length - 1) {
                await new Promise(resolve => setTimeout(resolve, 500))
            }
        } catch (error) {
            console.error(`Failed to download file ${i + 1}:`, error)
            failCount++
        }
    }
    
    if (failCount === 0) {
        toast.success(`All ${successCount} file(s) downloaded successfully.`)
    } else {
        toast.warning(`Downloaded ${successCount} file(s), ${failCount} failed.`)
    }
}
</script>
