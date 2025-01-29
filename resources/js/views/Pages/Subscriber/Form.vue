<template>
    <FormAction
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        redirect="Subscriber"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.name"
                    name="name"
                    :label="$trans('subscriber.props.name')"
                    v-model:error="formErrors.name"
                    autofocus
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.email"
                    name="email"
                    :label="$trans('subscriber.props.email')"
                    v-model:error="formErrors.email"
                    autofocus
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <DatePicker
                    as="datetime"
                    v-model="form.unsubscribedAt"
                    name="unsubscribedAt"
                    :label="$trans('subscriber.props.unsubscribed_at')"
                    v-model:error="formErrors.unsubscribedAt"
                />
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "SubscriberForm",
}
</script>

<script setup>
import { reactive, onMounted } from "vue"
import { getConfig, getFormErrors } from "@core/helpers/action"
import { cloneDeep } from "@core/utils"

const initForm = {
    name: "",
    email: "",
    unsubscribedAt: "",
}

const initUrl = "subscriber/"
const formErrors = getFormErrors(initUrl)

const form = reactive({ ...initForm })
</script>
