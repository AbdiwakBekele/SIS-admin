<template>
    <div class="p-6 bg-gray-50 rounded-lg my-2">
        <h3 class="text-sm font-bold">Gamification</h3>
        <hr>
        <!-- Weekly Leaderboard Header -->
        <div class="my-2 mt-4">
            <h3 class="text-sm font-semibold mb-1">Weekly Leaderboards</h3>
            <p class="text-[11px] text-gray-600">Week of {{ weekStart }} - {{ weekEnd }}</p>
        </div>
        <!-- 2x2 Grid of Cards -->
        <div v-if="participants && participants.length" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Total Points Earned -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">🏅 Total Points Earned</h2>
                <ul>
                    <li v-for="(p, idx) in topPoints" :key="p.id" class="flex justify-between py-2 border-b last:border-b-0">
                        <span>{{ idx + 1 }}. {{ p.name }}</span>
                        <span>{{ p.points }} pts</span>
                    </li>
                </ul>
            </div>
            <!-- Challenges Completed -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">✅ Challenges Completed</h2>
                <ul>
                    <li v-for="(p, idx) in topChallenges" :key="p.id" class="flex justify-between py-2 border-b last:border-b-0">
                        <span>{{ idx + 1 }}. {{ p.name }}</span>
                        <span>{{ p.challengesCompleted }}</span>
                    </li>
                </ul>
            </div>
            <!-- Badges Earned -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">🎖️ Badges Earned</h2>
                <ul>
                    <li v-for="(p, idx) in topBadges" :key="p.id" class="flex justify-between py-2 border-b last:border-b-0">
                        <span>{{ idx + 1 }}. {{ p.name }}</span>
                        <span>{{ p.badgesEarned }}</span>
                    </li>
                </ul>
            </div>
            <!-- Attendance Streak -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">🔥 Attendance Streak</h2>
                <ul>
                    <li v-for="(p, idx) in topStreaks" :key="p.id" class="flex justify-between py-2 border-b last:border-b-0">
                        <span>{{ idx + 1 }}. {{ p.name }}</span>
                        <span>{{ p.attendanceStreak }} days</span>
                    </li>
                </ul>
            </div>
        </div>
        <div v-else class="text-center py-4 text-gray-500">
            No leaderboard data available
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

defineProps({
    participants: Array,
    topPoints: Array,
    topChallenges: Array,
    topBadges: Array,
    topStreaks: Array,
    weekStart: String,
    weekEnd: String
});


</script>

