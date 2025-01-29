<template>
    <ConfigPage no-background>
        <template #action>
            <PageHeaderAction
                name="ConfigSMSTemplate"
                :title="$trans('config.sms.template.template')"
                :actions="['list']"
            />
        </template>

        <ParentTransition appear :visibility="true">
            <SMSTemplateForm></SMSTemplateForm>
        </ParentTransition>
    </ConfigPage>
</template>

<script>
export default {
    name: "ConfigSMSTemplateAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import SMSTemplateForm from "./Form.vue"

const route = useRoute()
</script>
