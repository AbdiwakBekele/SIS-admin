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
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <div>
                <BaseInput
                    type="text"
                    v-model="form.name"
                    name="name"
                    :label="$trans('plan.props.name')"
                    v-model:error="formErrors.name"
                    autofocus
                />
            </div>
            <div>
                <BaseInput
                    type="text"
                    v-model="form.code"
                    name="code"
                    :label="$trans('plan.props.code')"
                    v-model:error="formErrors.code"
                />
            </div>
            <div>
                <BaseInput
                    type="number"
                    v-model="form.minStudentLimit"
                    name="minStudentLimit"
                    :label="$trans('plan.props.min_student_limit')"
                    v-model:error="formErrors.minStudentLimit"
                />
            </div>
            <div>
                <BaseInput
                    type="number"
                    v-model="form.maxStudentLimit"
                    name="maxStudentLimit"
                    :label="$trans('plan.props.max_student_limit')"
                    v-model:error="formErrors.maxStudentLimit"
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

        <div class="mt-4">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                    <div>
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
                    <div>
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
                    <div>
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
                    <div>
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
                    <div>
                        <BaseSwitch
                            vertical
                            v-model="form.enableMonthlySubscription"
                            name="enableMonthlySubscription"
                            :label="
                                $trans('plan.props.enable_monthly_subscription')
                            "
                        />
                    </div>
                    <div>
                        <BaseSwitch
                            vertical
                            v-model="form.enableAnnualSubscription"
                            name="enableAnnualSubscription"
                            :label="
                                $trans('plan.props.enable_annual_subscription')
                            "
                        />
                    </div>
                    <div>
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
                    <div>
                        <BaseSwitch
                            vertical
                            v-model="form.showInclusionExclusion"
                            name="showInclusionExclusion"
                            :label="$trans('plan.props.show_inclusion_exclusion')"
                        />
                    </div>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-6">
            <div
                class="col-span-2"
                v-if="!form.showInclusionExclusion"
            >
                <BaseEditor
                    v-model="form.description"
                    name="description"
                    :label="$trans('plan.props.description')"
                    v-model:error="formErrors.description"
                    :edit="route.params.uuid ? true : false"
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
import { reactive, inject, watch } from "vue"
import { useRoute } from "vue-router"
import { getConfig, getFormErrors } from "@core/helpers/action"
import { cloneDeep } from "@core/utils"

const $trans = inject("$trans")
const route = useRoute()

/** Plans are differentiated by student capacity; other limits stay hidden. */
const PLAN_CREATE_HIDDEN_LIMIT_DEFAULT = 100000

const initForm = {
    name: "",
    code: "",
    minStudentLimit: "",
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

const currenciesConfig = getConfig("system.currencies")

const applyStudentTierDefaults = () => {
    const hiddenDefaults = {
        maxTeamLimit: PLAN_CREATE_HIDDEN_LIMIT_DEFAULT,
        maxUserLimit: PLAN_CREATE_HIDDEN_LIMIT_DEFAULT,
        maxEmployeeLimit: PLAN_CREATE_HIDDEN_LIMIT_DEFAULT,
        teamWiseLimit: false,
        maxStudentPerTeamLimit: "",
        maxEmployeePerTeamLimit: "",
    }
    Object.assign(initForm, hiddenDefaults)
    Object.assign(form, hiddenDefaults)
}

/**
 * Config may load after mount; `system.currencies` is undefined until then.
 * Only seed empty price/activation rows so edit/duplicate `setForm` data is never overwritten.
 */
watch(
    currenciesConfig,
    (currencies) => {
        if (!Array.isArray(currencies) || currencies.length === 0) {
            return
        }
        if (initForm.price.length > 0) {
            return
        }

        currencies.forEach((currency) => {
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
        applyStudentTierDefaults()
    },
    { immediate: true }
)

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
        minStudentLimit: data.minStudentLimit ?? "",
    })
    Object.assign(form, cloneDeep(initForm))
    applyStudentTierDefaults()
}
</script>
