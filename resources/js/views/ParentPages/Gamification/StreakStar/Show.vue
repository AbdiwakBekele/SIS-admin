<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })"
        :navs="[
            { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            { label: $trans('gamification.streakstar.streakstars'), path: 'MyParentStudentGamification', query: { tab: 'streak-stars' } },
        ]"
    >
    <PageHeaderAction
      name="ParentChallenge"
      :title="$trans('gamification.streakstar.streakstars')"
      :actions="[]"
    >
    <BaseButton design="primary" class="shadow-sm" @click="router.push({ name: 'MyParentStudentGamification', query: { tab: 'streak-stars' } })">
        {{ $trans("global.list_all", { attribute: $trans('gamification.streakstar.streakstars') }) }}
      </BaseButton>
    </PageHeaderAction>
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentStreakStarList' })"
        >
            <BaseCard v-if="streakStar.uuid">
                <template #title>
                    {{ streakStar.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('gamification.streak_star.props.code')">
                        {{ streakStar.code }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('gamification.streak_star.props.level')">
                        {{ streakStar.level }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('gamification.streak_star.props.points_required')">
                        {{ streakStar.pointsRequired }}
                    </BaseDataView>
                    <BaseDataView v-if="streakStar.icon" :label="$trans('gamification.streak_star.props.icon')">
                        <img :src="streakStar.icon" alt="" width="40px" />
                    </BaseDataView>
                    <BaseDataView class="col-span-1 sm:col-span-2" :label="$trans('gamification.streak_star.props.description')" html>
                        {{ streakStar.description }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.created_at')">
                        {{ streakStar.createdAt?.formatted }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ streakStar.updatedAt?.formatted }}
                    </BaseDataView>
                </dl>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentStreakStarShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"

const route = useRoute()
const router = useRouter()

const initialStreakStar = {}

const initUrl = "gamification/streakstar/"

const streakStar = reactive({ ...initialStreakStar })

const setItem = (data) => {
    Object.assign(streakStar, data)
}
</script>
