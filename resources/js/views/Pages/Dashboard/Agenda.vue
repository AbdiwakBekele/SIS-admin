<template>
    <div class="overflow-hidden rounded-[28px] bg-white shadow-lg ring-1 ring-slate-200/70 dark:bg-neutral-800 dark:ring-white/10">
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-700 to-blue-500 px-6 py-5 text-white">
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-white/10"></div>
            <div class="relative flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/20 backdrop-blur">
                        <i class="fa-solid fa-briefcase text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100/85">
                            Productivity
                        </p>
                        <h2 class="mt-1 text-xl font-semibold">
                            {{ $trans("general.greeting_message", { name: user }) }}
                        </h2>
                    </div>
                </div>
                <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">
                    {{ moment().format("ddd, MMM D") }}
                </span>
            </div>
        </div>

        <div class="scroller-thin-y scroller-hidden max-h-[420px] space-y-3 p-4">
            <template v-for="agenda in agendas">
                <div
                    class="rounded-2xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-gray-700 dark:hover:bg-neutral-700"
                >
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div
                                class="rounded-2xl p-3"
                                :class="[agenda.color]"
                            >
                                <i
                                    :class="agenda.icon"
                                    class="flex h-6 w-6 items-center justify-center text-white"
                                ></i>
                            </div>
                        </div>
                        <div class="ml-5 flex w-0 flex-1 items-center">
                            <div>
                                <p class="dark:text-gray-400">
                                    {{ agenda.title }}
                                </p>
                                <p class="text-sm dark:text-gray-500">
                                    {{ agenda.date.formatted }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <div class="p-2" v-if="agendas.length == 0">
                <BaseAlert size="xs" design="info">{{
                    $trans("utility.todo.no_pending_todo")
                }}</BaseAlert>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DashboardAgenda",
}
</script>

<script setup>
import { onMounted, inject, reactive, ref } from "vue"
import { useStore } from "vuex"
import { getAuthUser } from "@core/helpers/action"

const store = useStore()

const moment = inject("moment")

const user = getAuthUser("profile.name")

const isLoading = ref(false)
const agendas = reactive([])

onMounted(async () => {
    isLoading.value = true

    await store
        .dispatch("dashboard/getAgenda")
        .then((response) => {
            isLoading.value = false
            Object.assign(agendas, response)
        })
        .catch((e) => {
            isLoading.value = false
        })
})
</script>
