<template>
    <PageHeader :title="`Email`" :navs="[]" />

    <ParentTransition appear :visibility="true">

        <FormAction :has-setup-wizard="false" :pre-requisites="false" @setPreRequisites="setPreRequisites"
            :init-url="initUrl" :init-form="initForm" :form="form" :set-form="setForm" @redirectTo="router.back()">

            <div class="grid grid-cols-3 gap-6">

                <!-- Title Name -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" v-model="form.name" name="name" :label="`Title`" autofocus />
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
