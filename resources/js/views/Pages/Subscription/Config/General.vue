<template>
    <PageHeader
        :title="$trans(route.meta.label)"
        :navs="[
            {
                label: $trans('subscription.subscription'),
                path: 'Subscription',
            },
        ]"
    >
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <FormAction
            :pre-requisites="{ data: ['datePlaceholders'] }"
            @setPreRequisites="setPreRequisites"
            :init-url="initUrl"
            data-fetch="subscription"
            action="store"
            :init-form="initForm"
            :form="form"
            stay-on
            redirect="Subscription"
        >
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.companyName"
                        name="companyName"
                        :label="
                            $trans('subscription.config.props.company_name')
                        "
                        v-model:error="formErrors.companyName"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.companyEmail"
                        name="companyEmail"
                        :label="
                            $trans('subscription.config.props.company_email')
                        "
                        v-model:error="formErrors.companyEmail"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.companyPhone"
                        name="companyPhone"
                        :label="
                            $trans('subscription.config.props.company_phone')
                        "
                        v-model:error="formErrors.companyPhone"
                    />
                </div>
                <div class="col-span-3">
                    <BaseTextarea
                        :rows="2"
                        v-model="form.companyAddress"
                        name="companyAddress"
                        :label="
                            $trans('subscription.config.props.company_address')
                        "
                        v-model:error="formErrors.companyAddress"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.taxIdLabel"
                        name="taxIdLabel"
                        :label="
                            $trans('subscription.config.props.tax_id_label')
                        "
                        v-model:error="formErrors.taxIdLabel"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.taxIdNumber"
                        name="taxIdNumber"
                        :label="
                            $trans('subscription.config.props.tax_id_number')
                        "
                        v-model:error="formErrors.taxIdNumber"
                    />
                </div>
            </div>

            <div class="mt-4 grid grid-cols-3 gap-4">
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.invoiceNumberPrefix"
                        name="invoiceNumberPrefix"
                        :label="
                            $trans(
                                'subscription.config.props.invoice_number_prefix'
                            )
                        "
                        v-model:error="formErrors.invoiceNumberPrefix"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="number"
                        v-model="form.invoiceNumberDigit"
                        name="invoiceNumberDigit"
                        :label="
                            $trans(
                                'subscription.config.props.invoice_number_digit'
                            )
                        "
                        v-model:error="formErrors.invoiceNumberDigit"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.invoiceNumberSuffix"
                        name="invoiceNumberSuffix"
                        :label="
                            $trans(
                                'subscription.config.props.invoice_number_suffix'
                            )
                        "
                        v-model:error="formErrors.invoiceNumberSuffix"
                    />
                </div>
                <div class="col-span-3">
                    <BaseAlert design="info">{{
                        datePlaceholderInfo
                    }}</BaseAlert>
                </div>
                <div class="col-span-3">
                    <BaseTextarea
                        :rows="2"
                        v-model="form.invoiceTerms"
                        name="invoiceTerms"
                        :label="
                            $trans('subscription.config.props.invoice_terms')
                        "
                        v-model:error="formErrors.invoiceTerms"
                    />
                </div>
                <div class="col-span-3">
                    <BaseSwitch
                        reverse
                        v-model="form.enablePaypal"
                        name="enablePaypal"
                        :label="
                            $trans(
                                'subscription.config.props.enable_payment_gateway',
                                { attribute: 'PayPal' }
                            )
                        "
                        v-model:error="formErrors.enablePaypal"
                    />
                </div>
                <template v-if="form.enablePaypal">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.paypalClient"
                            name="paypalClient"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_client',
                                    { attribute: 'PayPal' }
                                )
                            "
                            v-model:error="formErrors.paypalClient"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.paypalSecret"
                            name="paypalSecret"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_secret',
                                    { attribute: 'PayPal' }
                                )
                            "
                            v-model:error="formErrors.paypalSecret"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.enableLivePaypalMode"
                            name="enableLivePaypalMode"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_mode',
                                    { attribute: 'PayPal' }
                                )
                            "
                            v-model:error="formErrors.enableLivePaypalMode"
                        />
                    </div>
                </template>
                <div class="col-span-3">
                    <BaseSwitch
                        reverse
                        v-model="form.enableStripe"
                        name="enableStripe"
                        :label="
                            $trans(
                                'subscription.config.props.enable_payment_gateway',
                                { attribute: 'Stripe' }
                            )
                        "
                        v-model:error="formErrors.enableStripe"
                    />
                </div>
                <template v-if="form.enableStripe">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.stripeClient"
                            name="stripeClient"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_client',
                                    { attribute: 'Stripe' }
                                )
                            "
                            v-model:error="formErrors.stripeClient"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.stripeSecret"
                            name="stripeSecret"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_secret',
                                    { attribute: 'Stripe' }
                                )
                            "
                            v-model:error="formErrors.stripeSecret"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.enableLiveStripeMode"
                            name="enableLiveStripeMode"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_mode',
                                    { attribute: 'Stripe' }
                                )
                            "
                            v-model:error="formErrors.enableLiveStripeMode"
                        />
                    </div>
                </template>
                <div class="col-span-3">
                    <BaseSwitch
                        reverse
                        v-model="form.enablePayzone"
                        name="enablePayzone"
                        :label="
                            $trans(
                                'subscription.config.props.enable_payment_gateway',
                                { attribute: 'Payzone' }
                            )
                        "
                        v-model:error="formErrors.enablePayzone"
                    />
                </div>
                <template v-if="form.enablePayzone">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.payzoneMerchant"
                            name="payzoneMerchant"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_client',
                                    { attribute: 'Payzone' }
                                )
                            "
                            v-model:error="formErrors.payzoneMerchant"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.payzoneSecretKey"
                            name="payzoneSecretKey"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_secret',
                                    { attribute: 'Payzone' }
                                )
                            "
                            v-model:error="formErrors.payzoneSecretKey"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.payzoneNotificationKey"
                            name="payzoneNotificationKey"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_notification',
                                    { attribute: 'Payzone' }
                                )
                            "
                            v-model:error="formErrors.payzoneNotificationKey"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.enableLivePayzoneMode"
                            name="enableLivePayzoneMode"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_mode',
                                    { attribute: 'Payzone' }
                                )
                            "
                            v-model:error="formErrors.enableLivePayzoneMode"
                        />
                    </div>
                </template>
                <div class="col-span-3">
                    <BaseSwitch
                        reverse
                        v-model="form.enableRazorpay"
                        name="enableRazorpay"
                        :label="
                            $trans(
                                'subscription.config.props.enable_payment_gateway',
                                { attribute: 'Razorpay' }
                            )
                        "
                        v-model:error="formErrors.enableRazorpay"
                    />
                </div>
                <template v-if="form.enableRazorpay">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.razorpayClient"
                            name="razorpayClient"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_client',
                                    { attribute: 'Razorpay' }
                                )
                            "
                            v-model:error="formErrors.razorpayClient"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.razorpaySecret"
                            name="razorpaySecret"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_secret',
                                    { attribute: 'Razorpay' }
                                )
                            "
                            v-model:error="formErrors.razorpaySecret"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.enableLiveRazorpayMode"
                            name="enableLiveRazorpayMode"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_mode',
                                    { attribute: 'Razorpay' }
                                )
                            "
                            v-model:error="formErrors.enableLiveRazorpayMode"
                        />
                    </div>
                </template>
                <div class="col-span-3">
                    <BaseSwitch
                        reverse
                        v-model="form.enablePaystack"
                        name="enablePaystack"
                        :label="
                            $trans(
                                'subscription.config.props.enable_payment_gateway',
                                { attribute: 'Paystack' }
                            )
                        "
                        v-model:error="formErrors.enablePaystack"
                    />
                </div>
                <template v-if="form.enablePaystack">
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.paystackClient"
                            name="paystackClient"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_client',
                                    { attribute: 'Paystack' }
                                )
                            "
                            v-model:error="formErrors.paystackClient"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.paystackSecret"
                            name="paystackSecret"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_secret',
                                    { attribute: 'Paystack' }
                                )
                            "
                            v-model:error="formErrors.paystackSecret"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSwitch
                            vertical
                            v-model="form.enableLivePaystackMode"
                            name="enableLivePaystackMode"
                            :label="
                                $trans(
                                    'subscription.config.props.payment_gateway_mode',
                                    { attribute: 'Paystack' }
                                )
                            "
                            v-model:error="formErrors.enableLivePaystackMode"
                        />
                    </div>
                </template>
            </div>
        </FormAction>
    </ParentTransition>
