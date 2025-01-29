<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('custom_field.custom_field'), path: 'CustomField' },
        ]"
    >
        <PageHeaderAction
            name="CustomField"
            :title="$trans('custom_field.custom_field')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <CustomFieldForm></CustomFieldForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "CustomFieldAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import CustomFieldForm from "./Form.vue"

const route = useRoute()
</script>
