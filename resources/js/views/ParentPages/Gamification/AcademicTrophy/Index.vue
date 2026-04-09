<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('gamification.academic_trophy.academic_trophy')" :navs="[
                { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            ]">
                <PageHeaderAction url="gamification/academicTrophy/" name="ParentAcademicTrophy"
                    :title="$trans('gamification.academic_trophy.academic_trophy')" :actions="userActions"
                    :dropdown-actions="dropdownActions" :additional-dropdown-actions-query="{
                        details: true,
                    }">
                </PageHeaderAction>

            </PageHeader>
        </template>

        <!-- <template #filter>
                <FilterForm v-if="showFilter" @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
        </template> -->

        <ParentTransition appear :visibility="true">
            <DataTable :header="academicTrophies.headers" :meta="academicTrophies.meta" module="gamification.academicTrophy"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="academicTrophy in academicTrophies.data" :key="academicTrophy.uuid" @double-click="
                    router.push({
                        name: 'ParentAcademicTrophyShow',
                        params: { uuid: academicTrophy.uuid },
                    })
                    ">

                    <!-- AcademicTrophy Name -->
                    <DataCell name="name">
                        {{ academicTrophy.name }}
                    </DataCell>

                    <DataCell name="createdAt">
                        {{ academicTrophy.createdAt.formatted }}
                    </DataCell>

                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentAcademicTrophyShow',
                                    params: { uuid: academicTrophy.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton> </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "ParentAcademicTrophyList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
// import FilterForm from "./Filter.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = [] // ["filter"] - removed since Filter.vue is missing

let dropdownActions = []
if (perform("academic_trophy:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "gamification/academicTrophy/"
const showFilter = ref(false)

const academicTrophies = reactive({})

const setItems = (data) => {
    Object.assign(academicTrophies, data)
}
</script>
