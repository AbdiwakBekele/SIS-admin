<template>
    <ListItem :init-url="initUrl" :pre-requisites="true" @setPreRequisites="setPreRequisites" :additional-query="{}"
        @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('resource.online_class.online_class')" :navs="[
                { label: $trans('resource.resource'), path: 'Resource' },
            ]">
                <PageHeaderAction url="resource/online-classes/" name="ResourceOnlineClass"
                    :title="$trans('resource.online_class.online_class')" :actions="userActions"
                    :dropdown-actions="dropdownActions" config-path="ResourceConfig"
                    @toggleFilter="showFilter = !showFilter" />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" :pre-requisites="preRequisites"
                    @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true" v-if="actingAs(['student', 'guardian'], 'any')">
            <CardList :header="onlineClasss.headers" :meta="onlineClasss.meta">
                <div class="grid grid-cols-1 gap-4 px-4 pt-4 md:grid-cols-2 lg:grid-cols-3">
                    <template v-for="onlineClass in onlineClasss.data" :key="onlineClass.uuid">
                        <div class="cursor-pointer" @click="
                            router.push({
                                name: 'ResourceOnlineClassShow',
                                params: { uuid: onlineClass.uuid },
                            })
                            ">
                            <CardView no-padding>
                                <div class="px-2 py-2 text-gray-800 dark:text-gray-400">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium">{{
                                            onlineClass.topic
                                        }}</span>
                                    </div>
                                    <div class="mt-2 flex justify-between">
                                        <span class="text-sm">{{
                                            onlineClass.employee?.name || "-"
                                        }}</span>
                                        <span class="text-xs" v-if="onlineClass.employee">{{
                                            onlineClass.employee
                                                ?.designation
                                        }}</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <p class="text-sm">
                                            {{ onlineClass.platform.label }}
                                        </p>

                                        <BaseBadge :design="onlineClass.status.color">{{
                                            onlineClass.status.label
                                        }}</BaseBadge>
                                    </div>

                                    <div class="text-xs" v-if="onlineClass.showUrl">
                                        <a :href="onlineClass.meetingUrl" target="_blank">{{ onlineClass.meetingUrl }}
                                            <span class="ml-1" v-if="onlineClass.password">({{
                                                onlineClass.password
                                            }})</span></a>
                                    </div>

                                    <p class="mt-2 text-xs">
                                        {{ onlineClass.startAt.formatted }}
                                        <TextMuted>({{ onlineClass.duration }}
                                            {{
                                                $trans("list.durations.m")
                                            }})
                                        </TextMuted>
                                    </p>
                                </div>
                            </CardView>
                        </div>
                    </template>
                </div>
                <div>
                    <Pagination card-view :meta="onlineClasss.meta" @refresh="emitter.emit('listItems')"></Pagination>
                </div>
                <template #content>
                    <div class="text-xl font-semibold">
                        {{ $trans("dashboard.nothing_to_show") }}
                    </div>
                </template>
            </CardList>
        </ParentTransition>

        <ParentTransition appear :visibility="true" v-else>
            <DataTable :header="onlineClasss.headers" :meta="onlineClasss.meta" module="resource.online_classes"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="onlineClass in onlineClasss.data" :key="onlineClass.uuid" @double-click="
                    router.push({
                        name: 'ResourceOnlineClassShow',
                        params: { uuid: onlineClass.uuid },
                    })
                    ">
                    <DataCell name="topic">
                        {{ onlineClass.topicExcerpt }}
                        <BaseBadge :design="onlineClass.status.color">{{
                            onlineClass.status.label
                        }}</BaseBadge>
                    </DataCell>
                    <DataCell name="records">
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
                    </DataCell>
                    <DataCell name="employee">
                        {{ onlineClass.employee?.name || "-" }}
                        <TextMuted block>{{
                            onlineClass.employee?.codeNumber
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="startAt">
                        {{ onlineClass.startAt.formatted }} ({{
                            onlineClass.duration
                        }}
                        {{ $trans("list.durations.m") }})
                        <TextMuted block>{{
                            onlineClass.platform.label
                        }}</TextMuted>
                        <TextMuted block v-if="onlineClass.showUrl">
                            <a :href="onlineClass.meetingUrl" target="_blank">{{
                                onlineClass.meetingUrl
                            }}</a>
                            <span class="ml-1" v-if="onlineClass.password">({{ onlineClass.password }})</span>
                        </TextMuted>
                    </DataCell>
                    <!-- <DataCell name="createdAt">
                        {{ onlineClass.createdAt.formatted }}
                    </DataCell> -->
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ResourceOnlineClassShow',
                                    params: { uuid: onlineClass.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="
                                perform('online-class:edit') &&
                                onlineClass.isEditable
                            " icon="fas fa-edit" @click="
                                    router.push({
                                        name: 'ResourceOnlineClassEdit',
                                        params: { uuid: onlineClass.uuid },
                                    })
                                    ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('online-class:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'ResourceOnlineClassDuplicate',
                                    params: { uuid: onlineClass.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="
                                perform('online-class:delete') &&
                                onlineClass.isDeletable
                            " icon="fas fa-trash" @click="
                                    emitter.emit('deleteItem', {
                                        uuid: onlineClass.uuid,
                                    })
                                    ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('online-class:create')" @click="
                        router.push({
                            name: 'ResourceOnlineClassCreate',
                        })
                        ">
                        {{
                            $trans("global.add", {
                                attribute: $trans(
                                    "resource.online_class.online_class"
                                ),
                            })
                        }}
                    </BaseButton>
                </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "ResourceOnlineClassList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform, actingAs } from "@core/helpers/action"
import FilterForm from "./Filter.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]

if (perform("resource:config")) {
    userActions.push("config")
}

if (perform("online-class:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("online-class:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "resource/onlineClass/"
const showFilter = ref(false)

const preRequisites = reactive({
    subjects: [],
})

const onlineClasss = reactive({})

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const setItems = (data) => {
    Object.assign(onlineClasss, data)
}
</script>
