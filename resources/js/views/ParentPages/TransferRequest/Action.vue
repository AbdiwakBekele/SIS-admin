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
                label: $trans('student.transfer_request.transfer_request'),
                path: 'StudentTransferRequest',
            },
        ]">
        <PageHeaderAction name="StudentTransferRequest" :title="$trans('student.transfer_request.transfer_requests')"
            :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <TransferRequestForm></TransferRequestForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "StudentTransferRequestAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import TransferRequestForm from "./Form.vue"

const route = useRoute()
</script>
