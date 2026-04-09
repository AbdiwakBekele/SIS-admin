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
                                <strong>School Year</strong>
                            </label>
                            <select id="year"
                                class="block w-full p-1 px-2 text-[12px] bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select...</option>
                                <option>2024/2025</option>
                                <option>2025/2026</option>
                            </select>
                        </div>
                        <!-- Term -->
                        <div class="col-span-2">
                            <label for="term" class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                <strong>Term</strong>
                            </label>
                            <select id="term"
                                class="block w-full p-1 px-2 text-[12px] bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select...</option>
                                <option>Semester I</option>
                                <option>Semester II</option>
                            </select>
                        </div>

                        <!-- Grade Level -->
                        <div class="col-span-2">
                            <label for="grade" class="block mt-1 text-[12px] font-medium text-gray-900 text-left">
                                <strong>Grade Level</strong>
                            </label>
                            <select id="grade"
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
                            <select id="course"
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
                            <input type="text" id="studentName"
                                class="block w-full p-1 px-2 text-[12px] bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Student Name" />
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-span-9 px-4 py-3 bg-white rounded-md shadow">
            <div v-for="(request, reqIndex) in courseRequests" :key="request.id" class="my-2 border rounded-lg">
                <div class="bg-gray-100 px-4 py-2 flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ request.studentName }}</p>
                        <p class="text-sm text-gray-600">{{ request.schoolYear }} | Grade {{ request.gradeLevel }} |
                            {{ request.term }}
                        </p>
                    </div>
                    <button class="text-blue-600 hover:underline" @click="toggleExpand(reqIndex)">
                        <span v-if="expandedIndex === reqIndex">Collapse</span>
                        <span v-else>View Courses</span>
                    </button>
                </div>
                <div v-if="expandedIndex === reqIndex" class="px-4 py-4">
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 text-left">Course</th>
                                <th class="px-2 py-1 text-left">Action</th>
                                <th class="px-2 py-1 text-left">Reason (if Denied)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(course, idx) in request.requestedCourses" :key="course.id" class="border-t">
                                <td class="px-2 py-1">{{ course.name }}</td>
                                <td class="px-2 py-1">
                                    <select v-model="course.status" class="border border-gray-300 rounded px-7 py-1">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approve</option>
                                        <option value="denied">Deny</option>
                                    </select>
                                </td>
                                <td class="px-2 py-1">
                                    <input v-if="course.status === 'denied'" v-model="course.reason" type="text"
                                        placeholder="Enter denial reason"
                                        class="border border-gray-300 rounded px-2 py-1 w-full" />
                                    <span v-else class="text-gray-500">—</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                            @click="submitRequest(reqIndex)">
                            Submit Decisions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { reactive, ref } from 'vue'

// Sample data; replace with API call in mounted hook or use fetch
const courseRequests = reactive([
    {
        id: 1,
        studentName: 'John Smith K.',
        schoolYear: '2025 - 2026',
        gradeLevel: 9,
        term: 'Semester I',
        requestedCourses: [
            { id: 'c1', name: 'Algebra 1', status: 'pending', reason: '' },
            { id: 'c2', name: 'Physical Science', status: 'pending', reason: '' },
            { id: 'c3', name: 'DE Literature', status: 'pending', reason: '' },
        ],
    },
    {
        id: 2,
        studentName: 'John Daniel',
        schoolYear: '2025 - 2026',
        gradeLevel: 10,
        term: 'Semester II',
        requestedCourses: [
            { id: 'c1', name: 'Algebra 1', status: 'pending', reason: '' },
            { id: 'c2', name: 'Physical Science', status: 'pending', reason: '' },
            { id: 'c3', name: 'DE Literature', status: 'pending', reason: '' },
        ],
    },
    {
        id: 3,
        studentName: 'Aisha Sol',
        schoolYear: '2025 - 2026',
        gradeLevel: 11,
        term: 'Semester I',
        requestedCourses: [
            { id: 'c1', name: 'Biology', status: 'pending', reason: '' },
            { id: 'c3', name: 'DE Literature', status: 'pending', reason: '' },
        ],
    },
])

const expandedIndex = ref(null)

function toggleExpand(index) {
    expandedIndex.value = expandedIndex.value === index ? null : index
}

async function submitRequest(index) {
    const request = courseRequests[index]
    // Prepare payload
    const decisions = request.requestedCourses.map(course => ({
        courseId: course.id,
        action: course.status,
        reason: course.reason || null,
    }))

    try {
        // TODO: replace with actual API call
        await fakeApiSubmit(request.id, decisions)
        alert('Decisions saved successfully!')
        toggleExpand(index)
    } catch (error) {
        console.error(error)
        alert('Failed to save decisions.')
    }
}

// Mock API function
function fakeApiSubmit(requestId, decisions) {
    return new Promise(resolve => setTimeout(resolve, 500))
}
</script>
