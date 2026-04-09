<template>
    <ListItem :init-url="initUrl" :pre-requisites="true" @setPreRequisites="setPreRequisites" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('school_setup.class.class')">
                <PageHeaderAction url="school-setup/academic_classes/" name="ParentClassList"
                    :title="$trans('school_setup.class.class')" :actions="userActions"
                    :dropdown-actions="dropdownActions" @toggleFilter="showFilter = !showFilter">
                </PageHeaderAction>
            </PageHeader>
        </template>

        <template #filter>
            <FilterForm v-if="showFilter" @refresh="emitter.emit('listItems')" :pre-requisites="preRequisites"
                @hide="showFilter = false" />
        </template>

        <div class="p-4 bg-white rounded-lg">
            <h2 class="text-2xl font-semibold mb-4">All Section Classes</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="cls in classes.data" :key="cls.uuid"
                    class="bg-white rounded-xl shadow-md hover:shadow-lg hover:shadow-blue-200/50 hover:ring-2 hover:ring-blue-300/30 overflow-hidden transition-all duration-200 cursor-pointer"
                    @click="viewDetail(cls.uuid)">
                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
                        <img :src="getClassImage(cls)" @error="handleImageError($event)" :alt="cls.name"
                            :class="isLogoImage(cls) ? 'max-w-full max-h-full object-contain' : 'w-full h-full object-cover'" />
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold truncate">{{ cls.name }}</h3>
                        <p v-if="cls.section" class="text-xs text-gray-500">Section {{ cls.section }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ cls.academicCourse?.code || '' }} •
                            {{ cls.academicCourse?.academicSubject?.name || 'N/A' }}
                        </p>
                        <div class="mt-3 flex justify-between items-center text-xs text-gray-600">
                            <span v-if="cls.instructor">
                                <i class="fa-solid fa-chalkboard-user mr-1"></i>
                                {{ cls.instructor.contact?.name || 'Unknown' }}
                            </span>
                            <span v-if="cls.enrollmentCount !== undefined">
                                <i class="fa-solid fa-users mr-1"></i>
                                {{ cls.enrollmentCount }}{{ cls.enrollmentSize ? `/${cls.enrollmentSize}` : '' }}
                            </span>
                        </div>
                        <div class="mt-2">
                            <button @click.stop="viewDetail(cls.uuid)" class="text-blue-600 text-sm hover:underline">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!classes.data || classes.data.length === 0" class="text-center py-12 text-gray-400">
                <i class="fa-solid fa-chalkboard-user text-4xl mb-3"></i>
                <p>No section classes found</p>
            </div>
        </div>
    </ListItem>
</template>

<script>
export default {
    name: "ParentAcademicClassList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform, getConfig } from "@core/helpers/action"
import FilterForm from "@views/Pages/Academics/ClassList/Filter.vue"

const router = useRouter()
const emitter = inject("emitter")

let userActions = ["filter"]

let dropdownActions = []
if (perform("batch:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "schoolSetup/academicClass/"
const showFilter = ref(false)

const classes = reactive({
    data: [],
    headers: [],
    meta: {},
})

const preRequisites = reactive({
    academicCourses: [],
    schoolYears: [],
    employees: [],
    subjects: [],
})

const getLightLogo = getConfig("assets.logoLight")

const setItems = (data) => {
    Object.assign(classes, data)
}

const setPreRequisites = (data) => {
    Object.assign(preRequisites, {
        academicCourses: data.academicCourses || [],
        schoolYears: data.schoolYears || [],
        employees: data.employees || [],
        subjects: data.subjects || [],
    })
}

function getClassImage(cls) {
    // First try to use the class's own image if available
    if (cls.cover_image || cls.image || cls.banner_image) {
        return cls.cover_image || cls.image || cls.banner_image
    }

    // Fallback to school logo
    return getLightLogo.value || '/images/logo.png'
}

function isLogoImage(cls) {
    // Check if we're using the logo (no class image)
    const hasClassImage = cls.cover_image || cls.image || cls.banner_image
    return !hasClassImage
}

function handleImageError(event) {
    // If image fails to load, replace with school logo
    const logoUrl = getLightLogo.value || '/images/logo.png'
    if (event.target.src !== logoUrl) {
        event.target.src = logoUrl
        // Add a class or data attribute to indicate it's a logo
        event.target.classList.add('object-contain')
        event.target.classList.remove('object-cover')
    }
}

function viewDetail(classUuid) {
    router.push({ name: 'ParentClassListDetail', params: { uuid: classUuid } })
}
</script>
