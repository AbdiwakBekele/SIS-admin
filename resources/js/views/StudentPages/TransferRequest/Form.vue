<template>
    <FormAction :pre-requisites="true" @setPreRequisites="setPreRequisites" :init-url="initUrl" :init-form="initForm"
        :form="form" :set-form="setForm" redirect="StudentTransferRequest" @resetMediaFiles="resetMediaFiles">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <template v-if="actingAs(['student', 'guardian'], 'any')">
                    <BaseSelect name="student" :label="$trans('global.select', {
                        attribute: $trans('student.student'),
                    })
                        " v-model="state.selectedStudent" v-model:error="formErrors.student"
                        :options="preRequisites.students" value-prop="uuid" :object-prop="true"
                        @selected="studentSelected" @removed="studentDeselect">
                        <template #selectedOption="slotProps">
                            {{ slotProps.value.name }}
                        </template>

                        <template #listOption="slotProps">
                            {{ slotProps.option.name }}
                        </template>
                    </BaseSelect>
                </template>
                <template v-else>
                    <div v-if="route.params.uuid && state.selectedStudent">
                        {{ state.selectedStudent.name }}
                        <TextMuted block>{{
                            state.selectedStudent.codeNumber
                        }}</TextMuted>
                        <TextMuted block>{{ $trans("student.admission.props.date") }}:
                            {{
                                state.selectedStudent.joiningDate.formatted
                            }}
                        </TextMuted>
                    </div>
                    <BaseSelectSearch v-if="!route.params.uuid" name="student" :label="$trans('global.select', {
                        attribute: $trans('student.student'),
                    })
                        " v-model="state.selectedStudent" v-model:error="formErrors.student" value-prop="uuid"
                        :object-prop="true" :init-search="fetchData.student" search-key="name"
                        search-action="student/summary" @selected="studentSelected" @removed="studentDeselect">
                        <template #selectedOption="slotProps">
                            {{ slotProps.value.name }}
                        </template>

                        <template #listOption="slotProps">
                            {{ slotProps.option.name }}
                            <!-- ({{
                                slotProps.option.courseName +
                                " " +
                                slotProps.option.batchName
                            }}) -->
                        </template>
                    </BaseSelectSearch>
                </template>

                <div class="mt-2 text-sm" v-if="!route.params.uuid && state.selectedStudent">
                    <TextMuted block>{{
                        state.selectedStudent.codeNumber
                    }}</TextMuted>
                    <div>
                        {{ $trans("student.admission.props.date") }}:
                        {{ state.selectedStudent.joiningDate.formatted }}
                    </div>
                    <div v-if="state.selectedStudent.leavingDate.value">
                        {{ $trans("student.transfer.props.date") }}:
                        {{ state.selectedStudent.leavingDate.formatted }}
                    </div>
                    <div v-if="state.feeSummary.balanceFee" class="font-semibold" :class="{
                        'text-red-500':
                            state.feeSummary.balanceFee.value > 0,
                        'text-green-500':
                            state.feeSummary.balanceFee.value < 0,
                    }">
                        {{
                            $trans("finance.fee.balance") +
                            ": " +
                            state.feeSummary.balanceFee.formatted
                        }}
                    </div>
                </div>
            </div>
            <div class="col-span-3 sm:col-span-1" v-if="!actingAs(['student', 'guardian'], 'any')">
                <DatePicker v-model="form.requestDate" name="requestDate" :label="$trans('student.transfer_request.props.request_date')
                    " no-clear v-model:error="formErrors.requestDate" />
            </div>
            <div class="col-span-3">
                <BaseTextarea :rows="1" v-model="form.reason" name="reason"
                    :label="$trans('student.transfer_request.props.reason')" v-model:error="formErrors.reason" />
            </div>
        </div>
        <div class="mt-4 grid grid-cols-1" v-if="
            state.selectedStudent &&
            (state.feeSummary.balanceFee?.value == 0 || route.params.uuid)
        ">
            <div class="col">
                <MediaUpload multiple :label="$trans('general.file')" module="transfer_request" section="application"
                    :media="form.application" :media-token="form.mediaToken" @isUpdated="form.mediaUpdated = true"
                    @setHash="(hash) => form.mediaHash.push(hash)" v-model:error="formErrors.application" />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "StudentTransferForm",
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
    requestDate: null,
    reason: "",
    application: [],
    mediaUpdated: false,
    mediaToken: uuidv4(),
    mediaHash: [],
}

const initUrl = "student/transferRequest/"
const formErrors = getFormErrors(initUrl)

const isLoading = ref(false)

const preRequisites = reactive({
    students: [],
})

const state = reactive({
    selectedStudent: null,
    feeSummary: {},
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
        courseName: student.courseName,
        batchName: student.batchName,
        codeNumber: student.codeNumber,
        joiningDate: student.joiningDate,
    }
    Object.assign(initForm, {
        ...data,
        student: student.uuid,
        requestDate: data.requestDate.value,
        application: data.media,
    })
    Object.assign(form, cloneDeep(initForm))

    fetchData.isLoaded = true
}

const getFeeSummary = async (uuid) => {
    isLoading.value = true
    await store
        .dispatch("student/getFeeSummary", {
            uuid,
        })
        .then((response) => {
            isLoading.value = false
            state.feeSummary = response
        })
        .catch((e) => {
            isLoading.value = false
        })
}

const studentSelected = (data) => {
    form.student = data ? data.uuid : ""
    if (data) {
        getFeeSummary(data.uuid)
    }
}

const studentDeselect = () => {
    state.selectedStudent = null
    form.student = ""
    state.feeSummary = {}
}
</script>
