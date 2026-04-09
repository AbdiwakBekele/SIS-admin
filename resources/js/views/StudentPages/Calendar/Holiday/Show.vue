<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('calendar.calendar'), path: 'Calendar' },
            {
                label: $trans('calendar.holiday.holiday'),
                path: 'StudentProfileCalendarHolidayList',
            },
        ]"
    >
        <PageHeaderAction
            name="StudentProfileCalendarHoliday"
            :title="$trans('calendar.holiday.holiday')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentProfileCalendarHolidayList' })"
        >
            <BaseCard v-if="holiday.uuid">
                <template #title>
                    {{ holiday.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2 md:grid-cols-3">
                    <BaseDataView
                        :label="$trans('calendar.holiday.props.name')"
                    >
                        {{ holiday.name }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('calendar.holiday.props.start_date')"
                    >
                        {{ holiday.startDate.formatted }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('calendar.holiday.props.end_date')"
                    >
                        {{ holiday.endDate.formatted }}
                    </BaseDataView>

                    <BaseDataView
                        class="col-span-1 sm:col-span-2 md:col-span-3"
                        :label="$trans('calendar.holiday.props.description')"
                    >
                        {{ holiday.description }}
                    </BaseDataView>
                </dl>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "StudentProfileCalendarHolidayShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"

const route = useRoute()
const router = useRouter()

const initialHoliday = {}

const initUrl = "calendar/holiday/"

const holiday = reactive({ ...initialHoliday })

const setItem = (data) => {
    Object.assign(holiday, data)
}
</script>
