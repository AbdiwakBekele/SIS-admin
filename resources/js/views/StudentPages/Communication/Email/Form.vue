<template>
    <FormAction :pre-requisites="true" @setPreRequisites="setPreRequisites" :init-url="initUrl" :init-form="initForm"
        :form="form" :setForm="setForm" redirect="CommunicationEmail" @resetMediaFiles="resetMediaFiles">
        <div class="grid grid-cols-4 gap-6">

            <!-- Subject -->
            <div class="col-span-4">
                <BaseInput type="text" v-model="form.subject" name="subject"
                    :label="$trans('communication.email.props.subject')" v-model:error="formErrors.subject" autofocus />
            </div>

            <!-- Inclusion -->
            <div class="col-span-4 sm:col-span-2">
                <BaseTextarea v-model="form.inclusion" name="inclusion"
                    :label="$trans('communication.email.props.inclusion')" v-model:error="formErrors.inclusion" />
            </div>

            <!-- Exclusion -->
            <div class="col-span-4 sm:col-span-2">
                <BaseTextarea v-model="form.exclusion" name="exclusion"
                    :label="$trans('communication.email.props.exclusion')" v-model:error="formErrors.exclusion" />
            </div>
        </div>

        <AudienceInput v-if="fetchData.isLoaded" :pre-requisites="preRequisites"
            v-model:studentAudienceType="form.studentAudienceType"
            v-model:employeeAudienceType="form.employeeAudienceType" v-model:studentAudiences="form.studentAudiences"
            v-model:guardianAudiences="form.guardianAudiences" v-model:formErrors="formErrors" />
        <div class="mt-4 grid grid-cols-1 gap-6">
            <div class="col-span-4">
                <BaseEditor v-model="form.content" name="content" :edit="route.params.uuid ? true : false"
                    :label="$trans('communication.email.props.content')" v-model:error="formErrors.content" />
            </div>
            <div class="col">
                <MediaUpload multiple :label="$trans('general.file')" module="communication" :media="form.media"
                    :media-token="form.mediaToken" @isUpdated="form.mediaUpdated = true"
                    @setHash="(hash) => form.mediaHash.push(hash)" />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "CommunicationEmailForm",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"
import { v4 as uuidv4 } from "uuid"

const route = useRoute()
const router = useRouter()

const initForm = {
    subject: "",
    guardianAudiences: [],
    studentAudienceType: "",
    employeeAudienceType: "",
    studentAudiences: [],
    employeeAudiences: [],
    inclusion: "",
    exclusion: "",
    courses: "",
    division: "",
    content: "",
    media: [],
    mediaUpdated: false,
    mediaToken: uuidv4(),
    mediaHash: [],
}

const initUrl = "communication/email/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    studentAudienceTypes: [],
    employeeAudienceTypes: [],
    courses: [],
    division: [],
    departments: [],
    guardian: []
})

const form = reactive({ ...initForm })
const fetchData = reactive({
    studentAudiences: [],
    // employeeAudiences: [],
    isLoaded: route.params.uuid ? false : true,
})

const setPreRequisites = (data) => {
    console.log('PreRequisites Guardian Data:', JSON.stringify(data.guardian, null, 2));
    console.log('PreRequisites Data:', data);
    Object.assign(preRequisites, data)
}

const resetMediaFiles = () => {
    form.mediaToken = uuidv4()
    form.mediaHash = []
}

const setForm = (data) => {
    let studentAudiences = data.audiences
        .filter((audience) => audience.type == "student")
        .map((audience) => audience.uuid);
    let employeeAudiences = data.audiences
        .filter((audience) => audience.type == "employee")
        .map((audience) => audience.uuid);
    let guardianAudiences = data.audiences
        .filter((audience) => audience.type == "guardian")
        .map((audience) => audience.uuid);

    Object.assign(initForm, {
        ...data,
        studentAudienceType: data.studentAudienceType?.value || "",
        employeeAudienceType: data.employeeAudienceType?.value || "",
        guardianAudiences, // Array of guardian UUIDs
        studentAudiences,
        employeeAudiences,
        courses: data.courses || "",
        division: data.division || "",
        departments: data.departments || ""
    });

    Object.assign(form, cloneDeep(initForm));
    fetchData.studentAudiences = studentAudiences;
    fetchData.employeeAudiences = employeeAudiences;
    fetchData.guardianAudiences = guardianAudiences;
    fetchData.isLoaded = true;
};
</script>


