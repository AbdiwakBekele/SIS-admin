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
                label: $trans('calendar.event.event'),
                path: 'ParentCalendarEventList',
            },
        ]"
    >
        <PageHeaderAction
            name="ParentCalendarEvent"
            :title="$trans('calendar.event.event')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentCalendarEventList' })"
        >
            <BaseCard
                v-if="event.uuid && actingAs(['student', 'guardian'], 'any')"
            >
                <template #title>
                    <div class="flex items-center justify-center gap-2">
                        {{ event.title }}
                        <span class="text-sm">{{ event.duration }}</span>
                    </div>
                </template>

                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('calendar.event.props.type')">
                        {{ event.type.name }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('calendar.event.props.venue')">
                        {{ event.venue }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('general.period')"
                        class="col-span-1 sm:col-span-2"
                    >
                        {{ event.durationInDetail }}
                    </BaseDataView>

                    <BaseDataView
                        v-if="event.isRecurring && event.recurring"
                        :label="$trans('calendar.event.props.is_recurring')"
                        class="col-span-1 sm:col-span-2"
                    >
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-repeat text-blue-600"></i>
                                <span>{{ getRecurringText() }}</span>
                            </div>
                            <TextMuted
                                v-if="(event.recurring.endDate || event.recurring.end_date) && !(event.recurring.noEndDate || event.recurring.no_end_date)"
                                block
                            >
                                {{ $trans('calendar.event.recurring.ends_on') }}: 
                                {{ formatDate(event.recurring.endDate || event.recurring.end_date) }}
                            </TextMuted>
                            <TextMuted
                                v-else-if="event.recurring.noEndDate || event.recurring.no_end_date"
                                block
                            >
                                {{ $trans('calendar.event.recurring.no_end_date') }}
                            </TextMuted>
                        </div>
                    </BaseDataView>
                </dl>

                <dl
                    class="mt-8 grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2"
                >
                    <BaseDataView class="col-span-1 sm:col-span-2" html>
                        {{ event.description }}
                    </BaseDataView>

                    <BaseDataView
                        class="col-span-1 sm:col-span-2"
                        v-if="event.media.length > 0"
                    >
                        <ListMedia
                            :media="event.media"
                            :url="`/api/v1/app/calendar/events/${event.uuid}/`"
                        />
                    </BaseDataView>
                </dl>
            </BaseCard>

            <DetailLayoutVertical
                v-if="event.uuid && !actingAs(['student', 'guardian'], 'any')"
            >
                <template #detail>
                    <div class="space-y-2">
                        <BaseCard no-padding no-content-padding>
                            <template #title>
                                #{{ event.codeNumber }}
                            </template>

                            <template #action> </template>
                            <ListContainerVertical>
                                <ListItemView
                                    :label="
                                        $trans('calendar.event.props.title')
                                    "
                                >
                                    {{ event.title }}
                                </ListItemView>
                                <ListItemView
                                    :label="
                                        $trans('calendar.event.props.venue')
                                    "
                                >
                                    {{ event.venue }}
                                </ListItemView>

                                <ListItemView
                                    :label="
                                        $trans(
                                            'calendar.event.props.start_date'
                                        )
                                    "
                                >
                                    {{ event.startDate.formatted }}
                                    <TextMuted
                                        block
                                        v-if="event.startTime.value"
                                        >{{
                                            event.startTime.formattedWithTimezone || event.startTime.formatted
                                        }}</TextMuted
                                    >
                                </ListItemView>

                                <ListItemView
                                    :label="
                                        $trans('calendar.event.props.end_date')
                                    "
                                >
                                    {{ event.endDate?.formatted || "-" }}
                                    <TextMuted
                                        block
                                        v-if="event.endTime.value"
                                        >{{
                                            event.endTime.formattedWithTimezone || event.endTime.formatted
                                        }}</TextMuted
                                    >
                                </ListItemView>

                                <ListItemView
                                    :label="
                                        $trans('employee.incharge.incharge')
                                    "
                                >
                                    <template
                                        v-for="incharges in event.incharges"
                                    >
                                        {{ incharges.employee?.name || "-" }}
                                        <TextMuted
                                            v-if="
                                                incharges.employee?.designation
                                            "
                                        >
                                            {{
                                                incharges.employee
                                                    ?.designation || "-"
                                            }}
                                        </TextMuted>
                                    </template>
                                </ListItemView>

                                <template
                                    v-if="!event.isPublic && event.forAlumni"
                                >
                                    <ListItemView
                                        :label="
                                            $trans(
                                                'calendar.event.props.audience'
                                            )
                                        "
                                    >
                                        {{
                                            $trans(
                                                "calendar.event.props.for_alumni"
                                            )
                                        }}
                                        <template
                                            v-for="period in event.periodDetails"
                                        >
                                            <TextMuted block>{{
                                                period.name
                                            }}</TextMuted>
                                        </template>

                                        <template
                                            v-for="session in event.sessionDetails"
                                        >
                                            <TextMuted block>{{
                                                session.name
                                            }}</TextMuted>
                                        </template>
                                    </ListItemView>
                                </template>

                                <template
                                    v-if="!event.isPublic && !event.forAlumni"
                                >
                                    <ListItemView
                                        v-if="event.studentAudienceType?.value"
                                        :label="
                                            $trans(
                                                'calendar.event.props.student_audience_type'
                                            )
                                        "
                                    >
                                        {{
                                            event.studentAudienceType.label
                                        }}
                                        <template
                                            v-for="audience in filteredAudience(
                                                'student'
                                            )"
                                            :key="audience.uuid"
                                        >
                                            <TextMuted block>
                                                {{ audience.name }}
                                                <span v-if="audience.detail"> - {{ audience.detail }}</span>
                                            </TextMuted>
                                        </template>
                                    </ListItemView>

                                    <ListItemView
                                        v-if="event.employeeAudienceType?.value"
                                        :label="
                                            $trans(
                                                'calendar.event.props.staff_audience_type'
                                            )
                                        "
                                    >
                                        {{
                                            event.employeeAudienceType.label
                                        }}
                                        <template
                                            v-for="audience in filteredAudience(
                                                'employee'
                                            )"
                                            :key="audience.uuid"
                                        >
                                            <TextMuted block>
                                                {{ audience.name }}
                                                <span v-if="audience.detail"> - {{ audience.detail }}</span>
                                            </TextMuted>
                                        </template>
                                    </ListItemView>

                                    <ListItemView
                                        v-if="event.guardianAudienceType?.value"
                                        :label="
                                            $trans(
                                                'calendar.event.props.parent_audience_type'
                                            )
                                        "
                                    >
                                        {{
                                            event.guardianAudienceType.label
                                        }}
                                        <template
                                            v-for="audience in filteredAudience(
                                                'guardian'
                                            )"
                                            :key="audience.uuid"
                                        >
                                            <TextMuted block>
                                                {{ audience.name }}
                                                <span v-if="audience.detail"> - {{ audience.detail }}</span>
                                            </TextMuted>
                                        </template>
                                    </ListItemView>

                                    <ListItemView
                                        :label="$trans('general.created_at')"
                                    >
                                        {{ event.createdAt.formatted }}
                                    </ListItemView>

                                    <ListItemView
                                        :label="$trans('general.updated_at')"
                                    >
                                        {{ event.updatedAt.formatted }}
                                    </ListItemView>
                                </template>
                            </ListContainerVertical>
                        </BaseCard>

                    </div>
                </template>

                <BaseCard v-if="event.uuid">
                    <template #title>
                        <div class="flex justify-center gap-2">
                            {{ event.title }}
                            <BaseBadge design="primary" v-if="event.isPublic">
                                {{ $trans("general.public") }}</BaseBadge
                            >
                        </div>
                    </template>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <BaseDataView class="col-span-1 sm:col-span-2" html>
                            {{ event.description }}
                        </BaseDataView>

                        <BaseDataView
                            class="col-span-1 sm:col-span-2"
                            v-if="event.media && event.media.length > 0"
                        >
                            <ListMedia
                                :media="event.media"
                                :url="`/app/calendar/events/${event.uuid}/`"
                            />
                        </BaseDataView>
                    </dl>
                </BaseCard>
            </DetailLayoutVertical>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "CalendarEventShow",
}
</script>

