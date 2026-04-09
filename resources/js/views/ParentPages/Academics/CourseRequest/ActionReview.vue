<template>
    <PageHeader :title="`Course Request Review`">

    </PageHeader>

    <ParentTransition appear :visibility="true">
        <CourseRequestReviewFrom></CourseRequestReviewFrom>
    </ParentTransition>
</template>

<script>
export default {
    name: "CourseRequestAction",
}
</script>

<script setup>
import { useRoute } from "vue-router"
import CourseRequestReviewFrom from "./FormReview.vue"

const route = useRoute()
</script>