<!-- <template>
    <FormAction
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        :setForm="setForm"
        :redirect="redirectRoute"
        @resetMediaFiles="resetMediaFiles"
        :method="formMethod"
        :action-url="formActionUrl"
    >
        <div class="grid grid-cols-4 gap-6">
            <div class="col-span-4">
                <BaseInput
                    type="text"
                    v-model="form.subject"
                    name="subject"
                    :label="$trans('communication.email.props.subject')"
                    v-model:error="formErrors.subject"
                    autofocus
                />
            </div>
            <div class="col-span-4 sm:col-span-2">
                <BaseTextarea
                    v-model="form.inclusion"
                    name="inclusion"
                    :label="$trans('communication.email.props.inclusion')"
                    v-model:error="formErrors.inclusion"
                />
            </div>
            <div class="col-span-4 sm:col-span-2">
                <BaseTextarea
                    v-model="form.exclusion"
                    name="exclusion"
                    :label="$trans('communication.email.props.exclusion')"
                    v-model:error="formErrors.exclusion"
                />
            </div>
        </div>
        <AudienceInput
            v-if="fetchData.isLoaded"
            :pre-requisites="preRequisites"
            v-model:studentAudienceType="form.studentAudienceType"
            v-model:employeeAudienceType="form.employeeAudienceType"
            v-model:guardianAudienceType="form.guardianAudienceType"
            v-model:studentAudiences="form.studentAudiences"
            v-model:employeeAudiences="form.employeeAudiences"
            v-model:guardianAudiences="form.guardianAudiences"
            v-model:formErrors="formErrors"
        />
        <div class="mt-4 grid grid-cols-1 gap-6">
            <div class="col-span-4">
                <BaseEditor
                    v-model="form.content"
                    name="content"
                    :edit="route.meta.type === 'edit' || route.meta.type === 'duplicate'"
                    :label="$trans('communication.email.props.content')"
                    v-model:error="formErrors.content"
                />
            </div>
            <div class="col">
                <MediaUpload
                    multiple
                    :label="$trans('general.file')"
                    module="communication"
                    :media="form.media"
                    :media-token="form.mediaToken"
                    @isUpdated="form.mediaUpdated = true"
                    @setHash="(hash) => form.mediaHash.push(hash)"
                />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "CommunicationEmailForm",
}
</script>

<script setup>
import { reactive, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"
import { v4 as uuidv4 } from "uuid"

const route = useRoute()
const router = useRouter()

const initForm = {
    subject: "",
    studentAudienceType: "",
    employeeAudienceType: "",
    guardianAudienceType: "",
    studentAudiences: [],
    employeeAudiences: [],
    guardianAudiences: [],
    inclusion: "",
    exclusion: "",
    content: "",
    media: [],
    mediaUpdated: false,
    mediaToken: uuidv4(),
    mediaHash: [],
}

const initUrl = "communication/email/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    studentAudienceTypes: [],
    employeeAudienceTypes: [],
    guardianAudienceTypes: [],
    courses: [],
    divisions: [],
    guardians: [],
    employees: [],
})

const form = reactive({ ...initForm })
const fetchData = reactive({
    studentAudiences: [],
    employeeAudiences: [],
    guardianAudiences: [],
    isLoaded: route.params.uuid && route.meta.type !== 'new' ? false : true,
})

const redirectRoute = computed(() => {
    return route.meta.type === 'duplicate' ? 'CommunicationEmailNew' : 'CommunicationEmail'
})

const formMethod = computed(() => {
    return route.meta.type === 'edit' ? 'PUT' : 'POST'
})

const formActionUrl = computed(() => {
    return route.meta.type === 'edit' ? `${initUrl}${route.params.uuid}` : initUrl
})

const setPreRequisites = (data) => {
    Object.assign(preRequisites, {
        studentAudienceTypes: data.studentAudienceTypes || [],
        employeeAudienceTypes: data.employeeAudienceTypes || [],
        guardianAudienceTypes: data.guardianAudienceTypes || [],
        courses: data.courses || [],
        divisions: data.divisions || [],
        guardians: data.guardians || [],
        employees: data.employees || [],
    })
}

const resetMediaFiles = () => {
    form.mediaToken = uuidv4()
    form.mediaHash = []
}

const setForm = (data) => {
    let studentAudiences = data.audiences
        ?.filter((audience) => audience.type === "student")
        .map((audience) => audience.uuid) || [];
    let employeeAudiences = data.audiences
        ?.filter((audience) => audience.type === "employee")
        .map((audience) => audience.uuid) || [];
    let guardianAudiences = data.audiences
        ?.filter((audience) => audience.type === "guardian")
        .map((audience) => audience.uuid) || [];

    Object.assign(initForm, {
        ...data,
        studentAudienceType: data.student_audience_type?.value || "",
        employeeAudienceType: data.employee_audience_type?.value || "",
        guardianAudienceType: data.guardian_audience_type?.value || "",
        studentAudiences,
        employeeAudiences,
        guardianAudiences,
        inclusion: data.inclusion || "",
        exclusion: data.exclusion || "",
        media: data.media || [],
        mediaToken: data.media_token || uuidv4(),
        mediaHash: [],
    })

    Object.assign(form, cloneDeep(initForm))

    fetchData.studentAudiences = studentAudiences
    fetchData.employeeAudiences = employeeAudiences
    fetchData.guardianAudiences = guardianAudiences
    fetchData.isLoaded = true
}
</script> -->