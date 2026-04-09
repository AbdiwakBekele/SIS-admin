<template>
    <component
        :is="as"
        type="button"
        class="relative z-0 inline-flex items-center justify-start overflow-hidden font-medium transition-all duration-300 before:absolute before:bottom-0 before:left-0 before:-z-10 before:h-full before:w-0 before:transition-all before:duration-300 before:content-[''] after:absolute after:bottom-0 after:left-0 after:-z-20 after:h-full after:w-full after:content-[''] hover:before:w-full"
        :class="{
            'cursor-not-allowed': disabled,
            'focus:outline-none focus:ring-2 focus:ring-offset-2': focusRing,
            'border border-transparent':
                design !== 'white' && design !== 'secondary',
            'flex w-full justify-center': block === true,
            'inline-flex items-center': block === false,
            'px-2.5 py-1.5 text-xs': size === 'xs',
            'px-3 py-2 text-sm leading-4': size === 'sm',
            'px-4 py-2 text-sm': size === 'md',
            'px-4 py-2 text-base': size === 'lg',
            'px-6 py-3 text-base': size === 'xl',
            'rounded-lg': !roundedFull,
            'rounded-full': roundedFull,
            'border border-blue-200 dark:border-blue-400 text-gray-600 before:bg-gray-100 after:bg-white hover:bg-blue-50 hover:text-blue-700 focus:ring-blue-300 dark:text-gray-400 dark:before:bg-neutral-700 dark:hover:bg-blue-900/10 dark:hover:text-blue-200':
                design === 'white',
            'after:bg-gradient-to-br after:from-blue-700 after:via-blue-600 after:to-blue-500 before:bg-gradient-to-br before:from-blue-800 before:via-blue-700 before:to-blue-600 text-white focus:ring-blue-600 dark:focus:ring-blue-600 dark:text-white dark:before:from-blue-800 dark:before:via-blue-700 dark:before:to-blue-600 dark:after:from-blue-700 dark:after:via-blue-600 dark:after:to-blue-500':
                design === 'primary',
            'after:bg-secondary before:bg-dark-secondary dark:after:bg-dark-body text-primary focus:ring-secondary dark:focus:ring-dark-body dark:text-gray-400 dark:before:bg-neutral-700':
                design === 'secondary',
            'after:bg-green-600 before:bg-green-700 text-white focus:ring-green-500 dark:focus:ring-green-500 dark:text-white dark:before:bg-green-700 dark:after:bg-green-600':
                design === 'success',
            'text-white before:bg-[#1f4494] after:bg-[#3b5998] focus:ring-[#3b5998]':
                design === 'facebook',
            'after:bg-info before:bg-dark-info text-secondary focus:ring-info':
                design === 'info',
            'after:bg-warning before:bg-dark-warning text-secondary focus:ring-warning':
                design === 'warning',
            'after:bg-danger before:bg-dark-danger text-secondary focus:ring-danger':
                design === 'danger' || design === 'error',
        }"
    >
        <slot></slot>
    </component>
</template>

<script>
export default {
    name: "BaseButton",
}
</script>

<script setup>
const props = defineProps({
    as: {
        type: String,
        default: "button",
    },
    size: {
        type: String,
        validator(value) {
            return ["xs", "sm", "md", "lg", "xl"].includes(value)
        },
        default: "md",
    },
    roundedFull: Boolean,
    focusRing: Boolean,
    block: Boolean,
    design: {
        type: String,
        validator(value) {
            return [
                "primary",
                "secondary",
                "info",
                "success",
                "warning",
                "danger",
                "error",
                "white",
                "facebook",
            ].includes(value)
        },
        default: "secondary",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
})
</script>
