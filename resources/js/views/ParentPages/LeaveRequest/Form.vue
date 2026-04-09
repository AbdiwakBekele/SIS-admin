<template>
    <FormAction :pre-requisites="true" @setPreRequisites="setPreRequisites" :init-url="initUrl" :init-form="initForm"
        :form="form" :set-form="setForm" redirect="StudentLeaveRequest" @resetMediaFiles="resetMediaFiles">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1" v-if="actingAs(['student', 'guardian'], 'any')">
                <BaseSelect name="student" :label="$trans('global.select', {
                    attribute: $trans('student.student'),
                })
                    " v-model="state.selectedStudent" v-model:error="formErrors.student"
                    :options="preRequisites.students" value-prop="uuid" :object-prop="true" @selected="studentSelected"
                    @removed="studentDeselect">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }} ({{
                            slotProps.value.courseName +
                            " " +
                            slotProps.value.batchName
                        }})
                    </template>

                    <template #listOption="slotProps">
                        {{ slotProps.option.name }} ({{
                            slotProps.option.courseName +
                            " " +
                            slotProps.option.batchName
                        }})
                    </template>
                </BaseSelect>
            </div>

            <div class="col-span-3 sm:col-span-1" v-else>
                <BaseSelectSearch name="student" :label="$trans('global.select', {
                    attribute: $trans('student.student'),
                })
                    " v-model="state.selectedStudent" v-model:error="formErrors.student" value-prop="uuid"
                    :object-prop="true" :init-search="fetchData.student" search-key="name"
                    search-action="student/summary" @selected="studentSelected" @removed="studentDeselect"
                    :additional-search-query="{ status: 'all' }">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }}
                    </template>

                    <template #listOption="slotProps">
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>
            <div class="col-span-3 sm:col-span-1">
                <DatePicker v-model:start="form.startDate" v-model:end="form.endDate" name="dateBetween" as="range"
                    :label="$trans('general.date_between')" />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect name="student" :label="$trans('student.leave_request.props.category')"
                    v-model="form.category" v-model:error="formErrors.category" :options="preRequisites.categories"
                    value-prop="uuid" label-prop="name" />
            </div>
            <div class="col-span-3">
                <BaseTextarea :rows="1" v-model="form.reason" name="reason"
                    :label="$trans('student.leave_request.props.reason')" v-model:error="formErrors.reason" />
            </div>
        </div>
        <div class="mt-4 grid grid-cols-1">
            <div class="col">
                <MediaUpload multiple :label="$trans('general.file')" module="student_leave_request" :media="form.media"
                    :media-token="form.mediaToken" @isUpdated="form.mediaUpdated = true"
                    @setHash="(hash) => form.mediaHash.push(hash)" v-model:error="formErrors.media" />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "StudentLeaveRequestForm",
}
</script>

<script setup>
import { reactive, ref } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { cloneDeep } from "@core/utils"
import { getFormErrors, actingAs } from "@core/helpers/action"
import { v4 as uuidv4 } from "uuid"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initForm = {
    student: "",
    startDate: "",
    endDate: "",
    reason: "",
    media: [],
    mediaUpdated: false,
    mediaToken: uuidv4(),
    mediaHash: [],
}

const initUrl = "student/leaveRequest/"
const formErrors = getFormErrors(initUrl)

const isLoading = ref(false)

const preRequisites = reactive({
    students: [],
    categories: [],
})

const state = reactive({
    selectedStudent: null,
})
const form = reactive({ ...initForm })
const fetchData = reactive({
    student: "",
    isLoaded: route.params.uuid ? false : true,
})

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
    Object.assign(form, cloneDeep(initForm))
}

const resetMediaFiles = () => {
    form.mediaToken = uuidv4()
    form.mediaHash = []
}

const setForm = (data) => {
    let student = data.student
    state.selectedStudent = {
        uuid: student.uuid,
        name: student.name,
        codeNumber: student.codeNumber,
        courseName: student.courseName,
        batchName: student.batchName,
        joiningDate: student.joiningDate,
    }
    Object.assign(initForm, {
        ...data,
        student: student.uuid,
        startDate: data.startDate.value,
        endDate: data.endDate.value,
        category: data.category.uuid,
    })
    Object.assign(form, cloneDeep(initForm))

    fetchData.isLoaded = true
}

const studentSelected = (data) => {
    form.student = data ? data.uuid : ""
}

const studentDeselect = () => {
    state.selectedStudent = null
    form.student = ""
}
</script>
