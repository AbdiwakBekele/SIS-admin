<template>
    <PageHeader :title="`Student Gradebook`" :navs="[
        { label: $trans('student.student'), path: 'StudentGradebook' },
    ]">
        <PageHeaderAction name="StudentProfile" :title="$trans('student.student')" :actions="['list']">


        </PageHeaderAction>
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentProfile' })" @refresh="emitter.emit('listItems')">

            <!-- Student Details Grid -->
            <div class="grid grid-cols-11 gap-2 p-3 shadow-md">
                <!-- Basic Information -->
                <div class="col-span-2 bg-white rounded-lg border-r py-5 text-center">

                    <span class="text-sm"> Apply Filter </span>

                    <div class="mx-2 text-left bg-gray-200 rounded-lg border-gray-300 py-2 px-3">
                        <div class="grid grid-cols-2 gap-1">

                            <!-- Select Term -->
                            <div class="col-span-2">
                                <label class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                    <strong>Term</strong>
                                </label>
                                <select id="dropdown"
                                    class="block w-full py-1 px-2 text-[12px] text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select...</option>
                                    <option value="option1">Semester I</option>
                                    <option value="option1">Semester II</option>
                                </select>
                            </div>

                            <!-- Select Gradelevel -->
                            <div class="col-span-2">
                                <label class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                    <strong>Grade Level</strong>
                                </label>
                                <select id="dropdown"
                                    class="block w-full p-1 px-2 text-[12px] text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select...</option>
                                    <option value="option1">5th Grade</option>
                                    <option value="option1">6th Grade</option>
                                    <option value="option1">7th Grade</option>
                                    <option value="option2">8th Grade</option>
                                    <option value="option3">9th Grade</option>
                                    <option value="option3">10th Grade</option>
                                </select>
                            </div>

                            <!-- Select Course -->
                            <div class="col-span-2">
                                <label class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                    <strong>Course</strong>
                                </label>
                                <select id="dropdown"
                                    class="block w-full p-1 px-2 text-[12px] text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select...</option>
                                    <option value="option1">Maths</option>
                                    <option value="option1">Biology</option>
                                    <option value="option1">Chemistry</option>
                                    <option value="option1">Physics</option>
                                </select>
                            </div>

                            <!-- Filter Dropdown with Checkboxes -->
                            <div class="relative inline-block w-full col-span-2">
                                <label class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                    <strong>Assessment</strong>
                                </label>

                                <div @click="showDropdown = !showDropdown"
                                    class="block w-full p-1 px-2 text-[12px] bg-gray-50 border border-gray-300 rounded-lg cursor-pointer">
                                    Select Assessment
                                </div>

                                <div v-if="showDropdown"
                                    class="absolute z-10 w-full p-2 mt-1 bg-white border border-gray-300 rounded-lg shadow">
                                    <div v-for="category in allCategories" :key="category"
                                        class="flex items-center space-x-2">
                                        <input type="checkbox" :value="category" v-model="selectedCategories" />
                                        <label class="text-[12px]">{{ category }}</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="">
                            <input id="checkbox1" type="checkbox" @change="toggleCheckbox('numeric')"
                                :checked="numericGrade"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="checkbox1" class="text-[12px] font-medium text-gray-900 mx-2 ">Numerical
                                Grade</label>
                        </div>

                        <div class="">
                            <input id="checkbox1" type="checkbox" @change="toggleCheckbox('letter')"
                                :checked="letterGrade"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="checkbox1" class="text-[12px] font-medium text-gray-900 mx-2 ">Letter
                                Grade</label>
                        </div>


                    </div>

                    <label class="block mt-3 ml-3 text-[12px] font-medium text-gray-900 text-left">
                        <strong>Filter by Students</strong>
                    </label>
                    <hr class="mx-4">

                    <div @click="activeStudent = 'All'" :class="[
                        'cursor-pointer px-2 pt-3 pb-1 text-sm sm:w-auto text-left mx-2',
                        activeStudent === 'All' ? 'border-b-2 border-gray-500 font-bold bg-white text-blue-900' : 'border-b-2 border-gray-300 bg-transparent'
                    ]">All Students</div>

                    <div v-for="student in students" @click="activeStudent = student.name" :class="[
                        'cursor-pointer px-2 pt-3 pb-1 text-sm sm:w-auto text-left mx-2',
                        activeStudent === student.name ? 'border-b-2 border-gray-500 font-bold bg-white text-blue-900' : 'border-b-2 border-gray-300 bg-transparent'
                    ]">
                        {{ student.name }}
                    </div>

                </div>


                <!-- Right Section with Tabs -->
                <div class="col-span-9 rounded-lg">

                    <!-- Tab Menus -->
                    <div class="bg-gray-100 border-b-2 rounded-lg border border-white">
                        <div class="flex flex-wrap gap-2">
                            <div v-for="(tab, index) in tabs" :key="index" @click="activeTab = tab" :class="[
                                'cursor-pointer px-2 pt-3 pb-1 text-sm sm:w-auto text-center',
                                activeTab === tab ? 'border-b-2 border-blue-500 font-bold bg-white' : 'border-b-2 border-gray-300 bg-transparent'
                            ]">
                                {{ tab }}
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div v-if="activeTab === 'Gradebook'">

                        <!-- Academic Info (2024) -->
                        <div class="bg-white rounded-lg border-r px-4 py-5 text-center mt-2">
                            <p class="text-sm text-left mx-4">


                                <button class="bg-blue-500 px-2 py-1 rounded text-white text-sm mb-3"
                                    style="float:right" @click="
                                        router.push({
                                            name: 'StudentGradebookReport',
                                            params: { uuid: student.uuid },
                                        })
                                        "> View Detail</button>
                            </p>

                            <hr class="mx-4">

                            <div class="text-[13px]" v-if="activeStudent == 'All'">
                                <!-- Semester I Grades -->
                                <div class="mb-5">

                                    <p class="text-left mb-5"> <strong>Semester I | Maths</strong> </p>

                                    <!-- Selected Rows Display -->
                                    <div v-if="selectedRows.length" class="mb-4 p-2 bg-gray-100 rounded">
                                        <strong>Selected Assessments:</strong>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <div v-for="row in selectedRows" :key="row.title"
                                                class="flex items-center bg-white px-2 py-1 rounded shadow">
                                                {{ row.title }}
                                                <button class="ml-2 text-red-500"
                                                    @click="removeSelectedRow(row)">x</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="yourdiv">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th v-for="(student, colIndex) in students" :key="student.id">
                                                        <div :class="'col-' + colIndex">
                                                            <span :class="'p-2 col-' + (colIndex % 5)">
                                                                {{ student.name }}
                                                            </span>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr v-for="(assessment, rowIndex) in visibleAssessments" :key="rowIndex"
                                                    @click="selectRow(assessment)" :class="'col-' + colIndex"
                                                    class="cursor-pointer hover:bg-gray-100">
                                                    <td class="border border-gray-300 p-2 font-bold">
                                                        {{ assessment.title }}
                                                    </td>
                                                    <td v-for="(student, colIndex) in students" :key="student.id"
                                                        :class="'border border-gray-300 p-2 col-' + colIndex % 5">
                                                        10
                                                    </td>
                                                </tr>


                                                <tr v-if="numericGrade == true && selectedCategories.length === 0">
                                                    <td> <strong>TOTAL</strong> </td>
                                                    <td v-for="(student, colIndex) in students" :key="student.id"
                                                        :class="'col-' + (colIndex % 5)">
                                                        <strong>100</strong>
                                                    </td>
                                                </tr>

                                                <tr v-if="letterGrade == true && selectedCategories.length === 0">
                                                    <td> <strong>LETTER</strong></td>
                                                    <td v-for="(student, colIndex) in students" :key="student.id"
                                                        :class="'col-' + (colIndex % 5)">
                                                        <strong>A+</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>

                            <div class="text-[13px]" v-else>
                                <!-- Semester I Grades -->
                                <div class="mb-5">
                                    <p class="text-left"> <strong>Semester I - {{ activeStudent }}</strong>
                                    </p>
                                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">

                                        <tr>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;">
                                                Course Title</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Homework 1</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Homework 2</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Homework 3</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Quiz 1</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Quiz 2</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Quiz 3</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Final</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Total</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Letter Grade</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Credits Earned</th>
                                        </tr>

                                        <tr>
                                            <td style="border: 1px solid black; padding: 8px;">Mathematics</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">40</td>
                                            <td style="border: 1px solid black; padding: 8px;">100</td>
                                            <td style="border: 1px solid black; padding: 8px;">A+</td>
                                            <td style="border: 1px solid black; padding: 8px;">4.0</td>
                                        </tr>

                                        <tr>
                                            <td style="border: 1px solid black; padding: 8px;">Chemistry</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">40</td>
                                            <td style="border: 1px solid black; padding: 8px;">100</td>
                                            <td style="border: 1px solid black; padding: 8px;">A+</td>
                                            <td style="border: 1px solid black; padding: 8px;">4.0</td>
                                        </tr>

                                        <tr>
                                            <td style="border: 1px solid black; padding: 8px;">Biology</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">40</td>
                                            <td style="border: 1px solid black; padding: 8px;">100</td>
                                            <td style="border: 1px solid black; padding: 8px;">A+</td>
                                            <td style="border: 1px solid black; padding: 8px;">4.0</td>
                                        </tr>

                                        <tr>
                                            <td style="border: 1px solid black; padding: 8px;">Physics</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">40</td>
                                            <td style="border: 1px solid black; padding: 8px;">100</td>
                                            <td style="border: 1px solid black; padding: 8px;">A+</td>
                                            <td style="border: 1px solid black; padding: 8px;">4.0</td>
                                        </tr>

                                    </table>
                                </div>

                                <div class="mb-3 text-left">
                                    <h4>Academic Summary: Semester I</h4>
                                    <p><strong>Average:</strong> 100</p>
                                    <p><strong>WGPA:</strong> 4.0</p>
                                </div>

                                <!-- Semester II Grades -->
                                <div class="mb-5">
                                    <p class="text-left"> <strong>Semester II - {{ activeStudent }}</strong>
                                    </p>
                                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">


                                        <tr>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #bfbfbf;">
                                                Course Title</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Homework 1</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Homework 2</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Homework 3</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Quiz 1</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Quiz 2</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Quiz 3</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Final</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Total</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Letter Grade</th>
                                            <th
                                                style="border: 1px solid black; padding: 8px; background-color: #d9d9d9;">
                                                Credits Earned</th>
                                        </tr>

                                        <tr>
                                            <td style="border: 1px solid black; padding: 8px;">Mathematics</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">40</td>
                                            <td style="border: 1px solid black; padding: 8px;">100</td>
                                            <td style="border: 1px solid black; padding: 8px;">A+</td>
                                            <td style="border: 1px solid black; padding: 8px;">4.0</td>
                                        </tr>

                                        <tr>
                                            <td style="border: 1px solid black; padding: 8px;">Chemistry</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">40</td>
                                            <td style="border: 1px solid black; padding: 8px;">100</td>
                                            <td style="border: 1px solid black; padding: 8px;">A+</td>
                                            <td style="border: 1px solid black; padding: 8px;">4.0</td>
                                        </tr>

                                        <tr>
                                            <td style="border: 1px solid black; padding: 8px;">Biology</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">40</td>
                                            <td style="border: 1px solid black; padding: 8px;">100</td>
                                            <td style="border: 1px solid black; padding: 8px;">A+</td>
                                            <td style="border: 1px solid black; padding: 8px;">4.0</td>
                                        </tr>

                                        <tr>
                                            <td style="border: 1px solid black; padding: 8px;">Physics</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">10</td>
                                            <td style="border: 1px solid black; padding: 8px;">40</td>
                                            <td style="border: 1px solid black; padding: 8px;">100</td>
                                            <td style="border: 1px solid black; padding: 8px;">A+</td>
                                            <td style="border: 1px solid black; padding: 8px;">4.0</td>
                                        </tr>

                                    </table>
                                </div>

                                <div class="mb-3 text-left">
                                    <h4>Academic Summary: Semester II</h4>
                                    <p><strong>Average:</strong> 100</p>
                                    <p><strong>WGPA:</strong> 4.0</p>
                                </div>
                            </div>

                        </div>


                    </div>

                </div>
            </div>

        </ShowItem>


    </ParentTransition>

