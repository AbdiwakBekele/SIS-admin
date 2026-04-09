<template>
    <PageHeader :title="`Add Course Assignment`" :navs="[]" />

    <ParentTransition appear :visibility="true">

        <FormAction :has-setup-wizard="false" :pre-requisites="false" @setPreRequisites="setPreRequisites"
            :init-url="initUrl" :init-form="initForm" :form="form" :set-form="setForm" @redirectTo="router.back()">
            <div class="grid grid-cols-3 gap-6">

                <!-- Title Name -->
                <!-- ToDo: Has No FormError -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" v-model="form.name" name="name" :label="`Title`" autofocus />
                </div>

                <!-- Links -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" v-model="form.links" name="links" :label="`Links`" autofocus />
                </div>

                <div class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" v-model="form.minScore" name="minScore" :label="`Min Score`" />
                </div>

                <!-- Links -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" v-model="form.maxScore" name="maxScore" :label="`Max Score`" autofocus />
                </div>

                <!-- Start Date -->
                <div class="col-span-3 sm:col-span-1">
                    <DatePicker v-model="form.startDate" name="startDate" :label="`Start Date`" no-clear
                        v-model:error="formErrors.startDate" />
                </div>

                <!-- Start Date -->
                <div class="col-span-3 sm:col-span-1">
                    <DatePicker v-model="form.startDate" name="startDate" :label="`Due Date`" no-clear
                        v-model:error="formErrors.startDate" />
                </div>

                <!-- Upload Files -->
                <div class="col-span-3 sm:col-span-1">
                    <MediaUpload :label="$trans('general.file')" module="schoolYear" :media="form.media"
                        :media-token="form.mediaToken" />
                </div>

                <div class="col-span-3">
                    <BaseEditor v-model="form.description" name="comment" :label="`Content`" />
                </div>

            </div>
        </FormAction>

    </ParentTransition>
</template>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"

const route = useRoute()
const router = useRouter()

const initForm = {
    name: "",
    courseUnit: route.query.uuidUnit,
    activityType: "",
    links: "",
    description: "",
}

const initUrl = "schoolSetup/unitTopic/"
const formErrors = getFormErrors(initUrl)

const preRequisites = reactive({
    // divisions: [],
    // gradeLevels: [],
    // courses: [],
})

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
    })
    Object.assign(form, cloneDeep(initForm))
}
</script>
