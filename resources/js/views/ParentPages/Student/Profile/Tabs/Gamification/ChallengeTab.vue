<template>
    <div class="space-y-8">
        <!-- Accepted Challenges Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-check-circle text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Accepted Challenges</h2>
                    <p class="text-gray-600 text-sm">Challenges {{ studentName }} is currently participating in</p>
                </div>
            </div>

            <div v-if="acceptedChallenges.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-inbox text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Accepted Challenges</h3>
                <p class="text-sm text-gray-500">{{ studentName }} hasn't accepted any challenges yet</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="ch in acceptedChallenges"
                    :key="ch.uuid"
                    @click="router.push({ name: 'MyStudentChallengeShow', params: { uuid: ch.uuid } })"
                    class="group relative bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border-2 border-green-200 hover:border-green-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="p-3">
                        <div class="mb-2">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                                    <i class="fa-solid fa-flag-checkered text-white text-sm"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-xs line-clamp-2 group-hover:text-green-700 transition-colors flex-1">
                                    {{ ch.name }}
                                </h3>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="flex items-center justify-between bg-white/70 rounded px-2 py-1 border border-green-200">
                                <span class="text-[10px] font-medium text-gray-600">Points</span>
                                <div class="flex items-center gap-0.5">
                                    <i class="fa-solid fa-coins text-yellow-500 text-[10px]"></i>
                                    <span class="font-bold text-gray-900 text-[10px]">{{ ch.rewardValue }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1 mb-2">
                            <div class="flex items-center text-[10px] text-gray-600">
                                <i class="fa-solid fa-calendar-check text-green-500 mr-1 text-[8px]"></i>
                                <span class="font-medium truncate">{{ formatDateOnly(ch.startDate) }}</span>
                            </div>
                            <div class="flex items-center text-[10px] text-gray-600">
                                <i class="fa-solid fa-calendar-times text-red-500 mr-1 text-[8px]"></i>
                                <span class="font-medium truncate">{{ formatDateOnly(ch.endDate) }}</span>
                            </div>
                        </div>

                        <button class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-1.5 px-2 rounded text-[10px] transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-eye mr-1"></i>
                            View
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Challenges Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-rocket text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Available Challenges</h2>
                    <p class="text-gray-600 text-sm">New challenges {{ studentName }} can participate in</p>
                </div>
            </div>

            <div v-if="availableChallenges.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-inbox text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Available Challenges</h3>
                <p class="text-sm text-gray-500">Check back later for new challenges</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="ch in availableChallenges"
                    :key="ch.uuid"
                    @click="router.push({ name: 'MyStudentChallengeShow', params: { uuid: ch.uuid } })"
                    class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 hover:border-blue-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="p-3">
                        <div class="mb-2">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-md flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fa-solid fa-trophy text-white text-sm"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-xs line-clamp-2 group-hover:text-blue-700 transition-colors flex-1">
                                    {{ ch.name }}
                                </h3>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="flex items-center justify-between bg-white/70 rounded px-2 py-1 border border-blue-200">
                                <span class="text-[10px] font-medium text-gray-600">Points</span>
                                <div class="flex items-center gap-0.5">
                                    <i class="fa-solid fa-coins text-yellow-500 text-[10px]"></i>
                                    <span class="font-bold text-gray-900 text-[10px]">{{ ch.rewardValue }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1 mb-2">
                            <div class="flex items-center text-[10px] text-gray-600">
                                <i class="fa-solid fa-calendar-check text-green-500 mr-1 text-[8px]"></i>
                                <span class="font-medium truncate">{{ formatDateOnly(ch.startDate) }}</span>
                            </div>
                            <div class="flex items-center text-[10px] text-gray-600">
                                <i class="fa-solid fa-calendar-times text-red-500 mr-1 text-[8px]"></i>
                                <span class="font-medium truncate">{{ formatDateOnly(ch.endDate) }}</span>
                            </div>
                        </div>

                        <button class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-1.5 px-2 rounded text-[10px] transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-eye mr-1"></i>
                            View
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Past Challenges Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-slate-500 to-gray-700 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-history text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Past Challenges</h2>
                    <p class="text-gray-600 text-sm">Completed and withdrawn challenges for {{ studentName }}</p>
                </div>
            </div>

            <div v-if="pastChallenges.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-history text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Past Challenges</h3>
                <p class="text-sm text-gray-500">Completed and withdrawn challenges will appear here</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="ch in pastChallenges"
                    :key="ch.uuid"
                    @click="router.push({ name: 'MyStudentChallengeShow', params: { uuid: ch.uuid } })"
                    class="group relative rounded-lg border-2 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                    :class="pastChallengeStatus(ch) === 'completed'
                        ? 'bg-gradient-to-br from-emerald-50 to-green-50 border-emerald-200 hover:border-emerald-400'
                        : 'bg-gradient-to-br from-rose-50 to-red-50 border-rose-200 hover:border-rose-400'"
                >
                    <div class="p-3">
                        <div class="mb-2">
                            <div class="flex items-center gap-2 mb-1">
                                <div
                                    class="w-8 h-8 rounded-lg flex items-center justify-center shadow-md flex-shrink-0"
                                    :class="pastChallengeStatus(ch) === 'completed'
                                        ? 'bg-gradient-to-br from-emerald-500 to-green-600'
                                        : 'bg-gradient-to-br from-rose-500 to-red-600'"
                                >
                                    <i
                                        class="text-white text-sm"
                                        :class="pastChallengeStatus(ch) === 'completed'
                                            ? 'fa-solid fa-trophy'
                                            : 'fa-solid fa-arrow-rotate-left'"
                                    ></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-xs line-clamp-2 flex-1">
                                    {{ ch.name }}
                                </h3>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div
                                class="flex items-center justify-center rounded px-2 py-1 border text-[10px] font-semibold uppercase tracking-wide"
                                :class="pastChallengeStatus(ch) === 'completed'
                                    ? 'bg-emerald-100 border-emerald-200 text-emerald-800'
                                    : 'bg-rose-100 border-rose-200 text-rose-800'"
                            >
                                {{ pastChallengeStatus(ch) === "completed" ? "Completed" : "Withdrawn" }}
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="flex items-center justify-between bg-white/70 rounded px-2 py-1 border border-gray-200">
                                <span class="text-[10px] font-medium text-gray-600">Points</span>
                                <div class="flex items-center gap-0.5">
                                    <i class="fa-solid fa-coins text-yellow-500 text-[10px]"></i>
                                    <span class="font-bold text-gray-900 text-[10px]">{{ ch.rewardValue }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1 mb-2">
                            <div class="flex items-center text-[10px] text-gray-600">
                                <i class="fa-solid fa-calendar-check text-green-500 mr-1 text-[8px]"></i>
                                <span class="font-medium truncate">{{ formatDateOnly(ch.startDate) }}</span>
                            </div>
                            <div class="flex items-center text-[10px] text-gray-600">
                                <i class="fa-solid fa-calendar-times text-red-500 mr-1 text-[8px]"></i>
                                <span class="font-medium truncate">{{ formatDateOnly(ch.endDate) }}</span>
                            </div>
                        </div>

                        <button
                            class="w-full text-white font-semibold py-1.5 px-2 rounded text-[10px] transition-all duration-300 shadow-sm"
                            :class="pastChallengeStatus(ch) === 'completed'
                                ? 'bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700'
                                : 'bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700'"
                        >
                            <i class="fa-solid fa-eye mr-1"></i>
                            View
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue"
import { useRouter } from "vue-router"
import { getPastChallengeStatus, splitStudentChallenges } from "../../../../../../utils/studentChallengeSections"

const router = useRouter()

const props = defineProps({
    challenges: {
        type: Array,
        required: true,
    },
    studentName: {
        type: String,
        default: "This student",
    },
})

const challengeSections = computed(() => splitStudentChallenges(props.challenges))

const acceptedChallenges = computed(() => {
    return challengeSections.value.accepted
})

const availableChallenges = computed(() => {
    return challengeSections.value.available
})

const pastChallenges = computed(() => {
    return challengeSections.value.past
})

const pastChallengeStatus = (challenge) => getPastChallengeStatus(challenge)

const formatDateOnly = (dateObj) => {
    if (!dateObj || !dateObj.formatted) return "-"
    let formatted = dateObj.formatted
    
    if (typeof formatted === 'string') {
        if (formatted.includes(",")) {
            const parts = formatted.split(",")
            formatted = parts[0] + "," + parts[1]
        }
        formatted = formatted.split(" ").slice(0, 3).join(" ")
        formatted = formatted.replace(/\d{1,2}:\d{2}(:\d{2})?(\s*(AM|PM))?/gi, "").trim()
    }
    
    return formatted || "-"
}
</script>

