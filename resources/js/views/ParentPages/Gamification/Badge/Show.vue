<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })"
        :navs="[
            { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            { label: $trans('gamification.badge.badges'), path: 'MyParentStudentGamification', query: { tab: 'badges' } },
        ]"
    >
    <PageHeaderAction
      name="ParentChallenge"
      :title="$trans('gamification.badge.badges')"
      :actions="[]"
    >
    <BaseButton design="primary" class="shadow-sm" @click="router.push({ name: 'MyParentStudentGamification', query: { tab: 'badges' } })">
        {{ $trans("global.list_all", { attribute: $trans('gamification.badge.badges') }) }}
      </BaseButton>
    </PageHeaderAction>
    </PageHeader>
    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentBadgeList' })"
        >
            <BaseCard v-if="badge.uuid">
                <template #title>
                    {{ badge.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('gamification.badge.props.code')">
                        {{ badge.code }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('gamification.badge.props.level')">
                        {{ badge.level }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('gamification.badge.props.points_required')">
                        {{ badge.pointsRequired }}
                    </BaseDataView>
                    <BaseDataView v-if="badge.icon" :label="$trans('gamification.badge.props.icon')">
                        <img :src="badge.icon" alt="" width="40px" />
                    </BaseDataView>
                    <BaseDataView class="col-span-1 sm:col-span-2" :label="$trans('gamification.badge.props.description')" html>
                        {{ badge.description }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.created_at')">
                        {{ badge.createdAt?.formatted }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ badge.updatedAt?.formatted }}
                    </BaseDataView>
                </dl>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentBadgeShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"

const route = useRoute()
const router = useRouter()

const initialBadge = {}

const initUrl = "gamification/badge/"

const badge = reactive({ ...initialBadge })

const setItem = (data) => {
    Object.assign(badge, data)
}
</script>
