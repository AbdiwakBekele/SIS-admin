<template>
    <FormAction
        :pre-requisites="true"
        pre-requisite-custom-url="paymentPreRequisite"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :uuid="registration.uuid"
        no-data-fetch
        action="payment"
        :init-form="initForm"
        :form="form"
        :keep-adding="false"
        :after-submit="afterSubmit"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    disabled
                    currency
                    v-model="form.amount"
                    name="amount"
                    :label="$trans('academic.course.props.registration_fee')"
                    v-model:error="formErrors.amount"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <DatePicker
                    v-model="form.date"
                    name="date"
                    :label="$trans('student.registration.props.payment_date')"
                    no-clear
                    v-model:error="formErrors.date"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    v-model="form.ledger"
                    name="ledger"
                    :label="$trans('finance.ledger.ledger')"
                    :options="preRequisites.ledgers"
                    :object-prop="true"
                    label-prop="name"
                    value-prop="uuid"
                    v-model:error="formErrors['ledger.uuid']"
                />
                <LedgerBalance :ledger="form.ledger" />
            </div>
        </div>

        <div class="mt-4 grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    v-model="form.paymentMethod"
                    name="paymentMethod"
                    :label="$trans('finance.payment_method.payment_method')"
                    label-prop="name"
                    value-prop="uuid"
                    :options="preRequisites.paymentMethods"
                    v-model:error="formErrors.paymentMethod"
                />
            </div>
            <PaymentMethodInput
                :selected-payment-method="selectedPaymentMethod"
                v-model:instrumentNumber="form.instrumentNumber"
                v-model:instrumentDate="form.instrumentDate"
                v-model:clearingDate="form.clearingDate"
                v-model:bankDetail="form.bankDetail"
                v-model:referenceNumber="form.referenceNumber"
                v-model:formErrors="formErrors"
            />
            <div class="col-span-3">
                <BaseTextarea
                    v-model="form.remarks"
                    name="remarks"
                    :label="$trans('finance.transaction.props.remarks')"
                    v-model:error="formErrors.remarks"
                />
            </div>
        </div>

        <template #additionalButton>
            <BaseButton design="success" @click="proceedWithoutPayment">
                {{ $trans("student.registration.proceed_without_payment") }}
            </BaseButton>
        </template>
    </FormAction>
</template>

<script>
export default {
    name: "RegistrationPaymentForm",
}
</script>

<script setup>
import { ref, reactive, computed } from "vue"
import { useRoute } from "vue-router"
import { useStore } from "vuex"
import { getFormErrors } from "@core/helpers/action"
import { confirmAction } from "@core/helpers/alert"

const route = useRoute()
const store = useStore()

const emit = defineEmits(["completed"])

const props = defineProps({
    registration: {
        type: Object,
        default() {
            return {}
        },
    },
})

const initForm = {
    amount: props.registration.fee.value,
    date: "",
    ledger: {},
    paymentMethod: "",
    instrumentNumber: "",
    instrumentDate: "",
    clearingDate: "",
    bankDetail: "",
    referenceNumber: "",
    remarks: "",
}

const initUrl = "student/registration/"
const formErrors = getFormErrors(initUrl)

const selectedPaymentMethod = computed(() => {
    if (!form.paymentMethod) {
        return {}
    }

    return preRequisites.paymentMethods.find(
        (paymentMethod) => paymentMethod.uuid === form.paymentMethod
    )
})

const isLoading = ref(false)

const preRequisites = reactive({
    paymentMethods: [],
    ledgers: [],
})

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const afterSubmit = (data) => {
    emit("completed")
}

const proceedWithoutPayment = async () => {
    if (!(await confirmAction())) {
        return
    }

    isLoading.value = true
    await store
        .dispatch(initUrl + "skipPayment", {
            uuid: route.params.uuid,
        })
        .then((response) => {
            isLoading.value = false
            emit("completed")
        })
        .catch((e) => {
            isLoading.value = false
        })
}
</script>
