<!-- src/views/course/CourseDetail.vue -->
<template>
    <PageHeader :title="`Biology Section A`" :navs="[
        { label: 'Course Detail', path: '' }
    ]">

        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <span
                    :class="[
                        'text-sm leading-4 font-bold px-3 py-1.5 rounded-lg flex items-center gap-2 shadow-sm',
                        status === 'activated' ? 'bg-green-500 text-white' : 'bg-yellow-400 text-yellow-900'
                    ]"
                >
                    <i
                        :class="[
                            'fa-solid text-sm',
                            status === 'activated' ? 'fa-circle-check' : 'fa-triangle-exclamation'
                        ]"
                    ></i>
                    <span class="font-semibold">{{ status === 'activated' ? 'Published' : 'Draft' }}</span>
                </span>
            </div>

            <BaseButton :design="status === 'activated' ? 'danger' : 'success'" size="sm" @click="toggleStatus">
                {{ status === 'activated' ? 'Unpublish' : 'Publish' }}
            </BaseButton>
        </div>

    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'CourseDetail' })" @refresh="emitter.emit('listItems')">

            <div class="grid grid-cols-11 gap-2 p-3 shadow-md">

                <!-- Left Section -->
                <div class="col-span-2 space-y-4">
                    <!-- 1) Image + Course Info (always open) -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <!-- replace `course.image` with your actual image URL field -->
                        <img v-if="course.image" :src="course.image" alt="Course image"
                            class="w-full h-32 object-cover" />
                        <div class="p-4 text-center">
                            <p class="text-sm text-left mb-2 font-semibold">Course Info</p>
                            <hr class="mb-2" />
                            <p class="text-sm text-left"><strong>Title:</strong> {{ course.title }}</p>
                            <p class="text-sm text-left"><strong>Code:</strong> {{ course.code }}</p>
                            <p class="text-sm text-left"><strong>Subject:</strong> {{ course.category }}</p>
                            <p class="text-sm text-left"><strong>Credits:</strong> {{ course.credits }}</p>
                        </div>
                    </div>

                    <!-- 2) Course Schedule (collapsible) -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <button class="w-full flex justify-between items-center p-4 font-semibold text-sm"
                            @click="showSchedule = !showSchedule">
                            <span>Course Schedule</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform"
                                :class="{ 'rotate-180': showSchedule }" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="showSchedule" class="border-t border-gray-200 p-4 text-sm space-y-2">
                            <p><strong>Time:</strong> {{ course.schedule.time }}</p>
                            <p><strong>Days:</strong> {{ course.schedule.day }}</p>
                            <p><strong>Location:</strong> {{ course.schedule.location }}</p>
                        </div>
                    </div>

                    <!-- 3) Course Overview (collapsible) -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <button class="w-full flex justify-between items-center p-4 font-semibold text-sm"
                            @click="showOverview = !showOverview">
                            <span>Course Overview</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform"
                                :class="{ 'rotate-180': showOverview }" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="showOverview" class="border-t border-gray-200 p-4 text-sm">
                            {{ course.description }}
                        </div>
                    </div>

                    <!-- 4) Instructors (collapsible) -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <button class="w-full flex justify-between items-center p-4 font-semibold text-sm"
                            @click="showInstructors = !showInstructors">
                            <span>Instructors</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform"
                                :class="{ 'rotate-180': showInstructors }" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="showInstructors" class="border-t border-gray-200 p-4 space-y-4">
                            <div v-for="inst in instructors" :key="inst.email" class="flex items-center space-x-3">
                                <img :src="inst.avatar" alt="Avatar" class="w-10 h-10 rounded-full object-cover" />
                                <div>
                                    <p class="text-sm font-semibold">
                                        {{ inst.name }}
                                        <span class="text-xs text-gray-500">({{ inst.role }})</span>
                                    </p>
                                    <p class="text-xs text-gray-600">{{ inst.email }}</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section with Tabs -->
                <div class="col-span-9 rounded-lg">

                    <!-- 1st Row of Tabs -->
                    <div class="w-full flex gap-2">
                        <div v-for="(tab, idx) in tabs" :key="'r1-' + idx" @click="activeTab = tab" :class="[
                            'flex-1 min-w-max whitespace-nowrap cursor-pointer px-2 pt-3 pb-1 text-sm text-center transition-all duration-150',
                            activeTab === tab
                                ? 'bg-gray-50 shadow-inner border border-gray-300 border-b-0 rounded-t-lg'
                                : 'bg-white border border-gray-300 border-b-0 rounded-t-lg -mb-px z-20 shadow-lg relative -top-1'
                        ]">{{ tab }}</div>
                    </div>

                    <!-- Tab Content -->
                    <div class="mt-2">

                        <!-- Curriculum -->
                        <div v-if="activeTab === 'Curriculum'" class="bg-white rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-semibold">UNITS</h3>
                                <button @click="addUnit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Add Unit
                                </button>
                            </div>

                            <div v-for="unit in units" :key="unit.id" class="border rounded mb-2 overflow-hidden">
                                <div class="flex justify-between items-center px-4 py-2 bg-gray-100 cursor-pointer"
                                    @click="toggleUnit(unit.id)">

                                    <div class="flex items-center space-x-2">
                                        <svg :class="{ 'transform rotate-90': openUnitId === unit.id }"
                                            class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span class="font-medium">
                                            {{ unit.number }}. {{ unit.title }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Curriculum section update in CourseDetail.vue -->
                                <div v-if="openUnitId === unit.id" class="p-2 bg-gray-50 space-y-4">

                                    <!-- Lessons -->
                                    <section class="space-y-2 bg-white border border-gray-300 shadow-lg rounded-lg p-3">
                                        <div class="flex justify-between items-center">
                                            <h4 class="text-sm font-semibold">Lessons</h4>
                                            <button @click="router.push({
                                                name: 'AddCourseLesson', params: {
                                                    id:
                                                        unit.id
                                                }
                                            })" class="text-blue-600 text-sm">+ Add Lesson</button>
                                        </div>
                                        <ul class="list-disc ml-5">
                                            <li v-for="lesson in unit.lessons" :key="lesson.id">
                                                <p class="font-medium">{{ lesson.title }}</p>
                                                <p class="text-xs text-gray-600">{{ lesson.content }}</p>
                                            </li>
                                        </ul>
                                    </section>

                                    <!-- Assignments -->
                                    <section class="bg-white border p-3 rounded shadow-sm">
                                        <div class="flex justify-between items-center">
                                            <h4 class="font-semibold">Assignments</h4>
                                            <button @click="router.push({
                                                name: 'AddCourseAssignment', params: {
                                                    id: unit.id
                                                }
                                            })" class="text-blue-600 text-sm">+ Add Assignment</button>
                                        </div>

                                        <div v-for="item in unit.assignments" :key="item.id" class="mt-4">
                                            <p class="font-medium mb-2">{{ item.title }}</p>
                                            <button @click.stop="toggleGrades(item.id, 'assignments')"
                                                class="text-green-600 text-sm hover:underline">
                                                {{ isGradesOpen(item.id, 'assignments') ? 'Hide Grades' : 'Grade Assignments' }}
                                            </button>

                                            <!-- Submitted Assignments -->
                                            <div v-if="isGradesOpen(item.id, 'assignments')">

                                                <table
                                                    class="w-full table-auto text-sm border mt-2 divide-y divide-gray-200">
                                                    <thead>
                                                        <tr class="bg-gray-100">
                                                            <th class="p-2 text-left">Student</th>
                                                            <th class="p-2 text-left">Status</th>
                                                            <th class="p-2 text-left">Action</th>
                                                            <th class="p-2 text-left">Score</th>

                                                            <!-- add left border on this TH -->
                                                            <th class="p-2 text-left border-l-4 border-gray-400">
                                                                Student</th>
                                                            <th class="p-2 text-left">Status</th>
                                                            <th class="p-2 text-left">Action</th>
                                                            <th class="p-2 text-left">Score</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="pair in chunkStudents(students, 2)"
                                                            :key="pair[0].name" class="bg-white">
                                                            <!-- first student -->
                                                            <td class="p-2">{{ pair[0].name }}</td>
                                                            <td class="p-2">
                                                                <span
                                                                    v-if="item.submittedStudents.some(s => s.name === pair[0].name)"
                                                                    class="text-green-700">Graded</span>
                                                                <span v-else class="text-red-500">Not
                                                                    submitted</span>
                                                            </td>
                                                            <td class="p-2">
                                                                <span
                                                                    @click="router.push({ name: 'GradeAssignmentDetail', params: { id: item.id } })"
                                                                    class="cursor-pointer text-blue-600 hover:text-blue-800"><i
                                                                        class="fa-solid fa-folder-open"></i></span>
                                                            </td>
                                                            <td class="p-2">
                                                                <input type="number"
                                                                    v-model.number="gradesMap[unit.id].assignments[pair[0].name]"
                                                                    class="w-16 border rounded p-1" />
                                                            </td>

                                                            <!-- second student, with a left border -->
                                                            <td v-if="pair[1]" class="p-2 border-l-4 border-gray-400">
                                                                {{ pair[1].name }}
                                                            </td>
                                                            <td v-if="pair[1]" class="p-2">
                                                                <span
                                                                    v-if="item.submittedStudents.some(s => s.name === pair[1].name)"
                                                                    class="text-green-700">Graded</span>
                                                                <span v-else class="text-red-500">Not
                                                                    submitted</span>
                                                            </td>
                                                            <td class="p-2">
                                                                <span
                                                                    @click="router.push({ name: 'GradeAssignmentDetail', params: { id: item.id } })"
                                                                    class="cursor-pointer text-blue-600 hover:text-blue-800"><i
                                                                        class="fa-solid fa-folder-open"></i></span>
                                                            </td>
                                                            <td v-if="pair[1]" class="p-2">
                                                                <input type="number"
                                                                    v-model.number="gradesMap[unit.id].assignments[pair[1].name]"
                                                                    class="w-16 border rounded p-1" />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="m-1 mr-7 text-right">
                                                    <BaseButton>Save</BaseButton>
                                                </div>

                                            </div>

                                        </div>
                                    </section>

                                    <!-- Homeworks Section -->
                                    <section class="bg-white border p-3 rounded shadow-sm">
                                        <div class="flex justify-between items-center">
                                            <h4 class="font-semibold">Homeworks</h4>
                                            <button @click="router.push({
                                                name: 'AddCourseHowework', params: {
                                                    id: unit.id
                                                }
                                            })" class="text-blue-600 text-sm">+ Add Homework</button>
                                        </div>
                                        <div v-for="item in unit.homeworks" :key="item.id" class="mt-4">
                                            <p class="font-medium mb-2">{{ item.title }}</p>
                                            <button @click.stop="toggleGrades(unit.id, 'homeworks')"
                                                class="text-green-600 text-sm hover:underline">
                                                {{ isGradesOpen(unit.id, 'homeworks') ? 'Hide Grades' : 'Grade Homeworks' }}
                                            </button>

                                            <!-- Submitted Homework -->
                                            <div v-if="isGradesOpen(unit.id, 'homeworks')">
                                                <table class="w-full table-auto text-sm border mt-2">
                                                    <thead>
                                                        <tr class="bg-gray-100">
                                                            <th class="p-2 text-left">Student</th>
                                                            <th class="p-2 text-left">Status</th>
                                                            <th class="p-2 text-left">Action</th>
                                                            <th class="p-2 text-left">Score</th>
                                                            <th class="p-2 text-left">Student</th>
                                                            <th class="p-2 text-left">Status</th>
                                                            <th class="p-2 text-left">Action</th>
                                                            <th class="p-2 text-left">Score</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="pair in chunkStudents(students, 2)"
                                                            :key="pair[0].name">

                                                            <!-- First Col -->
                                                            <td class="p-2">{{ pair[0].name }}</td>
                                                            <td class="p-2">
                                                                <span
                                                                    v-if="item.submittedStudents.find(s => s.name === pair[0].name)">Ready
                                                                    to Grade</span>
                                                                <span v-else class="text-red-500">Not submitted</span>
                                                            </td>
                                                            <td class="p-2">
                                                                <span
                                                                    @click="router.push({ name: 'GradeHWDetail', params: { id: item.id } })"
                                                                    class="cursor-pointer text-blue-600 hover:text-blue-800">
                                                                    <i class="fa-solid fa-folder-open"></i>
                                                                </span>
                                                            </td>
                                                            <td class="p-2"><input type="number"
                                                                    v-model.number="gradesMap[unit.id]['homeworks'][pair[0].name]"
                                                                    class="w-16 border rounded p-1" /></td>

                                                            <!-- Second Col -->
                                                            <td v-if="pair[1]" class="p-2">{{ pair[1].name }}</td>
                                                            <td v-if="pair[1]" class="p-2">
                                                                <span
                                                                    v-if="item.submittedStudents.find(s => s.name === pair[1].name)">Ready
                                                                    to Grade</span>
                                                                <span v-else class="text-red-500">Not submitted</span>
                                                            </td>
                                                            <td class="p-2">
                                                                <span
                                                                    @click="router.push({ name: 'GradeHWDetail', params: { id: item.id } })"
                                                                    class="cursor-pointer text-blue-600 hover:text-blue-800">
                                                                    <i class="fa-solid fa-folder-open"></i>
                                                                </span>
                                                            </td>
                                                            <td v-if="pair[1]" class="p-2"><input type="number"
                                                                    v-model.number="gradesMap[unit.id]['homeworks'][pair[1].name]"
                                                                    class="w-16 border rounded p-1" /></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="m-1 mr-7 text-right">
                                                    <BaseButton>Save</BaseButton>
                                                </div>
                                            </div>

                                        </div>
                                    </section>

                                    <!-- Quizs Section -->
                                    <section class="bg-white border p-3 rounded shadow-sm">
                                        <div class="flex justify-between items-center">
                                            <h4 class="font-semibold">Quizs</h4>
                                            <button
                                                @click="router.push({ name: 'AddCourseQuiz', params: { id: unit.id } })"
                                                class="text-blue-600 text-sm">+
                                                Add Quiz</button>
                                        </div>
                                        <div v-for="item in unit.quizs" :key="item.id" class="mt-4">
                                            <p class="font-medium mb-2">{{ item.title }}</p>
                                            <button @click.stop="toggleGrades(unit.id, 'quizs')"
                                                class="text-green-600 text-sm hover:underline">
                                                {{ isGradesOpen(unit.id, 'quizs') ? 'Hide Grades' : 'Grade Quizs' }}
                                            </button>

                                            <div v-if="isGradesOpen(unit.id, 'quizs')">
                                                <table class="w-full table-auto text-sm border mt-2">
                                                    <thead>
                                                        <tr class="bg-gray-100">
                                                            <th class="p-2 text-left">Student</th>
                                                            <th class="p-2 text-left">Status</th>
                                                            <th class="p-2 text-left">Score</th>
                                                            <th class="p-2 text-left">Student</th>
                                                            <th class="p-2 text-left">Status</th>
                                                            <th class="p-2 text-left">Score</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="pair in chunkStudents(students, 2)"
                                                            :key="pair[0].name">
                                                            <td class="p-2">{{ pair[0].name }}</td>
                                                            <td class="p-2">
                                                                <span
                                                                    v-if="item.submittedStudents.find(s => s.name === pair[0].name)">Submitted</span>
                                                                <span v-else class="text-red-500">Not submitted</span>
                                                            </td>
                                                            <td class="p-2"><input type="number"
                                                                    v-model.number="gradesMap[unit.id]['quizs'][pair[0].name]"
                                                                    class="w-16 border rounded p-1" /></td>

                                                            <td v-if="pair[1]" class="p-2">{{ pair[1].name }}</td>
                                                            <td v-if="pair[1]" class="p-2">
                                                                <span
                                                                    v-if="item.submittedStudents.find(s => s.name === pair[1].name)">Submitted</span>
                                                                <span v-else class="text-red-500">Not submitted</span>
                                                            </td>
                                                            <td v-if="pair[1]" class="p-2"><input type="number"
                                                                    v-model.number="gradesMap[unit.id]['quizs'][pair[1].name]"
                                                                    class="w-16 border rounded p-1" /></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="m-1 mr-7 text-right">
                                                    <BaseButton>Save</BaseButton>
                                                </div>
                                            </div>

                                        </div>
                                    </section>

                                    <!-- Exam Section -->
                                    <section class="bg-white border p-3 rounded shadow-sm">
                                        <div class="flex justify-between items-center">
                                            <h4 class="font-semibold">Exam</h4>
                                            <button @click.stop="addSectionItem(unit.id, 'exam')"
                                                class="text-blue-600 text-sm">+ Add Exam</button>
                                        </div>
                                        <p class="font-medium mb-2">{{ unit.exam.title }}</p>
                                        <button @click.stop="toggleGrades(unit.id, 'exam')"
                                            class="text-green-600 text-sm hover:underline">
                                            {{ isGradesOpen(unit.id, 'exam') ? 'Hide Grades' : 'Grade Exam' }}
                                        </button>

                                        <div v-if="isGradesOpen(unit.id, 'exam')">
                                            <table class="w-full table-auto text-sm border mt-2">
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        <th class="p-2 text-left">Student</th>
                                                        <th class="p-2 text-left">Status</th>
                                                        <th class="p-2 text-left">Score</th>
                                                        <th class="p-2 text-left">Student</th>
                                                        <th class="p-2 text-left">Status</th>
                                                        <th class="p-2 text-left">Score</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="pair in chunkStudents(students, 2)" :key="pair[0].name">
                                                        <td class="p-2">{{ pair[0].name }}</td>
                                                        <td class="p-2">
                                                            <span
                                                                v-if="unit.exam.submittedStudents.find(s => s.name === pair[0].name)">Submitted</span>
                                                            <span v-else class="text-red-500">Not submitted</span>
                                                        </td>
                                                        <td class="p-2"><input type="number"
                                                                v-model.number="gradesMap[unit.id]['exam'][pair[0].name]"
                                                                class="w-16 border rounded p-1" /></td>

                                                        <td v-if="pair[1]" class="p-2">{{ pair[1].name }}</td>
                                                        <td v-if="pair[1]" class="p-2">
                                                            <span
                                                                v-if="unit.exam.submittedStudents.find(s => s.name === pair[1].name)">Submitted</span>
                                                            <span v-else class="text-red-500">Not submitted</span>
                                                        </td>
                                                        <td v-if="pair[1]" class="p-2"><input type="number"
                                                                v-model.number="gradesMap[unit.id]['exam'][pair[1].name]"
                                                                class="w-16 border rounded p-1" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div class="m-1 mr-7 text-right">
                                                <BaseButton>Save</BaseButton>
                                            </div>
                                        </div>

                                    </section>
                                </div>
                            </div>
                        </div>

                        <!-- Discussion Threads -->
                        <div v-if="activeTab === 'Discussion'" class="bg-white rounded-lg p-6">

                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-semibold">Discussion</h3>
                                <button @click="openReplyModal(null)"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm">New
                                    Thread</button>
                            </div>

                            <div v-for="thread in discussions" :key="thread.id"
                                class="border border-gray-300 bg-gray-100 p-4 mb-4 rounded-lg">
                                <!-- Thread Header -->
                                <div class="grid grid-cols-5 items-center cursor-pointer" @click="toggle(thread.id)">
                                    <div class="col-span-3">
                                        <h4 class="font-medium">{{ thread.title }}</h4>
                                        <div class="text-xs text-gray-500">
                                            by {{ thread.author.name }} - {{ thread.createdAt }}
                                        </div>
                                    </div>

                                    <div class="col-span-1 text-sm">
                                        <span>Replies: {{ thread.comments.length }}</span>
                                        <button class="text-blue-600 underline mx-3">
                                            {{ openThreadId === thread.id ? 'Hide Replies' : 'View Replies' }}
                                        </button>
                                    </div>

                                    <div class="col-span-1 text-sm text-right">

                                        <button class="mx-2">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <button class="mx-2">
                                            <i class="fa-solid fa-lock"></i>
                                        </button>

                                        <button class="mx-2">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>

                                        <button class="mx-2">
                                            <i class="fa-solid fa-trash text-red-500"></i>
                                        </button>
                                    </div>

                                </div>

                                <!-- Replies Accordion -->
                                <div v-if="openThreadId === thread.id" class="mt-4 space-y-3">
                                    <div v-for="c in thread.comments" :key="c.id" class="bg-white p-3 rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium">{{ c.author.name }}</span>
                                            <span>
                                                <span class="text-xs text-gray-400">{{ c.createdAt }}</span>
                                                <i class="fa-solid fa-thumbs-up mx-5"></i>
                                            </span>

                                        </div>
                                        <p class="text-sm mt-1">{{ c.body }}</p>
                                    </div>

                                    <button @click="openReplyModal(thread.id)"
                                        class="mt-2 text-sm text-green-600 hover:underline">Add
                                        Reply</button>
                                </div>
                            </div>

                            <!-- Reply Modal -->
                            <div v-if="replyModalThreadId !== null"
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                <div class="bg-white rounded-lg p-6 w-96">
                                    <h4 class="font-semibold mb-2">
                                        Reply to:
                                        {{replyModalThreadId === null ? 'New Thread' : discussions.find(t => t.id === replyModalThreadId).title}}
                                    </h4>
                                    <textarea v-model="replyText" rows="4" class="w-full border rounded px-2 py-1 mb-4"
                                        placeholder="Your reply..."></textarea>
                                    <div class="flex justify-end space-x-2">
                                        <button @click="closeReplyModal" class="px-3 py-1">Cancel</button>
                                        <button @click="postReply"
                                            class="bg-blue-600 text-white px-3 py-1 rounded">Post</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gamification -->
                        <div v-if="activeTab === 'Gamification'">
                            <ClassGamificationTab :class-uuid="route.params.uuid" :class-name="course.title" />
                        </div>

                        <!-- Attendance -->
                        <div v-if="activeTab === 'Attendance'" class="bg-white rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-semibold">Attendance</h3>
                                <button @click="recordAttendance"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Record Attendance
                                </button>
                            </div>
                            <table class="w-full table-auto text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 text-left">Student</th>
                                        <th class="px-4 py-2 text-left">Days Present</th>
                                        <th class="px-4 py-2 text-left">Days Absent</th>
                                        <th class="px-4 py-2 text-left">Attendance %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="att in attendance" :key="att.studentId" class="border-b">
                                        <td class="px-4 py-2">{{ att.studentName }}</td>
                                        <td class="px-4 py-2">{{ att.present }}</td>
                                        <td class="px-4 py-2">{{ att.absent }}</td>
                                        <td class="px-4 py-2">
                                            {{ ((att.present / (att.present + att.absent)) * 100).toFixed(1) }}%
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Resources -->
                        <div v-if="activeTab === 'Resources'" class="bg-white rounded-lg p-13">
                            <!-- Documents -->
                            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center">
                                <p class="text-sm text-left mx-4"> <strong> Resources</strong>
                                    <button class="bg-blue-500 px-2 py-1 rounded text-white" style="float:right">
                                        <i class="fa-solid fa-plus text-white mx-1 py-2"></i> Add Resource</button>
                                </p>
                                <hr class="mx-4 my-3">

                                <div class="grid grid-cols-12">
                                    <div class="grid grid-cols-6 col-span-6 text-left bg-gray-100 border border-gray-300 rounded-lg p-3 mx-4 mt-2"
                                        v-for="res in resources" :key="res.id">

                                        <span class="col-span-1 mt-2 ml-4">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </span>

                                        <div class="col-span-4 mt-2">
                                            <p class="text-sm">{{ res.name }}</p>
                                        </div>

                                        <!-- Action -->
                                        <div class="col-span-1 mt-2">
                                            <button class="mx-2">
                                                <i class="fa-solid fa-download"></i>
                                            </button>

                                            <button class="mx-2">
                                                <i class="fa-solid fa-trash text-red-500"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Syllabus -->
                        <div v-if="activeTab === 'Syllabus'" class="bg-white rounded-lg p-6">
                            <div class="flex justify-between">
                                <h3 class="text-sm font-semibold mb-4">Syllabus</h3>
                                <button @click="" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Add Syllabus
                                </button>
                            </div>

                            <table class="w-full table-auto text-sm border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 text-left">Week</th>
                                        <th class="px-4 py-2 text-left">Topic</th>
                                        <th class="px-4 py-2 text-left">Readings</th>
                                        <th class="px-4 py-2 text-left">Resources</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="s in syllabus" :key="s.week" class="border-b">
                                        <td class="px-4 py-2">{{ s.week }}</td>
                                        <td class="px-4 py-2">{{ s.topic }}</td>
                                        <td class="px-4 py-2">{{ s.readings }}</td>
                                        <td class="px-4 py-2">
                                            <a v-for="(res, i) in s.resources" :key="i" :href="res.link"
                                                class="text-blue-600 underline mr-2" target="_blank">
                                                {{ res.label }}
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Progress -->
                        <div v-if="activeTab === 'Progress'" class="bg-white rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-semibold">Progress</h3>
                                <button @click="updateGrades" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Update Grades
                                </button>
                            </div>
                            <table class="w-full table-auto text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 text-left">Student</th>
                                        <th class="px-4 py-2 text-left">Current Grade %</th>
                                        <th class="px-4 py-2 text-left">Course Grade%</th>
                                        <th class="px-4 py-2 text-left">Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="g in grades" :key="g.studentId" class="border-b">
                                        <td class="px-4 py-2">{{ g.studentName }}</td>
                                        <td class="px-4 py-2">{{ g.course }}%</td>
                                        <td class="px-4 py-2">{{ g.current }}%</td>

                                        <td class="px-4 py-2">
                                            <div class="w-full bg-gray-200 h-2 rounded overflow-hidden">
                                                <div class="h-2 bg-blue-600" :style="{ width: g.current + '%' }"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-if="activeTab === 'Gradebook'" class="bg-white rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-semibold">Gradebook</h3>
                            </div>
                            <div id="yourdiv">
                                <table class="table-auto w-full border-collapse border border-gray-300">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th v-for="(assm, colIndex) in assessments" :key="assm.title">

                                                <div :class="'col-' + colIndex">
                                                    <span :class="'p-2 col-' + (colIndex % 5)">
                                                        {{ assm.title }}
                                                    </span>
                                                </div>


                                            </th>
                                            <th>
                                                <div :class="'col-' + 1">
                                                    <span :class="'p-2 col-' + (1 % 5)">
                                                        TOTAL
                                                    </span>
                                                </div>
                                            </th>
                                            <th>
                                                <div :class="'col-' + 2">
                                                    <span :class="'p-2 col-' + (2 % 5)">
                                                        LETTER
                                                    </span>
                                                </div>

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="student in students" :key="student.name" class="hover:bg-gray-50">
                                            <td class="border border-gray-300 p-2 font-semibold">
                                                {{ student.name }}
                                            </td>

                                            <!-- individual assessment scores -->
                                            <td v-for="(assm, colIndex) in assessments" :key="assm.title"
                                                :class="'col-' + (colIndex % 5)">
                                                {{ lookupScore(student, assm.title) }}
                                            </td>

                                            <!-- per-student total -->
                                            <td :class="'col-' + (1 % 5)">
                                                {{ studentTotal(student) }}
                                            </td>

                                            <!-- per-student letter grade -->
                                            <td :class="'col-' + (2 % 5)">
                                                {{ calculateLetter(studentTotal(student)) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div v-if="activeTab === 'Students'" class="bg-white rounded-lg p-6">
                            <p class="text-sm font-semibold mb-4">Students</p>

                            <table class="w-full table-auto text-sm border mt-2">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-2 text-left">Student</th>
                                        <th class="p-2 text-left">Action</th>
                                        <th class="p-2 text-left">Student</th>
                                        <th class="p-2 text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="pair in chunkStudents(students, 2)" :key="pair[0].name">

                                        <!-- First Col -->
                                        <td class="p-2">{{ pair[0].name }}</td>

                                        <td class="p-2">
                                            <span
                                                @click="router.push({ name: 'SendEmail', params: { id: pair[0].id } })"
                                                class="cursor-pointer text-blue-600 hover:text-blue-800">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                        </td>

                                        <!-- Second Col -->
                                        <td v-if="pair[1]" class="p-2">{{ pair[1].name }}</td>

                                        <td class="p-2">
                                            <span
                                                @click="router.push({ name: 'SendEmail', params: { id: pair[1].id } })"
                                                class="cursor-pointer text-blue-600 hover:text-blue-800">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

        </ShowItem>
    </ParentTransition>
</template>

<script setup>
import { ref, reactive, computed, inject } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ClassGamificationTab from '@views/Pages/Academics/LMS/Tabs/ClassGamificationTab.vue'

const route = useRoute()
const router = useRouter()
const emitter = inject('emitter')
const $trans = inject('$trans')

// when ShowItem emits @setItem
const initUrl = 'schoolSetup/academicClass/'
const setItem = (data) => {
    Object.assign(course, normalizeCourse(data))
    instructors.value = normalizeInstructors(data)
}

const showSchedule = ref(false)
const showOverview = ref(false)
const showInstructors = ref(false)

const students = ref([
    {
        name: "John Smith K.",
        assessments: [
            { type: "HW1", score: "6" },
            { type: "HW2", score: "4" },
            { type: "HW3", score: "5" },
            { type: "Q1", score: "8" },
            { type: "Q2", score: "7" },
            { type: "Q3", score: "3" },
            { type: "MTE", score: "16" },
            { type: "FE", score: "36" },
        ],
    },
    {
        name: "Janet Assefa",
        assessments: [
            { type: "HW1", score: "4.3" },
            { type: "HW2", score: "5" },
            { type: "HW3", score: "6" },
            { type: "Q1", score: "4.5" },
            { type: "Q2", score: "5" },
            { type: "Q3", score: "4" },
            { type: "MTE", score: "19" },
            { type: "FE", score: "35" },
        ],
    },
    {
        name: "John Smith",
        assessments: [
            { type: "HW1", score: "4.3" },
            { type: "HW2", score: "5" },
            { type: "HW3", score: "6" },
            { type: "Q1", score: "5" },
            { type: "Q2", score: "5" },
            { type: "Q3", score: "4" },
            { type: "MTE", score: "19" },
            { type: "FE", score: "42" },
        ],
    },
    {
        name: "Daniel Nor",
        assessments: [
            { type: "HW1", score: "5" },
            { type: "HW2", score: "5" },
            { type: "HW3", score: "4" },
            { type: "Q1", score: "4.5" },
            { type: "Q2", score: "4" },
            { type: "Q3", score: "4" },
            { type: "MTE", score: "18" },
            { type: "FE", score: "40" },
        ],
    },
    {
        name: "Joseph Kaleb",

        assessments: [
            { type: "HW1", score: "3" },
            { type: "HW2", score: "2" },
            { type: "HW3", score: "3" },
            { type: "Q1", score: "5" },
            { type: "Q2", score: "4" },
            { type: "Q3", score: "4" },
            { type: "MTE", score: "19" },
            { type: "FE", score: "38" },
        ],

    },
    {
        name: "Estif Johnson",
        assessments: [
            { type: "HW1", score: "5" },
            { type: "HW2", score: "5" },
            { type: "HW3", score: "4" },
            { type: "Q1", score: "4.5" },
            { type: "Q2", score: "4" },
            { type: "Q3", score: "4" },
            { type: "MTE", score: "18" },
            { type: "FE", score: "40" },
        ],
    },

    {
        name: "John Smith",
        assessments: [
            { type: "HW1", score: "10" },
            { type: "HW2", score: "10" },
            { type: "HW3", score: "10" },
            { type: "Q1", score: "10" },
            { type: "Q2", score: "10" },
            { type: "Q3", score: "10" },
            { type: "MTE", score: "10" },
            { type: "FE", score: "10" },
        ],
    },

    {
        name: "Alazar T",
        assessments: [
            { type: "HW1", score: "5" },
            { type: "HW2", score: "5" },
            { type: "HW3", score: "4" },
            { type: "Q1", score: "4.5" },
            { type: "Q2", score: "4" },
            { type: "Q3", score: "4" },
            { type: "MTE", score: "18" },
            { type: "FE", score: "40" },
        ],
    },

    {
        name: "Nati C.",
        assessments: [
            { type: "HW1", score: "10" },
            { type: "HW2", score: "10" },
            { type: "HW3", score: "10" },
            { type: "Q1", score: "10" },
            { type: "Q2", score: "10" },
            { type: "Q3", score: "10" },
            { type: "MTE", score: "10" },
            { type: "FE", score: "10" },
        ],
    },

    {
        name: "Alazar Johnson",
        assessments: [
            { type: "HW1", score: "5" },
            { type: "HW2", score: "5" },
            { type: "HW3", score: "4" },
            { type: "Q1", score: "4.5" },
            { type: "Q2", score: "4" },
            { type: "Q3", score: "4" },
            { type: "MTE", score: "18" },
            { type: "FE", score: "40" },
        ],
    },

    {
        name: "Ruth S.",
        assessments: [
            { type: "HW1", score: "1" },
            { type: "HW2", score: "3" },
            { type: "HW3", score: "1" },
            { type: "Q1", score: "5" },
            { type: "Q2", score: "4" },
            { type: "Q3", score: "5" },
            { type: "MTE", score: "10" },
            { type: "FE", score: "25" },
        ],
    },

    {
        name: "Tigabu A.",
        assessments: [
            { type: "HW1", score: "2" },
            { type: "HW2", score: "4" },
            { type: "HW3", score: "4" },
            { type: "Q1", score: "4" },
            { type: "Q2", score: "4" },
            { type: "Q3", score: "4" },
            { type: "MTE", score: "19" },
            { type: "FE", score: "40" },
        ],
    },
]);

const assessments = ref([
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
]);
// find a student's score for one assessment
function lookupScore(student, title) {
    const a = student.assessments.find(x => x.type === title)
    return a ? Number(a.score) : 0
}

// sum all of one student's assessments
function studentTotal(student) {
    return student.assessments
        .reduce((sum, x) => sum + (x.score ? Number(x.score) : 0), 0)
}

// map a raw total to a letter
function calculateLetter(total) {
    if (total >= 90) return 'A+'
    if (total >= 80) return 'A'
    if (total >= 70) return 'B'
    if (total >= 60) return 'C'
    return 'F'
}

const grades = ref([
    { studentId: 1, studentName: 'John Smith K.', current: 70.8, course: 70.8 },
    { studentId: 2, studentName: 'Janet Assefa', current: 69.0, course: 69.0 },
    { studentId: 3, studentName: 'John Smith', current: 75.3, course: 75.3 },
    { studentId: 4, studentName: 'Daniel Nor', current: 70.4, course: 70.4 },
    { studentId: 5, studentName: 'Joseph Kaleb', current: 65.0, course: 65.0 },
    { studentId: 6, studentName: 'Estif Johnson', current: 70.4, course: 70.4 },
    { studentId: 7, studentName: 'John Smith', current: 66.7, course: 66.7 },
    { studentId: 8, studentName: 'Alazar T', current: 70.4, course: 70.4 },
    { studentId: 9, studentName: 'Nati C.', current: 66.7, course: 66.7 },
    { studentId: 10, studentName: 'Alazar Johnson', current: 70.4, course: 70.4 },
    { studentId: 11, studentName: 'Ruth S.', current: 45.0, course: 45.0 },
    { studentId: 12, studentName: 'Tigabu A.', current: 67.5, course: 67.5 },
])

const syllabus = ref([
    {
        week: 'Week 1',
        topic: 'Introduction to Biology & Scientific Method',
        readings: 'Ch. 1-2',
        resources: [
            { label: 'Lecture Slides', link: '#' },
            { label: 'Video Intro', link: '#' }
        ]
    },
    {
        week: 'Week 2',
        topic: 'Cell Structure and Function',
        readings: 'Ch. 3-4',
        resources: [
            { label: 'Lab Manual', link: '#' }
        ]
    },
    {
        week: 'Week 3',
        topic: 'Classification of Living Organisms',
        readings: 'Ch. 5',
        resources: []
    },
    // ...additional weeks...
])

const attendance = ref([
    { studentId: 1, studentName: 'Alice Johnson', present: 18, absent: 2 },
    { studentId: 2, studentName: 'Bob Smith', present: 20, absent: 0 },
    { studentId: 3, studentName: 'Carol Lee', present: 16, absent: 4 },
    // ...more students
])

const units = ref([
    {
        id: 1,
        number: 1,
        title: 'Introduction to Biology',
        lessons: [
            { id: 11, title: 'What is Biology?', content: 'Biology is the study of life and living organisms.' },
            { id: 12, title: 'Branches of Biology', content: 'Subjects include botany, zoology, microbiology.' }
        ],
        assignments: [
            {
                id: 21,
                title: 'Read Chapter 1 & Summarize',
                submittedStudents: [
                    { name: 'John Smith K.', score: 85 },
                    { name: 'Janet Assefa', score: 90 },
                    { name: 'John Smith', score: 78 },
                    { name: 'Daniel Nor', score: 88 }
                ]
            },
            {
                id: 256,
                title: 'Read Chapter 2 & Summarize',
                submittedStudents: [
                    { name: 'John Smith K.', score: 10 },
                    { name: 'Janet Assefa', score: 5 },
                    { name: 'John Smith', score: 8 },
                    { name: 'Daniel Nor', score: 9 }
                ]
            },

        ],
        homeworks: [
            {
                id: 31,
                title: 'Worksheet: Scientific Method',
                submittedStudents: [
                    { name: 'Joseph Kaleb', score: 65 },
                    { name: 'Estif Johnson', score: 92 },
                    { name: 'Alazar T', score: 74 },
                    { name: 'Nati C.', score: 80 }
                ]
            }],
        quizs: [
            {
                id: 41,
                title: 'Quiz 1: Basic Terms',
                submittedStudents: [
                    { name: 'John Smith K.', score: 75 },
                    { name: 'Janet Assefa', score: 82 },
                    { name: 'John Smith', score: 85 },
                    { name: 'Daniel Nor', score: 79 }
                ]
            }],
        exam: {
            id: 51,
            title: 'Unit 1 Exam',
            submittedStudents: [
                { name: 'John Smith K.', score: 78 },
                { name: 'Janet Assefa', score: 88 },
                { name: 'John Smith', score: 90 },
                { name: 'Joseph Kaleb', score: 70 }
            ]
        }
    },
    {
        id: 2,
        number: 2,
        title: 'The Cell - The Building Block of Life',
        lessons: [
            { id: 21, title: 'Cell Theory', content: 'All living things are made of cells.' },
            { id: 22, title: 'Cell Organelles', content: 'Includes nucleus, mitochondria, ribosomes.' }
        ],
        assignments: [
            {
                id: 23,
                title: 'Diagram a Eukaryotic Cell',
                submittedStudents: [
                    { name: 'Alazar Johnson', score: 91 },
                    { name: 'Ruth S.', score: 68 },
                    { name: 'Tigabu A.', score: 77 }
                ]
            }],
        homeworks: [
            {
                id: 24,
                title: 'Compare Prokaryotes vs. Eukaryotes',
                submittedStudents: [
                    { name: 'Alazar T', score: 82 },
                    { name: 'Nati C.', score: 89 },
                    { name: 'Estif Johnson', score: 94 }
                ]
            }],
        quizs: [
            {
                id: 25,
                title: 'Quiz 2: Organelle Functions',
                submittedStudents: [
                    { name: 'Daniel Nor', score: 85 },
                    { name: 'John Smith', score: 80 },
                    { name: 'Joseph Kaleb', score: 73 }
                ]
            }],
        exam: {
            id: 52,
            title: 'Unit 2 Exam',
            submittedStudents: [
                { name: 'Alazar Johnson', score: 88 },
                { name: 'Ruth S.', score: 66 },
                { name: 'Tigabu A.', score: 79 }
            ]
        }
    }
])

const openUnitId = ref(null)

function toggleUnit(id) {
    openUnitId.value = openUnitId.value === id ? null : id
}

function addUnit() {
    // open "Add Unit" modal or route
}

function editUnit(unit) {
    // open edit modal pre-filled
}

function deleteUnit(id) {
    // confirm & call API
}

// track which grading tables are open
const openGrades = ref({})

// store scores per unit/section/student
const gradesMap = reactive({})
const submittedMap = reactive({})

// initialize maps from units data
units.value.forEach(unit => {
    const map = {}
    unit.assignments.forEach(item => {
        item.submittedStudents.forEach(s => {
            map[s.name] = true
            if (!gradesMap[unit.id]) gradesMap[unit.id] = {}
            if (!gradesMap[unit.id]['assignments']) gradesMap[unit.id]['assignments'] = {}
            gradesMap[unit.id]['assignments'][s.name] = s.score
        })
    })
    submittedMap[unit.id] = map
})

function toggleGrades(unitId, section) {
    const key = `${unitId}-${section}`
    openGrades.value[key] = !openGrades.value[key]
    if (!gradesMap[unitId]) gradesMap[unitId] = {}
    if (!gradesMap[unitId][section]) {
        gradesMap[unitId][section] = {}
        students.value.forEach(s => {
            gradesMap[unitId][section][s.name] = null
        })
    }
}

function isGradesOpen(unitId, section) {
    return !!openGrades.value[`${unitId}-${section}`]
}

function addSectionItem(unitId, section) {
    // implement modal or inline form to add new item
    console.log('Add', section, 'to unit', unitId)
}

// tabs
const activeTab = ref('Curriculum')
const tabs = [
    'Curriculum',
    'Discussion',
    'Resources',
    'Gamification',
    'Attendance',
    'Syllabus',
    'Progress',
    'Gradebook',
    'Students'
]

// dummy LMS data
const course = reactive({
    image: "https://astarequivalency.co.uk/app/uploads/2022/06/Product-images-05_1.jpg",
    title: 'Introduction to Biology',
    code: 'BIO101',
    category: 'Science',
    credits: 3,
    startDate: '2025-09-01',
    endDate: '2025-12-15',
    description: 'An introductory course covering cell biology, genetics, evolution & ecology.',
    objectives: [
        'Understand cell structure & function',
        'Explain basic genetic principles',
        'Describe evolutionary mechanisms',
        'Analyze ecological relationships'
    ],
    schedule: {
        time: "08:30 - 09:30",
        day: "Mon | Tue | Wed",
        location: "Rama 301",
    }
})

const instructors = ref([])

function normalizeCourse(data = {}) {
    const subjectName = data?.academic_course?.academic_subject?.name || data?.academic_course?.subjects?.[0]?.name || ''
    const scheduleDays = Array.isArray(data?.class_schedule)
        ? data.class_schedule.join(' | ')
        : data?.class_schedule || ''

    return {
        image: course.image,
        title: data?.academic_course?.name || data?.name || course.title,
        code: data?.academic_course?.code || course.code,
        category: subjectName || course.category,
        credits: data?.academic_course?.credits || course.credits,
        startDate: data?.created_at?.formatted || course.startDate,
        endDate: data?.updated_at?.formatted || course.endDate,
        description: data?.description || course.description,
        objectives: course.objectives,
        schedule: {
            time: [data?.start_time, data?.end_time].filter(Boolean).join(' - ') || course.schedule.time,
            day: scheduleDays || course.schedule.day,
            location: data?.location || course.schedule.location,
        }
    }
}

function normalizeInstructors(data = {}) {
    return [data?.instructor, data?.assistant_instructor]
        .filter(Boolean)
        .map((employee, index) => ({
            name: employee?.name || 'Instructor',
            role: index === 0 ? 'Instructor' : 'Assistant Instructor',
            email: employee?.contact?.email || '',
            avatar: employee?.contact?.photo || `https://i.pravatar.cc/40?img=${index + 1}`,
        }))
}

// utility to chunk array into smaller arrays of size n
function chunkStudents(arr, n) {
    const res = []
    for (let i = 0; i < arr.length; i += n) {
        res.push(arr.slice(i, i + n))
    }
    return res
}

const openThreadId = ref(null)
const replyModalThreadId = ref(null)
const replyText = ref('')

function toggle(id) {
    openThreadId.value = openThreadId.value === id ? null : id
}

function openReplyModal(threadId) {
    replyModalThreadId.value = threadId
    replyText.value = ''
}

function closeReplyModal() {
    replyModalThreadId.value = null
}

function postReply() {
    const thread = discussions.find(t => t.id === replyModalThreadId.value)
    const newComment = {
        id: Date.now(),
        author: { name: 'You' },
        createdAt: 'Just now',
        body: replyText.value
    }
    if (thread) {
        thread.comments.push(newComment)
    } else {
        // new thread
        discussions.push({
            id: Date.now(),
            title: replyText.value.slice(0, 30) + '...',
            author: { name: 'You' },
            createdAt: 'Just now',
            body: replyText.value,
            comments: []
        })
    }
    closeReplyModal()
}


const discussions = [
    {
        id: 1,
        title: 'Confusion about lab 2 schedule',
        author: { name: 'John Smith K.' },
        createdAt: '1h ago',
        body: "I'm unclear on the procedure for the enzyme assay - any tips?",
        comments: [
            {
                id: 11,
                author: { name: 'Lab TA' },
                createdAt: '45m ago',
                body: 'Make sure your buffer is at pH 7.4 before starting the assay.'
            }
        ]
    },
    {
        id: 2,
        title: 'Final Chapter Review and Summary Q&A',
        author: { name: 'John Daniel' },
        createdAt: '2d ago',
        body: 'Can someone explain Punnett squares step-by-step?',
        comments: [
            {
                id: 21,
                author: { name: 'Alice Smith' },
                createdAt: '1d ago',
                body: 'Draw all allele combinations on paper, then map them back to your phenotype table.'
            },
            {
                id: 22,
                author: { name: 'Bob Johnson' },
                createdAt: '20h ago',
                body: 'I wrote a quick guide here: https://example.com/punnett-guide'
            }
        ]
    },

    {
        id: 3,
        title: 'Help with genetics homework',
        author: { name: 'John Daniel' },
        createdAt: '2d ago',
        body: 'Can someone explain Punnett squares step-by-step?',
        comments: [
            {
                id: 21,
                author: { name: 'Alice Smith' },
                createdAt: '1d ago',
                body: 'Draw all allele combinations on paper, then map them back to your phenotype table.'
            },
            {
                id: 22,
                author: { name: 'Bob Johnson' },
                createdAt: '20h ago',
                body: 'I wrote a quick guide here: https://example.com/punnett-guide'
            }
        ]
    }
]

const resources = [
    { id: 1, name: 'Syllabus.pdf', link: '#' },
    { id: 2, name: 'Lecture 1 Slides.ppt', link: '#' },
    { id: 3, name: 'Lab Manual.docx', link: '#' }
]

// if you already receive `status` & `setStatus` as props/ from store, drop this
const status = ref('deactivated')

function toggleStatus() {
    status.value = status.value === 'activated' ? 'deactivated' : 'activated'
}

</script>

<style scoped>
#yourdiv {
    display: flex;
    flex-direction: column;
    max-width: 58vw;
    margin-top: 60px;
}

h4 {
    flex-basis: 140px;
}

#yourdiv table {
    border-collapse: collapse;
    --table-border-width: 1px;
}

#yourdiv th {
    white-space: nowrap;
    position: relative;
}

#yourdiv td {
    border: 1px solid black;
    padding: 8px;
    text-align: center;
}

#yourdiv th>div {
    position: absolute;
    bottom: 0;
    left: 0;
    text-align: left;
    transform:
        translate(calc(100% - var(--table-border-width) / 2), var(--table-border-width)) rotate(315deg);
    transform-origin: 0% calc(100% - var(--table-border-width));
    width: 100%;
}

#yourdiv th[class^="col-"]::before {
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

#yourdiv th>div {
    position: relative;
    z-index: 1;
}

#yourdiv th>div>span {
    position: absolute;
    bottom: 0;
    left: 0;
    border-bottom: var(--table-border-width) solid gray;
}

#yourdiv td {
    border-right: var(--table-border-width) solid gray;
    min-width: 30px;
    text-align: left;
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
