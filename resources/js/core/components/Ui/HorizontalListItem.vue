<template>
    <div class="flex items-center" v-if="value">
        <span
            class="text-gray-700 dark:text-gray-500 text-sm font-medium w-3/4 sm:w-1/2 lg:w-1/3"
        >
            {{ label }}
        </span>
        <div class="text-gray-500 dark:text-gray-300">
            {{ value }}
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    label: {
        type: String,
        default: "",
    },
    value: {
        type: String,
        default: "",
    },
})
</script>