</template>

<script>
export default {
    name: "StudentGradebook",
    data() {
        return {
            openIndex: 0, // Default to open the first test
            showDropdown: false,
            selectedCategories: [],
            selectedRows: [],
            activeTab: "Gradebook",
            tabs: ["Gradebook"],
            activeStudent: "All",


            assessments: [
                {
                    title: 'HW1', category: 'Home Work',
                },
                {
                    title: 'HW2', category: 'Home Work',
                },
                {
                    title: 'HW3', category: 'Home Work',
                },

                {
                    title: 'Q1', category: 'Quiz',
                },
                {
                    title: 'Q2', category: 'Quiz',
                },
                {
                    title: 'Q3', category: 'Quiz',
                },

                {
                    title: 'MTE', category: 'MTE',
                },
                {
                    title: 'FE', category: 'FE',
                },
            ]
        };
    },

    computed: {
        allCategories() {
            return [...new Set(this.assessments.map(a => a.category))];
        },
        filteredAssessments() {
            if (this.selectedCategories.length === 0) {
                return this.assessments;
            }
            return this.assessments.filter(a =>
                this.selectedCategories.includes(a.category)
            );
        },
        visibleAssessments() {
            return this.filteredAssessments.filter(a =>
                !this.selectedRows.find(selected => selected.title === a.title)
            );
        }
    },
    methods: {
        selectRow(assessment) {
            if (!this.selectedRows.find(a => a.title === assessment.title)) {
                this.selectedRows.push(assessment);
            }
        },
        removeSelectedRow(row) {
            this.selectedRows = this.selectedRows.filter(r => r.title !== row.title);
        }
    }
}

