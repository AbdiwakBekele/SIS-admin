<template>
    <FormAction
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        :set-form="setForm"
        redirect="CustomField"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    v-model="form.form"
                    name="form"
                    :options="preRequisites.forms"
                    :label="$trans('custom_field.props.form')"
                    v-model:error="formErrors.form"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    v-model="form.type"
                    name="type"
                    :options="preRequisites.types"
                    :label="$trans('custom_field.props.type')"
                    v-model:error="formErrors.type"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSwitch
                    vertical
                    v-model="form.isRequired"
                    name="is_required"
                    :label="$trans('custom_field.props.is_required')"
                    v-model:error="formErrors.isRequired"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.label"
                    name="label"
                    :label="$trans('custom_field.props.label')"
                    v-model:error="formErrors.label"
                />
            </div>
            <template
                v-if="
                    form.type == 'text_input' ||
                    form.type == 'multi_line_text_input'
                "
            >
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.minLength"
                        name="minLength"
                        :label="$trans('custom_field.props.min_length')"
                        v-model:error="formErrors.minLength"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.maxLength"
                        name="maxLength"
                        :label="$trans('custom_field.props.max_length')"
                        v-model:error="formErrors.maxLength"
                    />
                </div>
            </template>
            <template
                v-if="
                    form.type == 'number_input' || form.type == 'currency_input'
                "
            >
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="number"
                        v-model="form.minValue"
                        name="minValue"
                        :label="$trans('custom_field.props.min_value')"
                        v-model:error="formErrors.minValue"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="number"
                        v-model="form.maxValue"
                        name="maxValue"
                        :label="$trans('custom_field.props.max_value')"
                        v-model:error="formErrors.maxValue"
                    />
                </div>
            </template>
            <template
                v-if="
                    form.type == 'select_input' ||
                    form.type == 'multi_select_input' ||
                    form.type == 'checkbox_input' ||
                    form.type == 'radio_input'
                "
            >
                <div class="col-span-3 sm:col-span-2">
                    <BaseInput
                        type="text"
                        v-model="form.options"
                        name="options"
                        :label="$trans('custom_field.props.options')"
                        v-model:error="formErrors.options"
                        :label-hint="$trans('custom_field.option_info')"
                    />
                </div>
            </template>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="number"
                    v-model="form.position"
                    name="position"
                    :label="$trans('custom_field.props.position')"
                    v-model:error="formErrors.position"
                />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "CustomFieldForm",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute } from "vue-router"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"

const route = useRoute()

const initForm = {
    form: "",
    type: "",
    label: "",
    minLength: "",
    maxLength: "",
    minValue: "",
    maxValue: "",
    options: "",
    isRequired: false,
    position: 0,
}

const initUrl = "customField/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    forms: [],
    types: [],
})

const form = reactive({ ...initForm })
const fetchData = reactive({
    isLoaded: route.params.uuid ? false : true,
})

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
    Object.assign(form, cloneDeep(initForm))
}

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
        form: data.form.value,
        type: data.type.value,
    })
    Object.assign(form, cloneDeep(initForm))

    fetchData.isLoaded = true
}
</script>
