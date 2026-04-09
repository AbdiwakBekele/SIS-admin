<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            {
                label: $trans('link.link'),
                path: 'ParentLinks',
            },
        ]"
    >
        <PageHeaderAction
            name="ParentLinks"
            :title="$trans('link.link')"
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
                    name: 'Links',
                    params: { uuid: link.uuid },
                })
            "
        >
            <BaseCard
                v-if="
                    link.uuid &&
                    actingAs(['student', 'guardian'], 'any')
                "
            >
                <template #title>
                    <div class="flex items-center justify-center gap-2">
                        {{ link.title }}
                    </div>
                </template>

                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView
                        :label="
                            $trans(
                                'link.props.resource_type'
                            )
                        "
                    >
                        {{ link.resourceType?.resourceType || "-" }}
                    </BaseDataView>
                    <BaseDataView
                        :label="$trans('link.props.link')"
                    >
                        <a :href="link.link" target="_blank" class="text-blue-600 hover:underline">
                            {{ link.link }}
                        </a>
                    </BaseDataView>
                </dl>

                <dl
                    class="mt-8 grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2"
                >
                    <BaseDataView class="col-span-1 sm:col-span-2" :label="$trans('link.props.description')" html>
                        
                        {{ link.description }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('link.props.document')"
                        class="col-span-1 sm:col-span-2"
                        v-if="link.media && link.media.length > 0"
                    >
                        <ListMedia
                            :media="link.media"
                            :url="`/api/v1/app/links/${link.uuid}/`"
                        />
                    </BaseDataView>
                </dl>
            </BaseCard>

            <DetailLayoutVertical
                v-if="
                    link.uuid &&
                    !actingAs(['student', 'guardian'], 'any')
                "
            >
                <template #detail>
                    <div class="space-y-2">
                        <BaseCard no-padding no-content-padding>
                            <template #title>
                                {{ link.title }}
                            </template>

                            <template #action> </template>
                            <ListContainerVertical>
                                <ListItemView
                                    :label="
                                        $trans(
                                            'link.props.title'
                                        )
                                    "
                                >
                                    {{ link.title }}
                                </ListItemView>

                                <ListItemView
                                    :label="
                                        $trans(
                                            'link.props.resource_type'
                                        )
                                    "
                                >
                                    {{ link.resourceType?.resourceType || "-" }}
                                </ListItemView>

                                <ListItemView
                                    :label="
                                        $trans(
                                            'link.props.link'
                                        )
                                    "
                                >
                                    <a :href="link.link" target="_blank" class="text-blue-600 hover:underline">
                                        {{ link.link }}
                                    </a>
                                </ListItemView>

                                <template v-if="!link.isPublic">
                                    <ListItemView
                                        v-if="link.studentAudienceType?.value"
                                        :label="
                                            $trans(
                                                'link.props.student_audience_type'
                                            )
                                        "
                                    >
                                        {{
                                            link.studentAudienceType
                                                .label
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
                                        v-if="link.employeeAudienceType?.value"
                                        :label="
                                            $trans(
                                                'link.props.staff_audience_type'
                                            )
                                        "
                                    >
                                        {{
                                            link
                                                .employeeAudienceType.label
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
                                        v-if="link.guardianAudienceType?.value"
                                        :label="
                                            $trans(
                                                'link.props.parent_audience_type'
                                            )
                                        "
                                    >
                                        {{
                                            link
                                                .guardianAudienceType.label
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
                                        {{ link.createdAt.formatted }}
                                    </ListItemView>

                                    <ListItemView
                                        :label="$trans('general.updated_at')"
                                    >
                                        {{ link.updatedAt.formatted }}
                                    </ListItemView>
                                </template>
                            </ListContainerVertical>
                        </BaseCard>
                    </div>
                </template>

                <BaseCard v-if="link.uuid">
                    <template #title>
                        <div class="flex justify-center gap-2">
                            {{ link.title }}
                            <BaseBadge
                                design="primary"
                                v-if="link.isPublic"
                            >
                                {{ $trans("general.public") }}</BaseBadge
                            >
                        </div>
                    </template>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <BaseDataView class="col-span-1 sm:col-span-2" html>
                            {{ link.description }}
                        </BaseDataView>

                        <BaseDataView
                            class="col-span-1 sm:col-span-2"
                            v-if="link.media && link.media.length > 0"
                        >
                            <ListMedia
                                :media="link.media"
                                :url="`/api/v1/app/links/${link.uuid}/`"
                            />
                        </BaseDataView>
                    </dl>
                    <template #footer>
                        <ShowButton>
                            <BaseButton
                                v-if="perform('links:update')"
                                design="primary"
                                @click="
                                    router.push({
                                        name: 'LinksEdit',
                                        params: { uuid: link.uuid },
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
    name: "LinksShow",
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

const initialLink = {}

const initUrl = "links/"

const link = reactive({ ...initialLink })

const setItem = (data) => {
    Object.assign(link, data)
}

const filteredAudience = (type) => {
    return link.audiences.filter((audience) => {
        return audience.type === type
    })
}
</script>
