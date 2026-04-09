<template>
    <PageHeader :title="`Add Assessment`" :navs="[
    ]" />

    <FormAction :pre-requisites="true" @setPreRequisites="setPreRequisites" :init-url="initUrl" :init-form="initForm"
        :form="form" :set-form="setForm" redirect="StudentProfile">
        <!-- … other fieldsets (Personal Info, Demographics, Address, Medical, Emergency, Guardians, Registration) … -->

        <!-- Standardized Assessments -->
        <BaseFieldset class="mt-4">
            <template #legend>{{ `Standardized Assessments` }}</template>

            <template v-for="(assessment, index) in form.assessments" :key="index">
                <div class="mt-4 grid grid-cols-6 gap-6 items-end">
                    <!-- Test Title -->
                    <div class="col-span-2">
                        <BaseSelect v-model="assessment.title" :options="assessmentOptions" name="assessments.title"
                            :label="`Test Title`" v-model:error="formErrors[`assessments.${index}.title`]" />
                    </div>

                    <!-- Date -->
                    <div class="col-span-1">
                        <DatePicker v-model="assessment.date" name="assessments.date" :label="`Date`"
                            v-model:error="formErrors[`assessments.${index}.date`]" />
                    </div>

                    <!-- Total Score -->
                    <div class="col-span-1">
                        <BaseInput type="number" v-model="assessment.total" name="assessments.total"
                            :label="`Total Score`" v-model:error="formErrors[`assessments.${index}.total`]" />
                    </div>

                    <!-- Score 1 -->
                    <div class="col-span-1">
                        <BaseInput type="text" v-model="assessment.score1" name="assessments.score1" :label="`Score 1`"
                            v-model:error="formErrors[`assessments.${index}.score1`]" />
                    </div>

                    <!-- Score 2 -->
                    <div class="col-span-1">
                        <BaseInput type="number" v-model="assessment.score2" name="assessments.score2"
                            :label="`Score 2`" v-model:error="formErrors[`assessments.${index}.score2`]" />
                    </div>

                    <!-- Remove Button -->
                    <div class="col-span-1">
                        <BaseButton design="white" @click="removeAssessment(index)">
                            <i class="fas fa-trash"></i>
                        </BaseButton>
                    </div>
                </div>
            </template>

            <div class="mt-4 flex justify-end">
                <BaseButton design="primary" @click="addAssessment()">
                    Add Score
                </BaseButton>
            </div>
        </BaseFieldset>
        <!-- … end Standardized Assessments … -->
    </FormAction>
</template>

<script>
export default {
    name: "StudentProfileForm",
}
</script>

<script setup>
import { reactive, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"
import { confirmDelete } from "@core/helpers/alert"


const store = useStore()
const route = useRoute()
const router = useRouter()

const initForm = {
    idNumber: "",
    firstName: "",
    middleName: "",
    lastName: "",
    preferedName: "",
    socialSecurityNumber: "",
    gender: "",
    birthDate: "",
    email: "",
    phoneNumber: "",
    schoolEmail: "",
    userName: "",
    line1: "",
    line2: "",
    city: "",
    state: "",
    postalCode: "",
    country: "",
    nativeLanguage: "",
    otherLanguage: "",
    fatherName: "",
    motherName: "",
    nationality: "",
    race: "",
    reducedLunch: "",
    ell: "",
    emergencyContacts: [],
    previousAddresses: [],
    guardians: [],
    medicalHistory: "",
    medicationList: "",
    bloodGroup: "",
    allergies: "",
    otherMedicals: "",
    remark: "",

    // Standardized Assessments
    assessments: []
}

const initUrl = "student/studentProfile/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    genders: [],
    guardianTypes: [],
    relations: [],
    periods: []
})

const form = reactive({ ...initForm })

// Dropdown options for Test Title
const assessmentOptions = [
    { value: "SAT", label: "SAT" },
    { value: "PSAT", label: "PSAT" },
    { value: "ACT", label: "ACT" }
]

// Add a new assessment row
function addAssessment() {
    form.assessments.push({
        title: "",
        date: "",
        total: "",
        score1: "",
        score2: ""
    })
}

// Remove an assessment row
function removeAssessment(index) {
    form.assessments.splice(index, 1)
}

// Stubs for other FormAction handlers
function setPreRequisites(data) {
    Object.assign(preRequisites, data)
    Object.assign(form, cloneDeep(initForm))
}

function setForm(data) {
    Object.assign(form, cloneDeep(initForm))
}

function addNew() {
    form.guardians.push(newGuardian())
}

function addNewEmergencyContact() {
    form.emergencyContacts.push(newEmergencyContact())
}

async function remove(index) {
    if (await confirmDelete()) {
        form.guardians.splice(index, 1)
    }
}

async function removeEmergencyContact(index) {
    if (await confirmDelete()) {
        form.emergencyContacts.splice(index, 1)
    }
}

function newGuardian() {
    return {
        guardian: "",
        firstName: "",
        middleName: "",
        lastName: "",
        phoneNumber: "",
        email: "",
        relation: ""
    }
}

function newEmergencyContact() {
    return {
        firstName: "",
        middleName: "",
        lastName: "",
        phoneNumber: "",
        email: "",
        relation: ""
    }
}

onMounted(() => {
    if (!route.params.uuid) {
        addNew()
        addNewEmergencyContact()
    }
})
</script>
