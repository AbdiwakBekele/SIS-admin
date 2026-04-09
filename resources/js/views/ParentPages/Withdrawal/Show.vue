<template>
    <PageHeader :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })"
        :navs="[{ label: $trans('student.withdrawal.withdrawals'), path: 'ParentWithdrawalList' }]">
        <PageHeaderAction name="Withdrawal" :title="$trans('student.withdrawal.withdrawal')" :actions="['list']" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentWithdrawalList' })">
            <BaseCard v-if="withdrawal.uuid" class="w-full">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('student.withdrawal.props.withdrawal_effective_date')">
                        {{ withdrawal.withdrawalDate || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('student.withdrawal.props.school_transfering_to')">
                        {{ withdrawal.transferingTo || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('student.withdrawal.props.address')">
                        {{ withdrawal.address || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('student.withdrawal.props.phone')">
                        {{ withdrawal.phone || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('student.withdrawal.props.email')">
                        {{ withdrawal.email || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('student.withdrawal.props.completed_by')">
                        {{ withdrawal.withdrawnBy || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('student.withdrawal.props.relationship')">
                        {{ withdrawal.relationship || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('student.withdrawal.props.reason_for_withdrawal')">
                        {{ withdrawal.reason || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('student.withdrawal.props.confirm_withdrawal')">
                        {{ withdrawal.confirmWithdrawal ? 'Yes' : 'No' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('student.withdrawal.props.signature')">
                        <img v-if="withdrawal.meta?.signature" :src="withdrawal.meta.signature" alt="Signature"
                            class="max-w-xs" />
                        <span v-else>N/A</span>
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.created_at')">
                        {{ withdrawal.createdAt?.formatted || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ withdrawal.updatedAt?.formatted || 'N/A' }}
                    </BaseDataView>
                </dl>
                <template #footer>
                    <ShowButton>
                        <BaseButton v-if="perform('withdrawal:edit')" design="primary"
                            @click="router.push({ name: 'ParentWithdrawalEdit', params: { uuid: withdrawal.uuid } })">
                            {{ $trans('general.edit') }}
                        </BaseButton>
                    </ShowButton>
                </template>
            </BaseCard>
            <div v-else class="text-gray-500 p-4 text-center">
                No withdrawal data available
            </div>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentWithdrawalShow",
}
</script>

<script setup>
import { reactive, inject } from "vue"
import { useRoute, useRouter } from "vue-router"
import { perform } from "@core/helpers/action"

const route = useRoute()
const router = useRouter()
const $trans = inject("$trans")

const initialWithdrawal = {
    uuid: "",
    withdrawalDate: "",
    transferingTo: "",
    address: "",
    phone: "",
    email: "",
    withdrawnBy: "",
    relationship: "",
    reason: "",
    confirmWithdrawal: false,

    meta: {
        signature: "",
    },
    createdAt: null,
    updatedAt: null,
}

console.log('show page opened')

const initUrl = "student/withdrawal/"

const withdrawal = reactive({ ...initialWithdrawal })

console.log('Show page opened')

const setItem = (data) => {
    Object.assign(withdrawal, {
        uuid: data.uuid || "",
        withdrawalDate: data.withdrawalDate || "",
        transferingTo: data.transferingTo || "",
        address: data.address || "",
        phone: data.phone || "",
        email: data.email || "",
        withdrawnBy: data.withdrawnBy || "",
        relationship: data.relationship || "",
        confirmWithdrawal: data.confirmWithdrawal || false,
        reason: data.reason || "",
        meta: {
            signature: data.signature || "",
        },
        createdAt: data.createdAt || null,
        updatedAt: data.updatedAt || null,
    })
}
</script>
