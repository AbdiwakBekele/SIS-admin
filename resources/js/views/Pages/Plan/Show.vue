<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[{ label: $trans('plan.plan'), path: 'PlanList' }]"
    >
        <PageHeaderAction
            name="Plan"
            :title="$trans('plan.plan')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'Plan' })"
        >
            <BaseCard v-if="plan.uuid">
                <template #name>
                    {{ plan.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('plan.props.name')">
                        {{ plan.name }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('plan.props.code')">
                        {{ plan.code }}
                    </BaseDataView>

                    <BaseDataView class="col-span-1 sm:col-span-2" label="">
                        <div class="space-x-4">
                            <BaseBadge design="info" v-if="plan.isActive">{{
                                $trans("global.is_attribute", {
                                    attribute: $trans("plan.props.active"),
                                })
                            }}</BaseBadge>
                            <BaseBadge design="info" v-if="plan.isDefault">{{
                                $trans("global.is_attribute", {
                                    attribute: $trans("plan.props.default"),
                                })
                            }}</BaseBadge>
                            <BaseBadge design="info" v-if="plan.isFeatured">{{
                                $trans("global.is_attribute", {
                                    attribute: $trans("plan.props.featured"),
                                })
                            }}</BaseBadge>
                            <BaseBadge design="info" v-if="plan.isVisible">{{
                                $trans("global.is_attribute", {
                                    attribute: $trans("plan.props.visible"),
                                })
                            }}</BaseBadge>
                            <BaseBadge design="info" v-if="plan.isFree">{{
                                $trans("global.is_attribute", {
                                    attribute: $trans("plan.props.free"),
                                })
                            }}</BaseBadge>
                            <BaseBadge
                                design="info"
                                v-if="plan.hasActivationCharge"
                                >{{
                                    $trans("global.has_attribute", {
                                        attribute: $trans(
                                            "plan.props.activation_charge"
                                        ),
                                    })
                                }}</BaseBadge
                            >
                            <BaseBadge
                                design="info"
                                v-if="plan.allowusingGlobalMailService"
                                >{{
                                    $trans(
                                        "plan.props.allow_using_global_mail_service"
                                    )
                                }}</BaseBadge
                            >
                        </div>
                    </BaseDataView>

                    <BaseDataView :label="$trans('plan.props.max_student_limit')">
                        {{ plan.maxStudentLimit || plan.maxStudentPerTeamLimit }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('plan.props.price')"
                        v-if="!plan.isFree"
                    >
                        <ul>
                            <template v-for="price in plan.price">
                                <li
                                    v-if="
                                        (plan.enableMonthlySubscription &&
                                            price.frequency.value ===
                                                'monthly') ||
                                        (plan.enableAnnualSubscription &&
                                            price.frequency.value ===
                                                'annually')
                                    "
                                >
                                    {{ price.amount.formatted }}
                                    {{ price.frequency.label }}
                                </li>
                            </template>
                        </ul>
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('plan.props.tax')"
                        v-if="!plan.isFree"
                    >
                        <span v-if="plan.enableTax">
                            {{ plan.taxLabel + " " + plan.taxRate.formatted }}
                            <span v-if="plan.taxTypeExclusive"
                                >({{
                                    $trans("plan.props.tax_type_exclusive")
                                }})</span
                            >
                        </span>
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('plan.props.activation_charge')"
                        v-if="!plan.isFree"
                    >
                        <ul>
                            <li
                                v-for="activationCharge in plan.activationCharge"
                            >
                                {{ activationCharge.amount.formatted }}
                            </li>
                        </ul>
                    </BaseDataView>

                    <BaseDataView
                        v-if="!plan.showInclusionExclusion"
                        class="col-span-1 sm:col-span-2"
                        :label="$trans('plan.props.description')"
                    >
                        <ul>
                            <li v-for="description in plan.descriptionItems">
                                {{ description }}
                            </li>
                        </ul>
                    </BaseDataView>

                    <BaseDataView
                        v-if="plan.showInclusionExclusion"
                        class="col-span-1 sm:col-span-2"
                        :label="$trans('plan.props.inclusion')"
                    >
                        <ul>
                            <li v-for="inclusion in plan.inclusionItems">
                                {{ inclusion }}
                            </li>
                        </ul>
                    </BaseDataView>

                    <BaseDataView
                        v-if="plan.showInclusionExclusion"
                        class="col-span-1 sm:col-span-2"
                        :label="$trans('plan.props.exclusion')"
                    >
                        <ul>
                            <li v-for="exclusion in plan.exclusionItems">
                                {{ exclusion }}
                            </li>
                        </ul>
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.created_at')">
                        {{ plan.createdAt.formatted }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ plan.updatedAt.formatted }}
                    </BaseDataView>
                </dl>
                <template #footer>
                    <ShowButton>
                        <BaseButton
                            v-if="perform('plan:edit')"
                            design="primary"
                            @click="
                                router.push({
                                    name: 'PlanEdit',
                                    params: { uuid: plan.uuid },
                                })
                            "
                        >
                            {{ $trans("general.edit") }}
                        </BaseButton>
                    </ShowButton>
                </template>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "PlanShow",
}
</script>

<script setup>
import { reactive, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform } from "@core/helpers/action"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialPlan = {}

const initUrl = "plan/"

const plan = reactive({ ...initialPlan })

const setItem = (data) => {
    Object.assign(plan, data)
}
</script>
