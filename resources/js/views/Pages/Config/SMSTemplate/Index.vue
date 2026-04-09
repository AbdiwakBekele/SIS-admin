<template>
    <ConfigPage no-background>
        <template #action>
            <PageHeaderAction
                name="ConfigSMSTemplate"
                :title="$trans('config.sms.template.template')"
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
                    :header="smsTemplates.headers"
                    :meta="smsTemplates.meta"
                    module="config.sms.template"
                    @refresh="emitter.emit('listItems')"
                >
                    <DataRow
                        v-for="smsTemplate in smsTemplates.data"
                        :key="smsTemplate.uuid"
                        @doubleClick="showDetail(smsTemplate)"
                    >
                        <DataCell
                            name="name"
                            clickable
                            @click="showDetail(smsTemplate)"
                        >
                            <span class="font-medium text-blue-700 hover:underline">
                                {{ smsTemplate.name }}
                            </span>
                            <BaseBadge
                                v-if="!smsTemplate.enabledAt.value"
                                design="danger"
                                >{{
                                    $trans("config.template.statuses.disabled")
                                }}</BaseBadge
                            >
                        </DataCell>
                        <DataCell name="templateId">
                            {{ smsTemplate.templateId || "-" }}
                        </DataCell>
                        <DataCell name="subject">
                            {{ smsTemplate.subject }}

                            <TextMuted block>{{
                                smsTemplate.content
                            }}</TextMuted>
                        </DataCell>
                        <DataCell name="action">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    v-if="smsTemplate.enabledAt.value"
                                    icon="far fa-times-circle"
                                    @click="toggleStatus(smsTemplate)"
                                    >{{
                                        $trans("global.disable", {
                                            attribute: $trans(
                                                "config.template.template"
                                            ),
                                        })
                                    }}</FloatingMenuItem
                                >
                                <FloatingMenuItem
                                    v-if="!smsTemplate.enabledAt.value"
                                    icon="far fa-check-circle"
                                    @click="toggleStatus(smsTemplate)"
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
                                    @click="showDetail(smsTemplate)"
                                    >{{
                                        $trans("general.show")
                                    }}</FloatingMenuItem
                                >
                                <FloatingMenuItem
                                    icon="fas fa-edit"
                                    @click="
                                        router.push({
                                            name: 'ConfigSMSTemplateEdit',
                                            params: {
                                                uuid: smsTemplate.uuid,
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
    name: "ConfigSMSTemplateList",
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

const initUrl = "config/smsTemplate/"
const showFilter = ref(false)
const showDetailModal = ref(false)

const smsTemplates = reactive({})
const state = reactive({
    selectedTemplate: null,
})

const setItems = (data) => {
    Object.assign(smsTemplates, data)
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
