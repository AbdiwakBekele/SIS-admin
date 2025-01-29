<template>
    <FormAction
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        :init-form="initForm"
        :form="form"
        :set-form="setForm"
        redirect="Plan"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.name"
                    name="name"
                    :label="$trans('plan.props.name')"
                    v-model:error="formErrors.name"
                    autofocus
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.code"
                    name="code"
                    :label="$trans('plan.props.code')"
                    v-model:error="formErrors.code"
                />
            </div>
        </div>

        <div class="mt-4 grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="number"
                    v-model="form.maxTeamLimit"
                    name="maxTeamLimit"
                    :label="$trans('plan.props.max_team_limit')"
                    v-model:error="formErrors.maxTeamLimit"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="number"
                    v-model="form.maxUserLimit"
                    name="maxUserLimit"
                    :label="$trans('plan.props.max_user_limit')"
                    v-model:error="formErrors.maxUserLimit"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSwitch
                    vertical
                    v-model="form.teamWiseLimit"
                    name="teamWiseLimit"
                    :label="$trans('plan.props.team_wise_limit')"
                />
            </div>
            <div class="col-span-3 sm:col-span-1" v-if="form.teamWiseLimit">
                <BaseInput
                    type="number"
                    v-model="form.maxStudentPerTeamLimit"
                    name="maxStudentPerTeamLimit"
                    :label="$trans('plan.props.max_student_per_team_limit')"
                    v-model:error="formErrors.maxStudentPerTeamLimit"
                />
            </div>
            <div class="col-span-3 sm:col-span-1" v-if="!form.teamWiseLimit">
                <BaseInput
                    type="number"
                    v-model="form.maxStudentLimit"
                    name="maxStudentLimit"
                    :label="$trans('plan.props.max_student_limit')"
                    v-model:error="formErrors.maxStudentLimit"
                />
            </div>
            <div class="col-span-3 sm:col-span-1" v-if="form.teamWiseLimit">
                <BaseInput
                    type="number"
                    v-model="form.maxEmployeePerTeamLimit"
                    name="maxEmployeePerTeamLimit"
                    :label="$trans('plan.props.max_employee_per_team_limit')"
                    v-model:error="formErrors.maxEmployeePerTeamLimit"
                />
            </div>
            <div class="col-span-3 sm:col-span-1" v-if="!form.teamWiseLimit">
                <BaseInput
                    type="number"
                    v-model="form.maxEmployeeLimit"
                    name="maxEmployeeLimit"
                    :label="$trans('plan.props.max_employee_limit')"
                    v-model:error="formErrors.maxEmployeeLimit"
                />
            </div>
            <!-- <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    multiple
                    name="modules"
                    :label="
                        $trans('global.select', {
                            attribute: $trans('plan.props.modules'),
                        })
                    "
                    :options="preRequisites.modules"
                    v-model="form.modules"
                    v-model:error="formErrors.modules"
                />
            </div> -->
        </div>

        <div class="mt-4 grid grid-cols-2 gap-6">
            <div class="col-span-2 space-y-2 sm:col-span-1">
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.isActive"
                            name="isActive"
                            :label="
                                $trans('global.is_attribute', {
                                    attribute: $trans('plan.props.active'),
                                })
                            "
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.isDefault"
                            name="isDefault"
                            :label="
                                $trans('global.is_attribute', {
                                    attribute: $trans('plan.props.default'),
                                })
                            "
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.isVisible"
                            name="isVisible"
                            :label="
                                $trans('global.is_attribute', {
                                    attribute: $trans('plan.props.visible'),
                                })
                            "
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.isFeatured"
                            name="isFeatured"
                            :label="
                                $trans('global.is_attribute', {
                                    attribute: $trans('plan.props.featured'),
                                })
                            "
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.isFree"
                            name="isFree"
                            :label="
                                $trans('global.is_attribute', {
                                    attribute: $trans('plan.props.free'),
                                })
                            "
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.hasActivationCharge"
                            name="hasActivationCharge"
                            :label="
                                $trans('global.has_attribute', {
                                    attribute: $trans(
                                        'plan.props.activation_charge'
                                    ),
                                })
                            "
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.enableTax"
                            name="enableTax"
                            :label="
                                $trans('global.enable', {
                                    attribute: $trans('plan.props.tax'),
                                })
                            "
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.enableMonthlySubscription"
                            name="enableMonthlySubscription"
                            :label="
                                $trans('plan.props.enable_monthly_subscription')
                            "
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.enableAnnualSubscription"
                            name="enableAnnualSubscription"
                            :label="
                                $trans('plan.props.enable_annual_subscription')
                            "
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.allowUsingGlobalMailService"
                            name="allowUsingGlobalMailService"
                            :label="
                                $trans(
                                    'plan.props.allow_using_global_mail_service'
                                )
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-6">
            <div class="col-span-2">
                <BaseSwitch
                    vertical
                    v-model="form.showInclusionExclusion"
                    name="showInclusionExclusion"
                    :label="$trans('plan.props.show_inclusion_exclusion')"
                />
            </div>
            <div
                class="col-span-2 sm:col-span-1"
                v-if="!form.showInclusionExclusion"
            >
                <BaseTextarea
                    :rows="6"
                    v-model="form.description"
                    name="description"
                    :label="$trans('plan.props.description')"
                    v-model:error="formErrors.description"
                />
            </div>
            <template v-if="form.showInclusionExclusion">
                <div class="col-span-2 sm:col-span-1">
                    <BaseTextarea
                        :rows="6"
                        v-model="form.inclusion"
                        name="inclusion"
                        :label="$trans('plan.props.inclusion')"
                        v-model:error="formErrors.inclusion"
                    />
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <BaseTextarea
                        :rows="6"
                        v-model="form.exclusion"
                        name="exclusion"
                        :label="$trans('plan.props.exclusion')"
                        v-model:error="formErrors.exclusion"
                    />
                </div>
            </template>
        </div>

        <BaseFieldset class="mt-4" v-if="form.enableTax && !form.isFree">
            <template #legend>{{ $trans("plan.props.tax") }}</template>
            <div class="mt-4 grid grid-cols-3 gap-6">
                <template v-if="form.enableTax">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.taxLabel"
                            name="taxLabel"
                            :label="$trans('plan.props.tax_label')"
                            v-model:error="formErrors.taxLabel"
                            autofocus
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            percentage
                            v-model="form.taxRate"
                            name="taxRate"
                            :label="$trans('plan.props.tax_rate')"
                            v-model:error="formErrors.taxRate"
                            autofocus
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.taxTypeExclusive"
                            name="taxTypeExclusive"
                            :label="$trans('plan.props.tax_type_exclusive')"
                        />
                    </div>
                </template>
            </div>
        </BaseFieldset>

        <BaseFieldset class="mt-4" v-if="form.hasActivationCharge">
            <template #legend
                >{{ $trans("plan.props.activation_charge") }} ({{
                    $trans("plan.props.one_time")
                }})</template
            >
            <div class="grid grid-cols-2 gap-6">
                <div
                    class="col-span-2 sm:col-span-1"
                    v-for="(activationCharge, index) in form.activationCharge"
                >
                    <BaseInput
                        currency
                        :currency-name="activationCharge.currency.name"
                        :name="`activationCharge.${index}.amount`"
                        v-model="activationCharge.amount.value"
                        :label="activationCharge.currency.name"
                        v-model:error="
                            formErrors[`activationCharge.${index}.amount`]
                        "
                    />
                </div>
            </div>
        </BaseFieldset>

        <BaseFieldset class="mt-4" v-if="!form.isFree">
            <template #legend
                >{{ $trans("plan.props.price") }}
                {{ $trans("plan.props.per_month") }}</template
            >
            <div class="grid grid-cols-2 gap-6">
                <div
                    class="col-span-2 sm:col-span-1"
                    v-for="(price, index) in form.price"
                >
                    <BaseInput
                        :disabled="
                            (!form.enableMonthlySubscription &&
                                price.frequency.value === 'monthly') ||
                            (!form.enableAnnualSubscription &&
                                price.frequency.value === 'annually')
                        "
                        currency
                        :currency-name="price.currency.name"
                        :name="`price.${index}.amount`"
                        v-model="price.amount.value"
                        :label="
                            price.frequency.label + ' ' + price.currency.name
                        "
                        v-model:error="formErrors[`price.${index}.amount`]"
                    />
                </div>
            </div>
        </BaseFieldset>
    </FormAction>
