<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })"
        :navs="[
            { label: $trans('meeting.meeting.meetings'), path: 'StudentMeetingList' },
        ]"
    >
        <PageHeaderAction
            name="StudentMeeting"
            :title="$trans('meeting.meeting.meeting')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentMeetingList' })"
        >
            <BaseCard v-if="meeting.uuid" class="w-full">
                <template #title>
                    {{ meeting.title || 'N/A' }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('meeting.meeting.props.title')">
                        {{ meeting.title || 'N/A' }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.venue')">
                        {{ meeting.venue || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('meeting.meeting.props.virtual_link')">
                        <template v-if="meeting.virtualLink">
                            <a
                                :href="normalizeUrl(meeting.virtualLink)"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-blue-600 hover:underline"
                            >
                                {{ meeting.virtualLink }}
                            </a>
                        </template>
                        <template v-else>
                            N/A
                        </template>
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.start_date')">
                        {{ meeting.startDate?.formatted ?? 'N/A' }}
                        <TextMuted block v-if="meeting.startTime?.formatted || meeting.startTime?.formattedWithTimezone">
                            {{ meeting.startTime?.formattedWithTimezone || meeting.startTime?.formatted }}
                        </TextMuted>
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.end_time')">
                        {{ (meeting.endTime?.formattedWithTimezone || meeting.endTime?.formatted) || 'N/A' }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.audience')">
                        <template v-if="meeting.audienceTypes?.length">
                            <TextMuted block v-for="(type, index) in meeting.audienceTypes" :key="index">
                                {{ type }}
                            </TextMuted>
                        </template>
                        <template v-else>N/A</template>
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.student_audience_type')">
                        {{ meeting.studentAudienceType?.label || 'N/A' }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('meeting.meeting.props.employee_audience_type')">
                        {{ meeting.employeeAudienceType?.label || 'N/A' }}
                    </BaseDataView>

                    <BaseDataView class="col-span-1 sm:col-span-2" :label="$trans('meeting.meeting.props.description')" html>
                        {{ meeting.description || 'No description available' }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.created_at')">
                        {{ meeting.createdAt?.formatted ?? 'N/A' }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ meeting.updatedAt?.formatted ?? 'N/A' }}
                    </BaseDataView>

                    <BaseDataView
                        class="col-span-1 sm:col-span-2"
                        v-if="meeting.media && meeting.media.length > 0"
                    >
                        <ListMedia
                            :media="meeting.media"
                            :url="`/api/v1/app/meetings/${meeting.uuid}/`"
                        />
                    </BaseDataView>
                </dl>
                <template #footer>
                    <ShowButton>
                        <BaseButton
                            v-if="perform('meeting:edit')"
                            design="primary"
                            @click="router.push({ name: 'MeetingEdit', params: { uuid: meeting.uuid } })"
                        >
                            {{ $trans('general.edit') }}
                        </BaseButton>
                    </ShowButton>
                </template>
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

const route = useRoute()
const router = useRouter()
const $trans = inject("$trans")

const initialMeeting = {
    uuid: "",
    title: "",
    venue: "",
    virtualLink: "",
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
        virtualLink: data.virtualLink || "",
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

const normalizeUrl = (str) => {
    if (!str || typeof str !== "string") return "#"
    const s = str.trim()
    if (/^https?:\/\//i.test(s)) return s
    if (/^[a-z][a-z0-9+.-]*:\/\//i.test(s)) return s
    return "https://" + s
}
</script>