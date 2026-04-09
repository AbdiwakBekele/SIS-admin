<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('gamification.powerups.powerups')" :navs="[
                { label: $trans('gamification.gamification'), path: 'StudentGamification' },
            ]">
                <PageHeaderAction url="gamification/powerups/" name="PowerUps"
                    :title="$trans('gamification.powerups.powerups')" :actions="userActions"
                    :dropdown-actions="dropdownActions" :additional-dropdown-actions-query="{
                        details: true,
                    }">
                </PageHeaderAction>

            </PageHeader>
        </template>

        <!-- <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template> -->

        <ParentTransition appear :visibility="true">
            <DataTable :header="badges.headers" :meta="badges.meta" module="gamification.powerups"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="badge in badges.data" :key="badge.uuid" @double-click="
                    router.push({
                        name: 'BadgeShow',
                        params: { uuid: badge.uuid },
                    })
                    ">

                    <!--  Icon -->
                    <DataCell name="icon">
                        <img src="https://i.pinimg.com/736x/f8/fc/49/f8fc4970f3ef8cf8c996df8db471abeb.jpg" alt=""
                            width="40px" srcset="">
                    </DataCell>

                    <!-- Badge Name -->
                    <DataCell name="name">
                        {{ badge.name }}
                    </DataCell>

                    <DataCell name="createdAt">
                        {{ badge.createdAt.formatted }}
                    </DataCell>

                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'BadgeShow',
                                    params: { uuid: badge.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('division:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'BadgeEdit',
                                    params: { uuid: badge.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('division:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'BadgeDuplicate',
                                    params: { uuid: badge.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('division:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: badge.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('division:create')" @click="router.push({ name: 'BadgeCreate' })">
                        {{
                            $trans("global.add", {
                                attribute: $trans("gamification.badge.badge"),
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
    name: "BadgeList",
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
if (perform("division:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("division:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "gamification/badge/"
const showFilter = ref(false)
const showReorderModal = ref(false)

const badges = reactive({})

const setItems = (data) => {
    Object.assign(badges, data)
}
</script>
