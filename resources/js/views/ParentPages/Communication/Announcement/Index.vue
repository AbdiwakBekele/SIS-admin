<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('communication.announcement.announcement')" :navs="[]">
                <PageHeaderAction url="communication/announcements/" name="ParentCommunicationAnnouncement"
                    :title="$trans('communication.announcement.announcements')" :actions="userActions" :dropdown-actions="dropdownActions"
                    config-path="CommunicationConfig" />
            </PageHeader>
        </template>

        <!-- <template #filter>
                <FilterForm v-if="showFilter" @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
        </template> -->

        <ParentTransition appear :visibility="true">
            <div>
                <!-- <div v-if="hasUnreadAnnouncements" class="mb-4">
                    <BaseCheckbox
                        v-model="markAllChecked"
                        :label="$trans('global.mark_all_as_read', { attribute: $trans('communication.announcement.announcements') })"
                        @update:modelValue="handleMarkAllChange"
                    />
                </div> -->
                <DataTable :header="announcements.headers" :meta="announcements.meta" module="communication.announcement"
                    @refresh="emitter.emit('listItems')">
                <DataRow v-for="announcement in announcements.data" :key="announcement.uuid" @double-click="
                    router.push({
                        name: 'ParentCommunicationAnnouncementShow',
                        params: { uuid: announcement.uuid },
                    })
                    ">
                    <DataCell name="title">
                        <div class="flex items-center gap-2 flex-wrap">
                            <!-- <span v-if="(announcement.isRead ?? announcement.is_read) === false" class="w-2 h-2 bg-red-600 rounded-full"></span> -->
                            <span>{{ announcement.titleExcerpt }}</span>
                            <template v-for="child in (getMatchingChildren(announcement) || [])" :key="child.uuid || child.id || Math.random()">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-blue-400 text-white rounded-full"
                                >
                                    {{ child.name || child.firstName || 'Child' }}
                                </span>
                            </template>
                        </div>
                    </DataCell>
                    <DataCell name="type">
                        {{ announcement.type?.name || "-" }}
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ announcement.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentCommunicationAnnouncementShow',
                                    params: { uuid: announcement.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('announcements:update')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'ParentCommunicationAnnouncementEdit',
                                    params: { uuid: announcement.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('announcements:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'ParentCommunicationAnnouncementDuplicate',
                                    params: { uuid: announcement.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('announcements:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: announcement.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('announcements:create')" @click="
                        router.push({
                            name: 'ParentCommunicationAnnouncementCreate',
                        })
                        ">
                        {{
                            $trans("global.add", {
                                attribute: $trans(
                                    "communication.announcement.announcement"
                                ),
                            })
                        }}
                    </BaseButton>
                </template>
            </DataTable>
            </div>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "CommunicationAnnouncementList",
}
</script>

<script setup>
import { ref, reactive, inject, watch, computed } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import { useToast } from "vue-toastification"
import { perform, actingAs } from "@core/helpers/action"
// import FilterForm from "./Filter.vue"

const toast = useToast()

const router = useRouter()
const store = useStore()
const emitter = inject("emitter")

let userActions = [] // ["filter"] - removed since Filter.vue is missing

if (perform("communication:config")) {
    userActions.push("config")
}

if (perform("announcement:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("announcement:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "communication/announcement/"
const showFilter = ref(false)

const announcements = reactive({})
const markAllChecked = ref(false)

const hasUnreadAnnouncements = computed(() => {
    if (!announcements.data || !Array.isArray(announcements.data)) {
        return false
    }
    return announcements.data.some(announcement => {
        const isRead = announcement.isRead ?? announcement.is_read
        return isRead === false || isRead === undefined || isRead === null
    })
})

const handleMarkAllChange = async (checked) => {
    if (checked) {
        try {
            await store.dispatch("communication/announcement/markAllAsRead")
            emitter.emit("listItems")
            await store.dispatch("badges/fetch")
            toast.success(store.getters["trans/trans"]("global.marked_all_as_read", {
                attribute: store.getters["trans/trans"]("communication.announcement.announcements"),
            }))
            markAllChecked.value = false
        } catch (error) {
            // Error already handled in store action
        }
    }
}

const getMatchingChildren = (announcement) => {
    if (!announcement) return [];
    const children = announcement.matchingChildren || announcement.matching_children || [];
    if (!Array.isArray(children)) return [];
    return children.filter(child => child && (child.uuid || child.id));
};

const setItems = (data) => {
    if (data?.data) {
        data.data.forEach((announcement) => {
            const children = announcement.matching_children || announcement.matchingChildren;
            if (children && Array.isArray(children) && children.length > 0) {
                announcement.matchingChildren = [...children];
            }
        });
    }
    
    Object.assign(announcements, data)
}
</script>
