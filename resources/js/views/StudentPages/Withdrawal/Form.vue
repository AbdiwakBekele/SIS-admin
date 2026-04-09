<template>
    <FormAction
        :has-setup-wizard="false"
        :pre-requisites="false"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        :set-form="setForm"
        redirect="Withdrawal"
    >
        <div class="grid grid-cols-3 gap-6">

            <!--  Name -->
             <!-- ToDo: Has No FormError -->
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.name"
                    name="name"
                    :label="$trans('student.withdrawal.props.title')"
                    autofocus
                />
            </div>

            <!--  Code -->
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.code"
                    name="code"
                    :label="$trans('student.withdrawal.props.code')"
                />
            </div>
           

            <!-- Description -->
            <div class="col-span-3">
                <BaseTextarea
                    v-model="form.description"
                    name="description"
                    :label="$trans('student.withdrawal.props.description')"
                />
            </div>

        </div>
    </FormAction>
</template>

<script>
export default {
    name: "WithdrawalForm",
}
</script>

<script setup>
import { reactive } from "vue"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"

const initForm = {
    name: "",
    code: "",
    description: "",
}

const initUrl = "student/withdrawal/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    // programs: [],
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
