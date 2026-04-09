<template>
    <ListItem :init-url="initUrl" :additional-query="{}" @setItems="setItems">
        <template #header>
            <!-- BreadCrumbs | Title | RightSide Menus -->
            <PageHeader :title="$trans('communication.mygroups.mygroups')" :navs="[

            ]">
                <!-- Right Side Menus -->
                <PageHeaderAction url="communication/mygroups/" name="StudentMyGroups"
                    :title="$trans('communication.mygroups.groups')" :actions="userActions"
                    :dropdown-actions="dropdownActions" />
            </PageHeader>
        </template>

        <!-- <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template> -->

        <ParentTransition appear :visibility="true">
            <DataTable :header="mygroups.headers" :meta="mygroups.meta" module="communication.mygroups"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="mygroup in mygroups.data" :key="mygroup.uuid" @double-click="
                    router.push({
                        name: 'StudentMyGroupsShow',
                        params: { uuid: mygroup.uuid },
                    })
                    ">
                    <DataCell name="groupName">
                        {{ mygroup.groupName }}
                    </DataCell>
                    <DataCell name="groupType">
                        {{ mygroup.groupType }}
                    </DataCell>
                    <DataCell name="startDate">
                        {{ mygroup.startDate?.formatted || '-' }}
                    </DataCell>
                    <DataCell name="endDate">
                        {{ mygroup.endDate?.formatted || '-' }}
                    </DataCell>
                    <DataCell name="description">
                        <div v-html="mygroup.description"></div>
                    </DataCell>

                    <DataCell name="createdAt">
                        {{ mygroup.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">

                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'StudentMyGroupsShow',
                                    params: { uuid: mygroup.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>

                            <FloatingMenuItem v-if="perform('announcement:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'StudentMyGroupsEdit',
                                    params: { uuid: mygroup.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>

                            <FloatingMenuItem v-if="perform('announcement:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'StudentMyGroupsDuplicate',
                                    params: { uuid: mygroup.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>


                            <FloatingMenuItem v-if="perform('announcement:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: mygroup.uuid,
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
                            name: 'StudentMyGroupsCreate',
                        })
                        ">
                        {{
                            $trans("global.add", {
                                attribute: $trans(
                                    "communication.mygroups.mygroups"
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
    name: "MyGroupsList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
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

const initUrl = "communication/mygroups/"
const showFilter = ref(false)

const mygroups = reactive({})

const setItems = (data) => {
    Object.assign(mygroups, data)
}
</script>
