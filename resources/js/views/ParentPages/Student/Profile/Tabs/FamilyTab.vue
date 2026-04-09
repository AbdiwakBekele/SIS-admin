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
                <div class="col-span-1 mt-1">

                </div>
            </div>
        </div>

        <!-- Show message when no guardians -->
        <div v-else class="text-center py-4 text-gray-500">
            <i class="fa-solid fa-users text-2xl mb-2"></i>
            <p class="text-sm">No guardians added yet</p>
            <p class="text-xs text-gray-400">Click "Add Parent/Guardian" to add the first
                guardian</p>
        </div>

        <!-- Embedded Add/Edit Parent Modal -->
        <BaseModal :visibility="showParentModal" @close="showParentModal = false">
            <template #title>
                {{ parentForm.uuid ? 'Edit' : 'Add' }} Parent/Guardian
            </template>

            <div class="grid grid-cols-3 gap-6">
                <!-- First Name -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput v-model="parentForm.firstName" name="firstName" label="First Name" />
                </div>

                <!-- Middle Name -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput v-model="parentForm.middleName" name="middleName" label="Middle Name" />
                </div>

                <!-- Last Name -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput v-model="parentForm.lastName" name="lastName" label="Last Name" />
                </div>

                <!-- Email -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput v-model="parentForm.email" name="email" type="email" label="Email" />
                </div>

                <!-- Phone Number -->
                <div class="col-span-3 sm:col-span-1">
                    <PhoneInput v-model="parentForm.phoneNumber" name="phoneNumber" label="Phone Number" />
                </div>

                <!-- Relation -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect v-model="parentForm.relation" :name="`relation`" :label="`Relation to Student`"
                        :options="[
                            { value: 'Father', label: 'Father' },
                            { value: 'Mother', label: 'Mother' },
                            { value: 'Stepfather', label: 'Stepfather' },
                            { value: 'Stepmother', label: 'Stepmother' },
                            { value: 'Grandmother', label: 'Grandmother' },
                            { value: 'Grandfather', label: 'Grandfather' },
                            { value: 'Aunt', label: 'Aunt' },
                            { value: 'Uncle', label: 'Uncle' },
                            { value: 'Brother', label: 'Brother' },
                            { value: 'Sister', label: 'Sister' },
                            { value: 'Guardian', label: 'Guardian' },
                            { value: 'Legal Guardian', label: 'Legal Guardian' },
                            { value: 'Other', label: 'Other' }
                        ]" />
                </div>

                <!-- Username -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput v-model="parentForm.username" name="username" label="Username" />
                </div>
            </div>

            <div class="mt-6 text-right">
                <BaseButton design="primary" @click="submitParent">
                    Save
                </BaseButton>
            </div>
        </BaseModal>

    </div>

    <!-- Siblings -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong>Siblings</strong>

        </p>
        <hr class="mx-4 mt-2">
        <div class="grid grid-cols-12 text-left m-2 bg-gray-100 border border-gray-300 rounded"
            v-for="sibling in student.siblings" :key="sibling.name">
            <div class="col-span-1 m-2">
                <img src="https://png.pngtree.com/png-vector/20220709/ourmid/pngtree-businessman-user-avatar-wearing-suit-with-red-tie-png-image_5809521.png"
                    alt="Sibling Image" class="w-12 h-12 rounded-full border mr-4" />
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
            <div class="col-span-2 mt-1">
                <button class="px-2 py-1 my-2 rounded text-white text-sm">
                    <i class="fa-solid fa-pen-to-square text-black mx-1"></i>
                </button>
                <button class="px-2 py-1 rounded text-white text-sm">
                    <i class="fa-solid fa-trash text-red-500 mx-1"></i>
                </button>
            </div>
        </div>

    </div>

    <!-- Emergency Contacts -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong>Emergency Contacts</strong>
            <button class="bg-blue-500 px-2 py-1 rounded text-white float-right"
                @click="showEmergencyContactModal = true">
                <i class="fa-solid fa-plus text-white mx-1"></i> Add Emergency Contact
            </button>
        </p>
        <hr class="mx-4 mt-2">
        <div class="grid grid-cols-12 text-left m-2 bg-gray-100 border border-gray-300 rounded"
            v-for="emergencyContact in student.emergencyContacts" :key="emergencyContact.uuid || emergencyContact.name">
            <div class="col-span-1 m-2">
                <img src="https://png.pngtree.com/png-vector/20220709/ourmid/pngtree-businessman-user-avatar-wearing-suit-with-red-tie-png-image_5809521.png"
                    alt="Emergency Contact Image" class="w-12 h-12 rounded-full border mr-4" />
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
            <div class="col-span-1 mt-1">
                <button class="px-2 py-1 my-2 rounded text-white text-sm"
                    @click="editEmergencyContact(emergencyContact)">
                    <i class="fa-solid fa-pen-to-square text-black mx-1"></i>
                </button>
                <button class="px-2 py-1 rounded text-white text-sm" @click="removeEmergencyContact(emergencyContact)">
                    <i class="fa-solid fa-trash text-red-500 mx-1"></i>
                </button>
            </div>
        </div>
        <!-- Add/Edit Emergency Modal -->
        <BaseModal :visibility="showEmergencyContactModal" @close="showEmergencyContactModal = false">
            <template #title>
                {{ parentForm.uuid ? 'Edit' : 'Add ' }} Emergency Contact
            </template>

            <div class="grid grid-cols-3 gap-6">
                <!-- First Name -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput v-model="emergencyContactForm.firstName" name="firstName" label="First Name" />
                </div>

                <!-- Middle Name -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput v-model="emergencyContactForm.middleName" name="middleName" label="Middle Name" />
                </div>

                <!-- Last Name -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput v-model="emergencyContactForm.lastName" name="lastName" label="Last Name" />
                </div>

                <!-- Email -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput v-model="emergencyContactForm.email" name="email" type="email" label="Email" />
                </div>

                <!-- Phone Number -->
                <div class="col-span-3 sm:col-span-1">
                    <PhoneInput v-model="emergencyContactForm.phoneNumber" name="phoneNumber" label="Phone Number" />
                </div>

                <!-- Relation -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect v-model="emergencyContactForm.relation" :name="`relation`"
                        :label="`Relation to Student`" :options="[
                            { value: 'Aunt', label: 'Aunt' },
                            { value: 'Brother', label: 'Brother' },
                            { value: 'Co-worker', label: 'Co-worker' },
                            { value: 'Friend', label: 'Friend' },
                            { value: 'Grandfather', label: 'Grandfather' },
                            { value: 'Grandmother', label: 'Grandmother' },
                            { value: 'Guardian', label: 'Guardian' },
                            { value: 'Legal Guardian', label: 'Legal Guardian' },
                            { value: 'Neighbor', label: 'Neighbor' },
                            { value: 'Sister', label: 'Sister' },
                            { value: 'Stepfather', label: 'Stepfather' },
                            { value: 'Stepmother', label: 'Stepmother' },
                            { value: 'Uncle', label: 'Father' },
                            { value: 'Other', label: 'Other' },
                        ]" />
                </div>
            </div>

            <div class="mt-6 text-right">
                <BaseButton design="primary" @click="submitEmergencyContact">
                    Save
                </BaseButton>
            </div>
        </BaseModal>

    </div>

