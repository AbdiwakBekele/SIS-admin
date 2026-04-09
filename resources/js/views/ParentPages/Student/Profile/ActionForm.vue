<template>
    <FormAction :has-setup-wizard="true" :pre-requisites="true" pre-requisite-custom-url="actionPreRequisite"
        @setPreRequisites="setPreRequisites" :init-url="initUrl" :uuid="registration.uuid" no-data-fetch action="action"
        :no-action-button="form.status == 'pending'" :init-form="initForm" :form="form" :keep-adding="false"
        :after-submit="afterSubmit">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-3">
                <BaseLabel>{{
                    $trans("student.registration.props.status")
                    }}</BaseLabel>
                <BaseRadioGroup top-margin :options="preRequisites.statuses" name="status" v-model="form.status"
                    v-model:error="formErrors.status" horizontal />
            </div>
            <template v-if="form.status == 'approved'">
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput readonly type="text" v-model="form.codeNumber" name="codeNumber"
                        :label="$trans('student.admission.props.code_number')" v-model:error="formErrors.codeNumber" />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <DatePicker v-model="form.date" name="date" :label="$trans('student.admission.props.date')" no-clear
                        v-model:error="formErrors.date" />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect name="batch" :label="registration.course?.name +
                        ' ' +
                        $trans('global.select', {
                            attribute: $trans('academic.batch.batch'),
                        })
                        " v-model="state.selectedBatch" v-model:error="formErrors.batch"
                        :options="preRequisites.batches" :object-prop="true" label-prop="name" value-prop="uuid"
                        @change="batchChange" />
                    <FormInfo v-if="state.selectedBatch">
                        <span>
                            {{
                                $trans("academic.batch.current_strength", {
                                    attribute: state.selectedBatch.maxStrength,
                                })
                            }}
                        </span>
                    </FormInfo>
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect v-model="form.specialPopulation" name="specialPopulation" :label="$trans('student.special_population.special_population')
                        " :options="preRequisites.specialPopulations" label-prop="name" value-prop="uuid"
                        v-model:error="formErrors.specialPopulation" />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect multiple v-model="form.electiveSubjects" name="electiveSubjects"
                        :label="$trans('academic.subject.elective_subject')" :options="preRequisites.electiveSubjects"
                        label-prop="name" value-prop="uuid" v-model:error="formErrors.electiveSubjects" />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect multiple v-model="form.groups" name="groups" :label="$trans('student.group.group')"
                        :options="preRequisites.groups" label-prop="name" value-prop="uuid"
                        v-model:error="formErrors.groups" />
                </div>
                <div class="col-span-3">
                    <BaseSwitch vertical v-model="form.assignFee" name="assignFee" :label="$trans('global.assign', {
                        attribute: $trans('student.fee.fee'),
                    })
                        " v-model:error="formErrors.assignFee" />
                </div>
                <template v-if="form.assignFee">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSelect v-model="form.feeConcession" name="feeConcession" :label="$trans('finance.fee_concession.fee_concession')
                            " :options="preRequisites.feeConcessions" label-prop="name" value-prop="uuid"
                            v-model:error="formErrors.feeConcession" />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSelect v-model="form.transportCircle" name="transportCircle"
                            :label="$trans('transport.circle.circle')" :options="preRequisites.transportCircles"
                            label-prop="name" value-prop="uuid" v-model:error="formErrors.transportCircle" />
                    </div>
                    <div class="col-span-3 sm:col-span-2" v-if="form.transportCircle">
                        <BaseLabel>{{
                            $trans("transport.circle.direction")
                            }}</BaseLabel>
                        <BaseRadioGroup top-margin :options="preRequisites.directions" name="direction"
                            v-model="form.direction" v-model:error="formErrors.direction" horizontal />
                    </div>
                    <div class="col-span-3 space-y-2">
                        <BaseLabel>{{
                            $trans("student.fee.opted_fee")
                            }}</BaseLabel>
                        <div class="flex flex-wrap gap-y-2">
                            <div class="w-1/3" v-for="feeHead in preRequisites.feeHeads" :key="feeHead.uuid">
                                <BaseArrayCheckbox v-model:items="form.optedFeeHeads" :value="feeHead.uuid"
                                    :label="feeHead.name" />
                            </div>
                        </div>
                    </div>
                </template>
                <div class="col-span-3">
                    <BaseSwitch vertical v-model="form.createUserAccount" name="createUserAccount" :label="$trans('global.create', {
                        attribute: $trans('contact.user_account'),
                    })
                        " v-model:error="formErrors.createUserAccount" />
                </div>
                <template v-if="form.createUserAccount">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput type="text" v-model="form.email" name="email"
                            :label="$trans('contact.login.props.email')" v-model:error="formErrors.email" />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput type="text" v-model="form.username" name="username"
                            :label="$trans('contact.login.props.username')" v-model:error="formErrors.username" />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput :type="state.hidePassword ? 'password' : 'text'" v-model="form.password"
                            name="password" :label="$trans('contact.login.props.password')"
                            v-model:error="formErrors.password">
                            <template #additional-label>
                                <div class="flex space-x-2">
                                    <i class="fas fa-key cursor-pointer" @click="generatePassword" v-tooltip="$trans('global.generate', {
                                        attribute: $trans(
                                            'auth.login.props.password'
                                        ),
                                    })
                                        "></i><i v-if="form.password" class="fas fa-eye cursor-pointer" @click="
                                            state.hidePassword =
                                            !state.hidePassword
                                            "></i>
                                </div>
                            </template>
                        </BaseInput>
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput type="password" v-model="form.passwordConfirmation" name="passwordConfirmation"
                            :label="$trans(
                                'contact.login.props.password_confirmation'
                            )
                                " v-model:error="formErrors.passwordConfirmation" />
                    </div>
                </template>
                <div class="col-span-3 sm:col-span-3">
                    <BaseTextarea v-model="form.remarks" name="remarks"
                        :label="$trans('student.admission.props.remarks')" v-model:error="formErrors.remarks" />
                </div>
            </template>
            <template v-if="form.status == 'rejected'">
                <div class="col-span-3 sm:col-span-3">
                    <BaseTextarea v-model="form.rejectionRemarks" name="rejectionRemarks" :label="$trans(
                        'student.registration.props.rejection_remarks'
                    )
                        " v-model:error="formErrors.rejectionRemarks" />
                </div>
            </template>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "RegistrationActionForm",
}
</script>

