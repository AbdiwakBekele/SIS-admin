<template>
    <ConfigPage>
        <FormAction
            no-card
            :init-url="initUrl"
            :pre-requisites="false"
            @setPreRequisites="setPreRequisites"
            data-fetch="site"
            :init-form="initForm"
            :form="form"
            action="store"
            stay-on
            :after-submit="afterSubmit"
            redirect="Config"
        >
            <CardHeader
                first
                :title="$trans('config.site.site_config')"
                :description="$trans('config.site.site_info')"
            ></CardHeader>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-3 sm:col-span-1">
                    <BaseSwitch
                        vertical
                        v-model="form.enableSite"
                        name="enableSite"
                        :label="
                            $trans('global.enable', {
                                attribute: $trans('site.site'),
                            })
                        "
                        v-model:error="formErrors.enableSite"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.supportEmail"
                        name="supportEmail"
                        :label="$trans('site.config.support_email')"
                        v-model:error="formErrors.supportEmail"
                    />
                </div>
            </div>
        </FormAction>
    </ConfigPage>
</template>

<script>
export default {
    name: "ConfigSite",
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
    enableSite: false,
    supportEmail: "",
    type: "site",
}

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const afterSubmit = () => {
    store.dispatch("config/get", true)
}
</script>
