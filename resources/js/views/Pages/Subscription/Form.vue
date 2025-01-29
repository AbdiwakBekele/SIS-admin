<template>
    <FormAction
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        :set-form="setForm"
        redirect="Subscription"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.codeNumber"
                    name="codeNumber"
                    :label="$trans('subscription.props.code_number')"
                    v-model:error="formErrors.codeNumber"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    name="tenant"
                    :label="
                        $trans('global.select', {
                            attribute: $trans('tenant.tenant'),
                        })
                    "
                    :options="preRequisites.tenants"
                    label-prop="name"
                    value-prop="uuid"
                    v-model="form.tenant"
                    v-model:error="formErrors.tenant"
                />
            </div>
            <!-- <div class="col-span-3 sm:col-span-1">
                <BaseSelectSearch
                    v-if="fetchData.isLoaded"
                    name="tenant"
                    :label="
                        $trans('global.select', {
                            attribute: $trans('tenant.tenant'),
                        })
                    "
                    v-model="form.tenant"
                    v-model:error="formErrors.tenant"
                    :init-search="fetchData.tenant"
                    search-key="domain"
                    search-action="tenant/list"
                >
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.domain }} ({{
                            slotProps.value.email
                        }})
                    </template>

                    <template #listOption="slotProps">
                        {{ slotProps.option.domain }} ({{
                            slotProps.option.email
                        }})
                    </template>
                </BaseSelectSearch>
            </div> -->
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
                <BaseInput
                    currency
                    v-model="form.amount"
                    name="amount"
                    :label="$trans('subscription.props.amount')"
                    v-model:error="formErrors.amount"
                />
            </div>
            <div class="col-span-2 sm:col-span-1">
                <DatePicker
                    v-model="form.startDate"
                    name="startDate"
                    :label="$trans('subscription.props.start_date')"
                    no-clear
                    v-model:error="formErrors.startDate"
                />
            </div>
            <div class="col-span-2 sm:col-span-1">
                <DatePicker
                    v-model="form.expiryDate"
                    name="expiryDate"
                    :label="$trans('subscription.props.expiry_date')"
                    no-clear
                    v-model:error="formErrors.expiryDate"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.name"
                    name="name"
                    :label="$trans('subscription.props.name')"
                    v-model:error="formErrors.name"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.email"
                    name="email"
                    :label="$trans('subscription.props.email')"
                    v-model:error="formErrors.email"
                />
            </div>
        </div>
        <div class="mt-4 grid grid-cols-3 gap-6">
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
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "SubscriptionForm",
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
    codeNumber: "",
    tenant: "",
    plan: "",
    frequency: "",
    currency: "",
    name: "",
    email: "",
    address: {},
    amount: "",
    startDate: "",
    expiryDate: "",
}

const initUrl = "subscription/"
const preRequisites = reactive({
    codeNumber: "",
    currencies: [],
    frequencies: [],
    plans: [],
    tenants: [],
})
const formErrors = getFormErrors(initUrl)

const form = reactive({ ...initForm })
const fetchData = reactive({
    plan: "",
    tenant: "",
    isLoaded: route.params.uuid ? false : true,
})

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
    initForm.codeNumber = data.codeNumber
    Object.assign(form, cloneDeep(initForm))
}

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
        address: data.address || {},
        plan: data.plan?.uuid,
        tenant: data.tenant?.uuid,
    })
    Object.assign(form, cloneDeep(initForm))

    fetchData.plan = data.plan?.uuid
    fetchData.tenant = data.tenant?.uuid
    fetchData.isLoaded = true
}
</script>
