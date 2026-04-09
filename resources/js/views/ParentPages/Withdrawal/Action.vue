<template>
    <PageHeader :title="$trans(route.meta.trans, {
        attribute: $trans(route.meta.label),
    })
        " :navs="[
            {
                label: `Withdrawal Form`,
                path: 'ParentWithdrawalList',
            },
        ]">
        <PageHeaderAction name="ParentWithdrawal" :title="$trans('student.withdrawal.withdrawal')" :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ParentWithdrawalForm></ParentWithdrawalForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentWithdrawalAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import ParentWithdrawalForm from "./Form.vue"

const route = useRoute()
</script>