</template>

<script>
export default {
    name: "SubscriptionConfigGeneral",
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
const datePlaceholderInfo = computed(() =>
    $trans("global.placeholder_info", {
        attribute: preRequisites.datePlaceholders,
    })
)

const preRequisites = reactive({
    datePlaceholders: "",
})

const initForm = {
    invoiceNumberPrefix: "",
    invoiceNumberDigit: "",
    invoiceNumberSuffix: "",
    taxIdLabel: "",
    taxIdNumber: "",
    companyName: "",
    companyAddress: "",
    companyEmail: "",
    companyPhone: "",
    invoiceTerms: "",
    enablePaypal: "",
    enableLivePaypalMode: false,
    paypalClient: "",
    paypalSecret: "",
    enableStripe: "",
    enableLiveStripeMode: false,
    stripeClient: "",
    stripeSecret: "",
    enablePayzone: "",
    enableLivePayzoneMode: false,
    payzoneMerchant: "",
    payzoneSecretKey: "",
    payzoneNotificationKey: "",
    enableRazorpay: "",
    enableLiveRazorpayMode: false,
    razorpayClient: "",
    razorpaySecret: "",
    enablePaystack: "",
    enableLivePaystackMode: false,
    paystackClient: "",
    paystackSecret: "",
    type: "subscription",
}

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, {
        datePlaceholders: data.datePlaceholders
            .map((item) => item.value)
            .join(", "),
    })
}
</script>
