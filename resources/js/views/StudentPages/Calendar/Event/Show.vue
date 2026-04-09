<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('calendar.calendar'), path: 'StudentCalendar' },
            {
                label: $trans('calendar.event.event'),
                path: 'StudentCalendarEventList',
            },
        ]"
    >
        <PageHeaderAction
            name="StudentCalendarEvent"
            :title="$trans('calendar.event.event')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentCalendarEventList' })"
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
                        {{ event.type?.name || '-' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('calendar.event.props.venue')">
                        {{ event.venue || '-' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('calendar.event.props.virtual_link')">
                        <template v-if="event.virtualLink">
                            <a
                                :href="normalizeUrl(event.virtualLink)"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-blue-600 hover:underline"
                            >
                                {{ event.virtualLink }}
                            </a>
                        </template>
                        <template v-else>
                            -
                        </template>
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('general.period')"
                        class="col-span-1 sm:grid-cols-2"
                    >
                        {{ event.durationInDetail }}
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
                        v-if="event.media && event.media.length > 0"
                    >
                        <ListMedia
                            :media="event.media"
                            :url="`/app/calendar/events/${event.uuid}/`"
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
                                    :label="$trans('calendar.event.props.venue')"
                                >
                                    {{ event.venue || '-' }}
                                </ListItemView>
                                <ListItemView
                                    :label="$trans('calendar.event.props.virtual_link')"
                                >
                                    <template v-if="event.virtualLink">
                                        <a
                                            :href="normalizeUrl(event.virtualLink)"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-blue-600 hover:underline"
                                        >
                                            {{ event.virtualLink }}
                                        </a>
                                    </template>
                                    <template v-else>
                                        -
                                    </template>
                                </ListItemView>

                                <ListItemView
                                    :label="
                                        $trans(
                                            'calendar.event.props.start_date'
                                        )
                                    "
                                >
                                    {{ event.startDate?.formatted || '-' }}
                                    <TextMuted
                                        block
                                        v-if="event.startTime && event.startTime.value"
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
                                        v-if="event.endTime && event.endTime.value"
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
                                        :label="
                                            $trans(
                                                'calendar.event.props.audience'
                                            )
                                        "
                                    >
                                        {{ event.studentAudienceType?.label || '-' }}
                                        <template
                                            v-for="audience in filteredAudience(
                                                'student'
                                            )"
                                        >
                                            <TextMuted block>{{
                                                audience.name
                                            }}</TextMuted>
                                        </template>
                                    </ListItemView>

                                    <ListItemView
                                        :label="
                                            $trans(
                                                'calendar.event.props.audience'
                                            )
                                        "
                                    >
                                        <template
                                            v-if="
                                                event.employeeAudienceType &&
                                                event.employeeAudienceType.value
                                            "
                                        >
                                            {{
                                                event.employeeAudienceType.label
                                            }}
                                            <template
                                                v-for="audience in filteredAudience(
                                                    'employee'
                                                )"
                                            >
                                                <TextMuted block>{{
                                                    audience.name
                                                }}</TextMuted>
                                            </template>
                                        </template>
                                        <template v-else>-</template>
                                    </ListItemView>

                                    <ListItemView
                                        :label="$trans('general.created_at')"
                                    >
                                        {{ event.createdAt?.formatted || '-' }}
                                    </ListItemView>

                                    <ListItemView
                                        :label="$trans('general.updated_at')"
                                    >
                                        {{ event.updatedAt?.formatted || '-' }}
                                    </ListItemView>
                                </template>
                            </ListContainerVertical>
                        </BaseCard>

                        <BaseCard
                            no-padding
                            no-content-padding
                            v-if="perform('event:edit')"
                        >
                            <template #title>{{
                                $trans("calendar.event.props.cover_image")
                            }}</template>
                            <CoverImage
                                v-if="event.uuid"
                                :event="event"
                                @refreshItem="refreshItem = true"
                            />
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
                    <template #footer>
                        <ShowButton>
                            <BaseButton
                                v-if="perform('event:edit')"
                                design="primary"
                                @click="
                                    router.push({
                                        name: 'StudentCalendarEventEdit',
                                        params: { uuid: event.uuid },
                                    })
                                "
                            >
                                {{ $trans("general.edit") }}
                            </BaseButton>
                        </ShowButton>
                    </template>
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
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform, actingAs } from "@core/helpers/action"
import CoverImage from "./CoverImage.vue"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialEvent = {}

const initUrl = "calendar/event/"

const event = reactive({ ...initialEvent })

const setItem = (data) => {
    Object.assign(event, data)
}

const normalizeUrl = (str) => {
    if (!str || typeof str !== "string") return "#"
    const s = str.trim()
    if (/^https?:\/\//i.test(s)) return s
    if (/^[a-z][a-z0-9+.-]*:\/\//i.test(s)) return s
    return "https://" + s
}

const filteredAudience = (type) => {
    return event.audiences.filter((audience) => {
        return audience.type === type
    })
}
</script>
