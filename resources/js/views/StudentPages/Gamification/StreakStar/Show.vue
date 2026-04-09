<template>
    <PageHeader :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })" :navs="[
        { label: $trans('gamification.gamification'), path: 'MyStudentGamification' },
        { label: $trans('gamification.streakstar.streakstars'), path: 'MyStudentStreakStarList' },
    ]">
    </PageHeader>
    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'MyStudentStreakStarList' })">
            <DetailLayoutVertical v-if="streakstar.uuid">
                <template #detail>
                    <div class="space-y-2">
                        <BaseCard no-padding no-content-padding>
                            <template #title>
                                {{ $trans('gamification.streakstar.streakstar') }}
                            </template>
                            <ListContainerVertical>
                                <ListItemView :label="$trans('gamification.streakstar.props.name')">
                                    {{ streakstar.name }}
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.streakstar.props.points_required')">
                                    {{ streakstar.points_required || '-' }}
                                </ListItemView>
                                <ListItemView :label="$trans('general.created_at')">
                                    {{ streakstar.createdAt.formatted }}
                                </ListItemView>
                                <ListItemView :label="$trans('general.updated_at')">
                                    {{ streakstar.updatedAt.formatted }}
                                </ListItemView>
                            </ListContainerVertical>
                        </BaseCard>
                        <BaseCard no-padding no-content-padding bottom-content-padding>
                            <template #title>
                                {{ $trans('global.detail', { attribute: $trans('gamification.streakstar.streakstar') })
                                }}
                            </template>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 px-4 pt-4 sm:grid-cols-2">
                                <BaseDataView class="col-span-1 sm:col-span-2"
                                    :label="$trans('gamification.streakstar.props.media')">
                                    <img v-if="streakstar.media && streakstar.media.length" :src="streakstar.media[0]"
                                        alt="Streak Star Media" class="w-20 h-20" />
                                    <span v-else>-</span>
                                </BaseDataView>
                                <BaseDataView class="col-span-1 sm:col-span-2"
                                    :label="$trans('gamification.streakstar.props.description')">
                                    {{ streakstar.description || '-' }}
                                </BaseDataView>
                            </dl>
                        </BaseCard>
                    </div>
                </template>
            </DetailLayoutVertical>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: 'MyStudentStreakStarShow',
}
</script>

<script setup>
import { reactive, inject } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const $trans = inject('$trans')

const initialStreakStar = {}
const initUrl = 'gamification/streakstar/'
const streakstar = reactive({ ...initialStreakStar })

const setItem = (data) => {
    Object.assign(streakstar, data)
}
</script>