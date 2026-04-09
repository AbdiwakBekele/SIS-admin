<template>
    <PageHeader :title="$trans(route.meta.trans, {
        attribute: $trans(route.meta.label),
    })
        " :navs="[
            {
                label: $trans('student.student'),
                path: 'StudentProfile',
            },
        ]">
        <PageHeaderAction name="StudentProfile" :title="$trans('student.student')" :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <StudentProfileForm></StudentProfileForm>
    </ParentTransition>
</template>

<script>
export default {
    name: "StudentProfileAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import StudentProfileForm from "./Form.vue"

const route = useRoute()
</script>