</template>

<script setup>
import { reactive, ref, inject, computed, watch, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useToast } from "vue-toastification"
import { useStore } from 'vuex';
import AddEmergencyContactForm from './../AddEmergencyContactForm.vue';
import AddParentForm from './../AddParentForm.vue';
import { confirmAction, confirmDelete } from "@core/helpers/alert"

const store = useStore();
const router = useRouter();
const emitter = inject("emitter")


const props = defineProps({
    student: Object
})

const showParentModal = ref(false);
const showEmergencyContactModal = ref(false);

const parentForm = reactive({
    uuid: null,
    firstName: '',
    middleName: '',
    lastName: '',
    email: '',
    phoneNumber: '',
    relation: '',
    username: '',
    student: props.student?.uuid || '',
});

const emergencyContactForm = reactive({
    firstName: '',
    middleName: '',
    lastName: '',
    email: '',
    phoneNumber: '',
    relation: '',
    student: props.student?.uuid || '',
});


function resetParentForm() {
    parentForm.uuid = null
    parentForm.firstName = ''
    parentForm.middleName = ''
    parentForm.lastName = ''
    parentForm.email = ''
    parentForm.phoneNumber = ''
    parentForm.relation = ''
    parentForm.username = ''
}

function resetEmergencyContactForm() {
    emergencyContactForm.uuid = null
    emergencyContactForm.firstName = ''
    emergencyContactForm.middleName = ''
    emergencyContactForm.lastName = ''
    emergencyContactForm.phoneNumber = ''
    emergencyContactForm.relation = ''
}


