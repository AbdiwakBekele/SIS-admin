<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('meeting.meeting.meetings')" :navs="[
            ]">
                <PageHeaderAction url="meetings" name="ParentMeeting" :title="$trans('meeting.meeting.meetings')"
                    :actions="userActions" :dropdown-actions="dropdownActions"                     config-path="MeetingList" />
            </PageHeader>
        </template>

        <!-- <template #filter>
                <FilterForm v-if="showFilter" @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
        </template> -->

        <ParentTransition appear :visibility="true">
            <div>
                <!-- <div v-if="hasUnreadMeetings" class="mb-4">
                    <BaseCheckbox
                        v-model="markAllChecked"
                        :label="$trans('global.mark_all_as_read', { attribute: $trans('meeting.meeting.meetings') })"
                        @update:modelValue="handleMarkAllChange"
                    />
                </div> -->
                <DataTable :header="meetings.headers" :meta="meetings.meta" module="meeting.meeting"
                    @refresh="emitter.emit('listItems')">
                <DataRow v-for="meeting in meetings.data" :key="meeting.uuid" @double-click="
                    router.push({
                        name: 'ParentMeetingShow',
                        params: { uuid: meeting.uuid },
                    })
                    ">
                    <DataCell name="title">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span
                                v-if="isUnreadUpcomingMeeting(meeting)"
                                class="w-2 h-2 bg-red-600 rounded-full"
                            ></span>
                            <span>{{ meeting.title }}</span>
                            <template v-for="child in (getMatchingChildren(meeting) || [])" :key="child.uuid || child.id || Math.random()">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-blue-400 text-white rounded-full"
                                >
                                    {{ child.name || child.firstName || 'Child' }}
                                </span>
                            </template>
                        </div>
                        <TextMuted block v-if="meeting.venue">{{ meeting.venue }}</TextMuted>
                    </DataCell>
                    <DataCell name="startDate">
                        {{ meeting.startDate?.formatted }}
                        <TextMuted block v-if="meeting.startTime">{{ meeting.startTime.formattedWithTimezone || meeting.startTime.formatted }}</TextMuted>
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ meeting.createdAt?.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentMeetingShow',
                                    params: { uuid: meeting.uuid },
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
    name: "MeetingList",
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
if (perform("meeting:config")) {
    userActions.push("config")
}

let dropdownActions = []
if (perform("meeting:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}


const initUrl = "meetings/"
const showFilter = ref(false)
const meetings = reactive({})
const markAllChecked = ref(false)

const isUnreadUpcomingMeeting = (meeting) => {
    if (!meeting) {
        return false
    }

    const isRead = meeting.isRead ?? meeting.is_read
    const isUnread = isRead === false || isRead === undefined || isRead === null
    if (!isUnread) {
        return false
    }

    const startDateObj = meeting.startDate || meeting.start_date
    const startValue = startDateObj?.value
    if (!startValue) {
        return false
    }

    const today = new Date()
    const todayIso = today.toISOString().slice(0, 10)
    const tomorrow = new Date(today)
    tomorrow.setDate(today.getDate() + 1)
    const tomorrowIso = tomorrow.toISOString().slice(0, 10)

    return startValue === todayIso || startValue === tomorrowIso
}

const hasUnreadMeetings = computed(() => {
    if (!meetings.data || !Array.isArray(meetings.data)) {
        return false
    }
    return meetings.data.some(meeting => {
        const isRead = meeting.isRead ?? meeting.is_read
        return isRead === false || isRead === undefined || isRead === null
    })
})

const handleMarkAllChange = async (checked) => {
    if (checked) {
        try {
            await store.dispatch("meetings/markAllAsRead")
            emitter.emit("listItems")
            await store.dispatch("badges/fetch")
            toast.success(store.getters["trans/trans"]("global.marked_all_as_read", {
                attribute: store.getters["trans/trans"]("meeting.meeting.meetings"),
            }))
            markAllChecked.value = false
        } catch (error) {
            // Error already handled in store action
        }
    }
}

const getMatchingChildren = (meeting) => {
    if (!meeting) return [];
    const children = meeting.matchingChildren || meeting.matching_children || [];
    if (!Array.isArray(children)) return [];
    return children.filter(child => child && (child.uuid || child.id));
};

const setItems = (data) => {
    // Preserve matchingChildren explicitly before Object.assign
    if (data?.data) {
        data.data.forEach((meeting) => {
            const children = meeting.matching_children || meeting.matchingChildren;
            if (children && Array.isArray(children) && children.length > 0) {
                meeting.matchingChildren = [...children];
            }
        });
    }
    
    Object.assign(meetings, data)
}
</script>