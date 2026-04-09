<template>
    <div class="p-6 space-y-6">
        <!-- Page Title -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
        </div>


        <!-- ROW 1: Left = Announcements (fixed height). Right = Gamification (same height) -->
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
            <div class="xl:col-span-3">
                <AnnouncementsCard :style="{ height: topBlockHeight + 'px' }" class="w-full" />
            </div>

            <div class="xl:col-span-2">
                <GamificationCard 
                    :style="{ height: topBlockHeight + 'px' }" 
                    :gradient="{ from: 'from-amber-700', via: 'via-amber-500', to: 'to-yellow-400' }"
                    class="h-full" />

            </div>
        </div>

        <!-- ROW 2: Calendar (left) → Payment (right) -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="min-h-[400px]">
                <CalendarCard :events="calendarItems.events" :meetings="calendarItems.meetings" class="h-full" />
            </div>
            <div class="min-h-[400px]">
                <div
                    class="bg-white dark:bg-neutral-900 rounded-2xl p-0 shadow-lg ring-1 ring-black/5 dark:ring-white/10 h-full flex flex-col overflow-hidden">
                    <!-- Header -->
                    <div class="relative p-5 sm:p-6 bg-gradient-to-br from-green-800 to-green-400 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                                    <i class="fa-solid fa-credit-card text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs/4 opacity-90">Upcoming</p>
                                    <h2 class="text-lg sm:text-xl font-semibold">Payments</h2>
                                </div>
                            </div>
                        </div>
                        <div
                            class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-white/10">
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-5 flex-1 min-h-0 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fa-solid fa-clock text-4xl text-gray-400 dark:text-neutral-600 mb-4"></i>
                            <p class="text-lg font-semibold text-gray-600 dark:text-neutral-400">Coming Soon</p>
                            <p class="text-sm text-gray-500 dark:text-neutral-500 mt-2">Payment features will be available soon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import AnnouncementsCard from '../../Pages/Dashboard/Cards/AnnouncementsCard.vue'
import GamificationCard from '../../Pages/Dashboard/Cards/GamificationCard.vue'
import CalendarCard from '../../Pages/Dashboard/Cards/CalendarCard.vue'
import { storeKey, useStore } from 'vuex/dist/vuex.cjs.js'

const store = useStore(storeKey)
const calendarItems = ref({ events: [], meetings: [] })

onMounted(async () => {
    try {
        const calendarResponse = await store.dispatch('dashboard/getCalendarItems')
        calendarItems.value = calendarResponse || { events: [], meetings: [] }
    } catch (error) {
        console.error('Failed to fetch calendar items:', error)
        calendarItems.value = { events: [], meetings: [] }
    }
})

const now = computed(() =>
    new Date().toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })
)

/** Keep both sides perfectly aligned; tweak once and both update */
const topBlockHeight = 520 // px
</script>
