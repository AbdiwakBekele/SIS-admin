<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('gamification.quest.quest')" :navs="[
                { label: $trans('gamification.gamification'), path: 'MyStudentGamification' },
            ]">
                <PageHeaderAction url="gamification/challenge/" name="MyStudentChallenge"
                    :title="$trans('gamification.quest.quest')" :actions="userActions"
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
            <DataTable :header="challenges.headers" :meta="challenges.meta" module="gamification.challenge"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="challenge in challenges.data" :key="challenge.uuid" @double-click="
                    router.push({
                        name: 'MyStudentChallengeShow',
                        params: { uuid: challenge.uuid },
                    })
                    ">

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
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'MyStudentChallengeShow',
                                    params: { uuid: challenge.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('division:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'MyStudentChallengeEdit',
                                    params: { uuid: challenge.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('division:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'MyStudentChallengeDuplicate',
                                    params: { uuid: challenge.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('division:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: challenge.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('division:create')" @click="router.push({ name: 'MyStudentChallengeCreate' })">
                        {{
                            $trans("global.add", {
                                attribute: $trans("gamification.quest.quest"),
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
    name: "MyStudentChallengeList",
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

const initUrl = "gamification/challenge/"
const showFilter = ref(false)

const challenges = reactive({})

const setItems = (data) => {
    Object.assign(challenges, data)
}
</script>
