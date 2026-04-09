<template>
    <ListItem :init-url="initUrl" :additional-query="{}" @setItems="setItems">
        <template #header>
            <!-- BreadCrumbs | Title | RightSide Menus -->
            <PageHeader :title="`Sport & Activities`" :navs="[
                { label: `School Groups`, path: '' },

            ]">
                <!-- Right Side Menus -->
                <PageHeaderAction url="communication/mygroups/" name="SchoolActivity" :title="`Sport & Activity`"
                    :actions="userActions" :dropdown-actions="dropdownActions" />
            </PageHeader>
        </template>

        <!-- <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template> -->

        <ParentTransition appear :visibility="true">
            <DataTable :header="activities.headers" :meta="activities.meta" 
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="activity in activities.data" :key="activity.uuid" @double-click="
                    router.push({
                        name: 'ParentSchoolActivityShow',
                        params: { uuid: activity.uuid },
                    })
                    ">
                    <DataCell name="title">
                        {{ activity.title }}
                    </DataCell>

                    <DataCell name="type">
                        {{ activity.type }}
                    </DataCell>
                    <DataCell name="startDate">
                        {{ activity.startDate.formatted }}
                    </DataCell>
                    <DataCell name="endDate">
                        {{ activity.endDate.formatted }}
                    </DataCell>
                    <DataCell name="action">

                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentSchoolActivityShow',
                                    params: { uuid: activity.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>

                            <FloatingMenuItem v-if="perform('announcement:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'ParentSchoolActivityEdit',
                                    params: { uuid: activity.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>

                            <FloatingMenuItem v-if="perform('announcement:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'ParentSchoolActivityDuplicate',
                                    params: { uuid: activity.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>


                            <FloatingMenuItem v-if="perform('announcement:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: activity.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('announcement:create')" @click="
                        router.push({
                            name: 'ParentSchoolActivityCreate',
                        })
                        ">
                        {{
                            $trans("global.add", {
                                attribute: $trans(
                                    "activity.sports_and_activities"
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
    name: "SportAndActivityList",
}
</script>

<script setup>
import { ref, reactive, inject, watch } from "vue"
import { useRouter } from "vue-router"
import { perform, actingAs } from "@core/helpers/action"
// import FilterForm from "./Filter.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = [] // ["filter"] - removed since Filter.vue is missing

// ToDo: check the following permissions
if (perform("mygroups:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("announcement:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "schoolActivity/"
const showFilter = ref(false)

const activities = reactive({})


const setItems = (data) => {
    Object.assign(activities, data)
}
</script>
