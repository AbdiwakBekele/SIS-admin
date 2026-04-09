<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('calendar.event.event')" :navs="[

            ]">
                <PageHeaderAction url="calendar/events/" name="StudentCalendarEvent" :title="$trans('calendar.event.event')"
                    :actions="userActions" :dropdown-actions="dropdownActions" config-path="CalendarConfig" />
            </PageHeader>
        </template>

        <div>
            <!-- <div v-if="hasUnreadEvents" class="mb-4">
                <BaseCheckbox
                    v-model="markAllChecked"
                    :label="$trans('global.mark_all_as_read', { attribute: $trans('calendar.event.events') })"
                    @update:modelValue="handleMarkAllChange"
                />
            </div> -->
            <DataTable :header="events.headers" :meta="events.meta" module="calendar.event"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="event in events.data" :key="event.uuid" @double-click="
                    router.push({
                        name: 'StudentCalendarEventShow',
                        params: { uuid: event.uuid },
                    })
                    ">
                    <DataCell name="codeNumber">
                        {{ event.codeNumber }}
                    </DataCell>
                    <DataCell name="type">
                        {{ event.type?.name || '-' }}
                    </DataCell>
                    <DataCell name="title">
                        <div class="flex items-center gap-2">
                            <!-- <span v-if="(event.isRead ?? event.is_read) === false" class="w-2 h-2 bg-red-600 rounded-full"></span> -->
                            <div>
                                <span>{{ event.title }}</span>
                                <TextMuted block>{{ event.venue }}</TextMuted>
                            </div>
                        </div>
                    </DataCell>
                    <DataCell name="startDate">
                        {{ event.startDate?.formatted || '-' }}
                        <TextMuted block v-if="event.startTime && event.startTime.value">{{
                            event.startTime.formattedWithTimezone || event.startTime.formatted
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="endDate">
                        {{ event.endDate?.formatted || "-" }}
                        <TextMuted block v-if="event.endTime && event.endTime.value">{{
                            event.endTime.formattedWithTimezone || event.endTime.formatted
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="audience">
                        <BaseBadge v-if="event.forAlumni">
                            {{ $trans("calendar.event.props.for_alumni") }}
                        </BaseBadge>
                        <div v-for="audienceType in event.audienceTypes">
                            {{ audienceType }}
                        </div>
                    </DataCell>
                    <!-- <DataCell name="incharge">
                        {{ event.incharge?.employee?.name || "-" }}
                        <TextMuted block v-if="event.incharge?.employee">
                            {{ event.incharge?.employee?.designation }}
                        </TextMuted>
                    </DataCell> -->
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'StudentCalendarEventShow',
                                    params: { uuid: event.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('events:update')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'StudentCalendarEventEdit',
                                    params: { uuid: event.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('events:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'StudentCalendarEventDuplicate',
                                    params: { uuid: event.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('events:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: event.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('events:create')" @click="router.push({ name: 'StudentCalendarEventCreate' })">
                        {{
                            $trans("global.add", {
                                attribute: $trans("calendar.event.event"),
                            })
                        }}
                    </BaseButton>
                </template>
            </DataTable>
        </div>
    </ListItem>
</template>

<script>
export default {
    name: "CalendarEventList",
}
</script>

<script setup>
import { ref, reactive, inject, watch, computed } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import { useToast } from "vue-toastification"
import { perform, actingAs } from "@core/helpers/action"

const toast = useToast()

const router = useRouter()
const store = useStore()
const emitter = inject("emitter")

let userActions = []

if (perform("calendar:config")) {
    userActions.push("config")
}

if (perform("events:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("events:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "calendar/event/"

const events = reactive({})
const markAllChecked = ref(false)

const hasUnreadEvents = computed(() => {
    if (!events.data || !Array.isArray(events.data)) {
        return false
    }
    return events.data.some(event => {
        const isRead = event.isRead ?? event.is_read
        return isRead === false || isRead === undefined || isRead === null
    })
})

const handleMarkAllChange = async (checked) => {
    if (checked) {
        try {
            await store.dispatch("calendar/event/markAllAsRead")
            emitter.emit("listItems")
            await store.dispatch("badges/fetch")
            toast.success(store.getters["trans/trans"]("global.marked_all_as_read", {
                attribute: store.getters["trans/trans"]("calendar.event.events"),
            }))
            markAllChecked.value = false
        } catch (error) {
            // Error already handled in store action
        }
    }
}

const setItems = (data) => {
    Object.assign(events, data)
}
</script>
