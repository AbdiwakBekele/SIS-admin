<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[{ label: $trans('plan.plan'), path: 'PlanList' }]"
    >
        <PageHeaderAction
            name="Plan"
            :title="$trans('plan.plan')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <PlanForm></PlanForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "PlanAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import PlanForm from "./Form.vue"

const route = useRoute()
</script>
