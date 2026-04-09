<template>
    <PageHeader :title="$trans('gamification.leaderboard.leaderboard')" :navs="[
        { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
    ]">
        <PageHeaderAction url="gamification/leaderboard/" name="ParentLeaderboard"
            :title="$trans('gamification.leaderboard.leaderboard')" :actions="userActions"
            :dropdown-actions="dropdownActions" />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <BaseCard>
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-4">{{ $trans('gamification.leaderboard.leaderboard') }}</h1>
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">🏆 {{ $trans('gamification.leaderboard.student_leaderboard') }}</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse">
                            <thead>
                                <tr class="bg-blue-600 text-white">
                                    <th class="px-4 py-3 border-b">{{ $trans('student.student') }}</th>
                                    <th class="px-4 py-3 border-b">{{ $trans('gamification.points') }}</th>
                                    <th class="px-4 py-3 border-b">{{ $trans('gamification.badge.badges') }}</th>
                                    <th class="px-4 py-3 border-b">{{ $trans('gamification.streak_star.streak_stars') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(entry, index) in leaderboardData" :key="index" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 border-b">{{ entry.studentName }}</td>
                                    <td class="px-4 py-3 border-b text-green-600 font-semibold">{{ entry.points }}</td>
                                    <td class="px-4 py-3 border-b">{{ entry.badges }}</td>
                                    <td class="px-4 py-3 border-b">{{ entry.streakStars }}</td>
                                </tr>
                                <tr v-if="leaderboardData.length === 0">
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        {{ $trans('dashboard.nothing_to_show') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </BaseCard>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentLeaderboardList",
}
</script>

<script setup>
import { ref, inject } from "vue"
import { perform } from "@core/helpers/action"

const emitter = inject("emitter")

let userActions = ["filter"]

let dropdownActions = []
if (perform("leaderboard:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const leaderboardData = ref([
    { studentName: 'Alice Johnson', points: 250, badges: '🏅 Gold Achiever', streakStars: 4 },
    { studentName: 'Michael Smith', points: 200, badges: '🥈 Silver Explorer', streakStars: 5 },
    { studentName: 'Emma Brown', points: 180, badges: '🥉 Bronze Star', streakStars: 4 },
    { studentName: 'Oliver Davis', points: 160, badges: '⭐ Rising Star', streakStars: 3 },
    { studentName: 'Sophia Martinez', points: 140, badges: '🔥 New Challenger', streakStars: 2 },
])
</script>
