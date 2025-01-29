<template>
    <div
        class="cursor-pointer"
        :class="{
            'text-gray-900 dark:text-gray-400 rounded-none group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-dark-body py-2 px-4 text-center text-sm font-medium hover:bg-gray-50 focus:z-10':
                first,
            'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover-text-gray-600 group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-dark-body py-2 px-4 text-center text-sm font-medium hover:bg-gray-50 focus:z-10':
                !first && !last,
            'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover-text-gray-600 rounded-none group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-dark-body py-2 px-4 text-center text-sm font-medium hover:bg-gray-50 focus:z-10':
                last,
        }"
        @click="$emit('click')"
    >
        <span>{{ label }}</span>
        <span
            aria-hidden="true"
            class="absolute inset-x-0 bottom-0 h-0.5"
            :class="{
                'bg-transparent': !isActive,
                'bg-primary dark:bg-gray-400': isActive,
            }"
        ></span>
    </div>
</template>

<script setup>
const emit = defineEmits(["click"])

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    first: {
        type: Boolean,
        default: false,
    },
    last: {
        type: Boolean,
        default: false,
    },
    isActive: {
        type: Boolean,
        default: false,
    },
})
</script>