<script setup>
import { reactive, inject } from "vue"
import { getFormErrors } from "@core/helpers/action"
import { strongPassword } from "@core/helpers/string"

const emit = defineEmits(["completed"])

const emitter = inject("emitter")

const props = defineProps({
    registration: {
        type: Object,
        default() {
            return {}
        },
    },
})

const initForm = {
    status: props.registration?.status?.value || "",
    rejectionRemarks: "",
    date: "",
    codeNumber: "",
    batch: "",
    specialPopulation: "",
    electiveSubjects: [],
    groups: [],
    assignFee: false,
    feeConcession: "",
    transportCircle: "",
    direction: "",
    optedFeeHeads: [],
    createUserAccount: false,
    email: "",
    username: "",
    password: "",
    passwordConfirmation: "",
    remarks: "",
}

const initUrl = "student/registration/"
const formErrors = getFormErrors(initUrl)
const state = reactive({
    selectedBatch: null,
    hidePassword: true,
})
const preRequisites = reactive({
    statuses: [],
    batches: [],
    feeHeads: [],
    specialPopulations: [],
    electiveSubjects: [],
    groups: [],
    directions: [],
    transportCircles: [],
    feeConcessions: [],
    codeNumber: "",
})

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
    form.codeNumber = data.codeNumber
    form.username = data.codeNumber
    form.email = props.registration.contact.email
}

const batchChange = (batch) => {
    form.batch = batch?.uuid || ""
}

const generatePassword = () => {
    var password = strongPassword(12)
    form.password = password
    form.passwordConfirmation = password
}

const afterSubmit = (data) => {
    emit("completed")
}
</script>
