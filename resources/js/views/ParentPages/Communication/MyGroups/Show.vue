<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            {
                label: $trans('communication.communication'),
                path: 'Communication',
            },
            {
                label: $trans('communication.mygroups.mygroups'),
                path: 'ParentMyGroups',
            },
        ]"
    >
        <PageHeaderAction
            name="ParentMyGroups"
            :title="$trans('communication.mygroups.mygroups')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            :query="{ with: ['employee', 'groupAdmins', 'media'] }"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentMyGroupsList' })"
            @refresh="emitter.emit('listItems')"
        >
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-11 gap-6 p-4 bg-gray-50 rounded-lg shadow-sm">
                <!-- Left Section -->
                <div class="lg:col-span-3 space-y-4">
                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <div class="flex flex-col items-center mb-4">
                            <div
                                class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mb-3 border-4 border-white shadow-md">
                                <i class="fa-solid fa-users text-3xl text-indigo-500"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-800">{{ myGroup.groupName }}</h2>
                            <span class="text-sm text-gray-600 bg-blue-50 px-3 py-1 rounded-full mt-1">{{
                                getGroupTypeLabel(myGroup.groupType || myGroup.type) }}</span>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">GROUP INFORMATION</h3>
                            <div class="h-1 w-12 bg-blue-500 rounded mb-3"></div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ stripHtml(myGroup.description) || 'No description available' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-100">
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-700">Start Date</p>
                                <p class="text-sm font-medium text-gray-700">End Date</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">{{ myGroup.startDate?.formatted || 'Not set' }}</p>
                                <p class="text-sm text-gray-600">{{ myGroup.endDate?.formatted || 'Not set' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Group Admin Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">GROUP ADMIN</h3>
                        <div class="h-1 w-12 bg-blue-500 rounded mb-3"></div>

                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-green-100 to-teal-100 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-user-shield text-teal-500"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ myGroup.assignedAdmin || myGroup.employee?.name
                                    || 'Not assigned' }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <i class="fa-solid fa-phone text-gray-400 mr-2 w-5"></i>
                                <span class="text-gray-600">{{ myGroup.contactInfo || 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fa-solid fa-envelope text-gray-400 mr-2 w-5"></i>
                                <span class="text-gray-600">{{ myGroup.schoolEmail || 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section with Tabs -->
                <div class="lg:col-span-8 rounded-lg">
                    <!-- Tab Menus -->
                    <div class="border-b-2 border-gray-200">
                        <div class="w-full flex gap-1">
                            <div v-for="(tab, idx) in tabs" :key="`r1-${idx}`" @click="activeTab = tab"
                                :class="[
                                    'flex-1 min-w-max whitespace-nowrap cursor-pointer px-3 pt-2.5 pb-1.5 text-sm text-center transition-all duration-150 flex items-center justify-center gap-2',
                                    activeTab === tab
                                        ? 'bg-blue-200 shadow-inner border border-blue-400 border-b-0 rounded-t-lg text-blue-900 font-medium'
                                        : 'bg-white border border-gray-300 border-b-0 rounded-t-lg -mb-px z-20 shadow-lg relative -top-1 text-gray-700 hover:text-blue-600 hover:bg-gray-50'
                                ]">
                                <i :class="[
                                    getTabIcon(tab),
                                    activeTab === tab ? 'text-blue-700' : 'text-gray-500',
                                    'text-sm'
                                ]"></i>
                                <span>{{ tab }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div>
                        <!-- Highlights -->
                        <div v-if="activeTab === 'Highlights'"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                    <i class="fa-solid fa-star text-yellow-500 mr-2"></i>
                                    {{ $trans('communication.mygroups.highlights') }}
                                </h3>
                            </div>
                            <BaseDataView class="col-span-1 sm:col-span-2 bg-blue-50 p-4 rounded-lg">
                                <div v-if="stripHtml(myGroup.description)" class="text-gray-700">
                                    {{ stripHtml(myGroup.description) }}
                                </div>
                                <div v-else class="text-gray-500 italic">
                                    {{ $trans('dashboard.no_content') }}
                                </div>
                            </BaseDataView>
                        </div>

                        <!-- Announcements -->
                        <div v-if="activeTab === 'Announcements'"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
                                    <i class="fa-solid fa-bullhorn text-blue-500 mr-2"></i>
                                    {{ $trans('communication.mygroups.announcement.list') }}
                                </h3>
                            </div>

                            <div v-if="myGroup.groupAnnouncements?.length" class="space-y-2">
                                <div v-for="announcement in myGroup.groupAnnouncements" :key="announcement.uuid"
                                    class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors"
                                        @click="toggleAnnouncement(announcement.uuid)">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <i :class="[
                                                    expandedAnnouncements[announcement.uuid]
                                                        ? 'fa-solid fa-chevron-down'
                                                        : 'fa-solid fa-chevron-right',
                                                    'text-gray-400 text-sm transition-transform'
                                                ]"></i>
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ announcement.subject ||
                                                        $trans('communication.mygroups.announcement.no_subject') }}</p>
                                                    <span class="text-xs text-gray-500">{{ $trans('general.date') }}: {{
                                                        announcement.createdAt?.formatted }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-show="expandedAnnouncements[announcement.uuid]"
                                        class="px-4 pb-4 border-t border-gray-100 pt-4">
                                        <div v-if="announcement.description"
                                            class="text-gray-700 prose prose-sm max-w-none"
                                            v-html="announcement.description">
                                        </div>
                                        <div v-else class="text-gray-500 italic text-sm">
                                            {{ $trans('dashboard.no_content') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="text-center py-8 text-gray-500">
                                <i class="fa-solid fa-bullhorn text-4xl text-gray-300 mb-3"></i>
                                <p>{{ $trans('dashboard.nothing_to_show') }}</p>
                            </div>
                        </div>

                        <!-- Group Documents -->
                        <div v-if="activeTab === 'Group Documents'"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
                                    <i class="fa-solid fa-folder text-green-500 mr-2"></i>
                                    {{ $trans('communication.mygroups.documents') || 'Documents' }}
                                </h3>
                            </div>

                            <div v-if="myGroup.media && Array.isArray(myGroup.media) && myGroup.media.length > 0">
                                <ListMedia :media="myGroup.media"
                                    :url="`/api/v1/app/communication/mygroups/${myGroup.uuid}/`" grid="2">
                                </ListMedia>
                            </div>

                            <div v-else class="text-center py-8 text-gray-500">
                                <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                <p>{{ $trans('dashboard.nothing_to_show') || 'No documents found' }}</p>
                            </div>
                        </div>

                        <!-- Group Admins -->
                        <div v-if="activeTab === 'Group Admins'"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
                                    <i class="fa-solid fa-user-shield text-blue-500 mr-2"></i>
                                    {{ $trans('communication.mygroups.admin.list') }}
                                </h3>
                            </div>

                            <div v-if="allGroupAdmins?.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="admin in allGroupAdmins" :key="admin.key"
                                    class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                                <i class="fa-solid fa-user text-blue-500"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-800">{{ admin.name || 'Unnamed' }}</h4>
                                                <p class="text-xs text-gray-500">
                                                    {{ admin.isAssignedAdmin ? 'Primary Administrator' : 'Administrator' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2 mt-3">
                                        <BaseDataView v-if="admin.email" class="col-span-1 sm:col-span-2 text-sm">
                                            <span class="font-medium text-gray-700">Email:</span>
                                            <span class="ml-2 text-gray-600">{{ admin.email }}</span>
                                        </BaseDataView>
                                        <BaseDataView v-if="admin.address" class="col-span-1 sm:col-span-2 text-sm">
                                            <span class="font-medium text-gray-700">Address:</span>
                                            <span class="ml-2 text-gray-600">{{ admin.address }}</span>
                                        </BaseDataView>
                                        <BaseDataView v-if="!admin.name && !admin.email && !admin.address"
                                            class="col-span-1 sm:col-span-2 text-gray-500 italic text-sm">
                                            {{ $trans('dashboard.no_content') }}
                                        </BaseDataView>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="text-center py-8 text-gray-500">
                                <i class="fa-solid fa-user-shield text-4xl text-gray-300 mb-3"></i>
                                <p>{{ $trans('dashboard.nothing_to_show') }}</p>
                            </div>
                        </div>

                        <!-- Events & Calendar -->
                        <div v-if="activeTab === 'Events & Calendar'"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
                                    <i class="fa-solid fa-calendar-alt text-orange-500 mr-2"></i>
                                    {{ $trans('communication.mygroups.event.list') }}
                                </h3>
                            </div>

                            <div v-if="myGroup.groupEvents?.length" class="space-y-4">
                                <div v-for="event in myGroup.groupEvents" :key="event.uuid"
                                    class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <span class="text-xs text-gray-500">{{ $trans('general.by') }}: {{
                                                event.user?.name || 'Unknown'
                                                }}</span>
                                            <span class="text-xs text-gray-500 ml-2">{{ $trans('general.date') }}: {{
                                                event.createdAt?.formatted }}</span>
                                        </div>
                                    </div>

                                    <BaseDataView v-if="event.event" class="col-span-1 sm:col-span-2 mb-2">
                                        <span class="font-semibold text-gray-700">{{
                                            $trans('communication.mygroups.event.props.event')
                                            }}:</span>
                                        <span class="ml-2">{{ event.event }}</span>
                                    </BaseDataView>

                                    <BaseDataView v-if="event.description" class="col-span-1 sm:col-span-2"
                                        v-html="event.description">
                                    </BaseDataView>

                                    <BaseDataView v-if="!event.event && !event.description"
                                        class="col-span-1 sm:col-span-2 text-gray-500 italic">
                                        {{ $trans('dashboard.no_content') }}
                                    </BaseDataView>
                                </div>
                            </div>

                            <div v-else class="text-center py-8 text-gray-500">
                                <i class="fa-solid fa-calendar-alt text-4xl text-gray-300 mb-3"></i>
                                <p>{{ $trans('dashboard.nothing_to_show') }}</p>
                            </div>
                        </div>

                        <!-- Discussions -->
                        <div v-if="activeTab === 'Discussions'"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
                                    <i class="fa-solid fa-comments text-indigo-500 mr-2"></i>
                                    {{ $trans('communication.mygroups.discussion.list') }}
                                </h3>
                            </div>

                            <div v-if="myGroup.groupDiscussions?.length" class="space-y-2">
                                <div v-for="discussion in myGroup.groupDiscussions" :key="discussion.uuid"
                                    class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors"
                                        @click="toggleDiscussion(discussion.uuid)">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <i :class="[
                                                    expandedDiscussions[discussion.uuid]
                                                        ? 'fa-solid fa-chevron-down'
                                                        : 'fa-solid fa-chevron-right',
                                                    'text-gray-400 text-sm transition-transform'
                                                ]"></i>
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ discussion.subject || $trans('communication.mygroups.discussion.no_subject') }}</p>
                                                    <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                                        <span>{{ $trans('general.by') }}: {{ discussion.user?.name || 'Unknown' }}</span>
                                                        <span>â€¢</span>
                                                        <span>{{ $trans('general.date') }}: {{ discussion.createdAt?.formatted }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-show="expandedDiscussions[discussion.uuid]"
                                        class="px-4 pb-4 border-t border-gray-100 pt-4">
                                        <div v-if="discussion.description"
                                            class="text-gray-700 prose prose-sm max-w-none mb-4"
                                            v-html="discussion.description">
                                        </div>
                                        <div v-else class="text-gray-500 italic text-sm mb-4">
                                            {{ $trans('dashboard.no_content') }}
                                        </div>

                                        <!-- Comments section -->
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="text-sm font-semibold text-gray-700 flex items-center">
                                                    <i class="fa-solid fa-comment-dots text-gray-400 mr-2"></i>
                                                    {{ $trans('communication.mygroups.discussion.comments') }}
                                                    <span v-if="discussion.comments?.length" class="ml-2 text-xs text-gray-500">
                                                        ({{ discussion.comments.length }})
                                                    </span>
                                                </h4>
                                            </div>

                                            <div v-if="discussion.comments?.length" class="space-y-2">
                                                <div v-for="comment in discussion.comments" :key="comment.uuid"
                                                    class="border border-gray-200 rounded-lg overflow-hidden">
                                                    <div class="flex justify-between items-center p-3 cursor-pointer hover:bg-gray-50 transition-colors"
                                                        @click="toggleComment(discussion.uuid, comment.uuid)">
                                                        <div class="flex-1">
                                                            <div class="flex items-center gap-2">
                                                                <i :class="[
                                                                    expandedComments[`${discussion.uuid}-${comment.uuid}`]
                                                                        ? 'fa-solid fa-chevron-down'
                                                                        : 'fa-solid fa-chevron-right',
                                                                    'text-gray-400 text-xs transition-transform'
                                                                ]"></i>
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-700">
                                                                        {{ $trans('general.by') }}: {{ comment.user?.name || 'Unknown' }}
                                                                    </p>
                                                                    <span class="text-xs text-gray-500">
                                                                        {{ $trans('general.date') }}: {{ comment.createdAt?.formatted }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-show="expandedComments[`${discussion.uuid}-${comment.uuid}`]"
                                                        class="px-3 pb-3 border-t border-gray-100 pt-3">
                                                        <div v-if="comment.comment"
                                                            class="text-gray-700 prose prose-sm max-w-none text-sm"
                                                            v-html="comment.comment">
                                                        </div>
                                                        <div v-else class="text-gray-500 italic text-xs">
                                                            {{ $trans('dashboard.no_content') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-else class="text-center py-4 text-gray-500 text-sm">
                                                <i class="fa-solid fa-comment-slash text-xl text-gray-300 mb-2"></i>
                                                <p>{{ $trans('dashboard.nothing_to_show') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="!myGroup.groupDiscussions?.length" class="text-center py-8 text-gray-500">
                                <i class="fa-solid fa-comments text-4xl text-gray-300 mb-3"></i>
                                <p>{{ $trans('dashboard.nothing_to_show') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentMyGroupsShow",
    data() {
        return {
            openIndex: 0,
            activeTab: "Highlights",
            tabs: ["Highlights", "Announcements", "Discussions", "Events & Calendar", "Group Admins", "Group Documents"],
        };
    },
    methods: {
        getTabIcon(tabName) {
            const iconMap = {
                'Highlights': 'fa-solid fa-star',
                'Announcements': 'fa-solid fa-bullhorn',
                'Discussions': 'fa-solid fa-comments',
                'Events & Calendar': 'fa-solid fa-calendar-alt',
                'Group Admins': 'fa-solid fa-user-shield',
                'Group Documents': 'fa-solid fa-folder'
            };
            return iconMap[tabName] || 'fa-solid fa-circle';
        }
    }
}
</script>

<script setup>
import { reactive, ref, inject, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import ListMedia from "@core/components/Ui/ListMedia.vue"

const store = useStore()
const route = useRoute()
const router = useRouter()

const emitter = inject("emitter")
const $trans = inject("$trans")

const initialGroupInfo = {
    groupAnnouncements: [],
    groupDiscussions: [],
    groupEvents: [],
    groupAdmins: [],
    media: [],
}

const initUrl = "communication/mygroups/"

const refreshItem = ref(false)
const myGroup = reactive({ ...initialGroupInfo })
const expandedAnnouncements = reactive({})
const expandedDiscussions = reactive({})
const expandedComments = reactive({})

const allGroupAdmins = computed(() => {
    const admins = []
    
    // Add assigned admin from creation if it exists
    if (myGroup.assignedAdmin && myGroup.groupAdmin) {
        admins.push({
            key: `assigned-${myGroup.groupAdmin}`,
            uuid: myGroup.groupAdmin,
            name: myGroup.assignedAdmin,
            email: myGroup.employee?.email || myGroup.schoolEmail,
            address: null,
            isAssignedAdmin: true,
        })
    }
    
    // Add other group admins
    if (myGroup.groupAdmins?.length) {
        myGroup.groupAdmins.forEach(admin => {
            admins.push({
                key: `admin-${admin.uuid}`,
                uuid: admin.uuid,
                name: admin.name,
                email: admin.email,
                address: admin.address,
                isAssignedAdmin: false,
            })
        })
    }
    
    return admins
})

const groupTypeLabels = {
    sport: "Sports",
    club: "Club",
    studentLeadership: "Student Leadership",
    otherActivities: "Other Activities",
}

const getGroupTypeLabel = (type) => {
    return groupTypeLabels[type] || type || "-"
}

function stripHtml(html) {
    return html?.replace(/<\/?[^>]+(>|$)/g, '').trim();
}

const setItem = (data) => {
    Object.assign(myGroup, {
        ...data,
        groupAnnouncements: data.groupAnnouncements || [],
        groupDiscussions: data.groupDiscussions || [],
        groupEvents: data.groupEvents || [],
        groupAdmins: data.groupAdmins || [],
        media: data.media || [],
    })
}

const toggleAnnouncement = (uuid) => {
    expandedAnnouncements[uuid] = !expandedAnnouncements[uuid]
}

const toggleDiscussion = (uuid) => {
    expandedDiscussions[uuid] = !expandedDiscussions[uuid]
}

const toggleComment = (discussionUuid, commentUuid) => {
    const key = `${discussionUuid}-${commentUuid}`
    expandedComments[key] = !expandedComments[key]
}
</script>
