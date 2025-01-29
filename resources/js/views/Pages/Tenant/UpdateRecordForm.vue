<template>
    <FormAction
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :uuid="uuid"
        no-data-fetch
        action="updateRecord"
        :init-form="initForm"
        :form="form"
        :keep-adding="false"
        :after-submit="afterSubmit"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <DatePicker
                    v-model="form.expiryDate"
                    name="expiryDate"
                    :label="$trans('tenant.props.expiry_date')"
                    no-clear
                    v-model:error="formErrors.expiryDate"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    name="plan"
                    :label="
                        $trans('global.select', {
                            attribute: $trans('plan.plan'),
                        })
                    "
                    :options="preRequisites.plans"
                    label-prop="name"
                    value-prop="uuid"
                    v-model="form.plan"
                    v-model:error="formErrors.plan"
                />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "TenantUpdateRecordForm",
}
</script>

<script setup>
import { reactive, inject } from "vue"
import { getFormErrors } from "@core/helpers/action"

const emit = defineEmits(["completed"])

const emitter = inject("emitter")

const props = defineProps({
    uuid: {
        type: String,
        default: "",
    },
})

const initForm = {
    plan: [],
    expiryDate: "",
}

const initUrl = "tenant/"
const preRequisites = reactive({
    plans: [],
})

const formErrors = getFormErrors(initUrl)

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const afterSubmit = () => {
    emit("completed")
    emitter.emit("listItems")
}
</script>
