<template>
    <div class="flex items-center justify-between px-2 py-3 sm:rounded-bl-2xl sm:rounded-br-2xl" :class="{
        'bg-white/90 dark:bg-neutral-900/90 border-t border-gray-200 dark:border-neutral-800 backdrop-blur': !transparent,
    }" v-if="meta.from && meta.to">
        <!-- Mobile: Prev / Next -->
        <div class="flex flex-1 justify-between text-gray-700 dark:text-gray-300 sm:hidden" v-if="meta.lastPage >= 1">
            <div>
                <span v-if="currentPage <= 1" class="relative inline-flex items-center rounded-full px-4 py-2 text-sm font-medium 
                 ring-1 ring-black/5 dark:ring-white/10 bg-gray-100 dark:bg-neutral-800
                 text-gray-400 cursor-not-allowed">
                    {{ $trans("pagination.previous") }}
                </span>
                <a v-else @click="updateCurrentPage(currentPage - 1)" href="#" class="relative inline-flex items-center rounded-full px-4 py-2 text-sm font-medium 
                 ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-neutral-900
                 hover:bg-gradient-to-r hover:from-blue-50 hover:via-blue-50 hover:to-blue-50 transition">
                    {{ $trans("pagination.previous") }}
                </a>
            </div>

            <div>
                <span v-if="currentPage >= meta.lastPage" class="relative ml-3 inline-flex items-center rounded-full px-4 py-2 text-sm font-medium 
                 ring-1 ring-black/5 dark:ring-white/10 bg-gray-100 dark:bg-neutral-800
                 text-gray-400 cursor-not-allowed">
                    {{ $trans("pagination.next") }}
                </span>
                <a v-else @click="updateCurrentPage(+currentPage + 1)" href="#" class="relative ml-3 inline-flex items-center rounded-full px-4 py-2 text-sm font-medium 
                 ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-neutral-900
                 hover:bg-gradient-to-r hover:from-blue-50 hover:via-blue-50 hover:to-blue-50 transition">
                    {{ $trans("pagination.next") }}
                </a>
            </div>
        </div>

        <!-- Desktop -->
        <div class="hidden text-gray-700 dark:text-gray-300 sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm" v-if="meta.from && meta.to">
                    {{
                        $trans("pagination.showing_result", {
                            from: meta.from,
                            to: meta.to,
                            total: meta.total,
                        })
                    }}
                </p>
            </div>

            <div class="flex items-center justify-between space-x-4">
                <!-- Per-page dropdown (unchanged logic, refreshed styles come from your Dropdown components) -->
                <DropdownButton direction="up" v-if="paginations.length && !cardView"
                    :label="$trans('pagination.list_per_page', { attribute: perPage })"
                    class="!ring-1 !ring-black/5 dark:!ring-white/10 !rounded-full !bg-white dark:!bg-neutral-900">
                    <div v-for="pagination in paginations" :key="pagination">
                        <template v-if="pagination != perPage">
                            <DropdownItem as="span" @click="updatePerPage(pagination)">
                                {{ $trans("pagination.list_per_page", { attribute: pagination }) }}
                            </DropdownItem>
                        </template>
                    </div>
                </DropdownButton>

                <div v-if="meta.lastPage >= 1">
                    <nav class="relative z-0 inline-flex items-center gap-2" aria-label="Pagination">
                        <!-- Prev -->
                        <span v-if="currentPage <= 1" class="relative inline-flex items-center justify-center h-9 w-9 rounded-full
                     ring-1 ring-black/5 dark:ring-white/10 bg-gray-100 dark:bg-neutral-800
                     text-gray-400 cursor-not-allowed">
                            <span class="sr-only">{{ $trans("pagination.previous") }}</span>
                            <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                        </span>
                        <a v-else @click="updateCurrentPage(currentPage - 1)" href="#" class="relative inline-flex items-center justify-center h-9 w-9 rounded-full
                     ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-neutral-900
                     hover:bg-gradient-to-r hover:from-blue-50 hover:via-blue-50 hover:to-blue-50 transition">
                            <span class="sr-only">{{ $trans("pagination.previous") }}</span>
                            <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                        </a>

                        <!-- Pages -->
                        <template v-for="(page, key) in pageRange">
                            <a href="#" v-if="page != '...'" @click="updateCurrentPage(page)" aria-current="page" class="relative inline-flex items-center justify-center h-9 min-w-9 px-3 rounded-full text-sm font-medium
                       ring-1 ring-black/5 dark:ring-white/10 transition" :class="{
                        'bg-gradient-to-r from-blue-600 via-blue-500 to-blue-400 text-white shadow':
                            page == currentPage,
                        'bg-white dark:bg-neutral-900 text-gray-700 dark:text-gray-200 hover:bg-gradient-to-r hover:from-blue-50 hover:via-blue-50 hover:to-blue-50':
                            page != currentPage,
                    }">
                                {{ page }}
                            </a>

                            <span v-else aria-current="page" class="relative inline-flex items-center justify-center h-9 min-w-9 px-3 rounded-full text-sm font-medium
                       ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-neutral-900 text-gray-500">
                                {{ page }}
                            </span>
                        </template>

                        <!-- Next -->
                        <span v-if="currentPage >= meta.lastPage" class="relative inline-flex items-center justify-center h-9 w-9 rounded-full
                     ring-1 ring-black/5 dark:ring-white/10 bg-gray-100 dark:bg-neutral-800
                     text-gray-400 cursor-not-allowed">
                            <span class="sr-only">{{ $trans("pagination.next") }}</span>
                            <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                        </span>
                        <a v-else @click="updateCurrentPage(+currentPage + 1)" href="#" class="relative inline-flex items-center justify-center h-9 w-9 rounded-full
                     ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-neutral-900
                     hover:bg-gradient-to-r hover:from-blue-50 hover:via-blue-50 hover:to-blue-50 transition">
                            <span class="sr-only">{{ $trans("pagination.next") }}</span>
                            <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>



