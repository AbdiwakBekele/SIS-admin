<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('calendar.event.event')" :navs="[

            ]">
                <PageHeaderAction url="calendar/events/" name="ParentCalendarEvent" :title="$trans('calendar.event.event')"
                    :actions="userActions" :dropdown-actions="dropdownActions" config-path="CalendarConfig" />
            </PageHeader>
        </template>

        <!-- <template #filter>
                <FilterForm v-if="showFilter" @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
        </template> -->

        <ParentTransition appear :visibility="true">
            <div>
                <!-- <div v-if="hasUnreadEvents" class="mb-4">
                    <BaseCheckbox
                        v-model="markAllChecked"
                        :label="$trans('global.mark_all_as_read', { attribute: $trans('calendar.event.events') })"
                        @update:modelValue="handleMarkAllChange"
                    />
                </div> -->
                <DataTable :header="events.headers" :meta="events.meta" 
                    @refresh="emitter.emit('listItems')">
                <DataRow v-for="event in events.data" :key="event.uuid" @double-click="
                    router.push({
                        name: 'ParentCalendarEventShow',
                        params: { uuid: event.uuid },
                    })
                    ">
                    <DataCell name="codeNumber">
                        {{ event.codeNumber }}
                    </DataCell>
                    <DataCell name="type">
                        {{ event.type.name }}
                    </DataCell>
                    <DataCell name="title">
                        <div class="flex items-center gap-2 flex-wrap">
                            <!-- <span v-if="(event.isRead ?? event.is_read) === false" class="w-2 h-2 bg-red-600 rounded-full"></span> -->
                            <span>{{ event.title }}</span>
                            <template v-for="child in (getMatchingChildren(event) || [])" :key="child.uuid || child.id || Math.random()">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-blue-400 text-white rounded-full"
                                >
                                    {{ child.name || child.firstName || 'Child' }}
                                </span>
                            </template>
                        </div>
                        <TextMuted block v-if="event.venue">{{ event.venue }}</TextMuted>
                    </DataCell>
                    <DataCell name="startDate">
                        {{ event.startDate.formatted }}
                        <TextMuted block v-if="event.startTime.value">{{
                            event.startTime.formattedWithTimezone || event.startTime.formatted
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="endDate">
                        {{ event.endDate?.formatted || "-" }}
                        <TextMuted block v-if="event.endTime.value">{{
                            event.endTime.formattedWithTimezone || event.endTime.formatted
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="audience">
                        <BaseBadge v-if="event.forAlumni">
                            {{ $trans("calendar.event.props.for_alumni") }}
                        </BaseBadge>
                        <div v-for="(audienceType, index) in event.audienceTypes" :key="index">
                            {{ audienceType }}
                        </div>
                    </DataCell>

                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentCalendarEventShow',
                                    params: { uuid: event.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton> </template>
            </DataTable>
            </div>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "CalendarEventList",
}
</script>

<script setup>
import { ref, reactive, inject, computed } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import { useToast } from "vue-toastification"
import { perform } from "@core/helpers/action"
// import FilterForm from "./Filter.vue"

const toast = useToast()

const router = useRouter()
const store = useStore()
const emitter = inject("emitter")

let userActions = [] // ["filter"] - removed since Filter.vue is missing

if (perform("calendar:config")) {
    userActions.push("config")
}

let dropdownActions = []
if (perform("event:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "calendar/event/"
// const showFilter = ref(false) // Removed - filter functionality removed

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

const getMatchingChildren = (event) => {
    if (!event) return [];
    const children = event.matchingChildren || event.matching_children || [];
    if (!Array.isArray(children)) return [];
    return children.filter(child => child && (child.uuid || child.id));
};

const setItems = (data) => {
    if (data?.data) {
        data.data.forEach((event) => {
            const children = event.matching_children || event.matchingChildren;
            if (children && Array.isArray(children) && children.length > 0) {
                event.matchingChildren = [...children];
            }
        });
    }
    
    Object.assign(events, data)
}
</script>
