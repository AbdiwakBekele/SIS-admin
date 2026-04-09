<template>
    <PageHeader :title="$trans(route.meta.trans, {
        attribute: $trans(route.meta.label),
    })
        " :navs="[
            {
                label: `Manual Grade`,
                path: 'StudentProfile',
            },
        ]">

    </PageHeader>

    <ParentTransition appear :visibility="true">
        <FormAction :pre-requisites="true" @setPreRequisites="setPreRequisites" :init-url="initUrl"
            :init-form="initForm" :form="form" :set-form="setForm" redirect="StudentProfile">

            <!-- Personal Information -->
            <BaseFieldset class="mt-4">
                <template #legend>{{ `Homework` }}</template>
                <div class="mt-1 grid grid-cols-3 gap-6">

                    <BaseFieldset class="mt-4 col-span-1" v-for="(session, index) in form.sessions" :key="session.uuid">
                        <template #legend>
                            Homework {{ index + 1 }}
                            <span class="text-danger ml-2 cursor-pointer" @click=""><i
                                    class="fas fa-times-circle"></i></span>
                        </template>

                        <div class="mt-1 grid grid-cols-3 gap-6">
                            <div class="col-span-3">
                                <BaseInput type="text" v-model="session.name" :name="`sessions.${index}.name`"
                                    :label="`Title`" />
                            </div>
                        </div>
                    </BaseFieldset>

                </div>

                <!-- Add New Row -->
                <div class="mt-4">
                    <BaseBadge design="secondary" color="black" @click="addRow"
                        class="cursor-pointer border border-gray-300">
                        <span class="my-2">Add Homework</span>
                    </BaseBadge>
                </div>
            </BaseFieldset>

            <!-- Demographic -->
            <BaseFieldset class="mt-4">
                <template #legend>{{ `Quiz` }}</template>
                <div class="mt-1 grid grid-cols-3 gap-6">

                    <BaseFieldset class="mt-4 col-span-1" v-for="(session, index) in form.quizes" :key="session.uuid">
                        <template #legend>
                            Quiz {{ index + 1 }}
                            <span class="text-danger ml-2 cursor-pointer" @click=""><i
                                    class="fas fa-times-circle"></i></span>
                        </template>

                        <div class="mt-1 grid grid-cols-3 gap-6">
                            <div class="col-span-3">
                                <BaseInput type="text" v-model="session.name" :name="`sessions.${index}.name`"
                                    :label="`Title`" />
                            </div>
                        </div>
                    </BaseFieldset>

                </div>

                <!-- Add New Row -->
                <div class="mt-4">
                    <BaseBadge design="secondary" color="black" @click="addQuizRow"
                        class="cursor-pointer border border-gray-300">
                        <span class="my-2">Add Quiz</span>
                    </BaseBadge>
                </div>
            </BaseFieldset>


            <!-- Demographic -->
            <BaseFieldset class="mt-4">
                <template #legend>{{ `Exam` }}</template>
                <div class="mt-1 grid grid-cols-3 gap-6">

                    <BaseFieldset class="mt-4 col-span-1" v-for="(session, index) in form.exam" :key="session.uuid">
                        <template #legend>
                            Exam {{ index + 1 }}
                            <span class="text-danger ml-2 cursor-pointer" @click=""><i
                                    class="fas fa-times-circle"></i></span>
                        </template>

                        <div class="mt-1 grid grid-cols-3 gap-6">
                            <div class="col-span-3">
                                <BaseInput type="text" v-model="session.name" :name="`sessions.${index}.name`"
                                    :label="`Title`" />
                            </div>
                        </div>
                    </BaseFieldset>

                </div>

                <!-- Add New Row -->
                <div class="mt-4">
                    <BaseBadge design="secondary" color="black" @click="addExamRow"
                        class="cursor-pointer border border-gray-300">
                        <span class="my-2">Add Exam</span>
                    </BaseBadge>
                </div>
            </BaseFieldset>


        </FormAction>
    </ParentTransition>
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
import { useCustomFields } from "@core/composables/useCustomFields"
import { reduce } from "lodash"
import { v4 as uuidv4 } from "uuid"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initForm = {
    idNumber: "",
    firstName: "",
    middleName: "",
    lastName: "",
    preferedName: "",

    fatherName: "",
    motherName: "",
    idNumber: "",
    birthDate: "",
    socialSecurityNumber: "",

    email: "",
    contactNumber: "",
    schoolEmail: "",
    username: "",
    nationality: "",

    // Demographics
    gender: "",
    nativeLanguage: "",
    otherLanguage: "",
    race: "",
    reducedLunch: "",
    ell: "",

    line1: "",
    line2: "",
    city: "",
    state: "",
    postalCode: "",
    country: "",

    // Medical History
    medicalHistory: "",
    medicationList: "",
    bloodGroup: "",
    allergies: "",
    otherMedicals: "",

    // Registration
    schoolYear: "",
    gradeLevel: "",
    specialPopulation: "",
    applicationDate: "",
    enrollmentDate: "",

    emergencyContacts: [],
    guardians: [],
    sessions: ['1'],
    quizes: ['1'],
    exam: ['1'],

    // previousAddresses: [],
    // remark: "",
}

const initSession = {
    uuid: uuidv4(),
    name: "",
    startTime: "",
    endTime: "",
}

const addRow = () => {
    form.sessions.push({
        ...initSession,
        uuid: uuidv4(),
    })

    fetchData.isLoaded = true
}


// 

const initQuiz = {
    uuid: uuidv4(),
    name: "",
    startTime: "",
    endTime: "",
}

const addQuizRow = () => {
    form.quizes.push({
        ...initQuiz,
        uuid: uuidv4(),
    })

    fetchData.isLoaded = true
}

// 

const initExam = {
    uuid: uuidv4(),
    name: "",
    startTime: "",
    endTime: "",
}

const addExamRow = () => {
    form.exam.push({
        ...initExam,
        uuid: uuidv4(),
    })

    fetchData.isLoaded = true
}

const initUrl = "student/studentProfile/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    genders: [],
    schoolYears: [],
    gradeLevels: [],
    guardianTypes: [],
    relations: [],
    periods: [],
})


const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
    Object.assign(form, cloneDeep(initForm))
}

const setForm = (data) => {
    Object.assign(initForm, {

    })
    Object.assign(form, cloneDeep(initForm))
    fetchData.isLoaded = true
}


const addNew = () => {
    form.guardians.push(newGuardian())
}

const addNewEmergencyContact = () => {
    form.emergencyContacts.push(newEmergencyContact())
}

const remove = async (index) => {
    if (await confirmDelete()) {
        form.guardians.splice(index, 1)
    }
}

const removeEmergencyContact = async (index) => {
    if (await confirmDelete()) {
        form.emergencyContacts.splice(index, 1)
    }
}

const newGuardian = () => {
    return {
        guardian: "",
        firstName: "",
        middleName: "",
        lastName: "",
        phoneNumber: "",
        email: "",
        relation: "",
    }
}

const newEmergencyContact = () => {
    return {
        firstName: "",
        middleName: "",
        lastName: "",
        phoneNumber: "",
        email: "",
        relation: "",
    }
}

onMounted(() => {
    if (!route.params.uuid) {
        addNew()
        addNewEmergencyContact()
    }
})
</script>
