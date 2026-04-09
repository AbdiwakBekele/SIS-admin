<template>
    <h1> <strong>Degree Audit:</strong> John Smith K.</h1>
    <span> <strong>Enrollment Type:</strong> Honored/Gifted</span>

    <table style="width: 100%; border-collapse: collapse; text-align: center;">
        <tr>
            <th colspan="3" style="background-color: #fcd9b0; padding: 10px; font-size: 18px; font-weight: bold;">
                9th GRADE Enrollment <br> Year 2025-2026 <br> Semester 1
            </th>
            <th colspan="3" style="background-color: #fcedb0; padding: 10px; font-size: 18px; font-weight: bold;">
                9th GRADE Enrollment <br> Year 2025-2026 <br> Semester 2
            </th>
        </tr>

        <tr style="background-color: #f5e0c3; font-weight: bold;">
            <td style="border: 1px solid black; padding: 8px;">Planned Courses</td>
            <td style="border: 1px solid black; padding: 8px;">Student Course Request</td>
            <td style="border: 1px solid black; padding: 8px;">FINAL COURSE ENROLLMENT</td>
            <td style="border: 1px solid black; padding: 8px;">Planned Courses</td>
            <td style="border: 1px solid black; padding: 8px;">Student Course Request</td>
            <td style="border: 1px solid black; padding: 8px;">FINAL COURSE ENROLLMENT</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px;">Honors Algebra</td>
            <td style="border: 1px solid black; padding: 8px;">Honors Algebra</td>
            <td style="border: 1px solid black; padding: 8px;">Honors Algebra</td>
            <td style="border: 1px solid black; padding: 8px;">Honors Algebra</td>
            <td style="border: 1px solid black; padding: 8px;">Honors Algebra</td>
            <td style="border: 1px solid black; padding: 8px;">Honors Algebra</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px;">Honors Biology</td>
            <td style="border: 1px solid black; padding: 8px;">Physical Science</td>
            <td style="border: 1px solid black; padding: 8px;">Honors Biology</td>
            <td style="border: 1px solid black; padding: 8px;">Honors Biology</td>
            <td style="border: 1px solid black; padding: 8px;">Honors Biology</td>
            <td style="border: 1px solid black; padding: 8px;">Honors Biology</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px;">English 9</td>
            <td style="border: 1px solid black; padding: 8px;">DE English Composition</td>
            <td style="border: 1px solid black; padding: 8px;">DE English Composition</td>
            <td style="border: 1px solid black; padding: 8px;">DE English Composition</td>
            <td style="border: 1px solid black; padding: 8px;">DE English Composition</td>
            <td style="border: 1px solid black; padding: 8px;">DE English Composition</td>
        </tr>
    </table>

</template>

<script>
export default {
    name: "CourseRequestShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform } from "@core/helpers/action"
import ConfigForm from "./ConfigForm.vue"

// const store = useStore()
// const route = useRoute()
// const router = useRouter()

// const initialBatch = {}

// const initUrl = "academic/batch/"

// const batch = reactive({ ...initialBatch })

// const setItem = (data) => {
//     Object.assign(batch, data)
// }
</script>
