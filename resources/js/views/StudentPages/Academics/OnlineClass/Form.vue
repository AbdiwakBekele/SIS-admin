<template>
    <FormAction
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        :setForm="setForm"
        redirect="ResourceOnlineClass"
        @resetMediaFiles="resetMediaFiles"
    >
        <div class="grid grid-cols-4 gap-6">
            <div class="col-span-4">
                <BaseInput
                    type="text"
                    v-model="form.topic"
                    name="topic"
                    :label="$trans('resource.online_class.props.topic')"
                    v-model:error="formErrors.topic"
                    autofocus
                />
            </div>
           

            <!-- Subject -->
            <div class="col-span-4 sm:col-span-1">
                <BaseSelect
                    v-model="form.subject"
                    name="subject"
                    :label="$trans('academic.subject.subject')"
                    label-prop="name"
                    value-prop="uuid"
                    :options="preRequisites.subjects"
                    v-model:error="formErrors.subject"
                />
            </div>

            <!-- Course -->
              <div class="col-span-4 sm:col-span-1">
                <BaseSelectSearch
                    v-if="fetchData.isLoaded"
                    multiple
                    name="batches"
                    :label="`Course`"
                    v-model="form.batches"
                    v-model:error="formErrors.batches"
                    value-prop="uuid"
                    :init-search="fetchData.batches"
                    search-key="course_batch"
                    search-action="academic/batch/list"
                >
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.course.name }} -
                        {{ slotProps.value.name }}
                    </template>

                    <template #listOption="slotProps">
                        {{ slotProps.option.course.nameWithTerm }} -
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>

            <div class="col-span-3 sm:col-span-1">
                <DatePicker
                    as="datetime"
                    v-model="form.startAt"
                    name="startAt"
                    :label="$trans('resource.online_class.props.start_at')"
                    no-clear
                    v-model:error="formErrors.startAt"
                />
            </div>

            <!-- Duration -->
            <div class="col-span-4 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.duration"
                    name="duration"
                    :label="$trans('resource.online_class.props.duration')"
                    v-model:error="formErrors.duration"
                />
            </div>

            <!-- <div class="col-span-4 sm:col-span-1">
                <BaseSelect
                    v-model="form.platform"
                    name="platform"
                    :label="$trans('resource.online_class.props.platform')"
                    :options="preRequisites.platforms"
                    v-model:error="formErrors.platform"
                />
            </div> -->

            <div class="col-span-4 sm:col-span-1">
                <BaseSelect
                    v-model="form.platform"
                    name="platform"
                    :label="$trans('resource.online_class.props.platform')"
                    :options="preRequisites.platforms"
                    v-model:error="formErrors.platform"
                />
            </div>
            
            <!-- <div class="col-span-4 sm:col-span-2" v-if="hasMeetingCode">
                <BaseInput
                    type="text"
                    v-model="form.meetingCode"
                    name="meetingCode"
                    :label="$trans('resource.online_class.props.meeting_code')"
                    v-model:error="formErrors.meetingCode"
                />
            </div>

            <div class="col-span-4 sm:col-span-2" v-else>
                <BaseInput
                    type="text"
                    v-model="form.url"
                    name="url"
                    :label="$trans('resource.online_class.props.url')"
                    v-model:error="formErrors.url"
                />
            </div> -->

             <div class="col-span-4 sm:col-span-2">
                <BaseInput
                    type="text"
                    v-model="form.url"
                    name="url"
                    :label="$trans('resource.online_class.props.url')"
                    v-model:error="formErrors.url"
                />
            </div>
            
            <!-- <div class="col-span-4 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.password"
                    name="password"
                    :label="$trans('resource.online_class.props.password')"
                    v-model:error="formErrors.password"
                />
            </div> -->
            
            <div class="col-span-4">
                <BaseEditor
                    v-model="form.description"
                    name="description"
                    :edit="route.params.uuid ? true : false"
                    :label="$trans('resource.online_class.props.description')"
                    v-model:error="formErrors.description"
                />
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1">
            <div class="col">
                <MediaUpload
                    multiple
                    :label="$trans('general.file')"
                    module="online_class"
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
    name: "ResourceOnlineClassForm",
}
</script>

<script setup>
import { reactive, ref } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { cloneDeep } from "@core/utils"
import { getFormErrors, getConfig } from "@core/helpers/action"
import { v4 as uuidv4 } from "uuid"

const route = useRoute()
const router = useRouter()
const store = useStore()

const initForm = {
    topic: "",
    batches: [],
    subject: "",
    platform: "",
    meetingCode: "",
    url: "",
    password: "",
    description: "",
    media: [],
    mediaUpdated: false,
    mediaToken: uuidv4(),
    mediaHash: [],
}

const initUrl = "resource/onlineClass/"
const formErrors = getFormErrors(initUrl)
const hasMeetingCode = getConfig("resource.onlineClassUseMeetingCode")
const isLoading = ref(false)

const preRequisites = reactive({
    subjects: [],
    platforms: [],
})

const state = reactive({
    selectedBatch: null,
    subjects: [],
})

const form = reactive({ ...initForm })
const fetchData = reactive({
    batches: [],
    subject: "",
    isLoaded: route.params.uuid ? false : true,
})

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const resetMediaFiles = () => {
    form.mediaToken = uuidv4()
    form.mediaHash = []
}

const setForm = (data) => {
    let batches =
        data.records.map((record) => {
            return record.batch.uuid
        }) || []

    Object.assign(initForm, {
        ...data,
        batches,
        subject: data.records[0]?.subject?.uuid || "",
        startAt: data.startAt?.at || "",
        platform: data.platform?.value || "",
    })
    Object.assign(form, cloneDeep(initForm))

    fetchData.batches = batches
    fetchData.isLoaded = true
}
</script>
