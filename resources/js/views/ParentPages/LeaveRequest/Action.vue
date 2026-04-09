<template>
    <PageHeader :title="$trans(route.meta.trans, {
        attribute: $trans(route.meta.label),
    })
        " :navs="[
            {
                label: $trans('student.student'),
                path: '',
            },
            {
                label: $trans('student.leave_request.leave_request'),
                path: 'StudentLeaveRequest',
            },
        ]">
        <PageHeaderAction name="StudentLeaveRequest" :title="$trans('student.leave_request.leave_request')"
            :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <LeaveRequestForm></LeaveRequestForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "StudentLeaveRequestAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import LeaveRequestForm from "./Form.vue"

const route = useRoute()
</script>
