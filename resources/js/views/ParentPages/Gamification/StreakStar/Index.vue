<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('gamification.streak_star.streak_star')" :navs="[
                { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            ]">
                <PageHeaderAction url="gamification/streakstar/" name="ParentStreakStar"
                    :title="$trans('gamification.streak_star.streak_star')" :actions="userActions"
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
            <DataTable :header="streakStars.headers" :meta="streakStars.meta" module="gamification.streakstar"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="streakStar in streakStars.data" :key="streakStar.uuid" @double-click="
                    router.push({
                        name: 'ParentStreakStarShow',
                        params: { uuid: streakStar.uuid },
                    })
                    ">

                    <!--  Icon -->
                    <DataCell name="icon">
                        <img :src="streakStar.icon || 'https://i.pinimg.com/736x/f8/fc/49/f8fc4970f3ef8cf8c996df8db471abeb.jpg'" alt=""
                            width="40px" srcset="">
                    </DataCell>

                    <!-- StreakStar Name -->
                    <DataCell name="name">
                        {{ streakStar.name }}
                    </DataCell>

                    <!--  Code -->

                    <!--  Points Required -->
                    <DataCell name="pointsRequired">
                        {{ streakStar.pointsRequired }}
                    </DataCell>

                    <DataCell name="createdAt">
                        {{ streakStar.createdAt.formatted }}
                    </DataCell>

                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentStreakStarShow',
                                    params: { uuid: streakStar.uuid },
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
    name: "ParentStreakStarList",
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
if (perform("streak_star:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "gamification/streakstar/"
const showFilter = ref(false)

const streakStars = reactive({})

const setItems = (data) => {
    Object.assign(streakStars, data)
}
</script>
