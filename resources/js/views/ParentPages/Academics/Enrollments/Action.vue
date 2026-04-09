<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('academic.academic'), path: 'Academic' },
            {
                label: $trans('academic.batch.batch'),
                path: 'AcademicBatchList',
            },
        ]"
    >
        <PageHeaderAction
            name="AcademicBatch"
            :title="$trans('academic.batch.batch')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <BatchForm></BatchForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "AcademicBatchAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import BatchForm from "./Form.vue"

const route = useRoute()
</script>
