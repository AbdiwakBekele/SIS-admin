<template>
    <FormAction
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        redirect="ConfigSMSTemplate"
    >
        <div class="grid grid-cols-1 gap-6">
            <div class="col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.subject"
                    name="subject"
                    :label="$trans('config.sms.template.props.subject')"
                    v-model:error="formErrors.subject"
                    autofocus
                />
            </div>
            <div class="col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.templateId"
                    name="templateId"
                    :label="$trans('config.sms.template.props.template_id')"
                    v-model:error="formErrors.templateId"
                />
            </div>
            <div class="col-span-1">
                <BaseTextarea
                    v-model="form.content"
                    name="description"
                    :label="$trans('config.sms.template.props.content')"
                    v-model:error="formErrors.content"
                />
            </div>
            <div class="col-span-1">
                <p class="text-sm">
                    {{ $trans("config.sms.template.available_variables") }}:
                    {{ form.variablesDisplay }}
                </p>
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "ConfigSMSTemplateForm",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute } from "vue-router"
import { getFormErrors } from "@core/helpers/action"

const route = useRoute()

const initForm = {
    subject: "",
    content: "",
    templateId: "",
    variablesDisplay: "",
}

const initUrl = "config/smsTemplate/"
const formErrors = getFormErrors(initUrl)

const form = reactive({ ...initForm })
</script>
