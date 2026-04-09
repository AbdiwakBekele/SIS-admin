<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('gamification.attendance_ribbon.attendance_ribbon')" :navs="[
                { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            ]">
                <PageHeaderAction url="gamification/attendanceRibbon/" name="ParentAttendanceRibbon"
                    :title="$trans('gamification.attendance_ribbon.attendance_ribbon')" :actions="userActions"
                    :dropdown-actions="dropdownActions" :additional-dropdown-actions-query="{
                        details: true,
                    }">
                </PageHeaderAction>

            </PageHeader>
        </template>

        <!-- <template #filter>
                <FilterForm v-if="showFilter" @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
        </template> -->

        <ParentTransition appear :visibility="true">
            <DataTable :header="attendanceRibbons.headers" :meta="attendanceRibbons.meta" module="gamification.attendanceRibbon"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="attendanceRibbon in attendanceRibbons.data" :key="attendanceRibbon.uuid" @double-click="
                    router.push({
                        name: 'ParentAttendanceRibbonShow',
                        params: { uuid: attendanceRibbon.uuid },
                    })
                    ">

                    <!--  Icon -->
                    <DataCell name="icon">
                        <img :src="attendanceRibbon.icon || 'https://i.pinimg.com/736x/f8/fc/49/f8fc4970f3ef8cf8c996df8db471abeb.jpg'" alt=""
                            width="40px" srcset="">
                    </DataCell>

                    <!-- AttendanceRibbon Name -->
                    <DataCell name="name">
                        {{ attendanceRibbon.name }}
                    </DataCell>

                    <DataCell name="days">
                        {{ attendanceRibbon.days }}
                    </DataCell>

                    <DataCell name="createdAt">
                        {{ attendanceRibbon.createdAt.formatted }}
                    </DataCell>

                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentAttendanceRibbonShow',
                                    params: { uuid: attendanceRibbon.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton> </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "ParentAttendanceRibbonList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
// import FilterForm from "./Filter.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = [] // ["filter"] - removed since Filter.vue is missing

let dropdownActions = []
if (perform("attendance_ribbon:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "gamification/attendanceRibbon/"
const showFilter = ref(false)

const attendanceRibbons = reactive({})

const setItems = (data) => {
    Object.assign(attendanceRibbons, data)
}
</script>