</script>

<script setup>
import { reactive, ref, inject, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"

const store = useStore()
const route = useRoute()
const router = useRouter()

const emitter = inject("emitter")
const $trans = inject("$trans")

const initialStudentProfile = {}

const initUrl = "student/studentProfile/"

const refreshItem = ref(false)
const student = reactive({ ...initialStudentProfile })
const numericGrade = ref(true)
const letterGrade = ref(false)

const students = ref([])

const staticAssessments = [
    { type: 'HW1', score: '10' },
    { type: 'HW2', score: '10' },
    { type: 'HW3', score: '10' },
    { type: 'Q1', score: '10' },
    { type: 'Q2', score: '10' },
    { type: 'Q3', score: '10' },
    { type: 'MTE', score: '10' },
    { type: 'FE', score: '10' }
]

const setItem = (data) => {
    const rawStudents = data['data']

    // Overwrite `students.value` with transformed student objects
    students.value = rawStudents.map(student => ({
        name: `${student.contact?.first_name ?? ''} ${student.contact?.last_name ?? ''}`.trim(),
        assessments: staticAssessments
    }))

    console.log(students)
    Object.assign(student, data)
}

const toggleCheckbox = (key) => {
    if (key === 'numeric') {
        letterGrade.value = false
        numericGrade.value = true
    } else if (key === 'letter') {
        letterGrade.value = true
        numericGrade.value = false
    }
}

</script>

<style scoped>
#yourdiv {
    display: flex;
    flex-direction: column;
    max-width: 50vw;
    margin-top: 80px;
}

