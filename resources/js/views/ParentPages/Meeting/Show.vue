<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })"
        :navs="[
            { label: $trans('meeting.meeting.meetings'), path: 'ParentMeetingList' },
        ]"
    >
        <PageHeaderAction
            name="ParentMeeting"
            :title="$trans('meeting.meeting.meeting')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentMeetingList' })"
        >
            <BaseCard v-if="meeting.uuid" class="w-full shadow-lg">
                <template #title>
                    <div class="flex items-center gap-2">
                        <CalendarDaysIcon class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        <span class="text-xl">{{ meeting.title || 'N/A' }}</span>
                    </div>
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
                    <BaseDataView :label="$trans('meeting.meeting.props.venue')">
                        <div class="flex items-center gap-2">
                            <MapPinIcon class="h-4 w-4 text-gray-500" />
                            {{ meeting.venue || 'N/A' }}
                        </div>
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.start_date')">
                        <div class="flex items-center gap-2">
                            <CalendarIcon class="h-4 w-4 text-gray-500" />
                            {{ meeting.startDate?.formatted ?? 'N/A' }}
                        </div>
                        <TextMuted block v-if="meeting.startTime?.formatted || meeting.startTime?.formattedWithTimezone">
                            <div class="flex items-center gap-2 mt-1">
                                <ClockIcon class="h-4 w-4 text-gray-400" />
                                {{ meeting.startTime?.formattedWithTimezone || meeting.startTime?.formatted }}
                            </div>
                        </TextMuted>
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.end_time')">
                        <div class="flex items-center gap-2">
                            <ClockIcon class="h-4 w-4 text-gray-500" />
                            {{ (meeting.endTime?.formattedWithTimezone || meeting.endTime?.formatted) || 'N/A' }}
                        </div>
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.audience')">
                        <template v-if="meeting.audienceTypes?.length">
                            <div class="flex flex-wrap gap-2">
                                <BaseBadge design="primary" v-for="(type, index) in meeting.audienceTypes" :key="index">
                                    {{ type }}
                                </BaseBadge>
                            </div>
                        </template>
                        <template v-else>N/A</template>
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.student_audience_type')">
                        <BaseBadge v-if="meeting.studentAudienceType?.label" design="primary">
                            {{ meeting.studentAudienceType.label }}
                        </BaseBadge>
                        <span v-else>N/A</span>
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.employee_audience_type')">
                        <BaseBadge v-if="meeting.employeeAudienceType?.label" design="primary">
                            {{ meeting.employeeAudienceType.label }}
                        </BaseBadge>
                        <span v-else>N/A</span>
                    </BaseDataView>

                    <BaseDataView class="col-span-1 sm:col-span-2 lg:col-span-3" :label="$trans('meeting.meeting.props.description')" html>
                        <div v-if="meeting.description" v-html="meeting.description"></div>
                        <span v-else class="text-gray-500 italic">No description available</span>
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.created_at')">
                        {{ meeting.createdAt?.formatted ?? 'N/A' }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ meeting.updatedAt?.formatted ?? 'N/A' }}
                    </BaseDataView>

                    <BaseDataView
                        class="col-span-1 sm:col-span-2 lg:col-span-3"
                        v-if="meeting.media && meeting.media.length > 0"
                    >
                        <ListMedia
                            :media="meeting.media"
                            :url="`/api/v1/app/meetings/${meeting.uuid}/`"
                        />
                    </BaseDataView>
                </dl>
            </BaseCard>
            <div v-else class="text-gray-500 p-4 text-center">
                No meeting data available
            </div>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "MeetingShow",
}
</script>

<script setup>
import { reactive, inject } from "vue"
import { useRoute, useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
import { 
    MapPinIcon, 
    CalendarIcon, 
    ClockIcon, 
    CalendarDaysIcon,
} from "@heroicons/vue/20/solid"

const route = useRoute()
const router = useRouter()
const $trans = inject("$trans")

const initialMeeting = {
    uuid: "",
    title: "",
    venue: "",
    description: "",
    startDate: null,
    startTime: null,
    endTime: null,
    audienceTypes: [],
    audiences: [],
    studentAudienceType: null,
    employeeAudienceType: null,
    media: [],
    coverImage: "",
    createdAt: null,
    updatedAt: null,
}

const initUrl = "meetings/"

const meeting = reactive({ ...initialMeeting })

const setItem = (data) => {
    console.log("Meeting data:", data)
    Object.assign(meeting, {
        ...initialMeeting,
        uuid: data.uuid || "",
        title: data.title || "",
        venue: data.venue || "",
        description: data.description || "",
        startDate: data.startDate || null,
        startTime: data.startTime || null,
        endTime: data.endTime || null,
        audienceTypes: data.audienceTypes || [],
        audiences: data.audiences || [],
        studentAudienceType: data.studentAudienceType || null,
        employeeAudienceType: data.employeeAudienceType || null,
        media: data.media || [],
        coverImage: data.coverImage || "",
        createdAt: data.createdAt || null,
        updatedAt: data.updatedAt || null,
    })
}
</script>