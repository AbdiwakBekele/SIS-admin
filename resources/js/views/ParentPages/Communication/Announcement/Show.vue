<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            {
                label: $trans('communication.communication'),
                path: 'Communication',
            },
            {
                label: $trans('communication.announcement.announcement'),
                path: 'ParentCommunicationAnnouncement',
            },
        ]"
    >
        <PageHeaderAction
            name="ParentCommunicationAnnouncement"
            :title="$trans('communication.announcement.announcement')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            :module-uuid="route.params.muuid"
            @setItem="setItem"
            @redirectTo="
                router.push({
                    name: 'ParentCommunicationAnnouncementList',
                    params: { uuid: announcement.uuid },
                })
            "
        >
            <BaseCard
                v-if="
                    announcement.uuid &&
                    actingAs(['student', 'guardian'], 'any')
                "
            >
                <template #title>
                    <div class="flex items-center justify-center gap-2">
                        {{ announcement.title }}
                        <!-- <span class="text-sm">{{ announcement.duration }}</span> -->
                    </div>
                </template>

                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView
                        :label="
                            $trans(
                                'communication.announcement.props.code_number'
                            )
                        "
                    >
                        {{ announcement.codeNumber }}
                    </BaseDataView>
                    <BaseDataView
                        :label="$trans('communication.announcement.props.type')"
                    >
                        {{ announcement.type.name }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('general.date')"
                        class="col-span-1 sm:col-span-2"
                    >
                        {{ announcement.publishedAt.formatted }}
                    </BaseDataView>
                </dl>

                <dl
                    class="mt-8 grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2"
                >
                    <BaseDataView class="col-span-1 sm:col-span-2" html>
                        {{ announcement.description }}
                    </BaseDataView>

                    <BaseDataView
                        class="col-span-1 sm:col-span-2"
                        v-if="announcement.media.length > 0"
                    >
                        <ListMedia
                            :media="announcement.media"
                            :url="`/app/communication/announcements/${announcement.uuid}/`"
                        />
                    </BaseDataView>
                </dl>
            </BaseCard>

            <DetailLayoutVertical
                v-if="
                    announcement.uuid &&
                    !actingAs(['student', 'guardian'], 'any')
                "
            >
                <template #detail>
                    <div class="space-y-2">
                        <BaseCard no-padding no-content-padding>
                            <template #title>
                                #{{ announcement.codeNumber }}
                            </template>

                            <template #action> </template>
                            <ListContainerVertical>
                                <ListItemView
                                    :label="
                                        $trans(
                                            'communication.announcement.props.title'
                                        )
                                    "
                                >
                                    {{ announcement.title }}
                                </ListItemView>

                                <ListItemView
                                    :label="
                                        $trans(
                                            'communication.announcement.type.type'
                                        )
                                    "
                                >
                                    {{ announcement.type.name }}
                                </ListItemView>

                                <ListItemView
                                    :label="$trans('employee.employee')"
                                >
                                    {{ announcement.employee?.name || "-" }}
                                    <TextMuted block>{{
                                        announcement.employee?.designation || ""
                                    }}</TextMuted>
                                </ListItemView>

                                <template v-if="!announcement.isPublic">
                                    <ListItemView
                                        :label="
                                            $trans(
                                                'communication.announcement.props.audience'
                                            )
                                        "
                                    >
                                        {{
                                            announcement.studentAudienceType
                                                .label
                                        }}
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
                                                'communication.announcement.props.audience'
                                            )
                                        "
                                    >
                                        <template
                                            v-if="
                                                announcement
                                                    .employeeAudienceType.value
                                            "
                                        >
                                            {{
                                                announcement
                                                    .employeeAudienceType.label
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
                                        {{ announcement.createdAt.formatted }}
                                    </ListItemView>

                                    <ListItemView
                                        :label="$trans('general.updated_at')"
                                    >
                                        {{ announcement.updatedAt.formatted }}
                                    </ListItemView>
                                </template>

                                <ListItemView
                                    :label="
                                        $trans(
                                            'communication.announcement.props.published_at'
                                        )
                                    "
                                >
                                    {{ announcement.publishedAt.formatted }}
                                </ListItemView>
                            </ListContainerVertical>
                        </BaseCard>
                    </div>
                </template>

                <BaseCard v-if="announcement.uuid">
                    <template #title>
                        <div class="flex justify-center gap-2">
                            {{ announcement.title }}
                            <BaseBadge
                                design="primary"
                                v-if="announcement.isPublic"
                            >
                                {{ $trans("general.public") }}</BaseBadge
                            >
                        </div>
                    </template>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <BaseDataView class="col-span-1 sm:col-span-2" html>
                            {{ announcement.description }}
                        </BaseDataView>

                        <BaseDataView
                            class="col-span-1 sm:col-span-2"
                            v-if="announcement.media.length > 0"
                        >
                            <ListMedia
                                :media="announcement.media"
                                :url="`/app/communication/announcements/${announcement.uuid}/`"
                            />
                        </BaseDataView>

                        <BaseDataView
                            class="col-span-1 sm:col-span-2"
                            v-if="perform('announcement:view-log')"
                        >
                            <ViewLog :view-logs="announcement.viewLogs" />
                        </BaseDataView>
                    </dl>
                    <template #footer>
                        <ShowButton>
                            <BaseButton
                                v-if="perform('announcement:edit')"
                                design="primary"
                                @click="
                                    router.push({
                                        name: 'CommunicationAnnouncementEdit',
                                        params: { uuid: announcement.uuid },
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
    name: "CommunicationAnnouncementShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform, actingAs } from "@core/helpers/action"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialAnnouncement = {}

const initUrl = "communication/announcement/"

const announcement = reactive({ ...initialAnnouncement })

const setItem = (data) => {
    Object.assign(announcement, data)
}

const filteredAudience = (type) => {
    return announcement.audiences.filter((audience) => {
        return audience.type === type
    })
}
</script>
