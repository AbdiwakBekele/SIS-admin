<template>
    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4">
        <dt class="text-sm font-medium text-gray-500" v-if="label">
            {{ label }}
        </dt>
        <dd
            class="mt-1 text-sm text-gray-900 dark:text-gray-400 sm:col-span-2 sm:mt-0"
            :class="{
                'text-right': align == 'right',
                truncate: truncate,
            }"
            v-if="html"
        >
            <div v-html="$slots.default?.()?.[0]?.children"></div>
        </dd>
        <dd
            class="mt-1 text-sm text-gray-900 dark:text-gray-400 sm:col-span-2 sm:mt-0"
            :class="{
                'text-right': align == 'right',
                truncate: truncate,
            }"
            v-if="dynamic"
        >
            <slot></slot>
        </dd>
        <dd
            class="mt-1 text-sm text-gray-900 dark:text-gray-400 sm:col-span-2 sm:mt-0"
            :class="{
                'text-right': align == 'right',
                truncate: truncate,
            }"
            v-else
        >
            <span
                v-html="nl2br($slots.default?.()?.[0]?.children || '')"
            ></span>
        </dd>
    </div>
</template>

<script>
export default {
    name: "ListItemView",
}
</script>

<script setup>
import { useSlots } from "vue"

const $slots = useSlots()

const props = defineProps({
    label: {
        type: String,
        default: "",
    },
    align: {
        type: String,
        default: "left",
    },
    truncate: {
        type: Boolean,
        default: true,
    },
    html: {
        type: Boolean,
        default: false,
    },
    dynamic: {
        type: Boolean,
        default: false,
    },
})

const nl2br = (str) => {
    if (typeof str !== "string") return ""
    return str.replace(/(\r\n|\n|\r)/gm, "<br>")
}
</script>
