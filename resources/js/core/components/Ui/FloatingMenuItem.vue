<template>
    <div
        :class="{
            'py-1': separator,
        }"
    >
        <component
            :is="getComponent"
            :href="href"
            :target="$attrs.target"
            class="group flex items-center px-4 py-2 text-sm"
            :class="{
                'dark:bg-dark-body bg-gray-100 text-gray-900 dark:text-gray-400':
                    active,
                'text-gray-700 dark:text-gray-300': !active,
                'cursor-pointer': as === 'span',
            }"
        >
            <i
                v-if="icon"
                :class="[icon, 'mr-3 transition-colors', iconColorClass]"
                aria-hidden="true"
            ></i>
            <slot></slot>
        </component>
    </div>
</template>

<script>
export default {
    name: "FloatingMenuItem",
}
</script>

<script setup>
import { computed } from "vue"
import { useRouter } from "vue-router"
import { MenuItem } from "@headlessui/vue"

const props = defineProps({
    separator: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: String,
        default: "",
    },
    as: {
        type: String,
        default: "span", // span|link|route
    },
    href: {
        type: String,
        default: "#",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    active: {
        type: Boolean,
        default: false,
    },
})

const getComponent = computed(() => {
    if (props.as === "route") {
        return "router-link"
    } else if (props.as === "link") {
        return "a"
    } else {
        return "span"
    }
})

const iconColorClass = computed(() => {
    if (!props.icon) {
        return "text-gray-400 group-hover:text-gray-500"
    }

    if (
        props.icon.includes("fa-eye") ||
        props.icon.includes("fa-arrow-circle-right")
    ) {
        return "text-blue-500 group-hover:text-blue-600"
    }

    if (props.icon.includes("fa-edit")) {
        return "text-slate-600 group-hover:text-slate-700"
    }

    if (props.icon.includes("fa-copy")) {
        return "text-green-500 group-hover:text-green-600"
    }

    if (props.icon.includes("fa-trash")) {
        return "text-red-500 group-hover:text-red-600"
    }

    return "text-gray-400 group-hover:text-gray-500"
})
</script>
