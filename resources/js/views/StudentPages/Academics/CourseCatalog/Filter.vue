<template>
    <FilterForm :init-form="initForm" :form="form" @hide="emit('hide')">

        <div class="grid grid-cols-12 gap-4">

            <!-- Method: Division -->
            <div class="col-span-4">
                <BaseSelect v-model="form.division" name="division" :label="`Division`" :options="stateData.divisions"
                    label-prop="name" value-prop="value" />
            </div>

            <!-- Subjects -->
            <div class="col-span-4" v-if="form.division">
                <BaseSelect class="col-span-4 sm:col-span-1" v-model="form.subject" name="subject" :label="`Subject`"
                    :options="stateData.subjects" label-prop="name" value-prop="uuid" />
            </div>

            <!-- GradeLevel -->
            <div class="col-span-4" v-if="form.subject">
                <BaseSelect class="col-span-4 sm:col-span-1" v-model="form.gradeLevel" name="gradeLevel"
                    :label="`Grade Level`" :options="stateData.gradeLevels" label-prop="name" value-prop="uuid" />
            </div>

        </div>


    </FilterForm>
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
