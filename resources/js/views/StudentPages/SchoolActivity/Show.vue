<template>
    <PageHeader v-if="activity.uuid" :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })" :navs="[
        { label: activity.title, path: { name: 'StudentSchoolActivityShow', params: { uuid: activity.uuid } } },
    ]">
        <PageHeaderAction :url="`school-activity/${activity.uuid}/`" name="StudentSchoolActivity"
            :title="$trans('activity.activities')" :actions="userActions" :dropdown-actions="['print', 'pdf', 'excel']">
            <template #dropdown>
                <DropdownItem icon="fas fa-pencil" v-if="perform('schoolActivity:manage')"
                    @click="router.push({ name: 'SchoolActivityEdit', params: { uuid: activity.uuid } })">
                    {{ $trans('global.edit', { attribute: $trans('activity.activities') }) }}
                </DropdownItem>
                <DropdownItem icon="fas fa-trash" v-if="perform('schoolActivity:manage')"
                    @click="emitter.emit('showDeleteItem', { uuid: activity.uuid })">
                    {{ $trans('global.delete', { attribute: $trans('activity.activities') }) }}
                </DropdownItem>
            </template>
        </PageHeaderAction>
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentSchoolActivityList' })" :refresh="refreshItem"
            @refreshed="refreshItem = false">
            <BaseCard v-if="activity.uuid">
                <template #title>
                    {{ activity.title }}
                    <BaseBadge v-if="activity.type" :design="activity.type ? 'custom' : ''" :color="'blue'">
                        {{ activity.type }}
                    </BaseBadge>
                </template>

                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-4">
                    <BaseDataView :label="$trans('activity.props.type')">
                        {{ activity.type || '-' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('activity.props.start_date')">
                        {{ activity.startDate.formatted }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('activity.props.end_date')">
                        {{ activity.endDate.formatted || '-' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('activity.props.is_ongoing')">
                        {{ activity.isOngoing ? 'Yes' : 'No' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('activity.props.location')">
                        {{ activity.location || '-' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('activity.props.organizer')">
                        {{ activity.organizer || '-' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('activity.props.status')">
                        {{ activity.status || '-' }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('activity.props.student_members')">
                        {{ activity.student_members?.join(', ') || '-' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('activity.props.description')" class="col-span-1 sm:col-span-4">
                        {{ activity.description || '-' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.created_at')">
                        {{ activity.createdAt.formatted }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ activity.updatedAt.formatted }}
                    </BaseDataView>
                </dl>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "SchoolActivityShow",
}
</script>

<script setup>
import { reactive, ref, inject } from "vue"
import { useRoute, useRouter } from "vue-router"
import { perform } from "@core/helpers/action"

const route = useRoute()
const router = useRouter()
const emitter = inject("emitter")

let userActions = ["list"]

const initialActivity = {}
const initUrl = "schoolActivity/"
const activity = reactive({ ...initialActivity })
const refreshItem = ref(false)

const setItem = (data) => {
    Object.assign(activity, data)
}
</script>
