<template>
    <PageHeader :title="$trans(route.meta.trans, {
        attribute: $trans(route.meta.label),
    })
        " :navs="[
            { label: $trans('resource.resource'), path: 'Resource' },
            {
                label: $trans('resource.online_class.online_class'),
                path: 'ResourceOnlineClassList',
            },
        ]">
        <PageHeaderAction name="ResourceOnlineClass" :title="$trans('resource.online_class.online_classes')"
            :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <OnlineClassForm></OnlineClassForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "ResourceOnlineClassAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import OnlineClassForm from "./Form.vue"

const route = useRoute()
</script>
