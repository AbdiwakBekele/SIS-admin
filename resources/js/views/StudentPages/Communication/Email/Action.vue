<template>
    <PageHeader :title="$trans(route.meta.trans, {
        attribute: $trans(route.meta.label),
    })
        " :navs="[
            {
                label: $trans('communication.communication'),
                path: 'Communication',
            },
            {
                label: $trans('communication.email.email'),
                path: 'CommunicationEmailList',
            },
        ]">
        <PageHeaderAction name="CommunicationEmail" :title="$trans('communication.email.emails')" :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <EmailForm></EmailForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "CommunicationEmailAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import EmailForm from "./Form.vue"

const route = useRoute()
</script>