<script setup>
import { ref, computed, watch } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid"

const route = useRoute()
const router = useRouter()
const store = useStore()

const emit = defineEmits(["refresh"])

const props = defineProps({
    meta: {
        type: Object,
        default: {},
    },
    limit: {
        type: Number,
        default: 1,
    },
    cardView: {
        type: Boolean,
        default: false,
    },
    transparent: {
        type: Boolean,
        default: false,
    },
})

const paginations = computed(
    () => store.getters["config/config"]("system.paginations") ?? []
)
const defaultPerPage = computed(
    () => store.getters["config/config"]("system.perPage") ?? []
)
const currentPage = computed(() => route.query.currentPage ?? 1)
const perPage = computed(() => route.query.perPage ?? defaultPerPage.value)

const pageRange = computed(() => {
    if (props.limit === -1) {
        return 0
    }
    if (props.limit === 0) {
        return props.meta.lastPage
    }
    var current = currentPage.value
    var last = props.meta.lastPage
    var delta = props.limit
    var left = current - delta
    var right = Number(current) + Number(delta) + 1
    var range = []
    var pages = []
    var l
    for (var i = 1; i <= last; i++) {
        if (i === 1 || i === last || (i >= left && i < right)) {
            range.push(i)
        }
    }
    range.forEach(function (i) {
        if (l) {
            if (i - l === 2) {
                pages.push(l + 1)
            } else if (i - l !== 1) {
                pages.push("...")
            }
        }
        pages.push(i)
        l = i
    })
    return pages
})

const updateCurrentPage = (page) => {
    if (page === "...") {
        return
    }

    if (page > props.meta.lastPage) {
        return
    }

    if (page < 1) {
        return
    }

    updateURL({ currentPage: page })
}

const updatePerPage = (limit) => {
    updateURL({ perPage: limit, currentPage: 1 })
}

const updateURL = async (query) => {
    await router.push({
        name: route.name,
        query: {
            ...route.query,
            ...query,
        },
    })

    emit("refresh")
}
</script>
