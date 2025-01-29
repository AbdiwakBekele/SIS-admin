<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[{ label: $trans('tenant.tenant'), path: 'Tenant' }]"
    >
        <PageHeaderAction
            name="Tenant"
            :title="$trans('tenant.tenant')"
            :actions="['list']"
        >
            <BaseButton
                design="white"
                v-if="tenant.uuid && tenant.status.value == 'pending'"
                @click="
                    router.push({
                        name: 'TenantEdit',
                        params: { uuid: tenant.uuid },
                    })
                "
            >
                {{
                    $trans("global.edit", {
                        attribute: $trans("tenant.tenant"),
                    })
                }}
            </BaseButton>
        </PageHeaderAction>
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'Tenant' })"
            :refresh="refreshItem"
            @refreshed="refreshItem = false"
        >
            <DetailLayoutVertical v-if="tenant.uuid">
                <template #detail>
                    <BaseCard no-padding no-content-padding>
                        <template #title>
                            {{
                                $trans("global.detail", {
                                    attribute: $trans("tenant.tenant"),
                                })
                            }}
                            <BaseBadge design="error" v-if="tenant.isTrial">{{
                                $trans("global.is_attribute", {
                                    attribute: $trans("tenant.props.trial"),
                                })
                            }}</BaseBadge>
                        </template>
                        <ListContainerVertical>
                            <ListItemView :label="$trans('tenant.props.name')">
                                {{ tenant.name }}
                            </ListItemView>

                            <ListItemView :label="$trans('tenant.props.email')">
                                {{ tenant.email }}
                            </ListItemView>

                            <ListItemView
                                :label="$trans('tenant.props.contact_number')"
                            >
                                {{ tenant.contactNumber }}
                            </ListItemView>

                            <ListItemView
                                :label="$trans('tenant.props.status')"
                            >
                                <BaseBadge :design="tenant.status.color">{{
                                    tenant.status.label
                                }}</BaseBadge>
                            </ListItemView>

                            <ListItemView
                                :label="$trans('tenant.props.activation_token')"
                                :truncate="false"
                                v-if="tenant.status.value == 'pending'"
                            >
                                {{ tenant.activationToken }}
                            </ListItemView>

                            <ListItemView
                                :label="$trans('tenant.props.domain')"
                            >
                                {{ tenant.domain }}
                            </ListItemView>

                            <ListItemView
                                :label="$trans('tenant.props.custom_domain')"
                            >
                                {{ tenant.customDomain || "-" }}
                            </ListItemView>

                            <ListItemView :label="$trans('plan.plan')">
                                {{ tenant.plan.name }} ({{ tenant.plan.code }})
                            </ListItemView>

                            <ListItemView
                                :label="
                                    $trans('tenant.config.props.trial_period')
                                "
                                v-if="tenant.isTrial"
                            >
                                {{ tenant.trialPeriod }}
                                {{ $trans("list.durations.days") }}
                            </ListItemView>

                            <ListItemView
                                :label="$trans('tenant.props.expiry_date')"
                            >
                                {{ tenant.expiryDate.formatted }}
                            </ListItemView>

                            <ListItemView :label="$trans('general.created_at')">
                                {{ tenant.createdAt.formatted }}
                            </ListItemView>

                            <ListItemView :label="$trans('general.updated_at')">
                                {{ tenant.updatedAt.formatted }}
                            </ListItemView>
                        </ListContainerVertical>
                    </BaseCard>
                </template>

                <div class="space-y-4">
                    <BaseCard
                        no-padding
                        no-content-padding
                        :bottom-content-padding="
                            tenant.subscriptions.length ? true : false
                        "
                    >
                        <template #title>
                            {{ $trans("subscription.subscription") }}
                        </template>

                        <SimpleTable
                            :header="subscriptionHeader"
                            v-if="tenant.subscriptions.length > 0"
                        >
                            <DataRow
                                v-for="subscription in tenant.subscriptions"
                                :key="subscription.uuid"
                            >
                                <DataCell name="codeNumber">
                                    {{ subscription.codeNumber }}
                                    <BaseBadge v-if="subscription.isOnline">{{
                                        $trans("subscription.props.online")
                                    }}</BaseBadge>
                                </DataCell>
                                <DataCell name="plan">
                                    {{ subscription.plan.name }} ({{
                                        subscription.plan.code
                                    }})
                                </DataCell>
                                <DataCell name="startDate">
                                    {{ subscription.startDate.formatted }}
                                </DataCell>
                                <DataCell name="expiryDate">
                                    {{ subscription.expiryDate.formatted }}
                                </DataCell>
                                <DataCell name="amount">
                                    {{ subscription.amount.formatted }}
                                </DataCell>
                                <DataCell name="createdAt">
                                    {{ subscription.createdAt.formatted }}
                                </DataCell>
                                <DataCell name="action"> </DataCell>
                            </DataRow>
                        </SimpleTable>

                        <div class="p-2">
                            <BaseAlert
                                design="info"
                                size="xs"
                                v-if="tenant.subscriptions.length == 0"
                            >
                                {{ $trans("general.errors.record_not_found") }}
                            </BaseAlert>
                        </div>
                    </BaseCard>

                    <BaseCard
                        no-padding
                        no-content-padding
                        v-if="perform('tenant:edit')"
                    >
                        <template #title>
                            {{
                                $trans("global.edit", {
                                    attribute: $trans("tenant.tenant"),
                                })
                            }}
                        </template>
                        <UpdateRecordForm
                            @completed="emitter.emit('refreshItem')"
                        />
                    </BaseCard>
                </div>
            </DetailLayoutVertical>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "TenantShow",
}
</script>

<script setup>
import { reactive, ref, inject } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform, getFormErrors } from "@core/helpers/action"
import UpdateRecordForm from "./UpdateRecordForm.vue"

const store = useStore()
const route = useRoute()
const router = useRouter()
const $trans = inject("$trans")

const initialTenant = {}

const initForm = {}

const initUrl = "tenant/"

const subscriptionHeader = [
    {
        key: "codeNumber",
        label: $trans("subscription.props.code_number"),
        visibility: true,
    },
    { key: "plan", label: $trans("plan.plan"), visibility: true },
    {
        key: "startDate",
        label: $trans("subscription.props.start_date"),
        visibility: true,
    },
    {
        key: "expiryDate",
        label: $trans("subscription.props.expiry_date"),
        visibility: true,
    },
    {
        key: "amount",
        label: $trans("subscription.props.amount"),
        visibility: true,
    },
    { key: "createdAt", label: $trans("general.created_at"), visibility: true },
    { key: "action", label: "", visibility: true },
]

const formErrors = getFormErrors(initUrl)

const form = reactive({ ...initForm })

const refreshItem = ref(false)
const tenant = reactive({ ...initialTenant })

const setItem = (data) => {
    Object.assign(tenant, data)
}

const afterSubmit = () => {
    refreshItem.value = true
}
</script>
