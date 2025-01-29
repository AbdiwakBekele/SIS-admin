<template>
    <FormAction
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        redirect="ConfigPushNotificationTemplate"
    >
        <div class="grid grid-cols-1 gap-6">
            <div class="col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.subject"
                    name="subject"
                    :label="
                        $trans(
                            'config.push_notification.template.props.subject'
                        )
                    "
                    v-model:error="formErrors.subject"
                    autofocus
                />
            </div>
            <div class="col-span-1">
                <BaseTextarea
                    v-model="form.content"
                    name="description"
                    :label="
                        $trans(
                            'config.push_notification.template.props.content'
                        )
                    "
                    v-model:error="formErrors.content"
                />
            </div>
            <div class="col-span-1">
                <p class="text-sm">
                    {{
                        $trans(
                            "config.push_notification.template.available_variables"
                        )
                    }}:
                    {{ form.variablesDisplay }}
                </p>
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "ConfigPushNotificationTemplateForm",
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
    variablesDisplay: "",
}

const initUrl = "config/pushNotificationTemplate/"
const formErrors = getFormErrors(initUrl)

const form = reactive({ ...initForm })
</script>
