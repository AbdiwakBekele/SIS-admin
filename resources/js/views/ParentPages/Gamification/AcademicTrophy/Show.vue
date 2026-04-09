<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })"
        :navs="[
            { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            { label: $trans('gamification.academic_trophy.academic_trophies'), path: 'MyParentStudentGamification', query: { tab: 'academic-trophy' } },
        ]"
    >
    <PageHeaderAction
      name="ParentChallenge"
      :title="$trans('gamification.academic_trophy.academic_trophies')"
      :actions="[]"
    >
    <BaseButton design="primary" class="shadow-sm" @click="router.push({ name: 'MyParentStudentGamification', query: { tab: 'academic-trophy' } })">
        {{ $trans("global.list_all", { attribute: $trans('gamification.academic_trophy.academic_trophies') }) }}
      </BaseButton>
    </PageHeaderAction>
    </PageHeader>


    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentAcademicTrophyList' })"
        >
            <BaseCard v-if="academicTrophy.uuid">
                <template #title>
                    {{ academicTrophy.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView v-if="academicTrophy.icon" :label="$trans('gamification.academic_trophy.props.icon')">
                        <img :src="academicTrophy.icon" alt="" width="40px" />
                    </BaseDataView>
                    <BaseDataView class="col-span-1 sm:col-span-2" :label="$trans('gamification.academic_trophy.props.description')" html>
                        {{ academicTrophy.description }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.created_at')">
                        {{ academicTrophy.createdAt?.formatted }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ academicTrophy.updatedAt?.formatted }}
                    </BaseDataView>
                </dl>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentAcademicTrophyShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"

const route = useRoute()
const router = useRouter()

const initialAcademicTrophy = {}

const initUrl = "gamification/academicTrophy/"

const academicTrophy = reactive({ ...initialAcademicTrophy })

const setItem = (data) => {
    Object.assign(academicTrophy, data)
}
</script>
