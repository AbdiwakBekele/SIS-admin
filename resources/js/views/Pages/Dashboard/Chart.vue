<template>
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="overflow-hidden rounded-[28px] bg-white shadow-lg ring-1 ring-slate-200/70 dark:bg-neutral-800 dark:ring-white/10">
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-800 to-emerald-500 px-6 py-5 text-white">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-white/10"></div>
                <div class="relative flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/20 backdrop-blur">
                            <i class="fa-solid fa-chart-pie text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-100/85">
                                Status
                            </p>
                            <h2 class="mt-1 text-xl font-semibold">{{ $trans("dashboard.status_wise_tenant") }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <Doughnut
                    :data="state.statusWiseData"
                    :options="options"
                    v-if="state.statusWiseData?.labels"
                />
            </div>
        </div>

        <div class="overflow-hidden rounded-[28px] bg-white shadow-lg ring-1 ring-slate-200/70 dark:bg-neutral-800 dark:ring-white/10">
            <div class="relative overflow-hidden bg-gradient-to-br from-amber-700 to-amber-400 px-6 py-5 text-white">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-white/10"></div>
                <div class="relative flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/20 backdrop-blur">
                            <i class="fa-solid fa-layer-group text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-100/85">
                                Plans
                            </p>
                            <h2 class="mt-1 text-xl font-semibold">{{ $trans("dashboard.plan_wise_tenant") }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <Doughnut
                    :data="state.planWiseData"
                    :options="options"
                    v-if="state.planWiseData?.labels"
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DashboardChart",
}
</script>

<script setup>
import { onMounted, reactive, ref } from "vue"
import { useStore } from "vuex"
import { Doughnut } from "vue-chartjs"
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js"

ChartJS.register(ArcElement, Tooltip, Legend)

const store = useStore()

const options = ref({
    responsive: true,
})

const isLoading = ref(false)
const state = reactive({})

onMounted(async () => {
    isLoading.value = true

    await store
        .dispatch("dashboard/getChart")
        .then((response) => {
            isLoading.value = false
            Object.assign(state, response)
        })
        .catch((e) => {
            isLoading.value = false
        })
})
</script>
