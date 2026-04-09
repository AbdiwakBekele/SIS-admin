<template>
    <div class="p-6 bg-gray-50 rounded-lg my-2">
        <h3 class="text-sm font-bold">Gamification</h3>
        <hr />
        <div class="my-2 mt-4">
            <h3 class="text-sm font-semibold mb-1">Weekly Leaderboards</h3>
            <p class="text-[11px] text-gray-600">Week of {{ weekStart }} - {{ weekEnd }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">🏅 Total Points Earned</h2>
                <ul>
                    <li v-for="(p, idx) in topPoints" :key="p.id"
                        class="flex justify-between py-2 border-b last:border-b-0">
                        <span>{{ idx + 1 }}. {{ p.name }}</span>
                        <span>{{ p.points }} pts</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">✅ Challenges Completed</h2>
                <ul>
                    <li v-for="(p, idx) in topChallenges" :key="p.id"
                        class="flex justify-between py-2 border-b last:border-b-0">
                        <span>{{ idx + 1 }}. {{ p.name }}</span>
                        <span>{{ p.challengesCompleted }}</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">🎖️ Badges Earned</h2>
                <ul>
                    <li v-for="(p, idx) in topBadges" :key="p.id"
                        class="flex justify-between py-2 border-b last:border-b-0">
                        <span>{{ idx + 1 }}. {{ p.name }}</span>
                        <span>{{ p.badgesEarned }}</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">🔥 Attendance Streak</h2>
                <ul>
                    <li v-for="(p, idx) in topStreaks" :key="p.id"
                        class="flex justify-between py-2 border-b last:border-b-0">
                        <span>{{ idx + 1 }}. {{ p.name }}</span>
                        <span>{{ p.attendanceStreak }} days</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    participants: { type: Array, default: () => [] },
    weekStart: { type: String, default: '' },
    weekEnd: { type: String, default: '' },
})

function topBy(key) {
    return computed(() => [...props.participants].sort((a, b) => b[key] - a[key]).slice(0, 5))
}

const topPoints = topBy('points')
const topChallenges = topBy('challengesCompleted')
const topBadges = topBy('badgesEarned')
const topStreaks = topBy('attendanceStreak')
</script>
