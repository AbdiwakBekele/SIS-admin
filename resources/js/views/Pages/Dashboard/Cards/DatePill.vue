<template>
    <div
        class="inline-flex items-center gap-2 rounded-full bg-white/60 px-3 py-1.5 text-xs font-medium shadow-sm ring-1 ring-inset ring-gray-200/70 backdrop-blur transition dark:bg-neutral-900/60 dark:ring-white/10"
        :title="fullTooltip"
    >
        <span class="flex h-7 w-7 items-center justify-center rounded-full text-slate-500 dark:text-gray-300">
            <i class="fa-solid fa-calendar-days"></i>
        </span>
        <span class="text-[14px] text-slate-700 dark:text-gray-200">
            Today
        </span>
        <span
            class="rounded-full bg-gradient-to-r from-blue-300/15 to-blue-500/25 px-5 py-1 text-[14px] font-semibold text-blue-700 ring-1 ring-blue-400/20 dark:text-blue-300"
        >
            {{ formattedDate }}
        </span>
    </div>
</template>

<script setup>
import { computed } from "vue"

const props = defineProps({
    date: {
        type: Date,
        default: () => new Date(),
    },
})

const formattedDate = computed(() =>
    props.date.toLocaleDateString(undefined, {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric",
    })
)

const fullTooltip = computed(() =>
    props.date.toLocaleString(undefined, {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    })
)
</script>
