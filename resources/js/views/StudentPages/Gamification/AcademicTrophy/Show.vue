```vue
<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans('gamification.academic_trophy.academic_trophies') })"
        :navs="[
            { label: $trans('gamification.gamification'), path: 'MyStudentGamification' },
            { label: $trans('gamification.academic_trophy.academic_trophies'), path: 'MyStudentAcademicTrophyList' },
        ]">
    </PageHeader>
    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'MyStudentAcademicTrophyList' })">
            <DetailLayoutVertical v-if="trophy.uuid">
                <template #detail>
                    <div class="space-y-2">
                        <BaseCard no-padding no-content-padding bottom-content-padding>
                            <template #title>
                                {{ $trans('gamification.academic_trophy.academic_trophy') }}
                            </template>
                            <ListContainerVertical>
                                <ListItemView :label="$trans('gamification.academic_trophy.props.name')">
                                    {{ trophy.name }}
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.academic_trophy.props.points_required')">
                                    {{ trophy.points || '-' }}
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.academic_trophy.props.icon')">
                                    <img v-if="trophy.icon" :src="trophy.icon" alt="Trophy Icon" class="w-20 h-20" />
                                    <span v-else>-</span>
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.academic_trophy.props.description')">
                                    {{ trophy.description || '-' }}
                                </ListItemView>
                                <ListItemView :label="$trans('general.created_at')">
                                    {{ trophy.createdAt.formatted }}
                                </ListItemView>
                                <ListItemView :label="$trans('general.updated_at')">
                                    {{ trophy.updatedAt.formatted }}
                                </ListItemView>
                            </ListContainerVertical>
                        </BaseCard>
                    </div>
                </template>
            </DetailLayoutVertical>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: 'MyStudentAcademicTrophyShow',
}
</script>

<script setup>
import { reactive, inject } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const $trans = inject('$trans')

const initialTrophy = {}
const initUrl = 'gamification/academicTrophy/'
const trophy = reactive({ ...initialTrophy })

const setItem = (data) => {
    Object.assign(trophy, data)
}
</script>