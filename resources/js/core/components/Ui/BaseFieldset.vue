<template>
    <fieldset
        class="rounded-md border border-solid border-gray-300 py-3 dark:border-gray-700"
        :class="{
            'px-3': !noPadding,
            'px-0': noPadding,
        }"
    >
        <legend
            class="ml-2 rounded border border-gray-300 p-2 text-sm font-semibold text-gray-800 dark:border-gray-700 dark:text-gray-300"
        >
            <slot name="legend"></slot>
        </legend>
        <slot></slot>
    </fieldset>
</template>

<script>
export default {
    name: "BaseFieldset",
}
</script>

<script setup>
const props = defineProps({
    noPadding: {
        type: Boolean,
        default: false,
    },
})
</script>
