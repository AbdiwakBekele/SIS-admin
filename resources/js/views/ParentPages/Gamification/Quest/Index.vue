<template>
    <ListItem
        :init-url="initUrl"
        @setItems="setItems"
    >
        <template #header>
            <PageHeader
                :title="$trans('gamification.quest.quest')"
                :navs="[
                    { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
                ]"
            >
                <PageHeaderAction
                    url="gamification/challenge/"
                    name="ParentChallenge"
                    :title="$trans('gamification.quest.quest')"
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
                :header="challenges.headers"
                :meta="challenges.meta"
                module="gamification.challenge"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="challenge in challenges.data"
                    :key="challenge.uuid"
                    @double-click="
                        router.push({
                            name: 'ParentChallengeShow',
                            params: { uuid: challenge.uuid },
                        })
                    "
                >
                    <!-- Challenge Name -->
                    <DataCell name="name">
                        {{ challenge.name }}
                    </DataCell>

                    <!-- Challenge Code -->
                    <DataCell name="code">
                        {{ challenge.code }}
                    </DataCell>

                    <!-- Challenge Reward Type -->
                    <DataCell name="rewardType">
                        {{ challenge.rewardType }}
                    </DataCell>

                    <!-- Challenge Reward Value -->
                    <DataCell name="rewardValue">
                        {{ challenge.rewardValue }}
                    </DataCell>
                    
                    <DataCell name="createdAt">
                        {{ challenge.createdAt.formatted }}
                    </DataCell>

                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                icon="fas fa-eye"
                                @click="
                                    router.push({
                                        name: 'ParentChallengeShow',
                                        params: { uuid: challenge.uuid },
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
    name: "ParentChallengeList",
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
if (perform("challenge:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "gamification/challenge/"
const showFilter = ref(false)

const challenges = reactive({})

const setItems = (data) => {
    Object.assign(challenges, data)
}
</script>
