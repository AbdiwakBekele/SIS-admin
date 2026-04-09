<template>
    <tr
        class="dark:hover:bg-dark-body data-row"
        :class="[
            {
                'hidden md:table-row': !noResponsive,
                'bg-blue-100 font-semibold dark:bg-neutral-600': isHeading,
            },
            $attrs.class
        ]"
        @dblclick="$emit('doubleClick')"
    >
        <slot></slot>
    </tr>

    <div
        v-if="!noResponsive"
        class="dark:bg-dark-header dark:hover:bg-dark-body table-row md:hidden"
        :class="{
            'space-y-2': spacing,
            'bg-blue-100 font-semibold dark:bg-neutral-600': isHeading,
        }"
        @dblclick="$emit('doubleClick')"
    >
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "DataRow",
}
</script>

<script setup>
import { provide, computed } from "vue"

const props = defineProps({
    noResponsive: {
        type: Boolean,
        default: false,
    },
    isHeading: {
        type: Boolean,
        default: false,
    },
    spacing: {
        type: Boolean,
        default: true,
    },
    textColor: {
        type: String,
        default: "",
    },
})

provide(
    "TextColor",
    computed(() => props.textColor)
)

defineEmits(["doubleClick"])
</script>

<style>
/* Alternating stripes (light mode) using nth-child(of ...) so only data rows are counted */
tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        odd of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(252 253 255) !important; /* custom very light */
}

tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        even of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(222 235 255) !important; /* custom softer blue */
}

/* Alternating stripes (dark mode) */
.dark
    tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        odd of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(18 24 37) !important; /* slightly lighter dark */
}

.dark
    tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        even of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(59 130 246 / 0.25) !important; /* blue-500 @ 25% */
}
</style>