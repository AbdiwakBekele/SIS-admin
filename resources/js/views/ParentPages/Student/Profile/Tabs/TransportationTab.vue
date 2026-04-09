<template>
    <!-- Academic Info (2024) -->
    <div v-if="false" class="bg-white rounded-lg border-r px-4 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <i class="fa-solid fa-calendar-days mx-2"></i>
            <strong>Report card for 2024</strong>

            <button class="bg-blue-500 px-2 py-1 rounded text-white text-sm mb-3" style="float:right" @click="
                router.push({
                    name: 'StudentGradebookReport',
                    params: { uuid: student.uuid },
                })
                "> <i class="fa-solid fa-print"></i>
            </button>

        </p>
        <hr class="mx-4">

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <tr>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" rowspan="2">Course Title
                </th>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" colspan="2">Semester I
                </th>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" colspan="2">Semester II
                </th>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" colspan="2">Final Grade
                </th>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" rowspan="2">Credits Earned
                </th>
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
                <td style="border: 1px solid black; padding: 8px;">Biology</td>
                <td style="border: 1px solid black; padding: 8px;">92</td>
                <td style="border: 1px solid black; padding: 8px;">A</td>
                <td style="border: 1px solid black; padding: 8px;">89</td>
                <td style="border: 1px solid black; padding: 8px;">B+</td>
                <td style="border: 1px solid black; padding: 8px;">90</td>
                <td style="border: 1px solid black; padding: 8px;">A-</td>
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
        </table>
    </div>


    <!-- Academic Info (2023) -->
    <div v-if="false" class="bg-white rounded-lg border-r px-4 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <i class="fa-solid fa-calendar-days mx-2"></i>
            <strong>Report card for 2023</strong>

            <button class="bg-blue-500 px-2 py-1 rounded text-white text-sm mb-3" style="float:right" @click="
                router.push({
                    name: 'StudentGradebookReport',
                    params: { uuid: student.uuid },
                })
                "> <i class="fa-solid fa-print"></i>
            </button>
        </p>
        <hr class="mx-4">

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <tr>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" rowspan="2">Course Title
                </th>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" colspan="2">Semester I
                </th>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" colspan="2">Semester II
                </th>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" colspan="2">Final Grade
                </th>
                <th style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;" rowspan="2">Credits Earned
                </th>
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
                <td style="border: 1px solid black; padding: 8px;">Biology</td>
                <td style="border: 1px solid black; padding: 8px;">92</td>
                <td style="border: 1px solid black; padding: 8px;">A</td>
                <td style="border: 1px solid black; padding: 8px;">89</td>
                <td style="border: 1px solid black; padding: 8px;">B+</td>
                <td style="border: 1px solid black; padding: 8px;">90</td>
                <td style="border: 1px solid black; padding: 8px;">A-</td>
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
        </table>
    </div>

</template>

<script setup>
defineProps({
    student: Object
})
</script>