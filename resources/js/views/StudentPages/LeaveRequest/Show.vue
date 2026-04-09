<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('student.student'), path: 'Student' },
            {
                label: $trans('student.leave_request.leave_request'),
                path: 'StudentLeaveRequestList',
            },
        ]"
    >
        <PageHeaderAction
            name="StudentLeaveRequest"
            :title="$trans('student.leave_request.leave_request')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentLeaveRequest' })"
        >
            <BaseCard v-if="leaveRequest.uuid">
                <template #title>
                    {{ leaveRequest.student.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView
                        :label="$trans('student.admission.props.code_number')"
                    >
                        {{ leaveRequest.student.codeNumber }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('contact.props.birth_date')">
                        {{ leaveRequest.student.birthDate.formatted }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('contact.props.father_name')">
                        {{ leaveRequest.student.fatherName }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('contact.props.mother_name')">
                        {{ leaveRequest.student.motherName }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('academic.course.course')">
                        {{
                            leaveRequest.student.courseName +
                            " " +
                            leaveRequest.student.batchName
                        }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('student.leave_request.props.requester')"
                    >
                        {{ leaveRequest.requester.profile.name }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.period')">
                        {{ leaveRequest.period }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('student.leave_request.props.category')"
                    >
                        {{ leaveRequest.category.name }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('student.leave_request.props.reason')"
                    >
                        {{ leaveRequest.reason }}
                    </BaseDataView>
                </dl>

                <div class="mt-4 text-sm space-y-2">
                    <ListMedia
                        :media="leaveRequest.media"
                        :url="`/app/student/leave-requests/${leaveRequest.uuid}/`"
                    />
                </div>
                <template #footer>
                    <ShowButton>
                        <BaseButton
                            v-if="leaveRequest.isEditable"
                            design="primary"
                            @click="
                                router.push({
                                    name: 'StudentLeaveRequestEdit',
                                    params: { uuid: leaveRequest.uuid },
                                })
                            "
                        >
                            {{ $trans("general.edit") }}
                        </BaseButton>
                    </ShowButton>
                </template>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "StudentLeaveRequestShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform } from "@core/helpers/action"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialLeaveRequest = {}

const initUrl = "student/leaveRequest/"

const leaveRequest = reactive({ ...initialLeaveRequest })

const setItem = (data) => {
    Object.assign(leaveRequest, data)
}
</script>
