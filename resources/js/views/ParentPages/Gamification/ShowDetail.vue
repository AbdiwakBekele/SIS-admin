<template>
    <PageHeader :title="$trans('gamification.student_gamification')" :navs="[
        { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
    ]">
        <PageHeaderAction class="mr-3" name="MyParentStudentGamificationDetail" :title="$trans('gamification.student_gamification')"
            :actions="['filter']" @toggleFilter="showFilter = !showFilter">

        </PageHeaderAction>
    </PageHeader>

    <ParentTransition appear :visibility="showFilter">
        <FilterForm v-if="showFilter" @refresh="emitter.emit('listItems')" :pre-requisites="preRequisites" @hide="showFilter = false" />
    </ParentTransition>


    <ParentTransition appear :visibility="true">


        <!-- Details Grid -->
        <div class="grid grid-cols-11 gap-2 p-3 shadow-md">

            <!-- Right Section with Tabs -->
            <div class="col-span-11 rounded-lg">
                <ProgressSummaryCard :summary="progressSummary" />

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

                    <!-- Items  -->
                    <div v-if="currentTab === 'leaderboards'">
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
                        <ChallengeTab :challenges="challenges" />
                    </div>

                    <!-- Badges -->
                    <div v-if="currentTab === 'badges'">
                        <BadgeTab :badges="badges" />
                    </div>

                    <!-- Powerups -->
                    <div v-if="currentTab === 'powerups'">
                        <PowerupTab :powerups="powerups" />

                    </div>

                    <!-- Streak Stars -->
                    <div v-if="currentTab === 'streak-stars'">
                        <StreakstarTab :streakStars="streakStars" />
                    </div>
                    <!-- Attendance Ribbon -->
                    <div v-if="currentTab === 'attendance-ribbon'">
                        <AttendanceribbonTab :attendanceRibbons="attendanceRibbons" />
                    </div>
                    <!-- Academic Trophies -->
                    <div v-if="currentTab === 'academic-trophy'">
                        <AcademicTrophyTab :academicTrophies="academicTrophies" />
                    </div>


                    <!-- Redemption Requests -->
                    <div v-if="currentTab === 'redemption-requests'">
                        <RedemptionrequestTab :redemptionRequests="redemptionRequests" />
                    </div>


                </div>
            </div>
        </div>



    </ParentTransition>

</template>

<script>
export default {
    name: "ParentGamificationDetail",
}
</script>

<script setup>
import { ref, reactive, inject, onMounted, computed, onBeforeUnmount } from "vue";
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform } from "@core/helpers/action"
import FilterForm from "./Filter.vue"
import ChallengeTab from "./Tabs/ChallengeTab.vue";
import AcademicTrophyTab from "./Tabs/AcademicTrophyTab.vue"
import AttendanceribbonTab from "./Tabs/AttendanceribbonTab.vue"
import BadgeTab from "./Tabs/BadgeTab.vue"
import LeaderboardTab from "@views/Pages/Gamification/Tabs/LeaderboardTab.vue"
import PowerupTab from "./Tabs/PowerupTab.vue"
import RedemptionrequestTab from "./Tabs/RedemptionrequestTab.vue"
import StreakstarTab from "./Tabs/StreakstarTab.vue"
import ProgressSummaryCard from "@core/components/Gamification/ProgressSummaryCard.vue"

const showFilter = ref(false)



const store = useStore()
const route = useRoute()
const router = useRouter()

// Tab definitions for Student Gamification detail
const navTabs = [
    { key: "leaderboards", label: "Leaderboards", icon: "fa-solid fa-ranking-star" },
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


const redemptionRequests = ref([
    { id: 1, student: "John Smith", reward: "Skip Homework", points: 30, status: "Pending" },
    { id: 2, student: "Janet Assefa", reward: "Late Pass", points: 20, status: "Approved" },
    { id: 3, student: "Carol Lee", reward: "Extra Recess", points: 25, status: "Rejected" },
])

const emitter = inject("emitter")
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
            // Error handled by store
        });
};

onMounted(async () => {
    getGamification();
    
    emitter.on("listItems", () => {
        getGamification();
    });
});

onBeforeUnmount(() => {
    emitter.all.delete("listItems");
});

</script>
