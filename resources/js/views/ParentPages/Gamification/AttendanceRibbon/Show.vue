<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })"
        :navs="[
            { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            { label: $trans('gamification.attendance_ribbon.attendance_ribbons'), path: 'MyParentStudentGamification', query: { tab: 'streak-attendance-ribbon' } },
        ]"
    >
    <PageHeaderAction
      name="ParentChallenge"
      :title="$trans('gamification.attendance_ribbon.attendance_ribbons')"
      :actions="[]"
    >
    <BaseButton design="primary" class="shadow-sm" @click="router.push({ name: 'MyParentStudentGamification', query: { tab: 'attendance-ribbon' } })">
        {{ $trans("global.list_all", { attribute: $trans('gamification.attendance_ribbon.attendance_ribbons') }) }}
      </BaseButton>
    </PageHeaderAction>
    </PageHeader>


    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentAttendanceRibbonList' })"
        >
            <BaseCard v-if="attendanceRibbon.uuid">
                <template #title>
                    {{ attendanceRibbon.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView v-if="attendanceRibbon.icon" :label="$trans('gamification.attendance_ribbon.props.icon')">
                        <img :src="attendanceRibbon.icon" alt="" width="40px" />
                    </BaseDataView>
                    <BaseDataView class="col-span-1 sm:col-span-2" :label="$trans('gamification.attendance_ribbon.props.description')" html>
                        {{ attendanceRibbon.description }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.created_at')">
                        {{ attendanceRibbon.createdAt?.formatted }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ attendanceRibbon.updatedAt?.formatted }}
                    </BaseDataView>
                </dl>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentAttendanceRibbonShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"

const route = useRoute()
const router = useRouter()

const initialAttendanceRibbon = {}

const initUrl = "gamification/attendanceRibbon/"

const attendanceRibbon = reactive({ ...initialAttendanceRibbon })

const setItem = (data) => {
    Object.assign(attendanceRibbon, data)
}
</script>
