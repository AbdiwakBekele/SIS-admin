<template>
    <div class="text-sm">
        <a
            v-if="to"
            @click="router.push({ name: to })"
            class="cursor-pointer font-medium text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-200 dark:focus:text-blue-200"
        >
            <slot></slot>
        </a>
        <a
            v-else-if="route"
            @click="router.push(route)"
            class="cursor-pointer font-medium text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-200 dark:focus:text-blue-200"
        >
            <slot></slot>
        </a>
        <a
            v-else-if="href"
            :href="href"
            class="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-200 dark:focus:text-blue-200"
        >
            <slot></slot>
        </a>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router"

const router = useRouter()

const props = defineProps({
    href: {
        type: String,
        default: "#",
    },
    to: {
        type: String,
        default: null,
    },
    route: {
        type: Object,
        default: {},
    },
})
</script>
