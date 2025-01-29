<template>
    <ConfigPage>
        <template #action> </template>
        <FormAction
            no-card
            :init-url="initUrl"
            data-fetch="chat"
            :init-form="initForm"
            :form="form"
            action="store"
            stay-on
            redirect="Config"
        >
            <CardHeader
                first
                :title="$trans('config.chat.chat_config')"
                :description="$trans('config.chat.chat_info')"
            ></CardHeader>

            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-3 sm:col-span-1">
                    <BaseSwitch
                        reverse
                        v-model="form.enableChat"
                        name="enableChat"
                        :label="$trans('config.chat.props.enable_chat')"
                        v-model:error="formErrors.enableChat"
                    />
                </div>
            </div>
        </FormAction>
    </ConfigPage>
</template>

<script>
export default {
    name: "ConfigChat",
}
</script>

<script setup>
import { reactive } from "vue"
import { useStore } from "vuex"
import { getFormErrors } from "@core/helpers/action"

const store = useStore()

const initUrl = "config/"
const formErrors = getFormErrors(initUrl)

const initForm = {
    enableChat: false,
    type: "chat",
}

const form = reactive({ ...initForm })
</script>
