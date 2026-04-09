<template>
    <!-- Parent / Guardian  -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong>Parents/Guardians</strong>
        </p>
        <hr class="mx-4 mt-3">
        <!-- Display actual guardians from API -->
        <div v-if="student.guardians && student.guardians.length > 0">
            <div class="grid grid-cols-12 text-left m-2 bg-gray-100 border border-gray-300 rounded"
                v-for="guardian in student.guardians" :key="guardian.uuid">
                <div class="col-span-1 m-2 flex items-center justify-center">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-full flex items-center justify-center shadow-lg border border-blue-200">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                </div>
                <div class="col-span-3 mt-2">
                    <p class="text-sm"><strong>Full name</strong></p>
                    <span class="text-gray-600 text-sm">{{ guardian.firstName || guardian.contact?.firstName || '' }} {{
                        guardian.middleName || guardian.contact?.middleName || '' }} {{
                            guardian.lastName || guardian.contact?.lastName || '' }}</span>
                </div>
                <div class="col-span-2 mt-2">
                    <p class="text-sm"><strong>Relation</strong></p>
                    <span class="text-gray-600 text-sm">{{ guardian.relation || guardian.pivot?.relation || '-' }}</span>
                </div>
                <div class="col-span-3 mt-2">
                    <p class="text-sm"><strong>Email</strong></p>
                    <span class="text-gray-600 text-sm">{{ guardian.email || guardian.contact?.email || '-' }}</span>
                </div>
                <div class="col-span-2 mt-2">
                    <p class="text-sm"><strong>Phone</strong></p>
                    <span class="text-gray-600 text-sm">{{ guardian.phoneNumber || guardian.contact?.contactNumber || '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Show message when no guardians -->
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-users text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Parents/Guardians</p>
            <p class="text-xs text-gray-500 mt-1">Parents/Guardians information is not available at this time.</p>
        </div>
    </div>

    <!-- Siblings -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong>Siblings</strong>
        </p>
        <hr class="mx-4 mt-2">
        <div v-if="student.siblings && student.siblings.length > 0" class="grid grid-cols-12 text-left m-2 bg-gray-100 border border-gray-300 rounded"
            v-for="sibling in student.siblings" :key="sibling.name">
            <div class="col-span-1 m-2 flex items-center justify-center">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-full flex items-center justify-center shadow-lg border border-blue-200">
                    <i class="fas fa-user text-white text-lg"></i>
                </div>
            </div>
            <div class="col-span-2 mt-2">
                <p class="text-sm"><strong>Full name</strong></p>
                <span class="text-gray-600 text-sm">{{ sibling.name }}</span>
            </div>
            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Email</strong></p>
                <span class="text-gray-600 text-sm">{{ sibling.email }}</span>
            </div>
            <div class="col-span-2 mt-2">
                <p class="text-sm"><strong>Phone</strong></p>
                <span class="text-gray-600 text-sm">{{ sibling.phone }}</span>
            </div>
            <div class="col-span-2 mt-2">
                <p class="text-sm"><strong>Grade Level</strong></p>
                <span class="text-gray-600 text-sm">{{ sibling.gradeLevel }}</span>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Siblings</p>
            <p class="text-xs text-gray-500 mt-1">Siblings information is not available at this time.</p>
        </div>
    </div>

    <!-- Emergency Contacts -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong>Emergency Contacts</strong>
        </p>
        <hr class="mx-4 mt-2">
        <div v-if="student.emergencyContacts && student.emergencyContacts.length > 0" class="grid grid-cols-12 text-left m-2 bg-gray-100 border border-gray-300 rounded"
            v-for="emergencyContact in student.emergencyContacts" :key="emergencyContact.uuid || emergencyContact.name">
            <div class="col-span-1 m-2 flex items-center justify-center">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-full flex items-center justify-center shadow-lg border border-blue-200">
                    <i class="fas fa-user text-white text-lg"></i>
                </div>
            </div>
            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Full name</strong></p>
                <span class="text-gray-600 text-sm">{{ emergencyContact.firstName }} {{
                    emergencyContact.middleName }}
                    {{ emergencyContact.lastName }}</span>
            </div>
            <div class="col-span-2 mt-2">
                <p class="text-sm"><strong>Relation</strong></p>
                <span class="text-gray-600 text-sm">
                    {{
                        emergencyContact.relation
                            ? emergencyContact.relation.charAt(0).toUpperCase() + emergencyContact.relation.slice(1).toLowerCase()
                            : ''
                    }}
                </span>
            </div>
            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Email</strong></p>
                <span class="text-gray-600 text-sm">{{ emergencyContact.email }}</span>
            </div>
            <div class="col-span-2 mt-2">
                <p class="text-sm"><strong>Phone</strong></p>
                <span class="text-gray-600 text-sm">{{ emergencyContact.phoneNumber }}</span>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Emergency Contacts</p>
            <p class="text-xs text-gray-500 mt-1">Emergency Contacts information is not available at this time.</p>
        </div>
    </div>

    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong> Demographic Info </strong> </p>
        <hr class="mx-4">

        <div class="grid grid-cols-12 text-left">
            <!-- Citizenship -->
            <span class="col-span-1 mt-3 ml-4">
                <i class="fa-solid fa-globe"></i>
            </span>

            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Citizenship</strong></p>
                <span class="text-gray-600 text-sm">{{ student.nationality || '-' }}</span>
            </div>

            <!-- Race -->
            <span class="col-span-1 mt-3 ml-4">
                <i class="fa-solid fa-person"></i>
            </span>

            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Race/Ethnicity</strong></p>
                <span class="text-gray-600 text-sm">{{ student.race || '-' }}</span>
            </div>

            <!-- Gender-->
            <span class="col-span-1 mt-3 ml-4">
                <i class="fa-solid fa-venus-mars"></i>
            </span>

            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Gender</strong></p>
                <span class="text-gray-600 text-sm">{{ student.gender }}</span>
            </div>

            <!-- Native Language -->
            <span class="col-span-1 mt-3 ml-4">
                <i class="fa-solid fa-language"></i>
            </span>

            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Native Language</strong></p>
                <span class="text-gray-600 text-sm">English</span>
            </div>

            <!-- Other Language -->
            <span class="col-span-1 mt-3 ml-4">
                <i class="fa-solid fa-language"></i>
            </span>

            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Other Languages</strong></p>
                <span class="text-gray-600 text-sm"> - </span>
            </div>
        </div>
    </div>

    <!-- Special Populations -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong> Special Populations </strong>
        </p>
        <hr class="mx-4">

        <div v-if="hasAnySpecialPopulations" class="px-4 py-3">
            <!-- Display each Special Population as a card/label with its items as bubbles - inline -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                <div 
                    v-for="(items, categoryName) in groupedSpecialPopulations" 
                    :key="categoryName"
                    class="border border-gray-200 rounded-lg p-3 bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-center mb-2">
                        <h4 class="text-sm font-semibold text-gray-800">{{ categoryName }}</h4>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span 
                            v-for="(item, index) in items" 
                            :key="`${categoryName}-${index}`"
                            class="inline-flex items-center rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 px-3 py-1 text-xs font-medium text-white shadow-sm hover:shadow-md transition-shadow duration-200 border border-blue-400/30">
                            {{ item }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Special Populations</p>
            <p class="text-xs text-gray-500 mt-1">Special Populations information is not available at this time.</p>
        </div>
    </div>

    <!-- Address -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong> Previous Address</strong> </p>
        <hr class="mx-4">
        <div v-if="student.previousAddresses && student.previousAddresses.length > 0" class="grid grid-cols-2">
            <div class="col-span-1 mt-3 text-left" v-for="(previousAddress, index) in student.previousAddresses" :key="index">
                <span class="m-1 ml-5">
                    <i class="fa-solid fa-location-dot"></i>
                </span>
                <span class="text-gray-600 text-sm mx-1">{{ previousAddress }}</span>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Previous Address</p>
            <p class="text-xs text-gray-500 mt-1">Previous Address information is not available at this time.</p>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue"

const props = defineProps({
    student: Object
})

// Helper function to group special populations by category
const groupedSpecialPopulations = computed(() => {
    if (!props.student?.specialPopulations || !Array.isArray(props.student.specialPopulations)) {
        return {}
    }
    
    const grouped = {}
    props.student.specialPopulations.forEach((sp) => {
        if (sp.category && sp.item && sp.item.trim() !== '') {
            if (!grouped[sp.category]) {
                grouped[sp.category] = []
            }
            grouped[sp.category].push(sp.item)
        }
    })
    
    return grouped
})

// Helper function to check if there are any special populations
const hasAnySpecialPopulations = computed(() => {
    return Object.keys(groupedSpecialPopulations.value).length > 0
})
</script>

