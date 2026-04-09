<template>
    <FormAction
        :has-setup-wizard="false"
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        :set-form="setForm"
    >
        <div class="grid grid-cols-4 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    v-model="form.redemption_option_id"
                    name="redemption_option_id"
                    :label="$trans('gamification.redemption_option.redemption_option')"
                    label-prop="redemptionOption"
                    value-prop="uuid"
                    :options="preRequisites.redemptionOptions"
                />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "MyStudentRedemptionRequestForm",
}
</script>

<script setup>
import { reactive, computed } from "vue"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"
import { useStore } from "vuex"

const store = useStore()

const initForm = {
    redemption_option_id: "",
    status: "Pending",
}

const initUrl = "gamification/redemptionRequest/"
const formErrors = computed(() => store.state['gamification/redemptionRequest'].formErrors)

const preRequisites = reactive({
    redemptionOptions: [],
})

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
        status: "Pending",
    })
    Object.assign(form, cloneDeep(initForm))
}
</script>