<template>
    <ConfigPage no-background>
        <template #action>
            <PageHeaderAction
                name="ConfigPushNotificationTemplate"
                :title="$trans('config.push_notification.template.template')"
                :actions="['filter']"
                @toggleFilter="showFilter = !showFilter"
            />
        </template>

        <ListItem class="sm:-mt-4" :init-url="initUrl" @setItems="setItems">
            <template #filter>
                <ParentTransition appear :visibility="showFilter">
                    <FilterForm
                        @refresh="emitter.emit('listItems')"
                        @hide="showFilter = false"
                    ></FilterForm>
                </ParentTransition>
            </template>

            <ParentTransition appear :visibility="true">
                <DataTable
                    :header="pushNotificationTemplates.headers"
                    :meta="pushNotificationTemplates.meta"
                    module="config.push_notification.template"
                    @refresh="emitter.emit('listItems')"
                >
                    <DataRow
                        v-for="pushNotificationTemplate in pushNotificationTemplates.data"
                        :key="pushNotificationTemplate.uuid"
                        @doubleClick="showDetail(pushNotificationTemplate)"
                    >
                        <DataCell
                            name="name"
                            clickable
                            @click="showDetail(pushNotificationTemplate)"
                        >
                            <span class="font-medium text-blue-700 hover:underline">
                                {{ pushNotificationTemplate.name }}
                            </span>
                            <BaseBadge
                                v-if="!pushNotificationTemplate.enabledAt.value"
                                design="danger"
                                >{{
                                    $trans("config.template.statuses.disabled")
                                }}</BaseBadge
                            >
                        </DataCell>
                        <DataCell name="subject">
                            {{ pushNotificationTemplate.subject }}

                            <TextMuted block>{{
                                pushNotificationTemplate.content
                            }}</TextMuted>
                        </DataCell>
                        <DataCell name="action">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    v-if="
                                        pushNotificationTemplate.enabledAt.value
                                    "
                                    icon="far fa-times-circle"
                                    @click="
                                        toggleStatus(pushNotificationTemplate)
                                    "
                                    >{{
                                        $trans("global.disable", {
                                            attribute: $trans(
                                                "config.template.template"
                                            ),
                                        })
                                    }}</FloatingMenuItem
                                >
                                <FloatingMenuItem
                                    v-if="
                                        !pushNotificationTemplate.enabledAt
                                            .value
                                    "
                                    icon="far fa-check-circle"
                                    @click="
                                        toggleStatus(pushNotificationTemplate)
                                    "
                                    >{{
                                        $trans("global.enable", {
                                            attribute: $trans(
                                                "config.template.template"
                                            ),
                                        })
                                    }}</FloatingMenuItem
                                >
                                <FloatingMenuItem
                                    icon="fas fa-arrow-circle-right"
                                    @click="
                                        showDetail(pushNotificationTemplate)
                                    "
                                    >{{
                                        $trans("general.show")
                                    }}</FloatingMenuItem
                                >
                                <FloatingMenuItem
                                    icon="fas fa-edit"
                                    @click="
                                        router.push({
                                            name: 'ConfigPushNotificationTemplateEdit',
                                            params: {
                                                uuid: pushNotificationTemplate.uuid,
                                            },
                                        })
                                    "
                                    >{{
                                        $trans("general.edit")
                                    }}</FloatingMenuItem
                                >
                            </FloatingMenu>
                        </DataCell>
                    </DataRow>
                    <template #actionButton> </template>
                </DataTable>
            </ParentTransition>
        </ListItem>
    </ConfigPage>

    <BaseModal :show="showDetailModal" @close="showDetailModal = false">
        <template #title>
            {{
                $trans("global.detail", {
                    attribute: $trans("config.sms.template.template"),
                })
            }}
        </template>

        <div class="mb-4" v-if="state.selectedTemplate">
            {{ state.selectedTemplate.content }}
        </div>
    </BaseModal>
</template>

<script>
export default {
    name: "ConfigPushNotificationTemplateList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import FilterForm from "./Filter.vue"

const router = useRouter()
const store = useStore()

const emitter = inject("emitter")

const initUrl = "config/pushNotificationTemplate/"
const showFilter = ref(false)
const showDetailModal = ref(false)

const pushNotificationTemplates = reactive({})
const state = reactive({
    selectedTemplate: null,
})

const setItems = (data) => {
    Object.assign(pushNotificationTemplates, data)
}

const showDetail = (template) => {
    state.selectedTemplate = template
    showDetailModal.value = true
}

const toggleStatus = async (template) => {
    emitter.emit("actionItem", {
        uuid: template.uuid,
        action: "toggleStatus",
        confirmation: true,
    })
}
</script>
