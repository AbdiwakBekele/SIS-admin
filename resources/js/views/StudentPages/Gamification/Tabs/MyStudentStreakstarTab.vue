<template>
    <div class="space-y-8">
        <!-- Earned Streak Stars Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-fire text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Earned Streak Stars</h2>
                    <p class="text-gray-600 text-sm">Streak stars you've successfully earned</p>
                </div>
            </div>

            <div v-if="earnedStreakStars.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-fire text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Earned Streak Stars</h3>
                <p class="text-sm text-gray-500">Start earning streak stars by maintaining attendance!</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="streak in earnedStreakStars"
                    :key="streak.uuid"
                    @click="router.push({ name: 'MyStudentStreakStarShow', params: { uuid: streak.uuid } })"
                    class="group relative bg-gradient-to-br from-orange-50 to-red-50 rounded-lg border-2 border-orange-200 hover:border-orange-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="absolute top-1.5 right-1.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-300">
                            <i class="fa-solid fa-check-circle mr-0.5 text-[8px]"></i>
                            Earned
                        </span>
                    </div>

                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-fire text-white text-sm"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-xs mb-0.5 group-hover:text-orange-700 transition-colors line-clamp-2">
                                {{ streak.name }}
                            </h4>
                            <p class="text-[10px] text-gray-600">{{ streak.pointsRequired }} pts</p>
                        </div>

                        <div class="absolute top-1 right-1">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    icon="fas fa-eye"
                                    @click.stop="router.push({ name: 'MyStudentStreakStarShow', params: { uuid: streak.uuid } })"
                                >
                                    {{ $trans("general.view") }}
                                </FloatingMenuItem>
                            </FloatingMenu>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Streak Stars Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-lock-open text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Available Streak Stars</h2>
                    <p class="text-gray-600 text-sm">Streak stars you can earn</p>
                </div>
            </div>

            <div v-if="availableStreakStars.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-lock text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Available Streak Stars</h3>
                <p class="text-sm text-gray-500">Check back later for new streak stars</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="streak in availableStreakStars"
                    :key="streak.uuid"
                    @click="router.push({ name: 'MyStudentStreakStarShow', params: { uuid: streak.uuid } })"
                    class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 hover:border-blue-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-people-group text-white text-sm"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-xs mb-0.5 group-hover:text-blue-700 transition-colors line-clamp-2">
                                {{ streak.name }}
                            </h4>
                            <p class="text-[10px] text-gray-600">{{ streak.pointsRequired }} pts</p>
                        </div>

                        <div class="absolute top-1 right-1">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    icon="fas fa-eye"
                                    @click.stop="router.push({ name: 'MyStudentStreakStarShow', params: { uuid: streak.uuid } })"
                                >
                                    {{ $trans("general.view") }}
                                </FloatingMenuItem>
                            </FloatingMenu>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue"
import { inject } from "vue"
import { useRouter } from "vue-router"

const router = useRouter()
const emitter = inject("emitter")
const $trans = inject("$trans")

const props = defineProps({
    streakStars: {
        type: Array,
        required: true,
    },
})

const isEarned = (streak) => {
    return streak.isEarned === true || streak.earned === true || streak.status === "earned" || (streak.studentStreakStars && streak.studentStreakStars.length > 0)
}

const earnedStreakStars = computed(() => {
    return props.streakStars.filter((streak) => isEarned(streak))
})

const availableStreakStars = computed(() => {
    return props.streakStars.filter((streak) => !isEarned(streak))
})
</script>
