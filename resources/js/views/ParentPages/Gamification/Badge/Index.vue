<template>
    <ListItem
        :init-url="initUrl"
        @setItems="setItems"
    >
        <template #header>
            <PageHeader
                :title="$trans('gamification.badge.badge')"
                :navs="[
                    { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
                ]"
            >
                <PageHeaderAction
                    url="gamification/badge/"
                    name="ParentBadge"
                    :title="$trans('gamification.badge.badge')"
                    :actions="userActions"
                    :dropdown-actions="dropdownActions"
                    :additional-dropdown-actions-query="{
                        details: true,
                    }"
                >
                </PageHeaderAction>

            </PageHeader>
        </template>

        <!-- <template #filter>
                <FilterForm
                    v-if="showFilter"
                    @refresh="emitter.emit('listItems')"
                    @hide="showFilter = false"
                ></FilterForm>
        </template> -->

        <ParentTransition appear :visibility="true">
            <DataTable
                :header="badges.headers"
                :meta="badges.meta"
                module="gamification.badge"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="badge in badges.data"
                    :key="badge.uuid"
                    @double-click="
                        router.push({
                            name: 'ParentBadgeShow',
                            params: { uuid: badge.uuid },
                        })
                    "
                >

                <!--  Icon -->
                    <DataCell name="icon">
                       <img src="https://i.pinimg.com/736x/f8/fc/49/f8fc4970f3ef8cf8c996df8db471abeb.jpg" alt="" width="40px" srcset="">
                    </DataCell>

                <!-- Badge Name -->
                    <DataCell name="name">
                        {{ badge.name }}
                    </DataCell>

                    <!--  Code -->
                    <DataCell name="code">
                        {{ badge.code }}
                    </DataCell>

                     

                    <!--  Level -->
                    <DataCell name="level">
                        {{ badge.level }}
                    </DataCell>

                     <!--  Points Required -->
                    <DataCell name="pointsRequired">
                        {{ badge.pointsRequired }}
                    </DataCell>
                    
                    <DataCell name="createdAt">
                        {{ badge.createdAt.formatted }}
                    </DataCell>

                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                icon="fas fa-eye"
                                @click="
                                    router.push({
                                        name: 'ParentBadgeShow',
                                        params: { uuid: badge.uuid },
                                    })
                                "
                                >{{ $trans("general.view") }}</FloatingMenuItem
                            >
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
    name: "ParentBadgeList",
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
if (perform("badge:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "gamification/badge/"
const showFilter = ref(false)

const badges = reactive({})

const setItems = (data) => {
    Object.assign(badges, data)
}
</script>
