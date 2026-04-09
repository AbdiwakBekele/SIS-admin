<template>
    <div style="font-family: Arial, sans-serif; margin: 20px; padding: 20px; border: 2px solid black;">
        <div class="flex justify-center items-center gap-4">
            <a href="/" class="inline-block">
                <img class="h-16 w-auto" :src="getIcon" alt="Logo" />
            </a>
            <h1 class="text-3xl font-bold">
                LIGHTHOUSE EDUCATION <br>
                <span class="text-sm ">Address: 101 Bethel, Addis Ababa, Ethiopia</span>
            </h1>
        </div>

        <h1 class="text-2xl font-semibold mt-1 text-center">
            REPORT CARD
        </h1>

        <div style="margin-bottom: 20px;">
            <p><strong>Name:</strong> John Smith K.</p>
            <p><strong>Student ID:</strong> 789012</p>
            <p><strong>Birth Date:</strong> 02/15/2006</p>
            <p><strong>Address:</strong> 5678 Maple Street, AA, Ethiopia</p>
            <p><strong>Report Card Date:</strong> 02/25/2025</p>
            <p><strong>Attendance:</strong> 98%</p>
        </div>

        <!-- Report Card -->
        <div class="mb-5">
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <tr>
                    <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" rowspan="2">Course
                        Title</th>
                    <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" colspan="2">Semester I
                    </th>
                    <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" colspan="2">Semester
                        II</th>
                    <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" colspan="2">Final
                        Grade</th>
                    <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" rowspan="2">Credits
                        Earned</th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                        Numerical Grade</th>
                    <th style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                        Letter Grade</th>
                    <th style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                        Numerical Grade</th>
                    <th style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                        Letter Grade</th>
                    <th style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                        Numerical Grade</th>
                    <th style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                        Letter Grade</th>
                </tr>

                <tr>
                    <td style="border: 1px solid black; padding: 8px;">Mathematics</td>
                    <td style="border: 1px solid black; padding: 8px;">95</td>
                    <td style="border: 1px solid black; padding: 8px;">A</td>
                    <td style="border: 1px solid black; padding: 8px;">90</td>
                    <td style="border: 1px solid black; padding: 8px;">A-</td>
                    <td style="border: 1px solid black; padding: 8px;">92</td>
                    <td style="border: 1px solid black; padding: 8px;">A</td>
                    <td style="border: 1px solid black; padding: 8px;">4</td>
                </tr>

                <tr>
                    <td style="border: 1px solid black; padding: 8px;">Physics</td>
                    <td style="border: 1px solid black; padding: 8px;">88</td>
                    <td style="border: 1px solid black; padding: 8px;">B+</td>
                    <td style="border: 1px solid black; padding: 8px;">85</td>
                    <td style="border: 1px solid black; padding: 8px;">B</td>
                    <td style="border: 1px solid black; padding: 8px;">87</td>
                    <td style="border: 1px solid black; padding: 8px;">B+</td>
                    <td style="border: 1px solid black; padding: 8px;">4</td>
                </tr>

                <tr>
                    <td style="border: 1px solid black; padding: 8px;">Chemistry</td>
                    <td style="border: 1px solid black; padding: 8px;">92</td>
                    <td style="border: 1px solid black; padding: 8px;">A</td>
                    <td style="border: 1px solid black; padding: 8px;">89</td>
                    <td style="border: 1px solid black; padding: 8px;">B+</td>
                    <td style="border: 1px solid black; padding: 8px;">90</td>
                    <td style="border: 1px solid black; padding: 8px;">A-</td>
                    <td style="border: 1px solid black; padding: 8px;">4</td>
                </tr>

                <tr>
                    <td style="border: 1px solid black; padding: 8px;">Biology</td>
                    <td style="border: 1px solid black; padding: 8px;">92</td>
                    <td style="border: 1px solid black; padding: 8px;">A</td>
                    <td style="border: 1px solid black; padding: 8px;">89</td>
                    <td style="border: 1px solid black; padding: 8px;">B+</td>
                    <td style="border: 1px solid black; padding: 8px;">90</td>
                    <td style="border: 1px solid black; padding: 8px;">A-</td>
                    <td style="border: 1px solid black; padding: 8px;">4</td>
                </tr>
            </table>
        </div>

        <div style="margin-bottom: 20px;">
            <h4>Academic Summary</h4>
            <p><strong>Enrollment Date:</strong> 08/15/2021</p>
            <p><strong>Grade Level:</strong> 10</p>
            <p><strong>WGPA:</strong> 3.7</p>
        </div>

        <div style="margin-bottom: 20px;">
            <h4>Certification</h4>
            <p>I do hereby certify and affirm this report card.</p>
            <p><strong>Signature:</strong> ___________________________</p>
            <p><strong>Name & Title:</strong> Mark Thompson, Principal</p>
            <p><strong>Date:</strong> 02/25/2025</p>
        </div>
    </div>

</template>

<script>
export default {
    name: "AcademicDivisionReport",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
import { getConfig } from "@core/helpers/action"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]
if (perform("division:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("division:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const getIcon =
    getConfig("layout.display").value == "dark"
        ? getConfig("assets.iconLight")
        : getConfig("assets.icon")

const initUrl = "schoolSetup/division/"
const showFilter = ref(false)
const showReorderModal = ref(false)

const divisions = reactive({})

const setItems = (data) => {
    Object.assign(divisions, data)
}
</script>