h4 {
    flex-basis: 140px;
}

table {
    border-collapse: collapse;
    --table-border-width: 1px;
}

th {
    white-space: nowrap;
    position: relative;
}

td {
    border: 1px solid black;
    padding: 8px;
    text-align: center;
}

th>div {
    position: absolute;
    bottom: 0;
    left: 0;
    text-align: left;
    transform:
        translate(calc(100% - var(--table-border-width) / 2), var(--table-border-width)) rotate(315deg);
    transform-origin: 0% calc(100% - var(--table-border-width));
    width: 100%;
}

th[class^="col-"]::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: inherit;
    z-index: 0;
    clip-path: polygon(0% 100%, 0% 0%, 100% 0%);
}

th>div {
    position: relative;
    z-index: 1;
}

th>div>span {
    position: absolute;
    bottom: 0;
    left: 0;
    border-bottom: var(--table-border-width) solid gray;
}

td {
    border-right: var(--table-border-width) solid gray;
    min-width: 30px;
    text-align: right;
    padding-top: 2px;
    padding-left: 5px;
}

/* Column color labels */
.col-0 {
    background-color: #ffe0e0;
    /* light red */
}

.col-1 {
    background-color: #e0ffe0;
    /* light green */
}

.col-2 {
    background-color: #e0e0ff;
    /* light blue */
}

.col-3 {
    background-color: #fffacc;
    /* light yellow */
}

.col-4 {
    background-color: #ffffff;
    /* light red */
}

.col-5 {
    background-color: #ffe0e0;
    /* light red */
}
</style>