<script setup>
import { reactive, inject } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform, actingAs } from "@core/helpers/action"

const store = useStore()
const route = useRoute()
const router = useRouter()
const $trans = inject("$trans")

const initialEvent = {}

const initUrl = "calendar/event/"

const event = reactive({ ...initialEvent })

const setItem = (data) => {
    Object.assign(event, data)
}

const filteredAudience = (type) => {
    return event.audiences.filter((audience) => {
        return audience.type === type
    })
}

const getRecurringText = () => {
    if (!event.recurring) return ""

    const recurring = event.recurring
    const type = recurring.type

    switch (type) {
        case "daily":
            return $trans("calendar.event.recurring.every_day")
        case "weekly":
            const weeklyDaysList = recurring.weeklyDays || recurring.weekly_days || []
            if (weeklyDaysList.length) {
                const dayNames = [
                    $trans("calendar.event.recurring.sunday"),
                    $trans("calendar.event.recurring.monday"),
                    $trans("calendar.event.recurring.tuesday"),
                    $trans("calendar.event.recurring.wednesday"),
                    $trans("calendar.event.recurring.thursday"),
                    $trans("calendar.event.recurring.friday"),
                    $trans("calendar.event.recurring.saturday"),
                ]
                const days = weeklyDaysList.map((d) => dayNames[d]).join(", ")
                return `${$trans("calendar.event.recurring.every_week_on")} ${days}`
            }
            return $trans("calendar.event.recurring.every_week")
        case "monthly":
            const monthlyType = recurring.monthlyType || recurring.monthly_type
            if (monthlyType === "day") {
                const dayOfMonth = recurring.dayOfMonth || recurring.day_of_month
                return $trans("calendar.event.recurring.monthly_day", { day: dayOfMonth })
            } else {
                const weekdayPosition = recurring.weekdayPosition || recurring.weekday_position
                const weekday = recurring.weekday
                return weekdayPosition && weekday !== undefined
                    ? `${getWeekdayPosition(weekdayPosition)} ${getDayName(weekday)}`
                    : $trans("calendar.event.recurring.monthly")
            }
        case "annually":
            return $trans("calendar.event.recurring.every_year")
        case "weekdays":
            return $trans("calendar.event.recurring.every_weekday")
        case "custom":
            const customDatesList = recurring.customDates || recurring.custom_dates || []
            if (customDatesList.length) {
                return $trans("calendar.event.recurring.custom_dates_count", {
                    count: customDatesList.length,
                })
            }
            return $trans("calendar.event.recurring.custom")
        default:
            return ""
    }
}

const getWeekdayPosition = (position) => {
    const positions = [
        "",
        $trans("calendar.event.recurring.first"),
        $trans("calendar.event.recurring.second"),
        $trans("calendar.event.recurring.third"),
        $trans("calendar.event.recurring.fourth"),
        $trans("calendar.event.recurring.last"),
    ]
    return positions[position] || ""
}

const getDayName = (dayIndex) => {
    const dayNames = [
        $trans("calendar.event.recurring.sunday"),
        $trans("calendar.event.recurring.monday"),
        $trans("calendar.event.recurring.tuesday"),
        $trans("calendar.event.recurring.wednesday"),
        $trans("calendar.event.recurring.thursday"),
        $trans("calendar.event.recurring.friday"),
        $trans("calendar.event.recurring.saturday"),
    ]
    return dayNames[dayIndex] || ""
}

const formatDate = (dateString) => {
    if (!dateString) return ""
    try {
        const date = new Date(dateString + "T00:00:00")
        if (isNaN(date.getTime())) {
            return dateString
        }
        return date.toLocaleDateString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
        })
    } catch (e) {
        return dateString
    }
}
</script>
