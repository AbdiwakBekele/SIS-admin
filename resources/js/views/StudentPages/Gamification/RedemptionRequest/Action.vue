<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans('gamification.redemption_request.redemption_request') })"
        :navs="[
            { label: $trans('gamification.gamification'), path: { name: '' } },
            { label: $trans('gamification.redemption_request.redemption_requests'), path: { name: 'RedemptionRequestList' } },
        ]"
    >
        <PageHeaderAction
            name="RedemptionRequestList"
            :title="$trans('gamification.redemption_request.redemption_requests')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <MyStudentRedemptionRequestsForm></MyStudentRedemptionRequestsForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "MyStudentRedemptionRequestsAction",
}
</script>

<script setup>
import { useRoute } from "vue-router";
import MyStudentRedemptionRequestsForm from "./Form.vue";

const route = useRoute();
</script>