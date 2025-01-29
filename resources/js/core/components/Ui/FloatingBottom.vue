<template>
    <div
        class="pointer-events-none fixed inset-x-0 bottom-0 sm:flex sm:justify-center sm:px-6 sm:pb-5 lg:px-8"
    >
        <div
            class="pointer-events-auto flex items-center justify-between gap-x-6 px-6 py-2.5 sm:rounded-xl sm:py-3 sm:pl-4 sm:pr-3.5"
            :class="{
                'bg-gray-900': design == 'default',
                'bg-success': design == 'success',
                'bg-danger': design == 'danger' || design == 'error',
                'bg-warning': design == 'warning',
                'bg-info': design == 'info',
            }"
        >
            <p class="text-sm leading-6 text-white">
                <span @click="handleClick" :class="{ 'cursor-pointer': link }">
                    <strong class="font-semibold">{{ title }}</strong
                    ><svg
                        viewBox="0 0 2 2"
                        class="mx-2 inline h-0.5 w-0.5 fill-current"
                        aria-hidden="true"
                    >
                        <circle cx="1" cy="1" r="1" />
                    </svg>
                    {{ description }}<span aria-hidden="true">&rarr;</span>
                    <div class="text-xs text-white" v-if="subTitle">
                        {{ subTitle }}
                    </div>
                </span>
            </p>
            <!-- <button type="button" class="-m-1.5 flex-none p-1.5">
          <span class="sr-only">Dismiss</span>
          <XMarkIcon class="h-5 w-5 text-white" aria-hidden="true" />
        </button> -->
        </div>
    </div>
</template>

<script setup>
import { XMarkIcon } from "@heroicons/vue/20/solid"

const props = defineProps({
    title: {
        type: String,
        default: null,
    },
    subTitle: {
        type: String,
        default: null,
    },
    description: {
        type: String,
        default: null,
    },
    link: {
        type: String,
        default: null,
    },
    design: {
        type: String,
        default: "default",
    },
})

const handleClick = () => {
    if (props.link) {
        window.location.href = props.link
    }
}
</script>
