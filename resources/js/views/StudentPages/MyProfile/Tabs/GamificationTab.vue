<template>
    <div class="mt-4">
        <!-- Main Card Container -->
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-200 shadow-lg overflow-hidden">
            <!-- Card Header -->
            <div class="bg-white border-b border-blue-200 px-6 py-4">
                <div class="flex flex-wrap items-center gap-3">
                    <i class="fa-solid fa-trophy text-blue-600 text-xl"></i>
                    <h3 class="text-lg font-semibold text-blue-900">
                        {{ studentFirstName ? `Gamification Center - ${studentFirstName}` : 'Gamification Center' }}
                    </h3>
                </div>
            </div>

            <!-- Nested Tab Container -->
            <div class="p-6">
                <!-- Tab Navigation -->
                <div class="mb-4 border-b border-blue-200">
                    <div class="flex gap-2 overflow-x-auto -mb-px">
                        <button
                            v-for="(tab, idx) in navTabs"
                            :key="`tab-${idx}`"
                            @click="currentTab = tab.key"
                            :class="[
                                'px-4 py-3 text-sm font-medium whitespace-nowrap transition-all duration-200 border-b-2',
                                currentTab === tab.key
                                    ? 'text-blue-600 border-blue-600 bg-blue-50 rounded-t-lg'
                                    : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                            ]"
                        >
                            <i :class="tab.icon + ' mr-2'"></i>{{ tab.label }}
                        </button>
                    </div>
                </div>

                <!-- Tab Content Card -->
                <div class="bg-white rounded-lg border border-blue-200 shadow-sm p-6 mt-4">
                    <!-- Loading State -->
                    <div v-if="isLoading" class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>

                    <!-- Content -->
                    <div v-else>
                        <div v-if="currentTab === 'leaderboard'">
                            <ProgressSummaryCard :summary="progressSummary" self-view />
                        </div>

                        <!-- Challenges -->
                        <div v-if="currentTab === 'challenges'">
                            <MyStudentChallengeTab :challenges="challenges" />
                        </div>

                        <!-- Badges -->
                        <div v-if="currentTab === 'badges'">
                            <MyStudentBadgeTab :badges="badges" />
                        </div>

                        <!-- Powerups -->
                        <div v-if="currentTab === 'powerups'">
                            <MyStudentPowerupTab :powerups="powerups" />
                        </div>

                        <!-- Streak Stars -->
                        <div v-if="currentTab === 'streak-stars'">
                            <MyStudentStreakstarTab :streakStars="streakStars" />
                        </div>

                        <!-- Attendance Ribbon -->
                        <div v-if="currentTab === 'attendance-ribbon'">
                            <MyStudentAttendanceribbonTab
                                :attendanceRibbons="attendanceRibbons"
                            />
                        </div>

                        <!-- Academic Trophies -->
                        <div v-if="currentTab === 'academic-trophy'">
                            <MyStudentAcademicTrophyTab
                                :academicTrophies="academicTrophies"
                            />
                        </div>

                        <!-- Redemption Requests -->
                        <div v-if="currentTab === 'redemption-requests'">
                            <MyStudentRedemptionrequestTab
                                :redemptionRequests="redemptionRequests"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    ref,
    computed,
    inject,
    onMounted,
    onBeforeUnmount,
} from "vue"
import { useStore } from "vuex"

import MyStudentChallengeTab from "../../Gamification/Tabs/MyStudentChallengeTab.vue"
import MyStudentAcademicTrophyTab from "../../Gamification/Tabs/MyStudentAcademicTrophyTab.vue"
import MyStudentAttendanceribbonTab from "../../Gamification/Tabs/MyStudentAttendanceribbonTab.vue"
import MyStudentBadgeTab from "../../Gamification/Tabs/MyStudentBadgeTab.vue"
import MyStudentPowerupTab from "../../Gamification/Tabs/MyStudentPowerupTab.vue"
import MyStudentRedemptionrequestTab from "../../Gamification/Tabs/MyStudentRedemptionrequestTab.vue"
import MyStudentStreakstarTab from "../../Gamification/Tabs/MyStudentStreakstarTab.vue"
import ProgressSummaryCard from "@core/components/Gamification/ProgressSummaryCard.vue"

const props = defineProps({
    student: Object
})

const studentFirstName = computed(() =>
    props.student?.firstName ||
    props.student?.first_name ||
    props.student?.contact?.firstName ||
    props.student?.contact?.first_name ||
    props.student?.name?.split(" ")?.[0] ||
    props.student?.contact?.name?.split(" ")?.[0] ||
    ""
)

const store = useStore()

const currentTab = ref("leaderboard")

const navTabs = [
    { key: "leaderboard", label: "Leaderboard", icon: "fa-solid fa-ranking-star" },
    { key: "challenges", label: "Challenges", icon: "fa-solid fa-flag-checkered" },
    { key: "badges", label: "Badges", icon: "fa-solid fa-medal" },
    { key: "powerups", label: "Powerups", icon: "fa-solid fa-bolt" },
    { key: "streak-stars", label: "Streak Stars", icon: "fa-solid fa-fire" },
    { key: "attendance-ribbon", label: "Attendance Ribbon", icon: "fa-solid fa-ribbon" },
    { key: "academic-trophy", label: "Academic Trophy", icon: "fa-solid fa-trophy" },
    { key: "redemption-requests", label: "Redemption Requests", icon: "fa-solid fa-gift" },
]

const getCurrentWeek = () => {
    const today = new Date()
    const dayOfWeek = today.getDay()
    const diff = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1)
    const monday = new Date(today.setDate(diff))
    const sunday = new Date(monday)
    sunday.setDate(monday.getDate() + 6)
    
    const formatDate = (date) => {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`
    }
    
    return {
        start: formatDate(monday),
        end: formatDate(sunday)
    }
}

const powerups = ref([])
const challenges = ref([])
const badges = ref([])
const streakStars = ref([])
const attendanceRibbons = ref([])
const academicTrophies = ref([])
const redemptionRequests = ref([])
const progressSummary = ref(null)

const emitter = inject("emitter")
const handleListItemsRefresh = () => {
    void getGamification()
}

const isLoading = ref(false)

const getGamification = async () => {
    isLoading.value = true
    await store
        .dispatch("gamification/fetchGamificationDetails")
        .then((response) => {
            challenges.value = response.challenges || []
            badges.value = response.badges || []
            powerups.value = response.powerups || []
            streakStars.value = response.streakStars || []
            attendanceRibbons.value = response.attendanceRibbons || []
            academicTrophies.value = response.academicTrophies || []
            redemptionRequests.value = response.redemptionRequests || []
            progressSummary.value = response.progressSummary || null
            isLoading.value = false
        })
        .catch((e) => {
            isLoading.value = false
        })
}

onMounted(async () => {
    await getGamification()
    emitter?.on?.("listItems", handleListItemsRefresh)
})

onBeforeUnmount(() => {
    emitter?.off?.("listItems", handleListItemsRefresh)
})
</script>
