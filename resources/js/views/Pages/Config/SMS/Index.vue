<template>
    <ConfigPage>
        <template #action>
            <BaseButton design="primary" @click="testSMS">{{
                $trans("config.sms.test_sms")
            }}</BaseButton>
        </template>
        <FormAction
            no-card
            :init-url="initUrl"
            :pre-requisites="{ data: ['smsDrivers'] }"
            @setPreRequisites="setPreRequisites"
            data-fetch="sms"
            :init-form="initForm"
            :form="form"
            action="store"
            stay-on
            redirect="Config"
        >
            <CardHeader
                first
                :title="$trans('config.sms.sms_config')"
                :description="$trans('config.sms.sms_info')"
            ></CardHeader>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect
                        v-model="form.driver"
                        name="driver"
                        :label="$trans('config.sms.props.driver')"
                        :options="preRequisites.smsDrivers"
                        v-model:error="formErrors.driver"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.senderId"
                        name="senderId"
                        :label="$trans('config.sms.props.sender_id')"
                        v-model:error="formErrors.senderId"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.testNumber"
                        name="testNumber"
                        :label="$trans('config.sms.props.test_number')"
                        v-model:error="formErrors.testNumber"
                    />
                </div>
            </div>
            <template v-if="form.driver == 'twilio'">
                <CardHeader :title="$trans('config.sms.twilio')"></CardHeader>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.apiKey"
                            name="apiKey"
                            :label="$trans('config.sms.props.api_key')"
                            v-model:error="formErrors.apiKey"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.apiSecret"
                            name="apiSecret"
                            :label="$trans('config.sms.props.api_secret')"
                            v-model:error="formErrors.apiSecret"
                        />
                    </div>
                </div>
            </template>
            <template v-if="form.driver == 'msg91'">
                <CardHeader :title="$trans('config.sms.msg91')"></CardHeader>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.apiKey"
                            name="apiKey"
                            :label="$trans('config.sms.props.api_key')"
                            v-model:error="formErrors.apiKey"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.testTemplateId"
                            name="testTemplateId"
                            label="Test Template ID"
                            v-model:error="formErrors.testTemplateId"
                        />
                    </div>
                </div>
            </template>
            <template v-if="form.driver == 'custom'">
                <CardHeader :title="$trans('config.sms.custom')"></CardHeader>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.apiUrl"
                            name="apiUrl"
                            :label="$trans('config.sms.props.api_url')"
                            v-model:error="formErrors.apiUrl"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSelect
                            v-model="form.apiMethod"
                            name="apiMethod"
                            :label="$trans('config.sms.props.api_method')"
                            :options="[
                                {
                                    label: 'GET',
                                    value: 'GET',
                                },
                                {
                                    label: 'POST',
                                    value: 'POST',
                                },
                            ]"
                            v-model:error="formErrors.apiMethod"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.numberPrefix"
                            name="numberPrefix"
                            :label="$trans('config.sms.props.number_prefix')"
                            v-model:error="formErrors.numberPrefix"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.senderIdParam"
                            name="senderIdParam"
                            :label="$trans('config.sms.props.sender_id_param')"
                            v-model:error="formErrors.senderIdParam"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.receiverParam"
                            name="receiverParam"
                            :label="$trans('config.sms.props.receiver_param')"
                            v-model:error="formErrors.receiverParam"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.messageParam"
                            name="messageParam"
                            :label="$trans('config.sms.props.message_param')"
                            v-model:error="formErrors.messageParam"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.templateIdParam"
                            name="templateIdParam"
                            :label="
                                $trans('config.sms.props.template_id_param')
                            "
                            v-model:error="formErrors.templateIdParam"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseTextarea
                            v-model="form.apiHeaders"
                            name="apiHeaders"
                            :label="$trans('config.sms.props.api_headers')"
                            v-model:error="formErrors.apiHeaders"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseTextarea
                            v-model="form.additionalParams"
                            name="additionalParams"
                            :label="
                                $trans('config.sms.props.additional_params')
                            "
                            v-model:error="formErrors.additionalParams"
                        />
                    </div>
                </div>
            </template>
        </FormAction>
    </ConfigPage>
</template>

<script>
export default {
    name: "ConfigSMS",
}
</script>

<script setup>
import { reactive } from "vue"
import { useStore } from "vuex"
import { getFormErrors } from "@core/helpers/action"

const store = useStore()

const initUrl = "config/"
const formErrors = getFormErrors(initUrl)

const preRequisites = reactive({})
const initForm = {
    driver: "",
    senderId: "",
    testNumber: "",
    testTemplateId: "",
    apiKey: "",
    apiSecret: "",
    apiUrl: "",
    apiMethod: "",
    numberPrefix: "",
    senderIdParam: "",
    receiverParam: "",
    messageParam: "",
    templateIdParam: "",
    apiHeaders: "",
    additionalParams: "",
    type: "sms",
}

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const testSMS = () => {
    store.dispatch("config/testSMS").catch((e) => {})
}
</script>
