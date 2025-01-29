<template>
    <BaseCard no-padding no-content-padding bottom-content-padding>
        <template #title>
            {{
                $trans("global.recent", {
                    attribute: $trans("subscription.subscription"),
                })
            }}
        </template>
        <SimpleTable :header="detailHeader" v-if="records.length > 0">
            <DataRow v-for="record in records" :key="record.uuid">
                <DataCell name="codeNumber">
                    {{ record.codeNumber }}
                    <BaseBadge
                        v-if="record.isOnline"
                        :label="$trans('subscription.props.online')"
                    />
                </DataCell>
                <DataCell name="email">
                    {{ record.tenant.email }}
                </DataCell>
                <DataCell name="plan">
                    {{ record.plan.name }}
                </DataCell>
                <DataCell name="amount">
                    {{ record.amount.formatted }}
                </DataCell>
                <DataCell name="startDate">
                    {{ record.startDate.formatted }}
                </DataCell>
                <DataCell name="expiryDate">
                    {{ record.expiryDate.formatted }}
                </DataCell>
            </DataRow>
        </SimpleTable>

        <div class="p-2" v-if="records.length == 0">
            <BaseAlert size="xs" design="info">{{
                $trans("general.errors.record_not_found")
            }}</BaseAlert>
        </div>
    </BaseCard>
</template>

<script>
export default {
    name: "DashboardSubscriptionRecord",
}
</script>

<script setup>
import { onMounted, inject } from "vue"
import { useStore } from "vuex"

const $trans = inject("$trans")

const store = useStore()

const props = defineProps({
    records: {
        type: Array,
        default: [],
    },
})

const detailHeader = [
    {
        key: "codeNumber",
        label: $trans("subscription.props.code_number"),
        visibility: true,
    },
    {
        key: "email",
        label: $trans("subscription.props.email"),
        visibility: true,
    },
    { key: "plan", label: $trans("plan.plan"), visibility: true },
    {
        key: "amount",
        label: $trans("subscription.props.amount"),
        visibility: true,
    },
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
]
</script>
