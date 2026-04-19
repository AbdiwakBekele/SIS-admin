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
                    v-model="form.schoolName"
                    name="schoolName"
                    :label="$trans('tenant.props.school_name')"
                    v-model:error="formErrors.schoolName"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <div class="flex flex-col gap-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $trans("tenant.props.school_logo") }}
                    </span>
                    <input
                        type="file"
                        name="schoolLogo"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-medium file:text-white"
                        @change="onSchoolLogoChange"
                    />
                    <p
                        v-if="formErrors.schoolLogo"
                        class="text-sm text-red-600 dark:text-red-400"
                    >
                        {{ formErrors.schoolLogo }}
                    </p>
                    <img
                        v-if="schoolLogoDisplay"
                        :src="schoolLogoDisplay"
                        alt=""
                        class="mt-2 h-20 w-auto max-w-full rounded border border-gray-200 object-contain dark:border-gray-600"
                    />
                </div>
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

            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    name="academicTerm"
                    :label="
                        $trans('global.select', {
                            attribute: $trans('tenant.props.academic_term'),
                        })
                    "
                    :options="preRequisites.academicTerms"
                    label-prop="label"
                    value-prop="value"
                    v-model="form.academicTerm"
                    v-model:error="formErrors.academicTerm"
                />
            </div>

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
                        type="date"
                        v-model="form.trialStartDate"
                        name="trialStartDate"
                        :label="$trans('tenant.props.trial_start_date')"
                        v-model:error="formErrors.trialStartDate"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1" v-if="form.isTrial">
                    <BaseInput
                        type="date"
                        v-model="form.trialEndDate"
                        name="trialEndDate"
                        :label="$trans('tenant.props.trial_end_date')"
                        v-model:error="formErrors.trialEndDate"
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
    trialStartDate: "",
    trialEndDate: "",
    plan: "",
    name: "",
    schoolName: "",
    schoolLogo: "",
    schoolLogoUrl: "",
    email: "",
    address: {},
    contactNumber: "",
    academicTerm: "",
    frequency: "",
    currency: "",
}

const initUrl = "tenant/"
const preRequisites = reactive({
    currencies: [],
    frequencies: [],
    plans: [],
    academicTerms: [],
})
const formErrors = getFormErrors(initUrl)
const getTld = computed(() => getConfig("system.tld").value)

const form = reactive({ ...initForm })
const fetchData = reactive({
    plan: "",
    isLoaded: route.params.uuid ? false : true,
})

const schoolLogoDisplay = computed(
    () => form.schoolLogo || form.schoolLogoUrl || ""
)

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const onSchoolLogoChange = (event) => {
    const file = event.target.files?.[0]
    if (!file) {
        return
    }
    const reader = new FileReader()
    reader.onload = () => {
        form.schoolLogo = reader.result
    }
    reader.readAsDataURL(file)
}

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
        address: data.address || {},
        plan: data.plan?.uuid,
        frequency: data.frequency?.value,
        currency: data.currency?.name,
        schoolName: data.schoolName || "",
        schoolLogo: "",
        schoolLogoUrl: data.schoolLogo || "",
        trialStartDate: data.trialStartDate || "",
        trialEndDate: data.trialEndDate || "",
        academicTerm: data.academicTerm?.value ?? data.academicTerm ?? "",
    })
    Object.assign(form, cloneDeep(initForm))

    fetchData.plan = data.plan?.uuid
    fetchData.isLoaded = true
}
</script>
