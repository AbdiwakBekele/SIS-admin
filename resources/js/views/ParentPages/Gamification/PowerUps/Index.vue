<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('gamification.powerups.powerups')" :navs="[
                { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            ]">
                <PageHeaderAction url="gamification/powerup/" name="ParentPowerUps"
                    :title="$trans('gamification.powerups.powerups')" :actions="userActions"
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
            <DataTable :header="powerUps.headers" :meta="powerUps.meta" module="gamification.powerup"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="powerUp in powerUps.data" :key="powerUp.uuid" @double-click="
                    router.push({
                        name: 'ParentPowerUpsShow',
                        params: { uuid: powerUp.uuid },
                    })
                    ">

                    <!--  Icon -->
                    <DataCell name="icon">
                        <img :src="powerUp.icon || 'https://i.pinimg.com/736x/f8/fc/49/f8fc4970f3ef8cf8c996df8db471abeb.jpg'" alt=""
                            width="40px" srcset="">
                    </DataCell>

                    <!-- PowerUp Name -->
                    <DataCell name="name">
                        {{ powerUp.name }}
                    </DataCell>

                    <DataCell name="createdAt">
                        {{ powerUp.createdAt.formatted }}
                    </DataCell>

                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentPowerUpsShow',
                                    params: { uuid: powerUp.uuid },
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
    name: "ParentPowerUpsList",
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
if (perform("powerups:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "gamification/powerup/"
const showFilter = ref(false)

const powerUps = reactive({})

const setItems = (data) => {
    Object.assign(powerUps, data)
}
</script>