const editGuardian = (guardian) => {
    parentForm.uuid = guardian.uuid
    parentForm.firstName = guardian.firstName
    parentForm.middleName = guardian.middleName
    parentForm.lastName = guardian.lastName
    parentForm.email = guardian.email
    parentForm.phoneNumber = guardian.phoneNumber
    parentForm.relation = guardian.relation
    parentForm.username = guardian.username
    showParentModal.value = true
}

const editEmergencyContact = (emergencyContact) => {
    emergencyContactForm.uuid = emergencyContact.uuid
    emergencyContactForm.firstName = emergencyContact.firstName
    emergencyContactForm.middleName = emergencyContact.middleName
    emergencyContactForm.lastName = emergencyContact.lastName
    emergencyContactForm.phoneNumber = emergencyContact.phoneNumber
    emergencyContactForm.relation = emergencyContact.relation
    showEmergencyContactModal.value = true
}

const submitParent = async () => {
    try {
        if (parentForm.uuid) {
            // EDIT Mode
            await store.dispatch('student/studentProfile/updateGuardian', {
                form: parentForm,
                uuid: parentForm.uuid
            });
        } else {
            // CREATE Mode
            await store.dispatch('student/studentProfile/addGuardian', {
                form: parentForm
            });
        }

        showParentModal.value = false
        resetParentForm()
        emitter.emit("refreshItem")

    } catch (error) {
        console.error(error)
        if (error.response?.data?.errors) {
            // Handle backend validation errors
        }
    }
}


const submitEmergencyContact = async () => {
    try {
        if (emergencyContactForm.uuid) {
            // EDIT Mode
            await store.dispatch('student/studentProfile/updateEmergencyContact', {
                form: emergencyContactForm,
                uuid: emergencyContactForm.uuid
            });
        } else {
            // CREATE Mode
            await store.dispatch('student/studentProfile/addEmergencyContact', {
                form: emergencyContactForm
            });
        }

        showEmergencyContactModal.value = false
        resetEmergencyContactForm()
        emitter.emit("refreshItem")

    } catch (error) {
        console.error(error)
        if (error.response?.data?.errors) {
            // ToDo:
            // Handle backend validation errors
        }
    }
}

const removeGuardian = async (guardian) => {

    if (!(await confirmDelete())) {
        return
    }

    try {
        await store.dispatch('guardian/delete', {
            uuid: guardian.uuid
        });
        emitter.emit('refreshItem')
    } catch (error) {
        console.error(error)
    }
}


const removeEmergencyContact = async (emergencyContact) => {
    if (!(await confirmDelete())) {
        return
    }

    try {
        await store.dispatch('student/studentProfile/deleteEmergencyContact', {
            uuid: emergencyContact.uuid,
            firstName: emergencyContact.firstName,
            phoneNumber: emergencyContact.phoneNumber,
            student: props.student?.uuid

        });
        emitter.emit('refreshItem')
    } catch (error) {
        console.error(error)
    }
}
</script>
