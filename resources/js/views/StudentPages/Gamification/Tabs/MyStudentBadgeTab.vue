<template>
    <div class="space-y-8">
        <!-- Earned Badges Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-medal text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Earned Badges</h2>
                    <p class="text-gray-600 text-sm">Badges you've successfully earned</p>
                </div>
            </div>

            <div v-if="earnedBadges.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-medal text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Earned Badges</h3>
                <p class="text-sm text-gray-500">Start earning badges by completing challenges!</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="badge in earnedBadges"
                    :key="badge.uuid"
                    @click="router.push({ name: 'MyStudentBadgeShow', params: { uuid: badge.uuid } })"
                    class="group relative bg-gradient-to-br from-yellow-50 to-amber-50 rounded-lg border-2 border-yellow-200 hover:border-yellow-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="absolute top-1.5 right-1.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-300">
                            <i class="fa-solid fa-check-circle mr-0.5 text-[8px]"></i>
                            Earned
                        </span>
                    </div>

                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-lg flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-medal text-white text-sm"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-xs mb-0.5 group-hover:text-yellow-700 transition-colors line-clamp-2">
                                {{ badge.name }}
                            </h4>
                            <p class="text-[10px] text-gray-600">{{ badge.pointsRequired }} pts</p>
                        </div>

                        <div class="absolute top-1 right-1">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    icon="fas fa-eye"
                                    @click.stop="router.push({ name: 'MyStudentBadgeShow', params: { uuid: badge.uuid } })"
                                >
                                    {{ $trans("general.view") }}
                                </FloatingMenuItem>
                            </FloatingMenu>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Badges Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-lock-open text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Available Badges</h2>
                    <p class="text-gray-600 text-sm">Badges you can earn</p>
                </div>
            </div>

            <div v-if="availableBadges.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-lock text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Available Badges</h3>
                <p class="text-sm text-gray-500">Check back later for new badges</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="badge in availableBadges"
                    :key="badge.uuid"
                    @click="router.push({ name: 'MyStudentBadgeShow', params: { uuid: badge.uuid } })"
                    class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 hover:border-blue-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-bolt text-white text-sm"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-xs mb-0.5 group-hover:text-blue-700 transition-colors line-clamp-2">
                                {{ badge.name }}
                            </h4>
                            <p class="text-[10px] text-gray-600">{{ badge.pointsRequired }} pts</p>
                        </div>

                        <div class="absolute top-1 right-1">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    icon="fas fa-eye"
                                    @click.stop="router.push({ name: 'MyStudentBadgeShow', params: { uuid: badge.uuid } })"
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
    badges: {
        type: Array,
        required: true,
    },
})

const isEarned = (badge) => {
    return badge.isEarned === true || badge.earned === true || badge.status === "earned" || (badge.studentBadges && badge.studentBadges.length > 0)
}

const earnedBadges = computed(() => {
    return props.badges.filter((badge) => isEarned(badge))
})

const availableBadges = computed(() => {
    return props.badges.filter((badge) => !isEarned(badge))
})
</script>
