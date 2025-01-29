<template>
    <ConfigPage>
        <template #action>
            <BaseButton
                design="primary"
                v-if="hasCpanelPlatform"
                @click="testConnection"
                >{{ $trans("config.server.test_connection") }}</BaseButton
            >
        </template>
        <FormAction
            no-card
            :init-url="initUrl"
            :pre-requisites="{ data: ['hostingPlatforms'] }"
            @setPreRequisites="setPreRequisites"
            data-fetch="server"
            :init-form="initForm"
            :form="form"
            action="store"
            stay-on
            :after-submit="afterSubmit"
            redirect="Config"
        >
            <CardHeader
                first
                :title="$trans('config.server.server_config')"
                :description="$trans('config.server.server_info')"
            ></CardHeader>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect
                        v-model="form.platform"
                        name="platform"
                        :label="$trans('config.mail.props.platform')"
                        :options="preRequisites.hostingPlatforms"
                        v-model:error="formErrors.platform"
                    />
                </div>
            </div>
            <template v-if="form.platform == 'cpanel'">
                <CardHeader
                    :title="$trans('config.server.platform_cpanel')"
                ></CardHeader>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            leading-text="https://"
                            v-model="form.cpanelHostname"
                            name="cpanelHostname"
                            :label="
                                $trans('config.server.props.cpanel_hostname')
                            "
                            v-model:error="formErrors.cpanelHostname"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.cpanelPort"
                            name="cpanelPort"
                            :label="$trans('config.server.props.cpanel_port')"
                            v-model:error="formErrors.cpanelPort"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.cpanelUsername"
                            name="cpanelUsername"
                            :label="
                                $trans('config.server.props.cpanel_username')
                            "
                            v-model:error="formErrors.cpanelUsername"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.cpanelApiToken"
                            name="cpanelApiToken"
                            :label="
                                $trans('config.server.props.cpanel_api_token')
                            "
                            v-model:error="formErrors.cpanelApiToken"
                        />
                    </div>
                </div>
            </template>
            <div class="mt-4 grid grid-cols-3 gap-4">
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.dbPrefix"
                        name="dbPrefix"
                        :label="$trans('config.server.props.db_prefix')"
                        v-model:error="formErrors.dbPrefix"
                    />
                </div>
            </div>
        </FormAction>
    </ConfigPage>
</template>

<script>
export default {
    name: "ConfigServer",
}
</script>

<script setup>
import { reactive } from "vue"
import { useStore } from "vuex"
import { getConfig, getFormErrors } from "@core/helpers/action"

const store = useStore()

const initUrl = "config/"
const formErrors = getFormErrors(initUrl)
const hasCpanelPlatform =
    getConfig("server.platform").value == "cpanel" ? true : false

const preRequisites = reactive({})
const initForm = {
    platform: "",
    cpanelHostname: "",
    cpanelPort: "",
    cpanelUsername: "",
    cpanelApiToken: "",
    dbPrefix: "",
    type: "server",
}

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const afterSubmit = () => {
    store.dispatch("config/get", true)
}

const testConnection = () => {
    store.dispatch("config/testServerConnection").catch((e) => {})
}
</script>
