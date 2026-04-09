<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })"
        :navs="[
            { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            { label: $trans('gamification.leaderboard.leaderboard'), path: 'ParentLeaderboardList' },
        ]"
    >
        <PageHeaderAction
            name="ParentLeaderboard"
            :title="$trans('gamification.leaderboard.leaderboard')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentLeaderboardList' })"
        >
            <BaseCard v-if="leaderboard.uuid">
                <template #title>
                    {{ leaderboard.name || $trans('gamification.leaderboard.leaderboard') }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('gamification.leaderboard.props.name')">
                        {{ leaderboard.name }}
                    </BaseDataView>
                    <BaseDataView class="col-span-1 sm:col-span-2" :label="$trans('gamification.leaderboard.props.description')" html>
                        {{ leaderboard.description }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.created_at')">
                        {{ leaderboard.createdAt?.formatted }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ leaderboard.updatedAt?.formatted }}
                    </BaseDataView>
                </dl>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentLeaderboardShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"

const route = useRoute()
const router = useRouter()

const initialLeaderboard = {}

const initUrl = "gamification/leaderboard/"

const leaderboard = reactive({ ...initialLeaderboard })

const setItem = (data) => {
    Object.assign(leaderboard, data)
}
</script>
