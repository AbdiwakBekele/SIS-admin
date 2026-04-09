<template>
    <ListItem :init-url="initUrl" :additional-query="{}" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('communication.email.email')" :navs="[
                {
                    label: $trans('communication.communication'),
                    path: 'Communication',
                },
            ]">
                <PageHeaderAction url="communication/emails/" name="CommunicationEmail"
                    :title="$trans('communication.email.email')" :actions="userActions"
                    :dropdown-actions="dropdownActions" config-path="CommunicationConfig"
                    @toggleFilter="showFilter = !showFilter" />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="emails.headers" :meta="emails.meta" module="communication.email"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="email in emails.data" :key="email.uuid" @double-click="
                    router.push({
                        name: 'CommunicationEmailShow',
                        params: { uuid: email.uuid },
                    })
                    ">
                    <DataCell name="subject">
                        {{ email.subject_excerpt }}
                    </DataCell>
                    <DataCell name="audience">
                        <div v-for="audienceType in email.audienceTypes">
                            {{ audienceType }}
                        </div>
                    </DataCell>
                    <DataCell name="recipientCount">
                        {{ email.recipient_count }}
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ email.created_at.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'CommunicationEmailShow',
                                    params: { uuid: email.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('email:send')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'CommunicationEmailDuplicate',
                                    params: { uuid: email.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('email:send')" @click="
                        router.push({
                            name: 'CommunicationEmailNew',
                        })
                        ">
                        {{
                            $trans("global.new", {
                                attribute: $trans("communication.email.email"),
                            })
                        }}
                    </BaseButton>
                </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "CommunicationEmailList",
}
</script>

<script setup>
import { ref, reactive, inject, watch } from "vue"
import { useRouter } from "vue-router"
import { perform, actingAs } from "@core/helpers/action"
import FilterForm from "./Filter.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]

if (perform("email:send")) {
    userActions.unshift("new")
}

let dropdownActions = []
if (perform("email:read")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "communication/email/"
const showFilter = ref(false)

const emails = reactive({})

// Watcher to redirect if the email list is empty
watch(
    () => emails.data,
    (newData) => {
        if (Array.isArray(newData) && newData.length === 0) {
            router.push({ name: "CommunicationEmailNew" })
        }
    }
)

const setItems = (data) => {
    Object.assign(emails, data)
}
</script>

<!-- 
<template>
    <ListItem :init-url="initUrl" :additional-query="{}" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('communication.email.email')" :navs="[
                {
                    label: $trans('communication.communication'),
                    path: 'Communication',
                },
            ]">
                <PageHeaderAction url="communication/emails/" name="CommunicationEmail"
                    :title="$trans('communication.email.email')" :actions="userActions"
                    :dropdown-actions="dropdownActions" config-path="CommunicationConfig"
                    @toggleFilter="showFilter = !showFilter" />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="emails.headers" :meta="emails.meta" module="communication.email"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="email in emails.data" :key="email.uuid" @double-click="
                    router.push({
                        name: 'CommunicationEmailShow',
                        params: { uuid: email.uuid },
                    })
                    ">
                    <DataCell name="subject">
                        {{ email.subject_excerpt }}
                    </DataCell>
                    <DataCell name="audience">
                        <div v-for="audienceType in email.audience_types">
                            {{ audienceType }}
                        </div>
                    </DataCell>
                    <DataCell name="recipientCount">
                        {{ email.recipient_count }}
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ email.created_at.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'CommunicationEmailShow',
                                    params: { uuid: email.uuid },
                                })
                                ">{{ $trans('global.show') }}</FloatingMenuItem>
                            <FloatingMenuItem icon="fas fa-edit" @click="
                                router.push({
                                    name: 'CommunicationEmailEdit',
                                    params: { uuid: email.uuid },
                                })
                                ">{{ $trans('global.edit') }}</FloatingMenuItem>
                            <FloatingMenuItem icon="fas fa-copy" @click="
                                router.push({
                                    name: 'CommunicationEmailDuplicate',
                                    params: { uuid: email.uuid },
                                })
                                ">{{ $trans('global.duplicate') }}</FloatingMenuItem>
                            <FloatingMenuItem icon="fas fa-trash" @click="deleteEmail(email.uuid)">
                                {{ $trans('global.delete') }}
                            </FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton @click="router.push({ name: 'CommunicationEmailNew' })">
                        {{ $trans('global.new', { attribute: $trans('communication.email.email') }) }}
                    </BaseButton>
                </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "CommunicationEmailList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import FilterForm from "./Filter.vue"
import axios from "axios"

const router = useRouter()
const emitter = inject("emitter")

const userActions = ["new", "filter"]
const dropdownActions = ["print", "pdf", "excel"]

const initUrl = "communication/email/"
const showFilter = ref(false)
const emails = reactive({})

const setItems = (data) => {
    Object.assign(emails, data)
}

const deleteEmail = async (uuid) => {
    if (confirm($trans('global.confirm_delete', { attribute: $trans('communication.email.email') }))) {
        try {
            await axios.delete(`/communication/email/${uuid}`)
            emitter.emit('listItems')
        } catch (error) {
            alert($trans('global.error', { attribute: $trans('communication.email.email') }))
        }
    }
}
</script> -->