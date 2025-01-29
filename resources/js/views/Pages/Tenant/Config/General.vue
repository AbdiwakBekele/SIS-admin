<template>
    <PageHeader
        :title="$trans(route.meta.label)"
        :navs="[{ label: $trans('tenant.tenant'), path: 'Tenant' }]"
    >
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <FormAction
            :pre-requisites="{ data: ['timezones', 'currencies'] }"
            @setPreRequisites="setPreRequisites"
            :init-url="initUrl"
            data-fetch="tenant"
            action="store"
            :init-form="initForm"
            :form="form"
            stay-on
            redirect="Tenant"
        >
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.trialPeriod"
                        name="trialPeriod"
                        :label="$trans('tenant.config.props.trial_period')"
                        v-model:error="formErrors.trialPeriod"
                        :trailing-text="$trans('list.durations.days')"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseSwitch
                        vertical
                        v-model="form.enableTrialEndsOnSubscription"
                        name="enableTrialEndsOnSubscription"
                        :label="
                            $trans(
                                'tenant.config.props.trial_ends_on_subscription'
                            )
                        "
                        v-model:error="formErrors.enableTrialEndsOnSubscription"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.subscriptionEndAlert"
                        name="subscriptionEndAlert"
                        :label="
                            $trans('tenant.config.props.subscription_end_alert')
                        "
                        v-model:error="formErrors.subscriptionEndAlert"
                        :trailing-text="$trans('list.durations.days')"
                    />
                </div>
                <div class="col-span-3">
                    <BaseTextarea
                        v-model="form.unavailableSubdomain"
                        name="unavailableSubdomain"
                        :label="
                            $trans('tenant.config.props.unavailable_subdomain')
                        "
                        v-model:error="formErrors.unavailableSubdomain"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect
                        v-model="form.defaultTimezone"
                        name="defaultTimezone"
                        :label="$trans('tenant.config.props.default_timezone')"
                        :options="preRequisites.timezones"
                        v-model:error="formErrors.defaultTimezone"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.defaultLocale"
                        name="defaultLocale"
                        :label="$trans('tenant.config.props.default_locale')"
                        v-model:error="formErrors.defaultLocale"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect
                        v-model="form.defaultCurrency"
                        name="defaultCurrency"
                        :label="$trans('tenant.config.props.default_currency')"
                        :options="preRequisites.currencies"
                        v-model:error="formErrors.defaultCurrency"
                    >
                        <template #selectedOption="slotProps">
                            {{ slotProps.value.description }} ({{
                                slotProps.value.symbol
                            }})
                        </template>

                        <template #listOption="slotProps">
                            {{ slotProps.option.description }} ({{
                                slotProps.option.symbol
                            }})
                        </template>
                    </BaseSelect>
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.defaultAppName"
                        name="defaultAppName"
                        :label="$trans('tenant.config.props.default_app_name')"
                        v-model:error="formErrors.defaultAppName"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.defaultAuthor"
                        name="defaultAuthor"
                        :label="$trans('tenant.config.props.default_author')"
                        v-model:error="formErrors.defaultAuthor"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.defaultFooterCredit"
                        name="defaultFooterCredit"
                        :label="
                            $trans('tenant.config.props.default_footer_credit')
                        "
                        v-model:error="formErrors.defaultFooterCredit"
                    />
                </div>
            </div>
        </FormAction>
    </ParentTransition>
</template>

<script>
export default {
    name: "TenantConfigGeneral",
}
</script>

<script setup>
import { inject, reactive, computed } from "vue"
import { useRoute } from "vue-router"
import { getFormErrors } from "@core/helpers/action"

const route = useRoute()

const $trans = inject("$trans")

const initUrl = "config/"
const formErrors = getFormErrors(initUrl)

const preRequisites = reactive({
    timezones: [],
    currencies: [],
})

const initForm = {
    trialPeriod: "",
    enableTrialEndsOnSubscription: false,
    subscriptionEndAlert: "",
    unavailableSubdomain: "",
    defaultTimezone: "",
    defaultLocale: "",
    defaultCurrency: "",
    defaultAppName: "",
    defaultAuthor: "",
    defaultFooterCredit: "",
    type: "tenant",
}

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}
</script>
