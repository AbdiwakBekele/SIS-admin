<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('subscriber.subscriber'), path: 'SubscriberList' },
        ]"
    >
        <PageHeaderAction
            name="Subscriber"
            :title="$trans('subscriber.subscriber')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <SubscriberForm></SubscriberForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "SubscriberAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import SubscriberForm from "./Form.vue"

const route = useRoute()
</script>
