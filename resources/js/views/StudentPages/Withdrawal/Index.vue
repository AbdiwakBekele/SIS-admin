<template>
    <ListItem :init-url="initUrl" :additional-query="{ details: true }" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('student.withdrawal.withdrawal')" :navs="[
                { label: $trans('student.student'), path: '' },
            ]">
                <PageHeaderAction url="student/withdrawal/" name="Withdrawal"
                    :title="$trans('student.withdrawal.withdrawal')" :actions="userActions"
                    :dropdown-actions="dropdownActions" :additional-dropdown-actions-query="{
                        details: true,
                    }" @toggleFilter="showFilter = !showFilter">
                </PageHeaderAction>
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="withdrawals.headers" :meta="withdrawals.meta" module="student.withdrawal"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="withdrawal in withdrawals.data" :key="withdrawal.uuid" @double-click="
                    router.push({
                        name: 'WithdrawalShow',
                        params: { uuid: withdrawal.uuid },
                    })
                    ">
                    <DataCell name="name">
                        {{ withdrawal.name }}
                    </DataCell>

                    <DataCell name="code">
                        {{ withdrawal.code }}
                    </DataCell>

                    <DataCell name="createdAt">
                        {{ withdrawal.createdAt.formatted }}
                    </DataCell>

                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'WithdrawalShow',
                                    params: { uuid: withdrawal.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('batch:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'WithdrawalEdit',
                                    params: { uuid: withdrawal.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('batch:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'WithdrawalDuplicate',
                                    params: { uuid: withdrawal.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('batch:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: withdrawal.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('batch:create')" @click="router.push({ name: 'WithdrawalCreate' })">
                        {{
                            $trans("global.add", {
                                attribute: $trans("student.withdrawal.withdrawal"),
                            })
                        }}
                    </BaseButton>
                </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "WithdrawalList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
import FilterForm from "./Filter.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]
if (perform("batch:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("batch:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "student/withdrawal/" // Vuex - State
const showFilter = ref(false)

const withdrawals = reactive({})

const setItems = (data) => {
    Object.assign(withdrawals, data)
}
</script>
