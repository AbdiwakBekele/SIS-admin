<template>
    <ConfigPage no-background>
        <template #action>
            <PageHeaderAction
                name="ConfigSMSTemplate"
                :title="$trans('config.sms.template.template')"
            />
        </template>

        <ParentTransition appear :visibility="true">
            <ShowItem
                :init-url="initUrl"
                :uuid="route.params.uuid"
                @setItem="setItem"
                @redirectTo="router.push({ name: 'ConfigSMSTemplate' })"
            >
                <BaseCard v-if="smsTemplate.uuid">
                    <template #title>
                        {{ smsTemplate.subject }}
                    </template>

                    <p>{{ smsTemplate.content }}</p>
                </BaseCard>
            </ShowItem>
        </ParentTransition>
    </ConfigPage>
</template>

<script>
export default {
    name: "ConfigSMSTemplateShow",
}
</script>

<script setup>
import { reactive, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialSMSTemplate = {}

const initUrl = "config/smsTemplate/"

const smsTemplate = reactive({
    ...initialSMSTemplate,
})

const setItem = (data) => {
    Object.assign(smsTemplate, data)
}
</script>
