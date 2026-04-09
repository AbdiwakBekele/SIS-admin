<template>
    <PageHeader :title="$trans(route.meta.trans, {
        attribute: $trans(route.meta.label),
    })
        " :navs="[

        ]">
        <PageHeaderAction name="CourseRequest"
            :title="$trans('academics.academic_course_planner.course_request.course_request')" :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <CourseRequestFrom></CourseRequestFrom>
    </ParentTransition>
</template>

<script>
export default {
    name: "CourseRequestAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import CourseRequestFrom from "./Form.vue"

const route = useRoute()
</script>
