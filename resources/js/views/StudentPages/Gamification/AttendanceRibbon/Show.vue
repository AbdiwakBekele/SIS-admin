<template>
    <PageHeader :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })" :navs="[
        { label: $trans('gamification.gamification'), path: 'MyStudentGamification' },
        { label: $trans('gamification.attendance_ribbon.attendance_ribbon'), path: 'MyStudentAttendanceRibbonList' },
    ]">
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentAttendanceRibbonList' })">
            <DetailLayoutVertical v-if="ribbon.uuid">
                <template #detail>
                    <div class="space-y-2">
                        <BaseCard no-padding no-content-padding>
                            <template #title>
                                {{ $trans('gamification.attendance_ribbon.attendance_ribbon') }}
                            </template>
                            <ListContainerVertical>
                                <ListItemView :label="$trans('gamification.attendance_ribbon.props.name')">
                                    {{ ribbon.name }}
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.attendance_ribbon.props.days')">
                                    {{ ribbon.daysRequired || '-' }}
                                </ListItemView>
                                <ListItemView :label="$trans('general.created_at')">
                                    {{ ribbon.createdAt.formatted }}
                                </ListItemView>
                                <ListItemView :label="$trans('general.updated_at')">
                                    {{ ribbon.updatedAt.formatted }}
                                </ListItemView>
                            </ListContainerVertical>
                        </BaseCard>
                        <BaseCard no-padding no-content-padding bottom-content-padding>
                            <template #title>
                                {{ $trans('global.detail', {
                                    attribute:
                                        $trans('gamification.attendance_ribbon.attendance_ribbon')
                                }) }}
                            </template>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 px-4 pt-4 sm:grid-cols-2">
                                <BaseDataView class="col-span-1 sm:col-span-2"
                                    :label="$trans('gamification.attendance_ribbon.props.media')">
                                    <img v-if="ribbon.media && ribbon.media.length" :src="ribbon.media[0]?.url"
                                        alt="Ribbon Media" class="w-20 h-20" />
                                    <span v-else>-</span>
                                </BaseDataView>
                                <BaseDataView class="col-span-1 sm:col-span-2"
                                    :label="$trans('gamification.attendance_ribbon.props.description')">
                                    {{ ribbon.description || '-' }}
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
    name: 'MyStudentAttendanceRibbonShow',
}
</script>

<script setup>
import { reactive, inject } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const $trans = inject('$trans')

const initialRibbon = {}
const initUrl = 'gamification/attendanceRibbon/'
const ribbon = reactive({ ...initialRibbon })

const setItem = (data) => {
    Object.assign(ribbon, data)
}
</script>