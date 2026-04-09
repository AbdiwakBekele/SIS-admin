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
                <div class="col-span-1 m-2">
                    <img :src="guardian.photo || 'https://png.pngtree.com/png-vector/20220709/ourmid/pngtree-businessman-user-avatar-wearing-suit-with-red-tie-png-image_5809521.png'"
                        alt="Guardian Image" class="w-12 h-12 rounded-full border mr-4" />
                </div>
                <div class="col-span-3 mt-2">
                    <p class="text-sm"><strong>Full name</strong></p>
                    <span class="text-gray-600 text-sm">{{ guardian.firstName }} {{
                        guardian.middleName }} {{
                            guardian.lastName }}</span>
                </div>
                <div class="col-span-2 mt-2">
                    <p class="text-sm"><strong>Relation</strong></p>
                    <span class="text-gray-600 text-sm">{{ guardian.relation }}</span>
                </div>
                <div class="col-span-3 mt-2">
                    <p class="text-sm"><strong>Email</strong></p>
                    <span class="text-gray-600 text-sm">{{ guardian.email }}</span>
                </div>
                <div class="col-span-2 mt-2">
                    <p class="text-sm"><strong>Phone</strong></p>
                    <span class="text-gray-600 text-sm">{{ guardian.phoneNumber }}</span>
                </div>
            </div>
        </div>

        <!-- Show message when no guardians -->
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Parents/Guardians</p>
            <p class="text-xs text-gray-500 mt-1">Parents/Guardians information is not available at this time.</p>
        </div>
    </div>

    <!-- Emergency Contacts -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong>Emergency Contacts</strong>
        </p>
        <hr class="mx-4 mt-3">
        <div v-if="student.emergencyContacts && student.emergencyContacts.length > 0">
            <div class="grid grid-cols-12 text-left m-2 bg-gray-100 border border-gray-300 rounded"
                v-for="contact in student.emergencyContacts" :key="contact.uuid">
                <div class="col-span-1 m-2">
                    <img :src="contact.photo || 'https://png.pngtree.com/png-vector/20220709/ourmid/pngtree-businessman-user-avatar-wearing-suit-with-red-tie-png-image_5809521.png'"
                        alt="Emergency Contact Image" class="w-12 h-12 rounded-full border mr-4" />
                </div>
                <div class="col-span-3 mt-2">
                    <p class="text-sm"><strong>Full name</strong></p>
                    <span class="text-gray-600 text-sm">{{ contact.name || contact.firstName }} {{ contact.lastName }}</span>
                </div>
                <div class="col-span-2 mt-2">
                    <p class="text-sm"><strong>Relation</strong></p>
                    <span class="text-gray-600 text-sm">{{ contact.relation }}</span>
                </div>
                <div class="col-span-3 mt-2">
                    <p class="text-sm"><strong>Email</strong></p>
                    <span class="text-gray-600 text-sm">{{ contact.email }}</span>
                </div>
                <div class="col-span-2 mt-2">
                    <p class="text-sm"><strong>Phone</strong></p>
                    <span class="text-gray-600 text-sm">{{ contact.phoneNumber || contact.phone }}</span>
                </div>
            </div>
        </div>

        <!-- Show message when no emergency contacts -->
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Emergency Contacts</p>
            <p class="text-xs text-gray-500 mt-1">Emergency Contacts information is not available at this time.</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    student: Object,
})
</script>

