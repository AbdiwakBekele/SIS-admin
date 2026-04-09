<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Challenge Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-1">{{ challenge.title }}</h1>
            <p class="text-sm text-gray-600">
                {{ challenge.startDate }} — {{ challenge.endDate }}
            </p>
            <p class="mt-4 text-gray-800">{{ challenge.description }}</p>
        </div>

        <!-- Leaderboard Top 3 -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">🏆 Leaderboard</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div v-for="(p, idx) in topThree" :key="p.id" class="bg-white rounded-xl shadow p-6 text-center">
                    <div class="text-xl font-bold mb-2">#{{ idx + 1 }}</div>
                    <img :src="p.avatar" alt="avatar" class="w-20 h-20 rounded-full mx-auto mb-3" />
                    <div class="font-medium">{{ p.name }}</div>
                    <div class="mt-1 text-indigo-600 font-semibold">
                        {{ p.points }} pts
                    </div>
                </div>
            </div>
        </section>

        <!-- Full Participants List -->
        <section>
            <h3 class="text-xl font-semibold mb-3">All Participants</h3>
            <table class="w-full bg-white rounded-lg overflow-hidden shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Rank</th>
                        <th class="px-4 py-2 text-left">Student</th>
                        <th class="px-4 py-2 text-left">Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(p, idx) in sortedParticipants" :key="p.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ idx + 1 }}</td>
                        <td class="px-4 py-3">{{ p.name }}</td>
                        <td class="px-4 py-3">{{ p.points }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'

// Dummy challenges (could come from a store or API)
const challenges = ref([
    {
        id: 1,
        title: 'Walk 10,000 Steps',
        description: 'Hit 10,000 steps today to stay active.',
        startDate: '2025-05-20',
        endDate: '2025-05-20',
    },
    {
        id: 2,
        title: 'Read a Chapter',
        description: 'Read at least one chapter of any book.',
        startDate: '2025-05-21',
        endDate: '2025-05-21',
    },
    {
        id: 3,
        title: 'Drink 2L Water',
        description: 'Stay hydrated by drinking 2 liters of water.',
        startDate: '2025-05-22',
        endDate: '2025-05-22',
    },
    {
        id: 4,
        title: 'Meditate for 15 Minutes',
        description: 'Take a mindful break and meditate.',
        startDate: '2025-05-23',
        endDate: '2025-05-23',
    },
    {
        id: 5,
        title: 'Write a Journal Entry',
        description: 'Reflect on your day by writing at least 200 words.',
        startDate: '2025-05-24',
        endDate: '2025-05-24',
    },
])

// Dummy participant data
const participants = ref([
    { id: 1, name: 'John Smith K.', avatar: 'https://i.pravatar.cc/150?img=1', points: 20 },
    { id: 2, name: 'John Daniel', avatar: 'https://i.pravatar.cc/150?img=2', points: 18 },
    { id: 3, name: 'Janet Assefa', avatar: 'https://i.pravatar.cc/150?img=5', points: 15 },
    { id: 4, name: 'Alazar T.', avatar: 'https://i.pravatar.cc/150?img=4', points: 12 },
    { id: 5, name: 'Nati Ch.', avatar: 'https://i.pravatar.cc/150?img=5', points: 10 },
])

// Get challenge ID from route params (fallback to 1)
const route = useRoute()
const challengeId = Number(route.params.id) || 1

// Find current challenge
const challenge = computed(
    () => challenges.value.find((c) => c.id === challengeId) || challenges.value[0]
)

// Sort participants by points desc
const sortedParticipants = computed(() =>
    [...participants.value].sort((a, b) => b.points - a.points)
)

// Top 3 for highlighted leaderboard
const topThree = computed(() => sortedParticipants.value.slice(0, 3))
</script>

<style scoped>
/* You can tweak spacing or card styles here if needed */
</style>
