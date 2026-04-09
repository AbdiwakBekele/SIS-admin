<template>
    <div class="mx-auto bg-white rounded-2xl border shadow-sm print:shadow-none print:border-0 m-2">
        <!-- Header with Print and Back buttons -->
        <header class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-2xl print:hidden">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold">Unofficial Transcript Print Preview</h1>
                    <p v-if="selectedStudent" class="text-sm opacity-90 mt-1">
                        {{ fullName(selectedStudent) }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <BaseButton design="secondary" @click="goBack" title="Back to Profile">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Back
                    </BaseButton>
                    <BaseButton design="primary" @click="printTranscript" title="Print Unofficial Transcript">
                        <i class="fa-solid fa-print mr-2"></i> Print Unofficial Transcript
                    </BaseButton>
                </div>
            </div>
        </header>

        <!-- Body -->
        <div class="p-4">
            <!-- Loading State -->
            <div v-if="loading" class="text-center py-10">
                <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
                <p class="text-gray-600 font-medium">Loading transcript...</p>
            </div>

            <!-- Transcript Content -->
            <div v-else-if="selectedStudent && allAcademicRecords.length > 0" class="relative p-8 print:p-4 transcript-content" style="min-height: 11in;">
                <!-- Watermark -->
                <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                    <span class="text-8xl font-black text-gray-400/20 -rotate-45 whitespace-nowrap select-none" style="font-weight: 900;">
                        Unofficial Transcript
                    </span>
                </div>

                <!-- Header with Logo -->
                <div class="mb-2 print-header">
                    <div class="grid grid-cols-12 gap-0 mb-2">
                        <!-- First Column: School Logo -->
                        <div class="col-span-2 flex items-center justify-end pr-1">
                            <img class="h-12 w-auto transcript-logo" :src="getLightLogo" alt="School logo" />
                        </div>
                        
                        <!-- Second Column: School Name, Website, Accreditation Code, Accreditation Text -->
                        <div class="col-span-4 text-left text-xs">
                            <h1 class="text-lg font-bold mb-0.5">{{ school.name }}</h1>
                            <div v-if="school.website" class="mb-0.5 text-[9px]">{{ school.website }}</div>
                            <div v-if="school.accreditationCode" class="mb-0.5 text-[9px]">{{ school.accreditationCode }}</div>
                            <div v-if="school.accreditationInstitute" class="text-[9px] font-semibold">{{ school.accreditationInstitute }}</div>
                        </div>
                        
                        <!-- Third Column: Accreditation Logo -->
                        <div class="col-span-2 flex items-center justify-end pr-1">
                            <img v-if="school.accreditationLogo" class="h-12 w-auto max-w-full object-contain transcript-logo" :src="school.accreditationLogo" alt="Accreditation logo" />
                        </div>
                        
                        <!-- Fourth Column: School Name, Address, Phone, Email with icons -->
                        <div class="col-span-4 text-left text-xs">
                            <div class="mb-0.5">
                                <i class="fas fa-school mr-1 text-gray-600 text-[9px]"></i>
                                <span class="font-semibold text-[9px]">{{ school.name }}</span>
                            </div>
                            <div v-if="school.address" class="mb-0.5 text-[9px]">
                                <i class="fas fa-map-marker-alt mr-1 text-gray-600"></i>
                                <span>{{ school.address }}</span>
                            </div>
                            <div v-if="school.phone" class="mb-0.5 text-[9px]">
                                <i class="fas fa-phone mr-1 text-gray-600"></i>
                                <span>{{ school.phone }}</span>
                            </div>
                            <div v-if="school.email" class="text-[9px]">
                                <i class="fas fa-envelope mr-1 text-gray-600"></i>
                                <span>{{ school.email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-1">
                        <h2 class="text-sm font-bold">UNOFFICIAL TRANSCRIPT</h2>
                    </div>
                </div>

                <!-- Student Information and Academic Summary (Side by Side) -->
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <!-- Student Information -->
                    <div class="border-2 border-blue-600 bg-white p-1.5">
                        <h3 class="font-bold mb-1 text-xs text-center bg-blue-600 text-white py-1">STUDENT INFORMATION</h3>
                        <div class="space-y-1 text-[10px]">
                            <div class="flex">
                                <span class="font-semibold">Student Name: </span>
                                <span class="border-b border-black flex-1 ml-2">{{ fullName(selectedStudent) }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold">Student Id number: </span>
                                <span class="border-b border-black flex-1 ml-2">{{ selectedStudent.studentId || selectedStudent.student_id || '' }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold">Birth Date: </span>
                                <span class="border-b border-black flex-1 ml-2">{{ formatDate(selectedStudent.birthDate || selectedStudent.birth_date) }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold">Address: </span>
                                <span class="border-b border-black flex-1 ml-2">{{ formatAddress(selectedStudent) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Summary -->
                    <div class="border-2 border-blue-600 bg-white p-1.5">
                        <h3 class="font-bold mb-1 text-xs text-center bg-blue-600 text-white py-1">ACADEMIC SUMMARY</h3>
                        <div class="space-y-1 text-[10px]">
                            <!-- First Row: Enrollment Date and Exit Date -->
                            <div class="flex gap-2 whitespace-nowrap">
                                <div class="flex flex-[3] min-w-0">
                                    <span class="font-semibold whitespace-nowrap">Enrollment Date: </span>
                                    <span class="border-b border-black flex-1 ml-2 whitespace-nowrap overflow-hidden text-ellipsis">{{ formatDate(getLatestAcademicHistory(selectedStudent)?.enrollmentDate || getLatestAcademicHistory(selectedStudent)?.enrollment_date) }}</span>
                                </div>
                                <div class="flex flex-[2] min-w-0">
                                    <span class="font-semibold whitespace-nowrap">Exit Date: </span>
                                    <span class="border-b border-black flex-1 ml-2 whitespace-nowrap overflow-hidden text-ellipsis">{{ formatDate(selectedStudent.exitDate || selectedStudent.exit_date) }}</span>
                                </div>
                            </div>
                            <!-- Second Row: WGPA and Credits Earned -->
                            <div class="flex gap-2 whitespace-nowrap">
                                <div class="flex flex-1 min-w-0">
                                    <span class="font-semibold whitespace-nowrap">WGPA: </span>
                                    <span class="border-b border-black flex-1 ml-2 whitespace-nowrap overflow-hidden text-ellipsis">{{ cumulativeGpa }}</span>
                                </div>
                                <div class="flex flex-1 min-w-0">
                                    <span class="font-semibold whitespace-nowrap">Credits Earned: </span>
                                    <span class="border-b border-black flex-1 ml-2 whitespace-nowrap overflow-hidden text-ellipsis">{{ totalCredits }}</span>
                                </div>
                            </div>
                            <!-- Third Row: Promote/Retain -->
                            <div class="flex whitespace-nowrap">
                                <span class="font-semibold whitespace-nowrap">Promote/Retain: </span>
                                <span class="border-b border-black flex-1 ml-2 whitespace-nowrap overflow-hidden text-ellipsis">{{ selectedStudent.promoteRetain || selectedStudent.promote_retain || '' }}</span>
                            </div>
                            <!-- Fourth Row: Diploma Earned -->
                            <div class="flex whitespace-nowrap">
                                <span class="font-semibold whitespace-nowrap">Diploma Earned: </span>
                                <span class="border-b border-black flex-1 ml-2 whitespace-nowrap overflow-hidden text-ellipsis">{{ selectedStudent.diplomaEarned || selectedStudent.diploma_earned || '' }}</span>
                            </div>
                            <!-- Fifth Row: Graduation Date -->
                            <div class="flex whitespace-nowrap">
                                <span class="font-semibold whitespace-nowrap">Graduation Date: </span>
                                <span class="border-b border-black flex-1 ml-2 whitespace-nowrap overflow-hidden text-ellipsis">{{ formatDate(selectedStudent.graduationDate || selectedStudent.graduation_date) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grading Scale and Transcript Codes (Side by Side) -->
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <!-- Grading Scale -->
                    <div class="border-2 border-blue-600 bg-white p-1.5">
                        <h3 class="font-bold mb-1 text-xs text-center bg-blue-600 text-white py-1">GRADING SCALE</h3>
                        <div class="text-[10px]">
                            <p class="mb-1 text-center font-bold text-[9px]">Honors (HON) courses adds 0.5 points. Advanced Placement (AP) or Dual Enrollment (DE) courses adds 1 point.</p>
                            <div class="grid grid-cols-4 gap-2">
                                <div class="flex flex-col justify-center">
                                    <p class="flex justify-between"><span>A+ =</span><span>96 - 100</span></p>
                                    <p class="flex justify-between"><span>A  =</span><span>95 - 93</span></p>
                                    <p class="flex justify-between"><span>A- =</span><span>92 - 90</span></p>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <p class="flex justify-between"><span>B+ =</span><span>86 - 89</span></p>
                                    <p class="flex justify-between"><span>B  =</span><span>85 - 83</span></p>
                                    <p class="flex justify-between"><span>B- =</span><span>82 - 80</span></p>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <p class="flex justify-between"><span>C+ =</span><span>76 - 79</span></p>
                                    <p class="flex justify-between"><span>C  =</span><span>75 - 73</span></p>
                                    <p class="flex justify-between"><span>C- =</span><span>72 - 70</span></p>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <p class="flex justify-between"><span>D+ =</span><span>66 - 69</span></p>
                                    <p class="flex justify-between"><span>D  =</span><span>65 - 63</span></p>
                                    <p class="flex justify-between"><span>F  =</span><span>62 - 0</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transcript Codes -->
                    <div class="border-2 border-blue-600 bg-white p-1.5">
                        <h3 class="font-bold mb-1 text-xs text-center bg-blue-600 text-white py-1">TRANSCRIPT CODES</h3>
                        <div class="text-[10px]">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="text-left">
                                    <p class="whitespace-nowrap">W - Withdrawn</p>
                                    <p class="whitespace-nowrap">WP - Withdrawn while passing</p>
                                    <p class="whitespace-nowrap">WF - Withdrawn while failing</p>
                                    <p class="whitespace-nowrap">MC - Modified curriculum</p>
                                </div>
                                <div class="text-left">
                                    <p class="whitespace-nowrap">CR - Credit recovery</p>
                                    <p class="whitespace-nowrap">R - Repeated course</p>
                                    <p class="whitespace-nowrap">INC - Incomplete</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACADEMIC RECORD -->
                <div class="mb-2">
                    <h3 class="font-bold mb-1 text-sm text-center border-b border-blue-600 pb-1 text-blue-900">ACADEMIC RECORD</h3>
                    
                    <!-- All Academic Records in Grid -->
                    <div v-if="allAcademicRecords.length > 0" :class="getAcademicRecordsGridClass()" class="gap-2 academic-records">
                        <div v-for="(record, recordIdx) in allAcademicRecords" :key="recordIdx" class="border-2 border-blue-600 bg-white p-1.5 academic-record-item">
                            <div class="flex gap-2 mb-1 text-[10px]">
                                <div>
                                    <span class="font-semibold">Grade Level: </span>
                                    <span class="border-b border-black inline-block">{{ record.gradeLabel }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold">Year: </span>
                                    <span class="border-b border-black inline-block">{{ record.yearLabel }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold">School: </span>
                                    <span class="border-b border-black inline-block">{{ school.name }}</span>
                                </div>
                            </div>
                            <table class="w-full text-[10px] border-collapse mb-1">
                                <thead>
                                    <tr class="bg-blue-600 text-white">
                                        <th class="border border-blue-600 p-0 text-center">Course Title</th>
                                        <th class="border border-blue-600 p-0 text-center">Final Grade</th>
                                        <th class="border border-blue-600 p-0 text-center">Credit Earned</th>
                                        <th class="border border-blue-600 p-0 text-center">GPA Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(course, idx) in record.courses" :key="idx">
                                        <td class="border border-blue-600 p-0 text-center">{{ course.title }}</td>
                                        <td class="border border-blue-600 p-0 text-center">{{ course.final }}</td>
                                        <td class="border border-blue-600 p-0 text-center">{{ course.credits.toFixed(2) }}</td>
                                        <td class="border border-blue-600 p-0 text-center">{{ course.gpaPoints }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="flex gap-2 text-[9px] whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="font-semibold">Weighted GPA: </span>
                                    <span class="border-b border-black inline-block ml-1">{{ record.summary.weightedGpa }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold">Unweighted GPA: </span>
                                    <span class="border-b border-black inline-block ml-1">{{ record.summary.unweightedGpa }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold">Total Credits: </span>
                                    <span class="border-b border-black inline-block ml-1">{{ record.summary.credits }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="border-2 border-blue-600 bg-white p-1.5 text-center text-[10px] text-gray-500 py-4">
                        No academic records available for this student.
                    </div>
                </div>

                <!-- Accept and sign, Notes (Side by Side) - NO SEAL for unofficial -->
                <div class="flex gap-0">
                    <!-- Accept and sign -->
                    <div class="border-2 border-blue-600 bg-white p-1.5 flex-1">
                        <h3 class="font-bold mb-1 text-xs text-center bg-blue-600 text-white py-1">I do hereby certify and affirm this unofficial transcript</h3>
                        <div class="space-y-1 text-[10px]">
                            <div class="flex">
                                <span class="font-semibold">Signature: </span>
                                <span class="border-b border-black flex-1 ml-2"></span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold">Name & Title: </span>
                                <span class="border-b border-black flex-1 ml-2"></span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold">Date: </span>
                                <span class="border-b border-black flex-1 ml-2"></span>
                            </div>
                        </div>
                        <div class="mt-2 text-[10px] text-center italic">
                            Transcript is unofficial unless signed by a school official.
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="border-2 border-l-0 border-blue-600 bg-white p-1.5 flex-[0.5]">
                        <h3 class="font-bold mb-1 text-xs text-center bg-blue-600 text-white py-1">Notes:</h3>
                        <div class="border border-blue-600 min-h-[100px] p-1 text-[10px]">
                            {{ selectedStudent.transcriptNotes || selectedStudent.transcript_notes || selectedStudent.specialAwards || selectedStudent.special_awards || '' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Data State -->
            <div v-else class="text-center py-10">
                <p class="text-gray-500">No transcript available for this student.</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ParentTranscriptPrint",
}
</script>

<script setup>
import { ref, computed, onMounted, watch } from "vue"
import { useStore } from "vuex"
import { useRouter, useRoute } from "vue-router"
import { getConfig } from "@core/helpers/action"
import BaseButton from '@core/components/Forms/BaseButton.vue'

const store = useStore()
const router = useRouter()
const route = useRoute()

// State
const loading = ref(true)
const selectedStudent = ref(null)
const transcriptBlocks = ref([])
const reportCardBlocks = ref([])
const gradeLevels = ref([])
const academicCourses = ref([])
const gradingSystems = ref([])
const schoolYears = ref([])

// Logo - computed to ensure reactivity
const getLightLogo = computed(() => {
    const logoRef = getConfig("assets.logoLight")
    return logoRef && typeof logoRef.value === 'string' && logoRef.value.trim() !== ''
        ? logoRef.value
        : null
})

// School info
const school = computed(() => {
    const generalConfig = getConfig("general").value || {}
    const appName = getConfig("general.appName").value || generalConfig.app_name || ""
    const appEmail = getConfig("general.appEmail").value || generalConfig.app_email || ""
    const appPhone = getConfig("general.appPhone").value || generalConfig.app_phone || ""
    const appWebsite = getConfig("general.appWebsite").value || generalConfig.app_website || ""
    const appAddressLine1 = getConfig("general.appAddressLine1").value || generalConfig.app_address_line1 || ""
    const appAddressLine2 = getConfig("general.appAddressLine2").value || generalConfig.app_address_line2 || ""
    const appCity = getConfig("general.appCity").value || generalConfig.app_city || ""
    const appState = getConfig("general.appState").value || generalConfig.app_state || ""
    const appZipcode = getConfig("general.appZipcode").value || generalConfig.app_zipcode || ""
    const appCountry = getConfig("general.appCountry").value || generalConfig.app_country || ""
    const accreditationCode = getConfig("general.accreditationCode").value || generalConfig.accreditation_code || ""
    const accreditationInstitute = getConfig("general.accreditationInstitute").value || generalConfig.accreditation_institute || ""
    const accreditationLogo = getConfig("assets.accreditationLogo").value || getConfig("general.accreditationLogo").value || generalConfig.accreditation_logo || ""
    
    const addressParts = []
    if (appAddressLine1) addressParts.push(appAddressLine1)
    if (appAddressLine2) addressParts.push(appAddressLine2)
    if (appCity) addressParts.push(appCity)
    if (appState) addressParts.push(appState)
    if (appZipcode) addressParts.push(appZipcode)
    if (appCountry) addressParts.push(appCountry)
    
    const address = addressParts.length > 0 ? addressParts.join(', ') : ""
    const config = getConfig("school").value || {}
    
    return {
        name: appName || config?.name || "LIGHTHOUSEDUCATION",
        address: address || config?.address || "",
        phone: appPhone || config?.phone || config?.phoneNumber || "",
        email: appEmail || config?.email || config?.emailAddress || "",
        website: appWebsite || config?.website || "",
        accreditationCode: accreditationCode || "",
        accreditationInstitute: accreditationInstitute || "",
        accreditationLogo: accreditationLogo || "",
    }
})

const fullName = (s) =>
    [s.firstName, s.middleName, s.lastName].filter(Boolean).join(" ").replace(/\s+/g, " ").trim()

// Helper to get latest academic history
const getLatestAcademicHistory = (student) => {
    const histories = student?.academicHistories ?? student?.academic_histories ?? []
    if (!histories.length) return null
    const sorted = [...histories].sort((a, b) => {
        const dateA = b.enrollmentDate?.value || b.enrollment_date?.value || 0
        const dateB = a.enrollmentDate?.value || a.enrollment_date?.value || 0
        return new Date(dateA) - new Date(dateB)
    })
    return sorted[0]
}

// Format date helper
function formatDate(dateValue) {
    if (!dateValue) return ''
    const date = dateValue?.value || dateValue
    if (!date) return ''
    try {
        const d = new Date(date)
        return d.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" })
    } catch {
        return ''
    }
}

// Format address helper
function formatAddress(student) {
    const address = student.currentAddress || student.current_address || student.contact?.address || ''
    if (typeof address === 'string') return address
    if (typeof address === 'object') {
        const parts = [
            address.addressLine1 || address.address_line1,
            address.addressLine2 || address.address_line2,
            address.city,
            address.state,
            address.zipcode || address.zipCode,
            address.country
        ].filter(Boolean)
        return parts.join(', ')
    }
    return ''
}

// Extract grade number from label
function extractGradeNumber(value) {
    if (value === null || value === undefined || value === '') {
        return null
    }
    const match = String(value).match(/\d+/)
    return match ? Number(match[0]) : null
}

// Helper functions
function toNumberOrNull(value) {
    if (value === null || value === undefined || value === '') {
        return null
    }
    const n = Number(value)
    return Number.isFinite(n) ? n : null
}

function formatGrade(letter, numeric) {
    if (letter && String(letter).trim() !== '') {
        return letter
    }
    if (numeric !== null && numeric !== undefined && numeric !== '') {
        return Number(numeric).toFixed(2).replace(/\.00$/, '')
    }
    return '-'
}

function getLetterFromNumeric(numeric, gradingSystem = null) {
    if (numeric === null || numeric === undefined) return null
    const num = Number(numeric)
    if (!Number.isFinite(num)) return null
    
    if (gradingSystem) {
        const gradeScales = gradingSystem.gradeScales || gradingSystem.grade_scales || []
        if (gradeScales.length > 0) {
            const sortedScales = [...gradeScales].sort((a, b) => {
                const minA = Number(a.min_score || a.minScore || 0)
                const minB = Number(b.min_score || b.minScore || 0)
                return minA - minB
            })
            
            for (let i = 0; i < sortedScales.length; i++) {
                const scale = sortedScales[i]
                const minScore = Number(scale.min_score || scale.minScore || 0)
                const maxScore = Number(scale.max_score || scale.maxScore || 100)
                
                if (i < sortedScales.length - 1) {
                    const nextScale = sortedScales[i + 1]
                    const nextMinScore = Number(nextScale.min_score || nextScale.minScore || 0)
                    if (num >= minScore && num < nextMinScore) {
                        return scale.name || scale.letter || null
                    }
                } else {
                    if (num >= minScore && num <= maxScore) {
                        return scale.name || scale.letter || null
                    }
                }
            }
        }
    }
    
    // Fallback to hardcoded values
    if (num >= 97) return 'A+'
    if (num >= 93) return 'A'
    if (num >= 90) return 'A-'
    if (num >= 86) return 'B+'
    if (num >= 83) return 'B'
    if (num >= 80) return 'B-'
    if (num >= 76) return 'C+'
    if (num >= 73) return 'C'
    if (num >= 70) return 'C-'
    if (num >= 66) return 'D+'
    if (num >= 63) return 'D'
    if (num >= 60) return 'D-'
    return 'F'
}

// Get base points from grade
function getBasePointsFromGrade(grade) {
    if (!grade) return null
    const gradeStr = String(grade).trim().toUpperCase()
    const gradeMap = {
        'A+': 4.0, 'A': 4.0, 'A-': 3.7,
        'B+': 3.3, 'B': 3.0, 'B-': 2.7,
        'C+': 2.3, 'C': 2.0, 'C-': 1.7,
        'D+': 1.3, 'D': 1.0, 'D-': 0.7,
        'F': 0.0
    }
    return gradeMap[gradeStr] ?? null
}

function getGradeLevelSortValue(gradeLevelUuid) {
    if (!gradeLevelUuid) return 0
    
    const gradeLevel = gradeLevels.value.find(gl => gl.uuid === gradeLevelUuid)
    if (!gradeLevel) return 0
    
    const code = gradeLevel.code || ''
    const codeMatch = code.match(/\d+/)
    if (codeMatch) {
        return parseInt(codeMatch[0], 10)
    }
    
    const name = gradeLevel.name || ''
    const nameMatch = name.match(/\d+/)
    if (nameMatch) {
        return parseInt(nameMatch[0], 10)
    }
    
    return 0
}

/** Transform API data to UI format (high school grades only) - from CourseEnrollment and PreviousSchool */
const items = computed(() => {
    if (!Array.isArray(transcriptBlocks.value)) {
        return []
    }

    if (transcriptBlocks.value.length === 0) {
        return []
    }

    // Process reports from CourseEnrollment (same as ReportCardTab)
    const grouped = {}
    
    transcriptBlocks.value.forEach((report) => {
        const schoolYear = report.schoolYear || report.school_year || {}
        const gradeLevel = report.gradeLevel || report.grade_level || {}
        
        // Filter to only high school grades (9th-12th)
        const gradeLevelUuid = gradeLevel.uuid
        if (!gradeLevelUuid) {
            return
        }
        
        const gradeLevelModel = gradeLevels.value.find(gl => gl.uuid === gradeLevelUuid)
        if (!gradeLevelModel) {
            // Don't skip if gradeLevels haven't loaded yet - process anyway
            if (gradeLevels.value.length === 0) {
                // Process anyway - will be filtered later when gradeLevels load
            } else {
                return
            }
        }
        
        let gradeNumber = null
        if (gradeLevelModel) {
            gradeNumber = extractGradeNumber(gradeLevelModel.name) || 
                         extractGradeNumber(gradeLevelModel.shortName) || 
                         extractGradeNumber(gradeLevelModel.code) ||
                         extractGradeNumber(gradeLevelModel.displayName) ||
                         toNumberOrNull(gradeLevelModel.sequence) ||
                         toNumberOrNull(gradeLevelModel.level) ||
                         toNumberOrNull(gradeLevelModel.grade) ||
                         toNumberOrNull(gradeLevelModel.numeric)
        }
        
        // Skip if not high school grade (9-12) - but only if we have grade level data
        if (gradeLevelModel && gradeNumber !== null && (gradeNumber < 9 || gradeNumber > 12)) {
            return
        }
        
        // Create unique key for grouping
        const groupKey = `${schoolYear.uuid || 'unknown'}_${gradeLevel.uuid || 'unknown'}`
        
        if (!grouped[groupKey]) {
            const startDate = schoolYear.startDate ?? schoolYear.start_date
            const sortKey = startDate ? Date.parse(startDate) : 0
            
            grouped[groupKey] = {
                key: groupKey,
                sortKey,
                yearLabel: schoolYear.name || 'School Year',
                gradeLabel: gradeLevel.name || 'Grade Level',
                courses: [],
                meta: {
                    schoolYearUuid: schoolYear.uuid || null,
                    gradeLevelUuid: gradeLevel.uuid || null,
                },
                summary: report.summary || null,
            }
        }
        
        // Get report terms for looking up term titles
        const reportTerms = Array.isArray(report.terms) ? report.terms : []
        const reportTermsMap = {}
        reportTerms.forEach((rt) => {
            const termNo = rt.termNo ?? rt.term_no
            if (termNo !== null) {
                reportTermsMap[termNo] = {
                    title: rt.title || rt.shortTitle || rt.short_title || null,
                }
            }
        })
        
        // Process courses from this report
        const courses = (report.courses || []).map((course) => {
            const final = course.finalGrade || course.final_grade || {}
            const finalGradeString = formatGrade(final.letter, final.numeric)
            let numericGrade = toNumberOrNull(final.numeric ?? course.gradeNumeric ?? course.grade_numeric)
            let gradeLetter = final.letter || null
            
            // Collect term grades for display (as badges) - transcript API doesn't provide term data
            const courseTerms = course.terms || []
            const termGradesForDisplay = []
            const termNumericGrades = []
            
            // Only process term grades if they exist (report card has them, transcript doesn't)
            if (courseTerms.length > 0) {
                courseTerms.forEach((term) => {
                    const termNo = term.termNo ?? term.term_no
                    const termLetter = term.gradeLetter ?? term.grade_letter ?? null
                    const termNumeric = term.gradeNumeric ?? term.grade_numeric ?? null
                    
                    if (termNo !== null && (termLetter || termNumeric !== null)) {
                        // Look up term title from report terms, then from course term, then fallback
                        const termTitle = term.termTitle ?? 
                                         term.term_title ?? 
                                         term.title ?? 
                                         reportTermsMap[termNo]?.title ?? 
                                         `Term ${termNo}`
                        
                        const termGradeString = formatGrade(termLetter, termNumeric)
                        termGradesForDisplay.push({
                            termNo: termNo,
                            termTitle: termTitle,
                            grade: termGradeString,
                            letter: termLetter,
                            numeric: termNumeric,
                        })
                        if (termNumeric !== null) {
                            termNumericGrades.push(termNumeric)
                        }
                    }
                })
                
                // Sort term grades by term number
                termGradesForDisplay.sort((a, b) => (a.termNo || 0) - (b.termNo || 0))
            }
            
            // Get course_weighting and grading system from academicCourses prop
            const courseCode = course.courseCode || course.course_code || ''
            const courseUuid = course.academicCourseUuid || course.academic_course_uuid || null
            let courseWeighting = null
            let gradingSystem = null
            
            if (courseUuid) {
                const academicCourse = academicCourses.value.find(ac => ac.uuid === courseUuid)
                courseWeighting = academicCourse?.courseWeighting ?? academicCourse?.course_weighting ?? null
                const gradingSystemUuid = academicCourse?.gradingSystemUuid ?? 
                                         academicCourse?.grading_system_uuid ?? 
                                         academicCourse?.gradingSystem?.uuid ??
                                         academicCourse?.grading_system?.uuid ??
                                         null
                if (gradingSystemUuid) {
                    gradingSystem = gradingSystems.value.find(gs => gs.uuid === gradingSystemUuid)
                }
            } else if (courseCode) {
                const academicCourse = academicCourses.value.find(ac => 
                    (ac.code === courseCode) || (ac.name === (course.courseName || course.course_name))
                )
                courseWeighting = academicCourse?.courseWeighting ?? academicCourse?.course_weighting ?? null
                const gradingSystemUuid = academicCourse?.gradingSystemUuid ?? 
                                         academicCourse?.grading_system_uuid ?? 
                                         academicCourse?.gradingSystem?.uuid ??
                                         academicCourse?.grading_system?.uuid ??
                                         null
                if (gradingSystemUuid) {
                    gradingSystem = gradingSystems.value.find(gs => gs.uuid === gradingSystemUuid)
                }
            }
            
            // If final grade is not available, calculate from term grades for GPA calculation only
            if ((!finalGradeString || finalGradeString === '-' || (!final.letter && final.numeric === null)) && termNumericGrades.length > 0) {
                const isQuarterly = termGradesForDisplay.length === 4
                
                if (isQuarterly) {
                    const q1Grade = termGradesForDisplay.find(t => t.termNo === 1)?.numeric
                    const q2Grade = termGradesForDisplay.find(t => t.termNo === 2)?.numeric
                    const q3Grade = termGradesForDisplay.find(t => t.termNo === 3)?.numeric
                    const q4Grade = termGradesForDisplay.find(t => t.termNo === 4)?.numeric
                    
                    const semester1Grades = [q1Grade, q2Grade].filter(g => g !== null && g !== undefined)
                    const semester2Grades = [q3Grade, q4Grade].filter(g => g !== null && g !== undefined)
                    
                    const semester1Avg = semester1Grades.length > 0 
                        ? semester1Grades.reduce((sum, g) => sum + g, 0) / semester1Grades.length 
                        : null
                    const semester2Avg = semester2Grades.length > 0 
                        ? semester2Grades.reduce((sum, g) => sum + g, 0) / semester2Grades.length 
                        : null
                    
                    const semesterAverages = [semester1Avg, semester2Avg].filter(g => g !== null && g !== undefined)
                    numericGrade = semesterAverages.length > 0 
                        ? semesterAverages.reduce((sum, g) => sum + g, 0) / semesterAverages.length 
                        : null
                } else {
                    numericGrade = termNumericGrades.reduce((sum, g) => sum + g, 0) / termNumericGrades.length
                }
                
                if (numericGrade !== null) {
                    gradeLetter = getLetterFromNumeric(numericGrade, gradingSystem)
                }
            }
            
            // Get credits earned
            let creditsEarned = toNumberOrNull(
                final.creditsEarned ?? 
                final.credits_earned ?? 
                course.creditsEarned ?? 
                course.credits_earned ??
                course.credits ??
                0
            )
            
            // For transcript API, credits and points come directly from the API
            // The API already calculates points, so use them directly
            const apiPoints = course.points ?? course.weightedGradePoints ?? course.weighted_grade_points ?? null
            
            // Calculate weighted points if not provided by API
            let weightedPoints = apiPoints
            const gradeForGpa = gradeLetter || finalGradeString
            
            // If API didn't provide points, calculate them
            if (weightedPoints === null && creditsEarned > 0 && finalGradeString && finalGradeString !== '-' && final.letter) {
                const baseGradePoints = getBasePointsFromGrade(gradeForGpa)
                const gradeStr = String(gradeForGpa).trim().toUpperCase()
                const countsTowardGpa = baseGradePoints !== null && gradeStr !== 'F'
                
                if (countsTowardGpa) {
                    let additionalWeight = 0
                    if (numericGrade !== null && numericGrade >= 70 && courseWeighting) {
                        if (courseWeighting === 'Honors') {
                            additionalWeight = 0.5
                        } else if (courseWeighting === 'DE' || courseWeighting === 'AP') {
                            additionalWeight = 1.0
                        }
                    }
                    
                    const weightedGradeValue = baseGradePoints + additionalWeight
                    weightedPoints = weightedGradeValue * creditsEarned
                }
            }

            return {
                title: course.courseName || course.course_name || 'Course',
                courseCode: courseCode,
                final: finalGradeString || '-',
                numericGrade: numericGrade,
                gradeLetter: gradeLetter,
                courseWeighting: courseWeighting,
                credits: creditsEarned || 0,
                points: weightedPoints !== null ? weightedPoints : '-',
                termGrades: termGradesForDisplay,
            }
        })
        
        // Merge courses into the group
        grouped[groupKey].courses.push(...courses)
    })
    
    // Process PreviousSchool records (filtered to high school grades)
    const previousSchoolHistories = selectedStudent.value?.previousSchoolHistories || 
                                    selectedStudent.value?.previous_school_histories || 
                                    []
    
    previousSchoolHistories.forEach((history) => {
        const gradeLevelUuid = history.gradeLevel?.uuid || 
                               history.grade_level?.uuid || 
                               history.grade_level_uuid
        
        if (!gradeLevelUuid) return
        
        const gradeLevelModel = gradeLevels.value.find(gl => gl.uuid === gradeLevelUuid)
        if (!gradeLevelModel) return
        
        const gradeNumber = extractGradeNumber(gradeLevelModel.name) || 
                           extractGradeNumber(gradeLevelModel.shortName) || 
                           extractGradeNumber(gradeLevelModel.code) ||
                           extractGradeNumber(gradeLevelModel.displayName) ||
                           toNumberOrNull(gradeLevelModel.sequence) ||
                           toNumberOrNull(gradeLevelModel.level) ||
                           toNumberOrNull(gradeLevelModel.grade) ||
                           toNumberOrNull(gradeLevelModel.numeric)
        
        // Skip if not high school grade (9-12)
        if (gradeNumber === null || gradeNumber < 9 || gradeNumber > 12) {
            return
        }
        
        const groupKey = `previous_${gradeLevelUuid}`
        
        if (!grouped[groupKey]) {
            grouped[groupKey] = {
                key: groupKey,
                sortKey: 0,
                yearLabel: history.previousSchoolName || history.previous_school_name || 'Previous School',
                gradeLabel: gradeLevelModel.name || 'Grade Level',
                courses: [],
                meta: {
                    schoolYearUuid: null,
                    gradeLevelUuid: gradeLevelUuid,
                    isPreviousSchool: true,
                },
            }
        }
        
        const previousCourses = history.previousSchoolCourses || 
                               history.previous_school_courses || 
                               []
        
        const courses = previousCourses.map((course) => {
            const finalGrade = course.finalGrade || course.final_grade || ''
            const creditsEarned = toNumberOrNull(
                course.auditCreditEquivalency ?? 
                course.audit_credit_equivalency ?? 
                course.previousSchoolCredit ?? 
                course.previous_school_credit ??
                0
            )

            return {
                title: course.previousCourseTitle || course.previous_course_title || 'Course',
                courseCode: '',
                final: finalGrade || '-',
                credits: creditsEarned || 0,
                points: 0,
            }
        })
        
        grouped[groupKey].courses.push(...courses)
    })
    
    // Convert to array and calculate summary for each group
    const result = Object.values(grouped)
        .map(group => {
            let summaryData = group.summary || {}
            
            let totalCreditsEarned = 0
            let totalWeightedPoints = 0
            let totalUnweightedPoints = 0
            let gpaCredits = 0
            
            group.courses.forEach(course => {
                const credits = course.credits || 0
                
                totalCreditsEarned += credits
                
                const hasFinalGrade = course.final && course.final !== '-'
                const hasTermGrades = course.termGrades && course.termGrades.length > 0
                const hasValidGrade = hasFinalGrade || (hasTermGrades && course.numericGrade !== null)
                
                if (credits > 0 && hasValidGrade) {
                    let gradeForGpa = course.final
                    if (!hasFinalGrade && hasTermGrades && course.numericGrade !== null) {
                        gradeForGpa = course.gradeLetter || getLetterFromNumeric(course.numericGrade) || String(course.numericGrade)
                    }
                    
                    const basePoints = getBasePointsFromGrade(gradeForGpa)
                    const gradeStr = String(gradeForGpa).trim().toUpperCase()
                    const countsTowardGpa = basePoints !== null && gradeStr !== 'F'
                    
                    if (countsTowardGpa) {
                        let calculatedPoints = 0
                        if (hasFinalGrade && course.points !== '-' && course.points !== null) {
                            calculatedPoints = Number(course.points) || 0
                        } else if (hasTermGrades && course.numericGrade !== null) {
                            const termNumericGrades = course.termGrades
                                .map(tg => tg.numeric)
                                .filter(n => n !== null && n !== undefined)
                            
                            if (termNumericGrades.length > 0) {
                                const isQuarterly = course.termGrades.length === 4
                                
                                if (isQuarterly) {
                                    const q1Grade = course.termGrades.find(t => t.termNo === 1)?.numeric
                                    const q2Grade = course.termGrades.find(t => t.termNo === 2)?.numeric
                                    const q3Grade = course.termGrades.find(t => t.termNo === 3)?.numeric
                                    const q4Grade = course.termGrades.find(t => t.termNo === 4)?.numeric
                                    
                                    const semester1Grades = [q1Grade, q2Grade].filter(g => g !== null && g !== undefined)
                                    const semester2Grades = [q3Grade, q4Grade].filter(g => g !== null && g !== undefined)
                                    
                                    const semester1Avg = semester1Grades.length > 0 
                                        ? semester1Grades.reduce((sum, g) => sum + g, 0) / semester1Grades.length 
                                        : null
                                    const semester2Avg = semester2Grades.length > 0 
                                        ? semester2Grades.reduce((sum, g) => sum + g, 0) / semester2Grades.length 
                                        : null
                                    
                                    const semesterCredits = credits / 2
                                    
                                    [semester1Avg, semester2Avg].forEach((semAvg) => {
                                        if (semAvg !== null && semAvg !== undefined) {
                                            const semLetter = getLetterFromNumeric(semAvg, null)
                                            if (semLetter) {
                                                const semBasePoints = getBasePointsFromGrade(semLetter)
                                                const semGradeStr = String(semLetter).trim().toUpperCase()
                                                if (semBasePoints !== null && semGradeStr !== 'F') {
                                                    let additionalWeight = 0
                                                    if (semAvg >= 70 && course.courseWeighting) {
                                                        if (course.courseWeighting === 'Honors') {
                                                            additionalWeight = 0.5
                                                        } else if (course.courseWeighting === 'DE' || course.courseWeighting === 'AP') {
                                                            additionalWeight = 1.0
                                                        }
                                                    }
                                                    const weightedGradeValue = semBasePoints + additionalWeight
                                                    calculatedPoints += weightedGradeValue * semesterCredits
                                                }
                                            }
                                        }
                                    })
                                } else {
                                    const termCredits = credits / course.termGrades.length
                                    
                                    course.termGrades.forEach((termGrade) => {
                                        if (termGrade.numeric !== null && termGrade.numeric !== undefined) {
                                            const termLetter = termGrade.letter || getLetterFromNumeric(termGrade.numeric, null)
                                            if (termLetter) {
                                                const termBasePoints = getBasePointsFromGrade(termLetter)
                                                const termGradeStr = String(termLetter).trim().toUpperCase()
                                                if (termBasePoints !== null && termGradeStr !== 'F') {
                                                    let additionalWeight = 0
                                                    if (termGrade.numeric >= 70 && course.courseWeighting) {
                                                        if (course.courseWeighting === 'Honors') {
                                                            additionalWeight = 0.5
                                                        } else if (course.courseWeighting === 'DE' || course.courseWeighting === 'AP') {
                                                            additionalWeight = 1.0
                                                        }
                                                    }
                                                    const weightedGradeValue = termBasePoints + additionalWeight
                                                    calculatedPoints += weightedGradeValue * termCredits
                                                }
                                            }
                                        }
                                    })
                                }
                            }
                        }
                        
                        totalWeightedPoints += calculatedPoints
                        totalUnweightedPoints += basePoints * credits
                        gpaCredits += credits
                    }
                }
            })
            
            const creditsEarned = totalCreditsEarned > 0 ? totalCreditsEarned : (summaryData.creditsEarned ?? summaryData.credits_earned ?? 0)
            const weightedGpa = gpaCredits > 0 ? (totalWeightedPoints / gpaCredits) : 0
            const unweightedGpa = gpaCredits > 0 ? (totalUnweightedPoints / gpaCredits) : 0
            const weightedGradePoints = totalWeightedPoints

            return {
                ...group,
                summaryDisplay: {
                    creditsEarned: creditsEarned > 0 ? Number(creditsEarned).toFixed(2) : '0.00',
                    weightedGpa: Number(weightedGpa).toFixed(2),
                    unweightedGpa: Number(unweightedGpa).toFixed(2),
                    weightedGradePoints: weightedGradePoints > 0 ? Number(weightedGradePoints).toFixed(3) : '0.000',
                },
                gradeLevelSortValue: getGradeLevelSortValue(group.meta?.gradeLevelUuid),
            }
        })
        .sort((a, b) => {
            const gradeDiff = (b.gradeLevelSortValue ?? 0) - (a.gradeLevelSortValue ?? 0)
            if (gradeDiff !== 0) {
                return gradeDiff
            }
            return (b.sortKey ?? 0) - (a.sortKey ?? 0)
        })
    
    return result
})

// Transform items into all records for display
const allAcademicRecords = computed(() => {
    if (!Array.isArray(items.value) || items.value.length === 0) {
        return []
    }
    
    const records = []
    items.value.forEach((item) => {
        const gradeLabel = item.gradeLabel || ''
        const gradeNumber = extractGradeNumber(gradeLabel)
        
        // Only include high school grades (9-12)
        if (gradeNumber === null || gradeNumber < 9 || gradeNumber > 12) return
        
        const courses = (item.courses || []).map(course => {
            const finalGrade = course.final || '—'
            const credits = Number(course.credits || 0)
            const gpaPoints = finalGrade !== '—' ? getBasePointsFromGrade(finalGrade) : null
            const courseWeighting = course.courseWeighting || course.course_weighting || ''
            
            return {
                title: course.title || 'Course',
                final: finalGrade,
                credits: credits,
                gpaPoints: gpaPoints !== null ? gpaPoints.toFixed(2) : '—',
                courseWeighting: courseWeighting
            }
        })
        
        // Calculate summary for this record
        let totalCredits = 0
        let totalUnweightedPoints = 0
        let totalWeightedPoints = 0
        let gpaCredits = 0
        
        courses.forEach(course => {
            const credits = Number(course.credits || 0)
            const finalGrade = course.final || ''
            const basePoints = getBasePointsFromGrade(finalGrade)
            
            totalCredits += credits
            
            if (basePoints !== null && finalGrade.toUpperCase() !== 'F' && credits > 0) {
                totalUnweightedPoints += basePoints * credits
                
                const courseWeighting = course.courseWeighting || course.course_weighting || ''
                let additionalWeight = 0
                if (courseWeighting === 'Honors' || courseWeighting === 'HON') {
                    additionalWeight = 0.5
                } else if (courseWeighting === 'AP' || courseWeighting === 'DE') {
                    additionalWeight = 1.0
                }
                
                const weightedPoints = basePoints + additionalWeight
                totalWeightedPoints += weightedPoints * credits
                gpaCredits += credits
            }
        })
        
        const unweightedGpa = gpaCredits > 0 ? (totalUnweightedPoints / gpaCredits) : 0
        const weightedGpa = gpaCredits > 0 ? (totalWeightedPoints / gpaCredits) : 0
        
        records.push({
            block: item,
            gradeNumber,
            gradeLabel,
            yearLabel: item.yearLabel || '',
            courses,
            summary: {
                credits: totalCredits.toFixed(2),
                unweightedGpa: unweightedGpa.toFixed(2),
                weightedGpa: weightedGpa.toFixed(2)
            }
        })
    })
    
    return records
})

// Get grid class for academic records
function getAcademicRecordsGridClass() {
    const count = allAcademicRecords.value.length
    if (count === 4) {
        return 'grid grid-cols-2'
    } else if (count === 2) {
        return 'grid grid-cols-2'
    } else {
        return 'flex flex-col'
    }
}

// Calculate total credits and cumulative GPA
const totalCredits = computed(() => {
    return allAcademicRecords.value.reduce((sum, record) => {
        return sum + Number(record.summary.credits || 0)
    }, 0).toFixed(2)
})

const cumulativeGpa = computed(() => {
    let totalPoints = 0
    let totalGpaCredits = 0
    
    allAcademicRecords.value.forEach(record => {
        record.courses.forEach(course => {
            const credits = Number(course.credits || 0)
            const finalGrade = course.final || ''
            const points = getBasePointsFromGrade(finalGrade)
            
            if (points !== null && finalGrade.toUpperCase() !== 'F' && credits > 0) {
                totalPoints += points * credits
                totalGpaCredits += credits
            }
        })
    })
    
    return totalGpaCredits > 0 ? (totalPoints / totalGpaCredits).toFixed(2) : '0.00'
})

// Load transcript data
async function loadTranscript() {
    const uuid = route.params.uuid
    if (!uuid) {
        loading.value = false
        return
    }
    
    loading.value = true
    try {
        // Fetch student details
        try {
            const studentResponse = await store.dispatch("student/get", {
                uuid: uuid
            })
            if (studentResponse?.student) {
                selectedStudent.value = studentResponse.student
            }
        } catch (err) {
            console.error("Failed to fetch student details:", err)
        }
        
        // Fetch transcript data
        const reportCardResponse = await store.dispatch("student/studentProfile/getReportCard", {
            uuid: uuid,
            publishedOnly: false
        })
        
        const transcriptResponse = await store.dispatch("student/studentProfile/getTranscript", {
            uuid: uuid,
            highSchoolOnly: true
        })
        
        // Handle report card response
        if (reportCardResponse) {
            const reportData = reportCardResponse.data || reportCardResponse
            reportCardBlocks.value = reportData.reports || 
                                    reportData.blocks || 
                                    (Array.isArray(reportData) ? reportData : [])
            transcriptBlocks.value = reportCardBlocks.value
        } else {
            reportCardBlocks.value = []
            transcriptBlocks.value = []
        }
        
        // Handle transcript response if report card is empty
        if (transcriptBlocks.value.length === 0 && transcriptResponse) {
            const responseData = transcriptResponse.data || transcriptResponse
            const transcriptData = responseData.blocks || 
                                 responseData.academic_history || 
                                 responseData.reports || 
                                 (Array.isArray(responseData) ? responseData : [])
            
            // If blocks is empty but we have academic_history, transform it
            if (transcriptData.length === 0 && responseData.academic_history && Array.isArray(responseData.academic_history)) {
                transcriptBlocks.value = responseData.academic_history.map(history => ({
                    gradeLevel: history.grade_level || history.gradeLevel || {},
                    grade_level: history.grade_level || history.gradeLevel || {},
                    schoolYear: history.school_year || history.schoolYear || {},
                    school_year: history.school_year || history.schoolYear || {},
                    courses: history.courses || [],
                    terms: []
                }))
            } else {
                transcriptBlocks.value = transcriptData
            }
        }
        
        // Fetch prerequisites for grade levels, courses, etc.
        try {
            const preReqResponse = await store.dispatch('student/studentDocuments/preRequisite')
            if (preReqResponse) {
                gradeLevels.value = preReqResponse.gradeLevels || []
                academicCourses.value = preReqResponse.academicCourses || []
                gradingSystems.value = preReqResponse.gradingSystems || []
                schoolYears.value = preReqResponse.schoolYears || []
            }
        } catch (err) {
            console.error("Failed to fetch prerequisites:", err)
        }
    } catch (error) {
        console.error("Failed to load transcript:", error)
    } finally {
        loading.value = false
    }
}

// Actions
function goBack() {
    const uuid = route.params.uuid
    router.push({
        name: 'MyChildrenProfileShow',
        params: { uuid: uuid }
    })
}

function hideAppHeader() {
    if (typeof document !== 'undefined') {
        const allDivs = document.querySelectorAll('div')
        allDivs.forEach(div => {
            const classes = div.className || ''
            if (classes.includes('h-16') && classes.includes('flex') && classes.includes('bg-white')) {
                div.style.display = 'none'
                div.style.visibility = 'hidden'
                div.style.height = '0'
                div.style.overflow = 'hidden'
            }
        })
        
        const headers = document.querySelectorAll('div.flex.h-16, div[class*="h-16"], div.bg-white.h-16')
        headers.forEach(header => {
            if (header && header.style) {
                header.style.display = 'none'
                header.style.visibility = 'hidden'
                header.style.height = '0'
                header.style.overflow = 'hidden'
            }
        })
        
        const notificationBars = document.querySelectorAll('[class*="NotificationBar"]')
        notificationBars.forEach(bar => {
            if (bar && bar.style) {
                bar.style.display = 'none'
            }
        })
        
        const sidebars = document.querySelectorAll('[class*="AppSidebar"], aside')
        sidebars.forEach(sidebar => {
            if (sidebar && sidebar.style) {
                sidebar.style.display = 'none'
            }
        })
    }
}

if (typeof window !== 'undefined') {
    window.addEventListener('beforeprint', () => {
        hideAppHeader()
    })
    
    window.addEventListener('afterprint', () => {
        if (typeof document !== 'undefined') {
            const headers = document.querySelectorAll('div.flex.h-16')
            headers.forEach(header => {
                if (header && header.style) {
                    header.style.display = ''
                    header.style.visibility = ''
                    header.style.height = ''
                    header.style.overflow = ''
                }
            })
        }
    })
}

function printTranscript() {
    if (typeof window !== 'undefined' && window.print) {
        if (selectedStudent.value && typeof document !== 'undefined') {
            document.title = `${fullName(selectedStudent.value)} - Unofficial Transcript`
        }
        hideAppHeader()
        setTimeout(() => {
            window.print()
        }, 100)
    }
}

onMounted(() => {
    if (typeof document !== 'undefined') {
        document.title = selectedStudent.value 
            ? `${fullName(selectedStudent.value)} - Unofficial Transcript` 
            : 'Unofficial Transcript'
        hideAppHeader()
    }
    loadTranscript()
})
</script>

<style scoped>
</style>

<style>
/* Screen-only styles - make preview look normal-sized without affecting print */
@media screen {
    .mx-auto.bg-white.rounded-2xl {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Increase font sizes for screen readability */
    .transcript-content {
        font-size: 1.2em;
    }
    
    .transcript-content .text-xs {
        font-size: 0.875rem !important; /* 14px */
    }
    
    .transcript-content .text-sm {
        font-size: 1rem !important; /* 16px */
    }
    
    /* Scale up arbitrary small text sizes for screen */
    .transcript-content .text-\[9px\] {
        font-size: 0.875rem !important; /* 14px instead of 9px */
    }
    
    .transcript-content .text-\[10px\] {
        font-size: 1rem !important; /* 16px instead of 10px */
    }
    
    .transcript-content h1 {
        font-size: 1.5rem !important;
    }
    
    .transcript-content h2 {
        font-size: 1.25rem !important;
    }
    
    .transcript-content h3 {
        font-size: 1rem !important;
    }
    
    /* Increase spacing for screen */
    .transcript-content .gap-2 {
        gap: 1rem !important;
    }
    
    .transcript-content .mb-2 {
        margin-bottom: 1rem !important;
    }
    
    .transcript-content .p-1.5,
    .transcript-content .p-3 {
        padding: 1rem !important;
    }
    
    /* Make logos bigger for screen preview */
    .transcript-content .transcript-logo {
        height: 6rem !important; /* 96px instead of 48px (h-12) */
        max-width: none !important;
    }
}

@media print {
    @page {
        size: A4;
        margin: 0.5in;
        marks: none;
        @top-left { content: ""; }
        @top-center { content: ""; }
        @top-right { content: ""; }
        @bottom-left { content: ""; }
        @bottom-center { content: ""; }
        @bottom-right { content: ""; }
    }
    
    /* Reset screen styles for print - use original small sizes */
    .transcript-content {
        font-size: 1em !important;
    }
    
    .transcript-content .text-xs {
        font-size: 0.625rem !important; /* 10px for print */
    }
    
    .transcript-content .text-sm {
        font-size: 0.75rem !important; /* 12px for print */
    }
    
    /* Reset logos to original size for print */
    .transcript-content .transcript-logo {
        height: 3rem !important; /* 48px (h-12) for print */
        max-width: 100% !important;
    }
    
    div.flex.h-16,
    div[class*="h-16"][class*="flex"],
    div[class*="dark:bg-dark-header"],
    div.bg-white.h-16,
    div[class*="bg-white"][class*="h-16"],
    div[class*="shrink-0"][class*="h-16"],
    div.mx-5,
    [class*="NotificationBar"],
    div[class*="AppSidebar"],
    div[class*="AppHeader"],
    aside,
    header:not(.print\\:hidden),
    body > div > div > div:first-child,
    body > div > div > div.flex.h-16,
    main > div:first-child > div:first-child,
    div.relative.z-10.flex,
    div.flex.items-center.justify-between,
    div.flex.w-0.flex-1.flex-col.overflow-hidden > div:first-child,
    div.flex.w-0.flex-1.flex-col.overflow-hidden > div.flex.h-16,
    div.flex.w-0.flex-1.flex-col.overflow-hidden > div:has(.mx-5),
    div:has(> div.mx-5),
    div:has(> div > div.mx-5) {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        overflow: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    main > div.space-y-4 {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    
    div.flex.w-0.flex-1.flex-col.overflow-hidden {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    
    * {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
    
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
        background: white !important;
    }
    
    .print\:shadow-none {
        box-shadow: none !important;
    }

    .print\:border-0 {
        border: 0 !important;
    }
    
    .print\:p-4 {
        padding: 1rem !important;
    }
    
    .print\:hidden {
        display: none !important;
    }
    
    .no-break {
        page-break-inside: avoid;
        break-inside: avoid;
    }
    
    .print\:p-8 {
        padding: 2rem !important;
    }

    .print-header {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .academic-records {
        page-break-inside: auto;
    }

    .academic-record-item {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .academic-record-item:not(:first-child) {
        page-break-before: auto;
    }

    .academic-records > .academic-record-item:nth-child(2) {
        page-break-before: always;
    }
}
</style>
