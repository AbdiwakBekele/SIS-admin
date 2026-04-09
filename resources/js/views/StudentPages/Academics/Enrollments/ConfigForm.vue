<template>
    <FormAction
        :init-url="initUrl"
        :uuid="batch.uuid"
        no-data-fetch
        action="updateConfig"
        :init-form="initForm"
        :form="form"
        :keep-adding="false"
        :stay-on="true"
        :after-submit="afterSubmit"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3">
                <DatePicker
                    v-model="form.holidayExceptions"
                    name="holidayExceptions"
                    as="multiple"
                    :label="$trans('academic.batch.props.holiday_exceptions')"
                    v-model:error="formErrors.holidayExceptions"
                />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "AcademicBatchConfigForm",
}
</script>

<script setup>
import { reactive, inject, onMounted } from "vue"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"

const emit = defineEmits(["completed"])

const emitter = inject("emitter")

const props = defineProps({
    batch: {
        type: Object,
        default: () => ({}),
    },
})

const initForm = {
    holidayExceptions: "",
}

const initUrl = "academic/batch/"
const formErrors = getFormErrors(initUrl)

const form = reactive({ ...initForm })

const afterSubmit = () => {
    emit("completed")
}

onMounted(() => {
    initForm.holidayExceptions = props.batch.holidayExceptions.join(", ")
    Object.assign(form, cloneDeep(initForm))
})
</script>