</template>

<script>
export default {
    name: "PlanForm",
}
</script>

<script setup>
import { reactive, inject, onMounted } from "vue"
import { getConfig, getFormErrors } from "@core/helpers/action"
import { cloneDeep } from "@core/utils"

const $trans = inject("$trans")

const initForm = {
    name: "",
    code: "",
    maxTeamLimit: "",
    teamWiseLimit: false,
    maxStudentLimit: "",
    maxStudentPerTeamLimit: "",
    maxEmployeeLimit: "",
    maxEmployeePerTeamLimit: "",
    maxUserLimit: "",
    modules: [],
    price: [],
    enableMonthlySubscription: true,
    enableAnnualSubscription: true,
    enableTax: false,
    taxLabel: "",
    taxTypeExclusive: false,
    taxRate: 0,
    activationCharge: [],
    isActive: false,
    isDefault: false,
    isFeatured: false,
    isVisible: false,
    isFree: false,
    hasActivationCharge: false,
    allowUsingGlobalMailService: false,
    showInclusionExclusion: false,
    description: "",
    inclusion: "",
    exclusion: "",
}

const initUrl = "plan/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    modules: [],
})

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

onMounted(() => {
    getConfig("system.currencies").value.forEach((currency) => {
        initForm.price.push({
            frequency: {
                value: "monthly",
                label: $trans("plan.frequencies.monthly"),
            },
            currency: currency,
            amount: { value: 0 },
        })

        initForm.price.push({
            frequency: {
                value: "annually",
                label: $trans("plan.frequencies.annually"),
            },
            currency: currency,
            amount: { value: 0 },
        })

        initForm.activationCharge.push({
            currency: currency,
            amount: { value: 0 },
        })
    })

    Object.assign(form, cloneDeep(initForm))
})

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
        taxRate: data.taxRate?.value || "",
    })
    Object.assign(form, cloneDeep(initForm))
}
</script>
