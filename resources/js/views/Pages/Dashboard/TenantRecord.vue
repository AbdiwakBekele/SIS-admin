<template>
    <div class="overflow-hidden rounded-[28px] bg-white shadow-lg ring-1 ring-slate-200/70 dark:bg-neutral-800 dark:ring-white/10">
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-800 via-indigo-600 to-sky-400 px-6 py-5 text-white">
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-white/10"></div>
            <div class="relative flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/20 backdrop-blur">
                        <i class="fa-solid fa-building text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-indigo-100/85">
                            Recent
                        </p>
                        <h2 class="mt-1 text-xl font-semibold">
                            {{ $trans("tenant.tenant") }}
                        </h2>
                    </div>
                </div>
                <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">
                    {{ records.length }} items
                </span>
            </div>
        </div>

        <div class="max-h-[420px] space-y-3 overflow-y-auto p-4">
            <template v-if="records.length">
                <div
                    v-for="record in records"
                    :key="record.uuid"
                    class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 transition hover:bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900/40 dark:hover:bg-neutral-900/70"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ record.domain }}
                                </p>
                                <BaseBadge
                                    v-if="record.isTrial"
                                    :label="$trans('tenant.props.trial')"
                                    design="danger"
                                />
                            </div>
                            <p class="mt-1 truncate text-xs text-slate-500 dark:text-neutral-400">
                                {{ record.email }}
                            </p>
                        </div>
                        <BaseBadge
                            :label="record.status.label"
                            :design="record.status.color"
                        />
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div class="rounded-xl bg-white px-3 py-2 dark:bg-neutral-800">
                            <p class="uppercase tracking-[0.16em] text-slate-400">
                                Plan
                            </p>
                            <p class="mt-1 font-semibold text-slate-700 dark:text-neutral-200">
                                {{ record.plan.name }} ({{ record.plan.code }})
                            </p>
                        </div>
                        <div class="rounded-xl bg-white px-3 py-2 dark:bg-neutral-800">
                            <p class="uppercase tracking-[0.16em] text-slate-400">
                                Expiry
                            </p>
                            <p class="mt-1 font-semibold text-slate-700 dark:text-neutral-200">
                                {{ record.expiryDate.formatted }}
                            </p>
                        </div>
                    </div>
                </div>
            </template>

            <div v-else class="flex flex-col items-center justify-center gap-3 py-10 text-slate-400">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-neutral-700">
                    <i class="fa-solid fa-building text-lg"></i>
                </div>
                <p class="italic">{{ $trans("general.errors.record_not_found") }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DashboardTenantRecord",
}
</script>

<script setup>
import { inject } from "vue"

const $trans = inject("$trans")

defineProps({
    records: {
        type: Array,
        default: () => [],
    },
})
</script>
