<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>

            <PageHeader
                :title="$trans('student.leave_request.leave_request')"
                :navs="[{
                    label: $trans('student.student'),
                    path: '',
                }]"
            >
                <PageHeaderAction
                    url="student/leave-requests/"
                    name="StudentLeaveRequest"
                    :title="$trans('student.leave_request.leave_request')"
                    :actions="userActions"
                    :dropdown-actions="dropdownActions"
                    @toggleFilter="showFilter = !showFilter"
                />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="leaveRequests.headers" :meta="leaveRequests.meta" module="student.leave_request"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="leaveRequest in leaveRequests.data" :key="leaveRequest.uuid" @double-click="
                    router.push({
                        name: 'StudentLeaveRequestShow',
                        params: {
                            uuid: leaveRequest.uuid,
                        },
                    })
                    ">
                    <DataCell name="name">
                        {{ leaveRequest.student.name }}
                        <TextMuted block>{{
                            leaveRequest.student.contactNumber
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="parent">
                        {{ leaveRequest.student.fatherName }}
                        <TextMuted block>{{
                            leaveRequest.student.motherName
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="admissionDate">
                        {{ leaveRequest.student.joiningDate.formatted }}
                        <TextMuted block>{{
                            leaveRequest.student.codeNumber
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="course">
                        {{ leaveRequest.student.courseName }}
                        <TextMuted block>{{
                            leaveRequest.student.batchName
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="period">
                        {{ leaveRequest.period }}
                    </DataCell>
                    <DataCell name="category">
                        {{ leaveRequest.category.name }}
                    </DataCell>
                    <!-- <DataCell name="status">
                        <BaseBadge :design="leaveRequest.status.color">
                            {{ leaveRequest.status.label }}
                        </BaseBadge>
                    </DataCell> -->
                    <DataCell name="createdAt">
                        {{ leaveRequest.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'StudentLeaveRequestShow',
                                    params: { uuid: leaveRequest.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem icon="fas fa-edit" v-if="leaveRequest.isEditable" @click="
                                router.push({
                                    name: 'StudentLeaveRequestEdit',
                                    params: { uuid: leaveRequest.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem icon="fas fa-trash" v-if="leaveRequest.isEditable" @click="
                                emitter.emit('deleteItem', {
                                    uuid: leaveRequest.uuid,
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
                            name: 'StudentLeaveRequestCreate',
                        })
                        ">
                        {{
                            $trans("global.add", {
                                attribute: $trans(
                                    "student.leave_request.leave_request"
                                ),
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
    name: "StudentLeaveRequestList",
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

const initUrl = "student/leaveRequest/"
const showFilter = ref(false)

const leaveRequests = reactive({})

const setItems = (data) => {
    Object.assign(leaveRequests, data)
}
</script>
