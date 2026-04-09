<template>
    <BaseLoader :is-loading="isLoading">
        <div class="grid w-full grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <template v-for="stat in state.stats">
                <div
                    class="dark:bg-dark-header overflow-hidden rounded-3xl bg-white p-0 shadow-lg ring-1 ring-slate-200/70"
                >
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 px-5 py-5 text-white">
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-white/10"></div>
                        <div class="relative flex items-start justify-between gap-4">
                            <div class="min-w-0">
                            <h3
                                    class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-300"
                            >
                                {{ stat.title }}
                            </h3>
                            <p
                                    class="mt-4 text-3xl font-semibold leading-none text-white sm:text-4xl"
                            >
                                {{ stat.count }}
                            </p>
                            <h6
                                    class="mt-4 text-xs font-medium uppercase tracking-[0.18em] text-slate-300"
                            >
                                {{ stat.secondaryCount }}
                                {{ stat.secondaryTitle }}
                            </h6>
                        </div>
                        <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 text-white shadow-inner"
                        >
                                <i
                                    :class="stat.icon"
                                    class="text-xl"
                                ></i>
                        </div>
                    </div>
                    </div>
                    <div class="flex items-center justify-between px-5 py-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                Status
                            </p>
                            <p class="mt-1 text-sm text-slate-600">
                                Snapshot from your current dashboard feed
                            </p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                            Live
                        </span>
                    </div>
                </div>
            </template>
        </div>
    </BaseLoader>
</template>

<script>
export default {
    name: "DashboardStat",
}
</script>

<script setup>
import { onMounted, reactive, ref } from "vue"
import { useStore } from "vuex"

const store = useStore()

const isLoading = ref(false)
const state = reactive({
    stats: [],
})

onMounted(async () => {
    isLoading.value = true

    await store
        .dispatch("dashboard/getStat")
        .then((response) => {
            isLoading.value = false
            Object.assign(state, {
                stats: response.stats,
            })
        })
        .catch((e) => {
            isLoading.value = false
        })
})
</script>
