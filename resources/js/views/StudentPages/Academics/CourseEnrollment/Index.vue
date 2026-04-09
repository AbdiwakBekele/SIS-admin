<template>
    <div class="grid grid-cols-11 gap-2 p-3 shadow-md">

        <!-- Left Section -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg border-r py-5 text-center">

                <!-- Filter -->
                <p class="text-sm text-left mx-4"><strong>Filter</strong></p>
                <div class="mx-2 text-left bg-gray-200 rounded-lg border-gray-300 py-2 px-3">
                    <div class="grid grid-cols-2 gap-1">
                        <!-- School Year -->
                        <div class="col-span-2">
                            <label for="year" class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                <strong>Enrollment School Year</strong>
                            </label>
                            <select id="year" v-model="form.schoolYear"
                                class="block w-full p-1 px-2 text-[12px] bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select...</option>
                                <option>2024/2025</option>
                                <option>2025/2026</option>
                            </select>
                        </div>
                        <!-- Term -->
                        <div class="col-span-2">
                            <label for="term" class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                <strong>Enrollment Term</strong>
                            </label>
                            <select id="term" v-model="form.term"
                                class="block w-full p-1 px-2 text-[12px] bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select...</option>
                                <option>Semester I</option>
                                <option>Semester II</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Enroll Students -->
                <p class="text-sm text-left mx-4 mt-4"><strong>Enroll Students</strong></p>
                <div class="mx-2 text-left bg-gray-200 rounded-lg border-gray-300 py-2 px-3">
                    <div class="grid grid-cols-2 gap-1">
                        <!-- Grade Level -->
                        <div class="col-span-2">
                            <label for="grade" class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                <strong>Grade Level</strong>
                            </label>
                            <select id="grade" v-model="form.enrollGrade"
                                class="block w-full p-1 px-2 text-[12px] bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select...</option>
                                <option>Grade 6</option>
                                <option>Grade 7</option>
                                <option>Grade 8</option>
                            </select>
                        </div>
                        <!-- Course -->
                        <div class="col-span-2">
                            <label for="course" class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                <strong>Course</strong>
                            </label>
                            <select id="course" v-model="form.enrollCourse"
                                class="block w-full p-1 px-2 text-[12px] bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select...</option>
                                <option>Algebra I</option>
                                <option>Biology I</option>
                            </select>
                        </div>
                        <!-- Student Name -->
                        <div class="col-span-2">
                            <label for="studentName" class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                <strong>Name</strong>
                            </label>
                            <input type="text" id="studentName" v-model="form.enrollName"
                                class="block w-full p-1 px-2 text-[12px] bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Student Name" />
                        </div>
                    </div>
                </div>

                <!-- List of Students -->
                <p class="text-sm text-left mx-4 mt-4"><strong>Students</strong></p>
                <hr class="mx-4" />
                <div class="cursor-pointer px-2 pt-3 pb-1 text-sm text-left mx-2 border-b-2 border-gray-300">
                    <input type="checkbox" class="mr-2" /> All Students
                </div>
                <div v-for="student in students" :key="student.uuid"
                    class="cursor-pointer px-2 pt-3 pb-1 text-sm text-left mx-2 border-b-2 border-gray-300">
                    <input type="checkbox" class="mr-2" />
                    {{ student.firstName }} {{ student.lastName }}
                </div>

            </div>
        </div>

        <!-- Right Section -->
        <div class="col-span-9">

            <!-- Filter Panel -->
            <div class="bg-white rounded-lg p-4">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-4">
                        <BaseSelect v-model="form.division" name="division" :label="`Division`"
                            :options="stateData.divisions" label-prop="name" value-prop="value" />
                    </div>
                    <div class="col-span-4" v-if="form.division">
                        <BaseSelect v-model="form.subject" name="subject" :label="`Subject`"
                            :options="stateData.subjects" label-prop="name" value-prop="uuid" />
                    </div>
                    <div class="col-span-4" v-if="form.subject">
                        <BaseSelect v-model="form.gradeLevel" name="gradeLevel" :label="`Grade Level`"
                            :options="stateData.gradeLevels" label-prop="name" value-prop="uuid" />
                    </div>
                </div>
                <div class="mt-2 text-right">
                    <BaseButton @click="filter">Filter</BaseButton>
                    <BaseButton design="error" @click="clearFilters">Clear</BaseButton>
                </div>
            </div>

            <!-- Selected Courses -->
            <div v-if="selectedCourses.length" class="mt-4 bg-white rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-2">Selected Courses</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                    <div v-for="course in selectedCourses" :key="course.uuid"
                        class="bg-white border border-gray-200 rounded-lg shadow p-2 items-center">
                        <input type="checkbox" checked @change="toggleSelection(course)" class="mr-2" />
                        <img :src="course.url" alt="cover" class="w-12 h-12 object-cover rounded mx-2" />
                        <p class="text-sm ml-2">{{ course.name }}</p>
                    </div>
                </div>
            </div>

            <!-- Courses Grid -->
            <div class="mt-6 bg-white rounded-lg p-3">
                <h2 class="text-2xl font-semibold mb-4">Courses</h2>
                <div class="overflow-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2 bg-gray-100">Subject</th>
                                <th v-for="grade in visibleGrades" :key="grade"
                                    class="border px-4 py-2 bg-gray-200 text-center">
                                    {{ grade }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="subject in visibleSubjects" :key="subject">
                                <td class="border px-4 py-2 font-medium bg-gray-200">{{ subject }}</td>
                                <td v-for="grade in visibleGrades" :key="subject + grade"
                                    class="border px-4 py-2 align-top">
                                    <div v-if="visibleCourses[subject] && visibleCourses[subject][grade]?.length"
                                        class="grid grid-cols-2 gap-1">
                                        <label v-for="course in visibleCourses[subject][grade]" :key="course.uuid"
                                            class="col-span-1 bg-white border border-gray-200 rounded-lg shadow hover:shadow-md cursor-pointer transition overflow-hidden p-2 flex items-start">
                                            <input type="checkbox" :checked="isSelected(course)"
                                                @change="toggleSelection(course)" class="mr-1 mt-1" />
                                            <div>
                                                <img :src="course.url" alt="cover"
                                                    class="w-full h-20 object-cover rounded" />
                                                <p class="text-[10px] font-semibold pl-1 mt-1">{{ course.name }}</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div v-else class="text-xs text-gray-400 italic">— none —</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-2 text-right">
        <BaseButton @click="filter">Save</BaseButton>
    </div>
</template>

<script setup>
import { reactive, ref, inject, computed } from 'vue'

// Reactive form state
const form = reactive({
    division: '',
    subject: '',
    gradeLevel: '',
    date: inject('moment')().format('YYYY-MM-DD'),
    section: '',
    schoolYear: '',
    term: '',
    enrollGrade: '',
    enrollCourse: '',
    enrollName: '',
})

// Static dropdown options
const stateData = reactive({
    divisions: [
        { name: 'Elementary', value: 'Elementary' },
        { name: 'Middle School', value: 'Middle School' },
        { name: 'High School', value: 'High School' },
    ],
    gradeLevels: [
        { name: 'Grade 6', uuid: 'Grade 6' },
        { name: 'Grade 7', uuid: 'Grade 7' },
        { name: 'Grade 8', uuid: 'Grade 8' },
    ],
    subjects: [
        { name: 'Mathematics', uuid: 'Mathematics' },
        { name: 'Biology', uuid: 'Biology' },
        { name: 'History', uuid: 'History' },
        { name: 'Physics', uuid: 'Physics' },
    ],
})

// Sample students list
const students = [
    { uuid: 'asdfasdfsad', firstName: 'John', lastName: 'Smith' },
    { uuid: 'asdf-asd-fsad', firstName: 'John', lastName: 'Daniel' },
    { uuid: 'asdf-as-dfsad', firstName: 'Smith', lastName: 'Sol' },
]

// Flatten subject & grade arrays
const subjects = stateData.subjects.map(s => s.name)
const gradeLevels = stateData.gradeLevels.map(g => g.name)

// Course data by subject & grade
const coursesBySubject = reactive({
    Mathematics: {
        'Grade 6': [
            { uuid: 'alg-1', name: 'Algebra I', section: 'A', url: 'https://ebus.ams.org/ProductImages/gsm-56-01.jpg' },
            { uuid: 'geom-6', name: 'Geometry Basics', section: 'A', url: 'https://ebus.ams.org/ProductImages/gsm-56-01.jpg' },
        ],
        'Grade 7': [
            { uuid: 'alg2-7', name: 'Algebra II', section: 'B', url: 'https://ebus.ams.org/ProductImages/gsm-56-01.jpg' },
        ],
        'Grade 8': [],
    },
    Biology: {
        'Grade 6': [],
        'Grade 7': [
            { uuid: 'bio-1', name: 'Biology 101', section: 'B', url: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1CWQm8Z6dns_9GBVJwhroBiX8U9hogC5ARw&s' },
        ],
        'Grade 8': [
            {
                uuid: 'eco-8', name: 'Ecology', section: 'C',
                url: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUjPwvQD_XVIhjEmiaIT0w3D6avmQiBP9aztaV__LnsGaFewcSel9-dYuI8FDv_Wna3hg&usqp=CAU'
            },
        ],
    },
    History: {
        'Grade 6': [
            {
                uuid: 'hist-1', name: 'World History', section: 'C',
                url: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfvWQSBMAwJfX72LeUomZoNewREURYgLjcNg&s'
            },
        ],
        'Grade 7': [], 'Grade 8': [],
    },
    Physics: {
        'Grade 6': [], 'Grade 7': [],
        'Grade 8': [
            {
                uuid: 'phys-1', name: 'Fundamentals of Physics', section: 'D',
                url: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRr-qv6z-NZi0oaVMQ112cmB_ZFIeftEAuMuop0VWQmckeOvkmvEGLU-pz2pCN6lSzjEY8&usqp=CAU'
            },
        ],
    },
})

// State for which courses are selected
const selectedCourses = ref([])

// Computed lists after filtering out selected courses
const visibleSubjects = computed(() =>
    form.subject ? [form.subject] : subjects
)
const visibleGrades = computed(() =>
    form.gradeLevel ? [form.gradeLevel] : gradeLevels
)
const visibleCourses = computed(() => {
    const map = {}
    visibleSubjects.value.forEach(subj => {
        map[subj] = {}
        visibleGrades.value.forEach(grade => {
            const all = coursesBySubject[subj]?.[grade] || []
            map[subj][grade] = all.filter(c => !selectedCourses.value.some(sc => sc.uuid === c.uuid))
        })
    })
    return map
})

// Helpers
function isSelected(course) {
    return selectedCourses.value.some(c => c.uuid === course.uuid)
}
function toggleSelection(course) {
    const idx = selectedCourses.value.findIndex(c => c.uuid === course.uuid)
    if (idx === -1) {
        selectedCourses.value.push(course)
    } else {
        selectedCourses.value.splice(idx, 1)
    }
}

// No-op filter button; computed props handle updates
function filter() { }
function clearFilters() {
    form.division = ''
    form.subject = ''
    form.gradeLevel = ''
}
</script>

<style scoped>
table {
    table-layout: auto;
}
</style>
