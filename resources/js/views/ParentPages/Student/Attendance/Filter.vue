<template>

    <div class="p-4 bg-white rounded-lg">
        <!-- Toggle Between Filters & Cards -->
        <div class="mb-4 flex space-x-2">
            <button :class="[
                'px-2 py-2 rounded-lg text-[12px]',
                viewMode === 'cards'
                    ? 'bg-gray-600 text-white'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]" @click="viewMode = 'cards'">
                Courses
            </button>
        </div>

        <!-- Card View with Images -->
        <div class="grid grid-cols-8 gap-4">
            <div v-for="course in dummyCourses" :key="course.uuid" @click="selectCourse(course)"
                class="col-span-1 bg-white border border-gray-200 rounded-lg shadow hover:shadow-md cursor-pointer transition overflow-hidden">
                <!-- Course Image -->
                <img :src="course.url" alt="Course cover" class="w-full h-20 object-cover" />

                <!-- Course Details -->
                <div class="p-4">
                    <p class="text-sm font-semibold mb-1">{{ course.name }}</p>
                    <p class="text-[10px] text-gray-500">Section: {{ course.section }}</p>
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>
import { reactive, ref, inject, onMounted } from "vue"
import { useRoute } from "vue-router"
import { useStore } from "vuex"
import { getFormErrors, perform } from "@core/helpers/action"

const moment = inject("moment")

const route = useRoute()
const store = useStore()

const emit = defineEmits(["hide"])

const props = defineProps({
    initUrl: {
        type: String,
        default: "",
    },
    preRequisites: {
        type: Object,
        default() {
            return {}
        },
    },
})

const initForm = {
    gradeLevel: "",
    date: moment().format("YYYY-MM-DD"),
    section: "",
    // subject: "",
    // session: "",
    detail: false,
}

const isLoading = ref(false)

// control which view is shown
const viewMode = ref("filters")

const dummyCourses = [
    { uuid: "alg-1", name: "Algebra I", section: "A", url: "https://ebus.ams.org/ProductImages/gsm-56-01.jpg" },
    { uuid: "bio-1", name: "Biology 101", section: "B", url: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1CWQm8Z6dns_9GBVJwhroBiX8U9hogC5ARw&s" },
    { uuid: "hist-1", name: "World History", section: "C", url: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfvWQSBMAwJfX72LeUomZoNewREURYgLjcNg&s" },
    { uuid: "phys-1", name: "Fundamentals of Physics", section: "D", url: "data:image/jpeg;base64,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" },
];

function selectCourse(course) {
    form.course = course.uuid
    form.section = course.section
    // then you can emit or auto‐submit if needed
    isLoading.value = true;

    setTimeout(() => {
        isLoading.value = false
        // 🤚 if you need to trigger further actions (e.g. fetch or emit), do it here
    }, 1000)
}

const form = reactive({ ...initForm })
const formErrors = getFormErrors(props.initUrl)

const state = reactive({
    methods: props.preRequisites.methods,
    sessions: props.preRequisites.sessions,
    subjects: [],
    gradeLevels: props.preRequisites.gradeLevels,
    sections: props.preRequisites.sections,
})
const fetchData = reactive({
    batch: "",
    subject: "",
    isLoaded: route.query.batch ? false : true,
})

const batchSelected = async (batchUuid) => {
    if (!form.method == "batch_wise") {
        return
    }

    if (!batchUuid) {
        form.batch = ""
        form.subject = ""
        state.subjects = []
        return
    }

    form.batch = batchUuid || ""
    state.subjects = []
    form.subject = ""
    isLoading.value = true
    await store
        .dispatch("academic/batch/listSubjects", {
            uuid: batchUuid || "",
        })
        .then((response) => {
            state.subjects = response
            isLoading.value = false
        })
        .catch((e) => {
            isLoading.value = false
        })
}


// State for the options (you can replace these with your actual data)
// all options
const stateData = reactive({
    methods: [
        { name: 'All Students', value: 'all_students' },
        { name: 'Division', value: 'division' },
        { name: 'Classes', value: 'classes' },
        { name: 'Courses', value: 'courses' }
    ],
    divisions: [
        { name: 'Elementary', value: 'Elementary' },
        { name: 'Middle School', value: 'Middle School' },
        { name: 'High School', value: 'High School' }
    ],
    gradeLevels: [
        { name: 'Grade 1', uuid: 'g1' },
        { name: 'Grade 2', uuid: 'g2' },
        { name: 'Grade 3', uuid: 'g3' }
    ],
    subjects: [
        { name: 'Math', uuid: 'math' },
        { name: 'Science', uuid: 'science' }
    ],
    courses: [
        { name: 'Algebra', uuid: 'alg' },
        { name: 'Biology', uuid: 'bio' }
    ],
    sections: [
        { name: 'Section A', uuid: 'A' },
        { name: 'Section B', uuid: 'B' }
    ]
})

onMounted(async () => {
    fetchData.batch = route.query.batch
    form.batch = route.query.batch

    if (route.query.batch) {
        await batchSelected(route.query.batch)
        fetchData.subject = route.query.subject
        form.subject = route.query.subject
    }

    fetchData.isLoaded = true
})
</script>
