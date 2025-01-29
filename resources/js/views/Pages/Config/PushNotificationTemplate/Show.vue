<template>
    <ConfigPage no-background>
        <template #action>
            <PageHeaderAction
                name="ConfigPushNotificationTemplate"
                :title="$trans('config.push_notification.template.template')"
            />
        </template>

        <ParentTransition appear :visibility="true">
            <ShowItem
                :init-url="initUrl"
                :uuid="route.params.uuid"
                @setItem="setItem"
                @redirectTo="
                    router.push({ name: 'ConfigPushNotificationTemplate' })
                "
            >
                <BaseCard v-if="pushNotificationTemplate.uuid">
                    <template #title>
                        {{ pushNotificationTemplate.subject }}
                    </template>

                    <p>{{ pushNotificationTemplate.body }}</p>
                </BaseCard>
            </ShowItem>
        </ParentTransition>
    </ConfigPage>
</template>

<script>
export default {
    name: "ConfigPushNotificationTemplateShow",
}
</script>

<script setup>
import { reactive, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialPushNotificationTemplate = {}

const initUrl = "config/pushNotificationTemplate/"

const pushNotificationTemplate = reactive({
    ...initialPushNotificationTemplate,
})

const setItem = (data) => {
    Object.assign(pushNotificationTemplate, data)
}
</script>
