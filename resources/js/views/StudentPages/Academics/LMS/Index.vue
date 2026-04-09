<template>
    <ListItem :init-url="initUrl" :additional-query="{ details: true }" @setItems="setItems">
        <template #header>
            <PageHeader :title="`Classes`">
                <PageHeaderAction url="academicCoursePlanner/coursePlanner/" name="AcademicClass" :title="`Class`"
                    :actions="userActions" :dropdown-actions="dropdownActions" :additional-dropdown-actions-query="{
                        details: true,
                    }" @toggleFilter="showFilter = !showFilter">

                </PageHeaderAction>
            </PageHeader>
        </template>

        <div class="p-4 bg-white rounded-lg">
            <h2 class="text-2xl font-semibold mb-4">All Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="course in courses" :key="course.id"
                    class="bg-white rounded-xl shadow-md hover:shadow-lg overflow-hidden transition-shadow duration-200">
                    <img :src="course.image" :alt="course.title" class="w-full h-40 object-cover" />
                    <div class="p-4">
                        <h3 class="text-lg font-semibold truncate">{{ course.title }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ course.code }} • {{ course.category }}</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-800">{{ course.credits }} Credits</span>
                            <button @click="viewDetail(course)" class="text-blue-600 text-sm hover:underline">
                                View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </ListItem>
</template>

<script>
export default {
    name: "AcademicCoursePlannerList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]
if (perform("batch:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("batch:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "academic/batch/"
const showFilter = ref(false)

const batches = reactive({})

// sample data; replace with API fetch or store integration
const courses = ref([
    {
        id: 1,
        image: 'https://astarequivalency.co.uk/app/uploads/2022/06/Product-images-05_1.jpg',
        title: 'Introduction to Biology',
        code: 'BIO101',
        category: 'Science',
        credits: 3
    },
])

function viewDetail(courseId) {
    router.push({ name: 'StudentLMSDetail', params: { uuid: courseId.uuid || courseId.id } })
}


const setItems = (data) => {
    Object.assign(batches, data)
}
</script>
