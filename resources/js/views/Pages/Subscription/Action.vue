<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            {
                label: $trans('subscription.subscription'),
                path: 'SubscriptionList',
            },
        ]"
    >
        <PageHeaderAction
            name="Subscription"
            :title="$trans('subscription.subscription')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <SubscriptionForm></SubscriptionForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "SubscriptionAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import SubscriptionForm from "./Form.vue"

const route = useRoute()
</script>
