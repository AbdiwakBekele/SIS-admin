<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('student.student'), path: 'Student' },
            {
                label: $trans('student.transfer_request.transfer_request'),
                path: 'StudentTransferRequestList',
            },
        ]"
    >
        <PageHeaderAction
            name="StudentTransferRequest"
            :title="$trans('student.transfer_request.transfer_request')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentTransferRequest' })"
            :refresh="refreshItem"
            @refreshed="refreshItem = false"
        >
            <BaseCard v-if="transferRequest.uuid">
                <template #title>
                    <div class="flex items-center">
                        <span class="mr-2">{{
                            transferRequest.codeNumber
                        }}</span>
                        <BaseBadge :design="transferRequest.status.color">
                            {{ transferRequest.status.label }}
                        </BaseBadge>
                    </div>
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView
                        :label="$trans('student.admission.props.code_number')"
                    >
                        {{ transferRequest.student.codeNumber }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('contact.props.birth_date')">
                        {{ transferRequest.student.birthDate.formatted }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('contact.props.father_name')">
                        {{ transferRequest.student.fatherName }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('contact.props.mother_name')">
                        {{ transferRequest.student.motherName }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('academic.course.course')">
                        {{
                            transferRequest.student.courseName +
                            " " +
                            transferRequest.student.batchName
                        }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('student.admission.props.date')"
                    >
                        {{ transferRequest.student.joiningDate.formatted }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('student.transfer.props.date')"
                    >
                        {{
                            transferRequest.student.leavingDate.formatted || "-"
                        }}
                    </BaseDataView>

                    <BaseDataView
                        :label="
                            $trans(
                                'student.transfer_request.props.request_date'
                            )
                        "
                    >
                        {{ transferRequest.requestDate.formatted }}
                    </BaseDataView>

                    <BaseDataView
                        class="col-span-1 sm:col-span-2"
                        :label="$trans('student.transfer_request.props.reason')"
                    >
                        {{ transferRequest.reason }}
                    </BaseDataView>

                    <BaseDataView
                        :label="
                            $trans(
                                'student.transfer_request.props.processed_at'
                            )
                        "
                    >
                        {{ transferRequest.processedAt.formatted }}
                    </BaseDataView>

                    <BaseDataView
                        :label="
                            $trans('student.transfer_request.props.comment')
                        "
                    >
                        <span
                            :class="{
                                'text-danger':
                                    transferRequest.status.value == 'rejected',
                            }"
                            >{{ transferRequest.comment }}</span
                        >
                    </BaseDataView>
                </dl>

                <div class="mt-4 text-sm space-y-2">
                    <div>
                        <h2 class="font-semibold">
                            {{
                                $trans(
                                    "student.transfer_request.props.application"
                                )
                            }}
                        </h2>
                        <ListMedia
                            section="application"
                            :media="transferRequest.media"
                            :url="`/app/student/transfer-requests/${transferRequest.uuid}/`"
                        />
                    </div>
                </div>
                <template #footer>
                    <ShowButton>
                        <BaseButton
                            v-if="
                                perform('student:transfer-request') &&
                                transferRequest.isEditable
                            "
                            design="primary"
                            @click="
                                router.push({
                                    name: 'StudentTransferRequestEdit',
                                    params: { uuid: transferRequest.uuid },
                                })
                            "
                        >
                            {{ $trans("general.edit") }}
                        </BaseButton>
                    </ShowButton>
                </template>
            </BaseCard>

            <BaseCard
                v-if="
                    perform('student:transfer-request-action') &&
                    transferRequest.uuid &&
                    transferRequest.status.value != 'approved'
                "
            >
                <template #title>
                    {{ $trans("student.transfer_request.props.action") }}
                </template>

                <FormAction
                    no-card
                    :pre-requisites="true"
                    @setPreRequisites="setPreRequisites"
                    :keep-adding="false"
                    :init-url="initUrl"
                    :uuid="transferRequest.uuid"
                    :no-data-fetch="true"
                    action="action"
                    :init-form="initForm"
                    :form="form"
                    :after-submit="afterSubmit"
                >
                    <div class="grid grid-cols-3 gap-6">
                        <div class="col-span-3 sm:col-span-1">
                            <BaseSelect
                                name="status"
                                :label="
                                    $trans('global.select', {
                                        attribute: $trans(
                                            'student.transfer_request.props.status'
                                        ),
                                    })
                                "
                                v-model="form.status"
                                :options="preRequisites.statuses"
                                v-model:error="formErrors.status"
                            />
                        </div>
                        <template v-if="form.status == 'approved'">
                            <div class="col-span-3 sm:col-span-1">
                                <BaseInput
                                    type="text"
                                    v-model="form.transferCertificateNumber"
                                    name="transferCertificateNumber"
                                    :label="
                                        $trans(
                                            'student.transfer_request.props.certificate_number'
                                        )
                                    "
                                    v-model:error="
                                        formErrors.transferCertificateNumber
                                    "
                                />
                            </div>
                            <div class="col-span-3 sm:col-span-1">
                                <BaseSelect
                                    name="reason"
                                    :label="
                                        $trans('global.select', {
                                            attribute: $trans(
                                                'student.transfer.props.reason'
                                            ),
                                        })
                                    "
                                    label-prop="name"
                                    value-prop="uuid"
                                    v-model="form.reason"
                                    :options="preRequisites.reasons"
                                    v-model:error="formErrors.reason"
                                />
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <DatePicker
                                    v-model="form.transferDate"
                                    name="transferDate"
                                    :label="
                                        $trans('student.transfer.props.date')
                                    "
                                    no-clear
                                    v-model:error="formErrors.transferDate"
                                />
                            </div>
                        </template>
                        <div class="col-span-3">
                            <BaseTextarea
                                v-model="form.comment"
                                name="comment"
                                :label="
                                    $trans(
                                        'student.transfer_request.props.comment'
                                    )
                                "
                                v-model:error="formErrors.comment"
                            />
                        </div>
                    </div>
                </FormAction>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "StudentTransferRequestShow",
}
</script>

<script setup>
import { reactive, ref, inject, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform, getFormErrors } from "@core/helpers/action"

const emitter = inject("emitter")

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialTransferRequest = {}

const initForm = {
    status: "",
    reason: "",
    transferDate: "",
    transferCertificateNumber: "",
    comment: "",
}

const initUrl = "student/transferRequest/"

const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    reasons: [],
    statuses: [],
})

const form = reactive({ ...initForm })

const refreshItem = ref(false)

const transferRequest = reactive({ ...initialTransferRequest })

const setItem = (data) => {
    Object.assign(transferRequest, data)
}

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const action = async (action) => {
    emitter.emit("showActionItem", {
        uuid: transferRequest.uuid,
        action: "action",
        data: form,
        confirmation: true,
    })
}

const afterSubmit = () => {
    refreshItem.value = true
}
</script>
