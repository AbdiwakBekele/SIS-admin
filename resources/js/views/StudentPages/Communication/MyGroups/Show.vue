<template>
    <PageHeader :title="$trans('communication.mygroups.mygroups')" :navs="[

    ]">
        <PageHeaderAction name="StudentMyGroups" :title="$trans('communication.mygroups.mygroups')"
            :actions="['list']">

            <!-- Edit -->
            <BaseButton design="white" @click="
                router.push({
                    name: 'StudentMyGroupsEdit',
                    params: { uuid: route.params.uuid },
                })
                ">
                {{ $trans("general.edit") }}
            </BaseButton>

        </PageHeaderAction>
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentMyGroups' })" @refresh="emitter.emit('listItems')">

            <!-- Student Details Grid -->
            <div class="grid grid-cols-11 gap-2 p-3 shadow-md">
                <!-- Left Section -->
                <div class="col-span-3 ">

                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center">
                        <div class="grid grid-cols-2 gap-1">
                            <img src="data:image/jpeg;base64,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"
                                alt="Student" class="w-20 h-20 rounded-lg mx-auto">
                            <div class="text-left">
                                <p class="mt-2 text-base font-bold ">{{ myGroup.groupName }}</p>
                                <p class="text-gray-600 text-sm">Sport</p>
                            </div>
                        </div>

                        <p class="text-sm text-left mx-4 mt-4"> <strong>GROUP INFORMATION </strong> </p>
                        <hr class="mx-4">
                        <p class="text-justify text-sm p-3">{{ stripHtml(myGroup.description) }}
                        </p>

                        <hr>
                        <hr class="mx-4">
                        <div class="grid grid-cols-2 pl-4" style="text-align: left;">
                            <div>
                                <p class="text-sm my-2 mx-1"><strong>Start Date</strong></p>
                                <p class="text-sm my-2 mx-1"><strong>End Date: </strong></p>
                            </div>

                            <div>
                                <p class="text-sm my-2 mx-1">{{ myGroup.startDate?.formatted }} </p>
                                <p class="text-sm my-2 mx-1"> {{ myGroup.endDate?.formatted }} </p>
                            </div>
                        </div>
                    </div>

                    <!-- Group Admin-->
                    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
                        <p class="text-sm text-left mx-4"> <strong> GROUP ADMIN </strong> </p>
                        <hr class="mx-4">

                        <div class="grid grid-cols-2 pl-4" style="text-align: left;">
                            <div>
                                <p class="text-sm my-2 mx-1"><strong>Admin Name</strong></p>
                                <p class="text-sm my-2 mx-1"><strong>Phone No: </strong></p>
                                <p class="text-sm my-2 mx-1"><strong>School Email</strong></p>
                            </div>

                            <div>
                                <p class="text-sm my-2 mx-1"> {{ myGroup.assignedAdmin }} </p>
                                <p class="text-sm my-2 mx-1"> {{ myGroup.contactInfo }} </p>
                                <p class="text-sm my-2 mx-1"> {{ myGroup.schoolEmail }} </p>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Right Section with Tabs -->
                <div class="col-span-8 rounded-lg">

                    <!-- Tab Menus -->
                    <div class="bg-gray-100 border-b-2 rounded-lg border border-white">
                        <div class="w-full flex gap-2">
                            <div v-for="(tab, idx) in tabs" :key="`r1-${idx}`" @click="activeTab = tab" :class="[
                                'flex-1 min-w-max whitespace-nowrap cursor-pointer px-2 pt-3 pb-1 text-sm text-center transition-all duration-150',
                                activeTab === tab
                                    // pressed-in for active
                                    ? 'bg-gray-50 shadow-inner border border-gray-300 border-b-0 rounded-t-lg'
                                    // lifted for inactive
                                    : 'bg-white border border-gray-300 border-b-0 rounded-t-lg -mb-px z-20 shadow-lg relative -top-1'
                            ]">
                                {{ tab }}
                            </div>
                        </div>

                    </div>

                    <!-- Tab Content -->
                    <div>
                        <!-- Announcements -->
                        <div v-if="activeTab === 'Announcements'">

                            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
                                <p class="text-sm text-left mx-4"> <strong>List of announcements</strong>
                                    <button class="bg-blue-500 px-2 py-1 rounded text-white" style="float:right">
                                        <i class="fa-solid fa-plus text-white mx-1"></i> Add Announcement</button>
                                </p>
                                <hr class="mx-4 my-3">

                                <div class="bg-gray-200 rounded-lg mx-4 my-2 p-2">
                                    <p class="text-sm text-left mx-4"> <strong>Date: 12/01/2025</strong> </p>
                                    <hr class="mx-4">

                                    <p class="text-left px-4 py-2">
                                        Welcome to this group</p>

                                    <div class="text-right">
                                        <button class=" px-2 py-1 my-2 rounded text-white text-sm">
                                            <i class="fa-solid fa-pen-to-square text-black mx-1"></i>
                                        </button>

                                        <button class="px-2 py-1 rounded text-white text-sm">
                                            <i class="fa-solid fa-trash text-red-500 mx-1"></i>
                                        </button>
                                    </div>
                                </div>


                            </div>

                        </div>

                        <!-- Group Documents -->
                        <div v-if="activeTab === 'Group Documents'">
                            <!-- Documents -->
                            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
                                <p class="text-sm text-left mx-4"> <strong> Documents</strong>
                                    <button class="bg-blue-500 px-2 py-1 rounded text-white" style="float:right">
                                        <i class="fa-solid fa-plus text-white mx-1"></i> Add Document</button>
                                </p>
                                <hr class="mx-4 my-3">

                                <div class="grid grid-cols-12">
                                    <div
                                        class="grid grid-cols-6 col-span-6 text-left bg-gray-100 border border-gray-300 rounded-lg p-3 mx-4 mt-2">

                                        <span class="col-span-1 mt-2 ml-4">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </span>

                                        <div class="col-span-4 mt-2">
                                            <p class="text-sm">Handbook.pdf</p>
                                        </div>

                                        <div class="col-span-1 mt-2">
                                            <button class="mx-1">
                                                <i class="fa-solid fa-download"></i>
                                            </button>

                                            <button class="mx-1">
                                                <i class="fa-solid fa-trash text-red-500"></i>
                                            </button>
                                        </div>

                                    </div>

                                    <div
                                        class="grid grid-cols-6 col-span-6 text-left bg-gray-100 border border-gray-300 rounded-lg p-3 mx-4 mt-2">

                                        <span class="col-span-1 mt-2 ml-4">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </span>

                                        <div class="col-span-4 mt-2">
                                            <p class="text-sm">Certificate.pdf</p>
                                        </div>

                                        <button class="col-span-1 mt-2 mr-4">
                                            <i class="fa-solid fa-download"></i>
                                        </button>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- Group Admins   -->
                        <div v-if="activeTab === 'Group Admins'">
                            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
                                <p class="text-sm text-left mx-4"> <strong> Group Admins </strong>
                                    <button class="bg-blue-500 px-2 py-1 rounded text-white" style="float:right">
                                        <i class="fa-solid fa-plus text-white mx-1"></i> Add Admin</button>
                                </p>
                                <hr class="mx-4 mt-3">

                                <div class="grid grid-cols-12 text-left m-2 bg-gray-100 border border-gray-300 rounded">

                                    <div class="col-span-1 m-2 ">
                                        <img src="https://png.pngtree.com/png-vector/20220709/ourmid/pngtree-businessman-user-avatar-wearing-suit-with-red-tie-png-image_5809521.png"
                                            alt="Parent Image" class="w-12 h-12 rounded-full border mr-4">
                                    </div>

                                    <!-- Full name -->
                                    <div class="col-span-3 mt-2">
                                        <p class="text-sm"><strong>Full name</strong></p>
                                        <span class="text-gray-600 text-sm">{{ myGroup.assignedAdmin }}</span>
                                    </div>

                                    <!-- Email-->
                                    <div class="col-span-3 mt-2">
                                        <p class="text-sm"><strong>Email</strong></p>
                                        <span class="text-gray-600 text-sm">{{ myGroup.schoolEmail }}</span>
                                    </div>

                                    <!-- Phone-->
                                    <div class="col-span-3 mt-2">
                                        <p class="text-sm"><strong>Phone</strong></p>
                                        <span class="text-gray-600 text-sm">{{ myGroup.contactInfo }}</span>
                                    </div>

                                    <div class="col-span-2 mt-1">
                                        <button class=" px-2 py-1 my-2 rounded text-white text-sm">
                                            <i class="fa-solid fa-pen-to-square text-black mx-1"></i>
                                        </button>

                                        <button class="px-2 py-1 rounded text-white text-sm">
                                            <i class="fa-solid fa-trash text-red-500 mx-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Events & Calendar -->
                        <div v-if="activeTab === 'Events & Calendar'">
                            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
                                <p class="text-sm text-left mx-4"> <strong> Events & Calendar </strong> </p>
                                <hr class="mx-4">


                            </div>
                        </div>

                        <!-- Discussions -->
                        <div v-if="activeTab === 'Discussions'">
                            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
                                <p class="text-sm text-left mx-4"> <strong> Discussions </strong> </p>
                                <hr class="mx-4">


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
    name: "StudentProfileShow",
    data() {
        return {
            openIndex: 0, // Default to open the first test
            activeTab: "Highlights",
            tabs: ["Highlights", "Announcements", "Discussions", "Events & Calendar", "Group Admins", "Group Documents",],
        };
    },

}
</script>

<script setup>
import { reactive, ref, inject } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform } from "@core/helpers/action"

import { Doughnut } from 'vue-chartjs';



const store = useStore()
const route = useRoute()
const router = useRouter()

const emitter = inject("emitter")
const $trans = inject("$trans")

const initialGroupInfo = {}

const initUrl = "communication/mygroups/"

const refreshItem = ref(false)
const myGroup = reactive({ ...initialGroupInfo })

function stripHtml(html) {
    return html?.replace(/<\/?[^>]+(>|$)/g, '').trim();
}

const setItem = (data) => {
    Object.assign(myGroup, data)
}

</script>
