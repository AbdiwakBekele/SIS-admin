<template>
    <FormAction
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        :set-form="setForm"
        redirect="Tenant"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.name"
                    name="name"
                    :label="$trans('tenant.props.name')"
                    v-model:error="formErrors.name"
                    autofocus
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.email"
                    name="email"
                    :label="$trans('tenant.props.email')"
                    v-model:error="formErrors.email"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.contactNumber"
                    name="contactNumber"
                    :label="$trans('tenant.props.contact_number')"
                    v-model:error="formErrors.contactNumber"
                />
            </div>
            <AddressInput
                prefix="address"
                v-model:addressLine1="form.address.addressLine1"
                v-model:addressLine2="form.address.addressLine2"
                v-model:city="form.address.city"
                v-model:state="form.address.state"
                v-model:zipcode="form.address.zipcode"
                v-model:country="form.address.country"
                v-model:formErrors="formErrors"
            />

            <template
                v-if="
                    !route.params.uuid ||
                    (route.params.uuid && form.status == 'pending')
                "
            >
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        leading-text="https://"
                        :trailing-text="getTld"
                        v-model="form.domain"
                        name="domain"
                        :label="$trans('tenant.props.domain')"
                        v-model:error="formErrors.domain"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        leading-text="https://"
                        v-model="form.customDomain"
                        name="customDomain"
                        :label="$trans('tenant.props.custom_domain')"
                        v-model:error="formErrors.customDomain"
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
                <!-- <div class="col-span-3 sm:col-span-1">
                    <BaseSelectSearch
                        v-if="fetchData.isLoaded"
                        name="plan"
                        :label="
                            $trans('global.select', {
                                attribute: $trans('plan.plan'),
                            })
                        "
                        v-model="form.plan"
                        v-model:error="formErrors.plan"
                        :init-search="fetchData.plan"
                        search-action="plan/list"
                    />
                </div> -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect
                        name="frequency"
                        :label="
                            $trans('global.select', {
                                attribute: $trans('plan.props.frequency'),
                            })
                        "
                        :options="preRequisites.frequencies"
                        v-model="form.frequency"
                        v-model:error="formErrors.frequency"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect
                        v-model="form.currency"
                        name="currency"
                        :label="$trans('config.system.props.currency')"
                        :options="preRequisites.currencies"
                        v-model:error="formErrors.currency"
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
                    <BaseSwitch
                        vertical
                        v-model="form.isTrial"
                        name="isTrial"
                        :label="
                            $trans('global.is_attribute', {
                                attribute: $trans('tenant.props.trial'),
                            })
                        "
                    />
                </div>
                <div class="col-span-3 sm:col-span-1" v-if="form.isTrial">
                    <BaseInput
                        type="number"
                        v-model="form.trialPeriod"
                        name="trialPeriod"
                        :label="$trans('tenant.config.props.trial_period')"
                        v-model:error="formErrors.trialPeriod"
                    />
                </div>
            </template>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "TenantForm",
}
</script>

<script setup>
import { reactive, computed, inject } from "vue"
import { useRoute } from "vue-router"
import { getConfig, getFormErrors } from "@core/helpers/action"
import { cloneDeep } from "@core/utils"

const route = useRoute()

const $trans = inject("$trans")

const initForm = {
    status: "pending",
    domain: "",
    customDomain: "",
    isTrial: false,
    trialPeriod: "",
    plan: "",
    name: "",
    email: "",
    address: {},
    contactNumber: "",
    frequency: "",
    currency: "",
}

const initUrl = "tenant/"
const preRequisites = reactive({
    currencies: [],
    frequencies: [],
    plans: [],
})
const formErrors = getFormErrors(initUrl)
const getTld = computed(() => getConfig("system.tld").value)

const form = reactive({ ...initForm })
const fetchData = reactive({
    plan: "",
    isLoaded: route.params.uuid ? false : true,
})

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
        address: data.address || {},
        plan: data.plan?.uuid,
        frequency: data.frequency?.value,
        currency: data.currency?.name,
    })
    Object.assign(form, cloneDeep(initForm))

    fetchData.plan = data.plan?.uuid
    fetchData.isLoaded = true
}
</script>
