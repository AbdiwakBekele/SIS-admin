<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('resource.resource'), path: 'Resource' },
            {
                label: $trans('resource.online_class.online_class'),
                path: 'ResourceOnlineClass',
            },
        ]"
    >
        <PageHeaderAction
            name="ResourceOnlineClass"
            :title="$trans('resource.online_class.online_class')"
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
                    name: 'ResourceOnlineClass',
                    params: { uuid: onlineClass.uuid },
                })
            "
        >
            <BaseCard v-if="onlineClass.uuid">
                <template #title>
                    {{ onlineClass.topic }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('academic.course.course')">
                        <div v-for="record in onlineClass.records">
                            {{
                                record.batch.course?.name +
                                " " +
                                record.batch.name
                            }}
                            <TextMuted v-if="record.subject">{{
                                record.subject.name
                            }}</TextMuted>
                        </div>
                    </BaseDataView>

                    <BaseDataView :label="$trans('employee.employee')">
                        {{ onlineClass.employee?.name || "-" }}
                        <TextMuted block>{{
                            onlineClass.employee?.designation || ""
                        }}</TextMuted>
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('resource.online_class.props.start_at')"
                    >
                        {{ onlineClass.startAt.formatted }}
                        <TextMuted block
                            >{{ onlineClass.duration }}
                            {{ $trans("list.durations.m") }}</TextMuted
                        >
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('resource.online_class.props.url')"
                        class="col-span-1 sm:col-span-2"
                    >
                        <span v-if="onlineClass.showUrl">
                            <a :href="onlineClass.meetingUrl" target="_blank"
                                >{{ onlineClass.meetingUrl }}
                                <span class="ml-1" v-if="onlineClass.password"
                                    >({{ onlineClass.password }})</span
                                ></a
                            >
                        </span>
                    </BaseDataView>

                    <BaseDataView
                        :label="
                            $trans('resource.online_class.props.description')
                        "
                        class="col-span-1 sm:col-span-2"
                        html
                    >
                        {{ onlineClass.description }}
                    </BaseDataView>

                    <BaseDataView class="col-span-1 sm:col-span-2">
                        <ListMedia
                            :media="onlineClass.media"
                            :url="`/app/resource/online-classes/${onlineClass.uuid}/`"
                        />
                    </BaseDataView>

                    <template v-if="!actingAs(['student', 'guardian'], 'any')">
                        <BaseDataView :label="$trans('general.created_at')">
                            {{ onlineClass.createdAt.formatted }}
                        </BaseDataView>

                        <BaseDataView :label="$trans('general.updated_at')">
                            {{ onlineClass.updatedAt.formatted }}
                        </BaseDataView>
                    </template>
                </dl>
                <template #footer>
                    <ShowButton>
                        <BaseButton
                            v-if="
                                perform('online-class:edit') &&
                                onlineClass.isEditable
                            "
                            design="primary"
                            @click="
                                router.push({
                                    name: 'ResourceOnlineClassEdit',
                                    params: { uuid: onlineClass.uuid },
                                })
                            "
                        >
                            {{ $trans("general.edit") }}
                        </BaseButton>
                    </ShowButton>
                </template>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "ResourceOnlineClassShow",
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

const initialOnlineClass = {}

const initUrl = "resource/onlineClass/"

const onlineClass = reactive({ ...initialOnlineClass })

const setItem = (data) => {
    Object.assign(onlineClass, data)
}
</script>
