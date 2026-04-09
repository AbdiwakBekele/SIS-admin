<template>
    <div class="space-y-8">
        <!-- Earned Powerups Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-star text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Earned Powerups</h2>
                    <p class="text-gray-600 text-sm">Powerups {{ studentName }} has successfully earned</p>
                </div>
            </div>

            <div v-if="earnedPowerups.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-star text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Earned Powerups</h3>
                <p class="text-sm text-gray-500">{{ studentName }} hasn't earned any powerups yet</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="power in earnedPowerups"
                    :key="power.uuid"
                    @click="router.push({ name: 'MyStudentPowerUpsShow', params: { uuid: power.uuid } })"
                    class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 hover:border-blue-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="absolute top-1.5 right-1.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-300">
                            <i class="fa-solid fa-check-circle mr-0.5 text-[8px]"></i>
                            Earned
                        </span>
                    </div>

                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-bolt text-white text-sm"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-xs mb-0.5 group-hover:text-blue-700 transition-colors line-clamp-2">
                                {{ power.name }}
                            </h4>
                            <p class="text-[10px] text-gray-600">{{ power.pointsRequired }} pts</p>
                        </div>

                        <div class="absolute top-1 right-1">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    icon="fas fa-eye"
                                    @click.stop="router.push({ name: 'MyStudentPowerUpsShow', params: { uuid: power.uuid } })"
                                >
                                    {{ $trans("general.view") }}
                                </FloatingMenuItem>
                            </FloatingMenu>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Powerups Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-lock-open text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Available Powerups</h2>
                    <p class="text-gray-600 text-sm">Powerups {{ studentName }} can earn</p>
                </div>
            </div>

            <div v-if="availablePowerups.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-lock text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Available Powerups</h3>
                <p class="text-sm text-gray-500">Check back later for new powerups</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="power in availablePowerups"
                    :key="power.uuid"
                    @click="router.push({ name: 'MyStudentPowerUpsShow', params: { uuid: power.uuid } })"
                    class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 hover:border-blue-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-star text-white text-sm"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-xs mb-0.5 group-hover:text-blue-700 transition-colors line-clamp-2">
                                {{ power.name }}
                            </h4>
                            <p class="text-[10px] text-gray-600">{{ power.pointsRequired }} pts</p>
                        </div>

                        <div class="absolute top-1 right-1">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    icon="fas fa-eye"
                                    @click.stop="router.push({ name: 'MyStudentPowerUpsShow', params: { uuid: power.uuid } })"
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
    powerups: {
        type: Array,
        required: true,
    },
    studentName: {
        type: String,
        default: "This student",
    },
})

const isEarned = (power) => {
    return power.isEarned === true || power.earned === true || power.status === "earned" || (power.studentPowerups && power.studentPowerups.length > 0)
}

const earnedPowerups = computed(() => {
    return props.powerups.filter((power) => isEarned(power))
})

const availablePowerups = computed(() => {
    return props.powerups.filter((power) => !isEarned(power))
})
</script>

