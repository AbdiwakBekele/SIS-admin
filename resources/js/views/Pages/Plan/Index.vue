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
                <DataRow v-for="plan in plans.data" :key="plan.uuid">
                    <DataCell name="name">
                        {{ plan.name }}
                    </DataCell>
                    <DataCell name="code">
                        {{ plan.code }}
                    </DataCell>
                    <DataCell name="maxTeamLimit">
                        {{ plan.maxTeamLimit }}
                        <TextMuted block>{{ plan.maxUserLimit }}</TextMuted>
                    </DataCell>
                    <DataCell name="maxStudentLimit">
                        {{ plan.maxStudentLimit ?? "-" }}
                        <TextMuted block>{{
                            plan.maxEmployeeLimit ?? "-"
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="maxStudentPerTeamLimit">
                        {{ plan.maxStudentPerTeamLimit ?? "-" }}
                        <TextMuted block>{{
                            plan.maxEmployeePerTeamLimit ?? "-"
                        }}</TextMuted>
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
                    <DataCell name="isFree">
                        <i
                            class="far fa-lg fa-circle-check text-success"
                            v-if="plan.isFree"
                        ></i>
                        <i
                            class="far fa-lg fa-circle-xmark text-danger"
                            v-else
                        ></i>
                    </DataCell>
                    <DataCell name="hasActivationCharge">
                        <i
                            class="far fa-lg fa-circle-check text-success"
                            v-if="plan.hasActivationCharge"
                        ></i>
                        <i
                            class="far fa-lg fa-circle-xmark text-danger"
                            v-else
                        ></i>
                    </DataCell>
                    <DataCell name="enableTax">
                        <i
                            class="far fa-lg fa-circle-check text-success"
                            v-if="plan.enableTax"
                        ></i>
                        <i
                            class="far fa-lg fa-circle-xmark text-danger"
                            v-else
                        ></i>
                    </DataCell>
                    <DataCell name="isVisible">
                        <i
                            class="far fa-lg fa-circle-check text-success"
                            v-if="plan.isVisible"
                        ></i>
                        <i
                            class="far fa-lg fa-circle-xmark text-danger"
                            v-else
                        ></i>
                    </DataCell>
                    <DataCell name="modules">
                        <div class="flex flex-wrap gap-2">
                            <BaseBadge
                                design="primary"
                                v-for="moduleName in plan.moduleSummary"
                                >{{ moduleName }}</BaseBadge
                            >
                        </div>
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ plan.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                icon="fas fa-arrow-circle-right"
                                @click="
                                    router.push({
                                        name: 'PlanShow',
                                        params: { uuid: plan.uuid },
                                    })
                                "
                                >{{ $trans("general.show") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('plan:edit')"
                                icon="fas fa-edit"
                                @click="
                                    router.push({
                                        name: 'PlanEdit',
                                        params: { uuid: plan.uuid },
                                    })
                                "
                                >{{ $trans("general.edit") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('plan:create')"
                                icon="fas fa-copy"
                                @click="
                                    router.push({
                                        name: 'PlanDuplicate',
                                        params: { uuid: plan.uuid },
                                    })
                                "
                                >{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('plan:delete')"
                                icon="fas fa-trash"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: plan.uuid,
                                    })
                                "
                                >{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem
                            >
                        </FloatingMenu>
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
</script>
