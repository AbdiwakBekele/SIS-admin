<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('plan.plan')">
                <PageHeaderAction
                    url="plans/"
                    name="Plan"
                    :title="$trans('plan.plan')"
                    :actions="userActions"
                    :dropdown-actions="dropdownActions"
                    @toggleFilter="showFilter = !showFilter"
                >
                    <BaseButton
                        design="white"
                        @click="showReorderModal = !showReorderModal"
                        v-tooltip="
                            $trans('global.reorder', {
                                attribute: $trans('plan.plan'),
                            })
                        "
                    >
                        <i class="fas fa-arrows-up-down-left-right"></i>
                    </BaseButton>
                </PageHeaderAction>
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm
                    @refresh="emitter.emit('listItems')"
                    @hide="showFilter = false"
                ></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable
                :header="plans.headers"
                :meta="plans.meta"
                module="plan"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="plan in plans.data"
                    :key="plan.uuid"
                    @doubleClick="
                        router.push({
                            name: 'PlanShow',
                            params: { uuid: plan.uuid },
                        })
                    "
                >
                    <DataCell
                        name="name"
                        clickable
                        @click="
                            router.push({
                                name: 'PlanShow',
                                params: { uuid: plan.uuid },
                            })
                        "
                    >
                        <span class="font-medium text-blue-700 hover:underline">
                            {{ plan.name }}
                        </span>
                    </DataCell>
                    <DataCell name="code">
                        {{ plan.code }}
                    </DataCell>
                    <DataCell name="minStudentLimit">
                        {{ plan.minStudentLimit ?? "-" }}
                    </DataCell>
                    <DataCell name="maxStudentLimit">
                        {{ plan.maxStudentLimit ?? "-" }}
                    </DataCell>
                    <DataCell name="priceSummary">
                        <div class="space-y-1 text-xs">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-gray-500">Monthly</span>
                                <span>{{ getPriceByFrequency(plan, "monthly") }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-gray-500">Annually</span>
                                <span>{{ getPriceByFrequency(plan, "annually") }}</span>
                            </div>
                        </div>
                    </DataCell>
                    <DataCell name="isActive">
                        <i
                            class="far fa-lg fa-circle-check text-success"
                            v-if="plan.isActive"
                        ></i>
                        <i
                            class="far fa-lg fa-circle-xmark text-danger"
                            v-else
                        ></i>
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ plan.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action" align="center">
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <button
                                type="button"
                                class="text-blue-500 hover:text-blue-600"
                                v-tooltip="$trans('general.show')"
                                @click="
                                    router.push({
                                        name: 'PlanShow',
                                        params: { uuid: plan.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-eye text-[12px]"></i>
                            </button>
                            <button
                                v-if="perform('plan:edit')"
                                type="button"
                                class="text-slate-600 hover:text-slate-700"
                                v-tooltip="$trans('general.edit')"
                                @click="
                                    router.push({
                                        name: 'PlanEdit',
                                        params: { uuid: plan.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-edit text-[12px]"></i>
                            </button>
                            <button
                                v-if="perform('plan:create')"
                                type="button"
                                class="text-green-500 hover:text-green-600"
                                v-tooltip="$trans('general.duplicate')"
                                @click="
                                    router.push({
                                        name: 'PlanDuplicate',
                                        params: { uuid: plan.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-copy text-[12px]"></i>
                            </button>
                            <button
                                v-if="perform('plan:delete')"
                                type="button"
                                class="text-red-500 hover:text-red-600"
                                v-tooltip="$trans('general.delete')"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: plan.uuid,
                                    })
                                "
                            >
                                <i class="fas fa-trash text-[12px]"></i>
                            </button>
                        </div>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton
                        v-if="perform('plan:create')"
                        @click="router.push({ name: 'PlanCreate' })"
                    >
                        {{
                            $trans("global.add", {
                                attribute: $trans("plan.plan"),
                            })
                        }}
                    </BaseButton>
                </template>
            </DataTable>
        </ParentTransition>
    </ListItem>

    <ReorderPlan
        :visibility="showReorderModal"
        @close="showReorderModal = false"
        @refresh="emitter.emit('listItems')"
    />
</template>

<script>
export default {
    name: "PlanList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
import FilterForm from "./Filter.vue"
import ReorderPlan from "./Reorder.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]
if (perform("plan:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("plan:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "plan/"
const showFilter = ref(false)
const showReorderModal = ref(false)

const plans = reactive({})

const setItems = (data) => {
    Object.assign(plans, data)
}

const getPriceByFrequency = (plan, frequency) => {
    const isEnabled =
        (frequency === "monthly" && plan.enableMonthlySubscription) ||
        (frequency === "annually" && plan.enableAnnualSubscription)

    if (!isEnabled) {
        return "-"
    }

    const matchedPrice = (plan.price || []).find(
        (price) => price.frequency.value === frequency
    )

    return matchedPrice?.amount?.formatted || "-"
}
</script>
