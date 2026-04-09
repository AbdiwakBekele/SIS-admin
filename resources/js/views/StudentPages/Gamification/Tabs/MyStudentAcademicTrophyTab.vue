<template>
    <div class="space-y-8">
        <!-- Earned Academic Trophies Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-trophy text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Earned Academic Trophies</h2>
                    <p class="text-gray-600 text-sm">Academic trophies you've successfully earned</p>
                </div>
            </div>

            <div v-if="earnedTrophies.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-trophy text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Earned Academic Trophies</h3>
                <p class="text-sm text-gray-500">Start earning trophies by excelling academically!</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="trophy in earnedTrophies"
                    :key="trophy.uuid"
                    @click="router.push({ name: 'MyStudentAcademicTrophyShow', params: { uuid: trophy.uuid } })"
                    class="group relative bg-gradient-to-br from-amber-50 to-yellow-50 rounded-lg border-2 border-amber-200 hover:border-amber-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="absolute top-1.5 right-1.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                            <i class="fa-solid fa-check-circle mr-0.5 text-[8px]"></i>
                            Earned
                        </span>
                    </div>

                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-lg flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-trophy text-white text-sm"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-xs mb-0.5 group-hover:text-amber-700 transition-colors line-clamp-2">
                                {{ trophy.name }}
                            </h4>
                            <p class="text-[10px] text-gray-600">{{ trophy.pointsRequired }} pts</p>
                        </div>

                        <div class="absolute top-1 right-1">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    icon="fas fa-eye"
                                    @click.stop="router.push({ name: 'MyStudentAcademicTrophyShow', params: { uuid: trophy.uuid } })"
                                >
                                    {{ $trans("general.view") }}
                                </FloatingMenuItem>
                            </FloatingMenu>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Academic Trophies Section -->
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-lock-open text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Available Academic Trophies</h2>
                    <p class="text-gray-600 text-sm">Academic trophies you can earn</p>
                </div>
            </div>

            <div v-if="availableTrophies.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-lock text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Available Academic Trophies</h3>
                <p class="text-sm text-gray-500">Check back later for new trophies</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <div
                    v-for="trophy in availableTrophies"
                    :key="trophy.uuid"
                    @click="router.push({ name: 'MyStudentAcademicTrophyShow', params: { uuid: trophy.uuid } })"
                    class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 hover:border-blue-400 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5"
                >
                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-unlock text-white text-sm"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-xs mb-0.5 group-hover:text-blue-700 transition-colors line-clamp-2">
                                {{ trophy.name }}
                            </h4>
                            <p class="text-[10px] text-gray-600">{{ trophy.pointsRequired }} pts</p>
                        </div>

                        <div class="absolute top-1 right-1">
                            <FloatingMenu>
                                <FloatingMenuItem
                                    icon="fas fa-eye"
                                    @click.stop="router.push({ name: 'MyStudentAcademicTrophyShow', params: { uuid: trophy.uuid } })"
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
    academicTrophies: {
        type: Array,
        required: true,
    },
})

const isEarned = (trophy) => {
    return trophy.isEarned === true || trophy.earned === true || trophy.status === "earned" || (trophy.studentAcademicTrophies && trophy.studentAcademicTrophies.length > 0)
}

const earnedTrophies = computed(() => {
    return props.academicTrophies.filter((trophy) => isEarned(trophy))
})

const availableTrophies = computed(() => {
    return props.academicTrophies.filter((trophy) => !isEarned(trophy))
})
</script>
