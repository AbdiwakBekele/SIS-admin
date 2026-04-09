<template>
    <PageHeader :title="$trans(route.meta.trans, {
        attribute: $trans(route.meta.label),
    })
        " :navs="[
            { label: $trans('student.student'), path: '' },
            {
                label: $trans('student.withdrawal.withdrawal'),
                path: 'WithdrawalList',
            },
        ]">
        <PageHeaderAction name="WithdrawalType" :title="$trans('student.withdrawal.withdrawal')" :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <WithdrawalFrom></WithdrawalFrom>
    </ParentTransition>
</template>

<script>
export default {
    name: "WithdrawalAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import WithdrawalFrom from "./Form.vue"

const route = useRoute()
</script>
