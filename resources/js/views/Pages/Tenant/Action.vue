<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[{ label: $trans('tenant.tenant'), path: 'TenantList' }]"
    >
        <PageHeaderAction
            name="Tenant"
            :title="$trans('tenant.tenant')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <TenantForm></TenantForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "TenantAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import TenantForm from "./Form.vue"

const route = useRoute()
</script>
