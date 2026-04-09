<template>
    <FormAction
        :has-setup-wizard="true"
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        :setForm="setForm"
        redirect="AcademicBatch"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.name"
                    name="name"
                    :label="$trans('academic.batch.props.name')"
                    v-model:error="formErrors.name"
                    autofocus
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    v-model="form.course"
                    name="course"
                    :label="$trans('academic.course.course')"
                    value-prop="uuid"
                    :options="preRequisites.courses"
                    v-model:error="formErrors.course"
                >
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.nameWithTerm }}
                    </template>

                    <template #listOption="slotProps">
                        {{ slotProps.option.nameWithTerm }}
                    </template>
                </BaseSelect>
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.maxStrength"
                    name="maxStrength"
                    :label="$trans('academic.batch.props.max_strength')"
                    v-model:error="formErrors.maxStrength"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.rollNumberPrefix"
                    name="rollNumberPrefix"
                    :label="$trans('academic.batch.props.roll_number_prefix')"
                    v-model:error="formErrors.rollNumberPrefix"
                />
            </div>
            <!-- <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="number"
                    v-model="form.position"
                    name="position"
                    :label="$trans('general.position')"
                    v-model:error="formErrors.position"
                />
            </div> -->
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.pgAccount"
                    name="pgAccount"
                    :label="$trans('finance.config.props.pg_account')"
                    v-model:error="formErrors.pgAccount"
                >
                    <template #additional-label>
                        <i
                            class="fas fa-question-circle"
                            v-tooltip="
                                $trans('finance.config.props.pg_account_info')
                            "
                        ></i>
                    </template>
                </BaseInput>
            </div>
            <div class="col-span-3">
                <BaseTextarea
                    v-model="form.description"
                    name="description"
                    :label="$trans('academic.batch.props.description')"
                    v-model:error="formErrors.description"
                />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "AcademicBatchForm",
}
</script>

<script setup>
import { reactive } from "vue"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"

const initForm = {
    name: "",
    course: "",
    maxStrength: "",
    rollNumberPrefix: "",
    position: "",
    pgAccount: "",
    description: "",
}

const initUrl = "academic/batch/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    courses: [],
})

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
        course: data.course.uuid,
    })
    Object.assign(form, cloneDeep(initForm))
}
</script>
