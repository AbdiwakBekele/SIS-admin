<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader
                :title="$trans('calendar.holiday.holiday')"
                :navs="[
                    { label: $trans('calendar.calendar'), path: 'Calendar' },
                ]"
            >
                <PageHeaderAction
                    url="calendar/holidays/"
                    name="ParentCalendarHoliday"
                    :title="$trans('calendar.holiday.holiday')"
                    :actions="userActions"
                    :dropdown-actions="dropdownActions"
                />
            </PageHeader>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable
                :header="holidays.headers"
                :meta="holidays.meta"
                module="calendar.holiday"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="holiday in holidays.data"
                    :key="holiday.uuid"
                    @double-click="
                        router.push({
                            name: 'ParentCalendarHolidayShow',
                            params: { uuid: holiday.uuid },
                        })
                    "
                >
                    <DataCell name="name">
                        {{ holiday.name }}
                    </DataCell>
                    <DataCell name="startDate">
                        {{ holiday.startDate.formatted }}
                    </DataCell>
                    <DataCell name="endDate">
                        {{ holiday.endDate.formatted }}
                    </DataCell>
                    <DataCell name="duration">
                        {{ holiday.duration }}
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ holiday.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                icon="fas fa-eye"
                                @click="
                                    router.push({
                                        name: 'ParentCalendarHolidayShow',
                                        params: { uuid: holiday.uuid },
                                    })
                                "
                                >{{ $trans("general.view") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('holiday:edit')"
                                icon="fas fa-edit"
                                @click="
                                    router.push({
                                        name: 'ParentCalendarHolidayEdit',
                                        params: { uuid: holiday.uuid },
                                    })
                                "
                                >{{ $trans("general.edit") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('holiday:create')"
                                icon="fas fa-copy"
                                @click="
                                    router.push({
                                        name: 'ParentCalendarHolidayDuplicate',
                                        params: { uuid: holiday.uuid },
                                    })
                                "
                                >{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('holiday:delete')"
                                icon="fas fa-trash"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: holiday.uuid,
                                    })
                                "
                                >{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem
                            >
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton
                        v-if="perform('holiday:create')"
                        @click="router.push({ name: 'ParentCalendarHolidayCreate' })"
                    >
                        {{
                            $trans("global.add", {
                                attribute: $trans("calendar.holiday.holiday"),
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
    name: "CalendarHolidayList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"

const router = useRouter()

const emitter = inject("emitter")

let userActions = []
if (perform("holiday:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("holiday:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "calendar/holiday/"

const holidays = reactive({})

const setItems = (data) => {
    Object.assign(holidays, data)
}
</script>
