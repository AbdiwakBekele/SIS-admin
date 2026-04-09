<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('student.transfer_request.transfer_request')"
                :navs="[{ label: $trans('student.student'), path: '' }]">
                <PageHeaderAction url="student/transfer-requests/" name="StudentTransferRequest"
                    :title="$trans('student.transfer_request.transfer_request')" :actions="userActions"
                    :dropdown-actions="dropdownActions" @toggleFilter="showFilter = !showFilter" />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="transferRequests.headers" :meta="transferRequests.meta"
                module="student.transfer_request" @refresh="emitter.emit('listItems')">
                <DataRow v-for="transferRequest in transferRequests.data" :key="transferRequest.uuid" @double-click="
                    router.push({
                        name: 'StudentTransferRequestShow',
                        params: {
                            uuid: transferRequest.uuid,
                        },
                    })
                    ">
                    <DataCell name="codeNumber">
                        {{ transferRequest.codeNumber }}
                    </DataCell>
                    <DataCell name="name">
                        {{ transferRequest.student.name }}
                        <TextMuted block>{{
                            transferRequest.student.contactNumber
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="parent">
                        {{ transferRequest.student.fatherName }}
                        <TextMuted block>{{
                            transferRequest.student.motherName
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="admissionDate">
                        {{ transferRequest.student.joiningDate.formatted }}
                        <TextMuted block>{{
                            transferRequest.student.codeNumber
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="course">
                        {{ transferRequest.student.courseName }}
                        <TextMuted block>{{
                            transferRequest.student.batchName
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="requestDate">
                        {{ transferRequest.requestDate.formatted }}
                    </DataCell>
                    <DataCell name="status">
                        <BaseBadge :design="transferRequest.status.color">
                            {{ transferRequest.status.label }}
                        </BaseBadge>
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ transferRequest.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'StudentTransferRequestShow',
                                    params: { uuid: transferRequest.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem icon="fas fa-edit" v-if="transferRequest.isEditable" @click="
                                router.push({
                                    name: 'StudentTransferRequestEdit',
                                    params: { uuid: transferRequest.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem icon="fas fa-trash" v-if="transferRequest.isEditable" @click="
                                emitter.emit('deleteItem', {
                                    uuid: transferRequest.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton @click="
                        router.push({
                            name: 'StudentTransferRequestCreate',
                        })
                        ">
                        {{
                            $trans("global.add", {
                                attribute: $trans("student.transfer.transfer"),
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
    name: "StudentTransferRequestList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import FilterForm from "./Filter.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["create", "filter"]

let dropdownActions = ["print", "pdf", "excel"]

const initUrl = "student/transferRequest/"
const showFilter = ref(false)

const transferRequests = reactive({})

const setItems = (data) => {
    Object.assign(transferRequests, data)
}
</script>
