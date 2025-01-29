<template>
    <ConfigPage no-background>
        <template #action>
            <PageHeaderAction
                name="ConfigPushNotificationTemplate"
                :title="$trans('config.push_notification.template.template')"
                :actions="['list']"
            />
        </template>

        <ParentTransition appear :visibility="true">
            <PushNotificationTemplateForm></PushNotificationTemplateForm>
        </ParentTransition>
    </ConfigPage>
</template>

<script>
export default {
    name: "ConfigPushNotificationTemplateAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import PushNotificationTemplateForm from "./Form.vue"

const route = useRoute()
</script>
