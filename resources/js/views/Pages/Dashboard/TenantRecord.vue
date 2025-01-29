<template>
    <BaseCard no-padding no-content-padding bottom-content-padding>
        <template #title>
            {{
                $trans("global.recent", { attribute: $trans("tenant.tenant") })
            }}
        </template>
        <SimpleTable :header="detailHeader" v-if="records.length > 0">
            <DataRow v-for="record in records" :key="record.uuid">
                <DataCell name="domain">
                    {{ record.domain }}
                    <BaseBadge
                        v-if="record.isTrial"
                        :label="$trans('tenant.props.trial')"
                        design="danger"
                    />
                </DataCell>
                <DataCell name="email">
                    {{ record.email }}
                </DataCell>
                <DataCell name="status">
                    <BaseBadge
                        :label="record.status.label"
                        :design="record.status.color"
                    />
                </DataCell>
                <DataCell name="plan">
                    {{ record.plan.name }} ({{ record.plan.code }})
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
    name: "DashboardTenantRecord",
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
    { key: "domain", label: $trans("tenant.props.domain"), visibility: true },
    { key: "email", label: $trans("tenant.props.email"), visibility: true },
    { key: "status", label: $trans("tenant.props.status"), visibility: true },
    { key: "plan", label: $trans("plan.plan"), visibility: true },
    {
        key: "expiryDate",
        label: $trans("tenant.props.expiry_date"),
        visibility: true,
    },
]
</script>
