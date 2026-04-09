<template>
    <div class="space-y-6 p-6">
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-8">
            <div class="xl:col-span-2">
                <div class="flex min-h-full items-center">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Dashboard
                    </h1>
                </div>
            </div>

            <div
                v-for="(stat, index) in topStats"
                :key="`${stat.title}-${index}`"
                class="xl:col-span-2"
            >
                <div
                    class="overflow-hidden rounded-2xl text-white shadow-lg"
                    :class="stat.gradient"
                >
                    <div class="flex min-h-[108px] items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white/85">
                                {{ stat.title }}
                            </p>
                            <p class="mt-2 text-3xl font-bold leading-none">
                                {{ stat.count }}
                            </p>
                            <p v-if="stat.secondary" class="mt-2 text-xs text-white/80">
                                {{ stat.secondary }}
                            </p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/20 backdrop-blur">
                            <i :class="stat.icon" class="text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
            <div class="xl:col-span-2">
                <DashboardAgenda />
            </div>
            <div class="xl:col-span-3">
                <DashboardChart />
            </div>
        </div>

        <DashboardRecord />
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue"
import { useStore } from "vuex"
import DashboardAgenda from "./Agenda.vue"
import DashboardChart from "./Chart.vue"
import DashboardRecord from "./Record.vue"

const store = useStore()
const stats = ref([])

const gradients = [
    "bg-gradient-to-br from-amber-700 to-yellow-400",
    "bg-gradient-to-br from-green-800 to-green-400",
    "bg-gradient-to-br from-cyan-700 to-cyan-400",
    "bg-gradient-to-br from-orange-800 to-orange-400",
]

const fallbackIcons = [
    "fa-solid fa-building",
    "fa-solid fa-file-invoice-dollar",
    "fa-solid fa-chart-line",
    "fa-solid fa-circle-check",
]

const topStats = computed(() =>
    stats.value.slice(0, 4).map((stat, index) => ({
        ...stat,
        gradient: gradients[index] || gradients[gradients.length - 1],
        icon: stat.icon || fallbackIcons[index] || "fa-solid fa-chart-column",
        secondary:
            stat.secondaryTitle || stat.secondaryCount
                ? `${stat.secondaryCount ?? ""} ${stat.secondaryTitle ?? ""}`.trim()
                : "",
    }))
)

onMounted(async () => {
    try {
        const response = await store.dispatch("dashboard/getStat")
        stats.value = response?.stats || []
    } catch (error) {
        stats.value = []
    }
})
</script>
