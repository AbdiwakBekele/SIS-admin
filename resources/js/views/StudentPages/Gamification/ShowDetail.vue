<template>
    <PageHeader
        :title="`Student Gamification Detail`"
        :navs="[
            { label: 'Gamification', path: 'MyStudentGamificationDetail' },
        ]"
    >
        <PageHeaderAction
            class="mr-3"
            name="MyStudentGamificationDetail"
            :title="`Student Gamification Detail`"
            :actions="[]"
        />
    </PageHeader>

    <!-- <ParentTransition appear :visibility="showFilter">
        <FilterForm
            @refresh="emitter.emit('listItems')"
            :pre-requisites="preRequisites"
            @hide="showFilter = false"
        />
    </ParentTransition> -->

    <ParentTransition appear :visibility="true">
        <!-- Details Grid -->
        <div class="grid grid-cols-11 gap-2 p-3 shadow-md">
            <!-- Right Section with Tabs -->
            <div class="col-span-11 rounded-lg">
                <!-- Tab Menus -->
                <!-- 1st Row -->
                <div class="w-full flex gap-2">
                    <RouterLink
                        v-for="(tab, idx) in navTabs"
                        :key="`r1-${idx}`"
                        :to="{ name: route.name, query: { ...route.query, tab: tab.key } }"
                        :class="[
                            'flex-1 min-w-max whitespace-nowrap cursor-pointer px-2 pt-3 pb-1 text-sm text-center transition-all duration-150',
                            currentTab === tab.key
                                ? 'bg-blue-200 shadow-inner border border-blue-400 border-b-0 rounded-t-lg'
                                : 'bg-white border border-gray-300 border-b-0 rounded-t-lg -mb-px z-20 shadow-lg relative -top-1'
                        ]"
                    >
                        <i :class="tab.icon + ' mr-2'"></i>{{ tab.label }}
                    </RouterLink>
                </div>

                <!-- Tab Content -->
                <div>
                    <!-- Leaderboards -->
                    <div v-if="currentTab === 'leaderboards'">
                        <ProgressSummaryCard :summary="progressSummary" self-view />
                        <LeaderboardTab
                            :participants="participants"
                            :challenges="challenges"
                            :leaderboard-metric-keys="leaderboardMetricKeys"
                            :custom-leaderboard-metrics="customLeaderboardMetrics"
                            :week-start="weekStart"
                            :week-end="weekEnd"
                        />
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
    </ParentTransition>
</template>

<script>
export default {
    name: "StudentGamificationDetail",
}
</script>

<script setup>
import {
    ref,
    reactive,
    inject,
    onMounted,
    computed,
    onBeforeUnmount,
} from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
// import FilterForm from "./Filter.vue"

import MyStudentChallengeTab from "./Tabs/MyStudentChallengeTab.vue"
import MyStudentAcademicTrophyTab from "./Tabs/MyStudentAcademicTrophyTab.vue"
import MyStudentAttendanceribbonTab from "./Tabs/MyStudentAttendanceribbonTab.vue"
import MyStudentBadgeTab from "./Tabs/MyStudentBadgeTab.vue"
import LeaderboardTab from "@views/Pages/Gamification/Tabs/LeaderboardTab.vue"
import MyStudentPowerupTab from "./Tabs/MyStudentPowerupTab.vue"
import MyStudentRedemptionrequestTab from "./Tabs/MyStudentRedemptionrequestTab.vue"
import MyStudentStreakstarTab from "./Tabs/MyStudentStreakstarTab.vue"
import ProgressSummaryCard from "@core/components/Gamification/ProgressSummaryCard.vue"

const showFilter = ref(false)

const store = useStore()
const route = useRoute()
const router = useRouter()

// Tab definitions for Student Gamification detail
const navTabs = [
    { key: "leaderboards", label: "Leaderboard", icon: "fa-solid fa-ranking-star" },
    { key: "challenges", label: "Challenges", icon: "fa-solid fa-flag-checkered" },
    { key: "badges", label: "Badges", icon: "fa-solid fa-medal" },
    { key: "powerups", label: "Powerups", icon: "fa-solid fa-bolt" },
    { key: "streak-stars", label: "Streak Stars", icon: "fa-solid fa-fire" },
    { key: "attendance-ribbon", label: "Attendance Ribbon", icon: "fa-solid fa-ribbon" },
    { key: "academic-trophy", label: "Academic Trophy", icon: "fa-solid fa-trophy" },
    { key: "redemption-requests", label: "Redemption Requests", icon: "fa-solid fa-gift" },
]

const currentTab = computed(() => route.query.tab || "leaderboards")
const weekStart = ref("")
const weekEnd = ref("")
const leaderboardMetricKeys = ref([])
const customLeaderboardMetrics = ref([])
const progressSummary = ref(null)

const participants = ref([])

const powerups = ref([])
const challenges = ref([])
const badges = ref([])
const streakStars = ref([])
const attendanceRibbons = ref([])
const academicTrophies = ref([])

const redemptionRequests = ref([])

const emitter = inject("emitter")
const handleListItemsRefresh = () => {
    void getGamification()
}
const $trans = inject("$trans")

const initialStudentProfile = {}
const initUrl = "gamification/fetchGamificationDetails"

const refreshItem = ref(false)
const student = reactive({ ...initialStudentProfile })

const preRequisites = ref({})

const isLoading = ref(false);
const getGamification = async () => {
    isLoading.value = true;
    await store
        .dispatch("gamification/fetchGamificationDetails")
        .then((response) => {
            challenges.value = response.challenges || [];
            badges.value = response.badges || [];
            powerups.value = response.powerups || [];
            streakStars.value = response.streakStars || [];
            attendanceRibbons.value = response.attendanceRibbons || [];
            academicTrophies.value = response.academicTrophies || [];
            redemptionRequests.value = response.redemptionRequests || [];
            participants.value = Array.isArray(response.participants) ? response.participants : [];
            weekStart.value = response.weekStart || "";
            weekEnd.value = response.weekEnd || "";
            leaderboardMetricKeys.value = response.leaderboardMetricKeys || [];
            customLeaderboardMetrics.value = response.customLeaderboardMetrics || [];
            progressSummary.value = response.progressSummary || null;
            isLoading.value = false;
        })
        .catch((e) => {
            isLoading.value = false;
        });
};

onMounted(async () => {
    await getGamification()
    emitter?.on?.("listItems", handleListItemsRefresh)
})

onBeforeUnmount(() => {
    emitter?.off?.("listItems", handleListItemsRefresh)
})
</script>
