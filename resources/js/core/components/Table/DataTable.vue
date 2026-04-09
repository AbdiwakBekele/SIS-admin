<template>
    <BaseCard no-padding no-content-padding v-if="meta.total">
        <!-- Rounded + shadowed wrapper -->
        <div class="rounded-xl overflow-hidden shadow-lg ring-1 ring-blue-300/50 dark:ring-white/10" :class="{
            'scroller-thin-x scroller-hidden scrollbar-track-transparent scrollbar-thumb-body dark:scrollbar-thumb-dm-body': scroll,
        }">
            <!-- Desktop -->
            <table
                class="hidden min-w-full md:table divide-y divide-blue-300 dark:divide-blue-300 bg-white dark:bg-neutral-900 border border-blue-300 dark:border-blue-300">
                <!-- Dark blue gradient header -->
                <thead class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-md">
                    <tr>
                        <template v-for="item in normalizedHeader">
                            <th v-if="isVisible(item)" scope="col"
                                class="sm:px-2 xl:px-4 py-4 text-left text-[15px] font-semibold uppercase tracking-wider"
                                :class="{
                                    'sticky-column bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500': stickyColumns.includes(item.key),
                                    'text-right': item.align == 'right',
                                    'text-center': item.align == 'center',
                                    'cursor-pointer select-none hover:bg-blue-500/80 transition-all duration-200': item.sortable,
                                }" @click="updateSort(item.key)">
                                <span class="inline-flex items-center gap-2">
                                    <span class="text-white font-semibold">{{ item.label }}</span>

                                    <!-- sort icons -->
                                    <span v-if="item.sortable"
                                        class="hidden xl:inline-block text-white/90">
                                        <template v-if="sort == item.key">
                                            <i class="fas fa-sort-amount-down-alt" v-if="!order || order == 'asc'"></i>
                                            <i class="fas fa-sort-amount-up-alt" v-if="order == 'desc'"></i>
                                        </template>
                                        <template v-else>
                                            <i class="fas fa-arrows-alt-v opacity-70"></i>
                                        </template>
                                    </span>
                                </span>

                                <!-- select all checkbox (unchanged logic) -->
                                <span v-if="item.key == 'selectAll' && !selected.global"
                                    class="ml-3 align-middle inline-flex">
                                    <BaseCheckbox name="selectAll" v-model="selected.all" @change="toggleSelectAll" />
                                </span>
                            </th>
                        </template>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody class="divide-y divide-blue-300 dark:divide-blue-300 striped-rows">
                    <!-- selection banner row -->
                    <DataRow v-if="selected.global || selected.items.length > 0" class="selection-banner">
                        <DataCell name="selectAll" :colspan="100">
                            <div class="flex items-center justify-center gap-3 text-sm">
                                <span>
                                    {{
                                        $trans("global.record_selected", {
                                            attribute: selected.global ? meta.total : selected.items.length,
                                        })
                                    }}
                                </span>
                                <slot name="selectionBannerActions"></slot>
                                <BaseButton
                                    design="white"
                                    size="sm"
                                    @click="toggleGlobalSelect"
                                >
                                    <span v-if="!selected.global">
                                        {{ $trans("global.select_all_records", { attribute: meta.total }) }}
                                    </span>
                                    <span v-else>
                                        {{ $trans("general.unselect_all_records") }}
                                    </span>
                                </BaseButton>
                            </div>
                        </DataCell>
                    </DataRow>

                    <!-- your rows -->
                    <slot></slot>

                    <!-- empty-state row -->
                    <tr v-if="meta.from === null || meta.to === null" class="empty-state-row">
                        <td colspan="100"
                            class="py-3 text-center text-sm text-gray-500 dark:text-gray-400 bg-white dark:bg-neutral-900">
                            {{ $trans("general.errors.record_not_found") }}
                        </td>
                    </tr>
                </tbody>

                <!-- Footer with dark blue gradient to match header -->
                <tfoot v-if="meta.hasFooter"
                    class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-inner dark:bg-neutral-800/60">
                    <tr>
                        <template v-for="item in normalizedHeader">
                            <td v-if="isVisible(item)"
                                class="sm:px-2 xl:px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-white dark:text-gray-300 border-t border-r border-blue-700 dark:border-blue-500 last:border-r-0"
                                :class="{
                                    'text-right': getFooterData(item.key, 'align') == 'right',
                                    'text-center': getFooterData(item.key, 'align') == 'center',
                                }">
                                {{ getFooterData(item.key) }}
                            </td>
                        </template>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Mobile -->
        <table
            class="md:hidden min-w-full divide-y divide-blue-300 dark:divide-blue-300 rounded-xl overflow-hidden shadow-lg ring-1 ring-blue-300/50 dark:ring-white/10">
            <thead class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-md">
                <tr>
                    <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-white border-b border-blue-600 dark:border-blue-500">
                        {{ $trans("global.list") }}
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y-2 divide-blue-400 dark:divide-blue-300 bg-white dark:bg-neutral-900 striped-rows">
                <tr v-if="selected.global || selected.items.length > 0">
                    <td colspan="100" class="px-4 py-3">
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <span>
                                {{
                                    $trans("global.record_selected", {
                                        attribute: selected.global ? meta.total : selected.items.length,
                                    })
                                }}
                            </span>
                            <slot name="selectionBannerActions"></slot>
                            <BaseButton
                                design="white"
                                size="sm"
                                @click="toggleGlobalSelect"
                            >
                                <span v-if="!selected.global">
                                    {{ $trans("global.select_all_records", { attribute: meta.total }) }}
                                </span>
                                <span v-else>
                                    {{ $trans("general.unselect_all_records") }}
                                </span>
                            </BaseButton>
                        </div>
                    </td>
                </tr>

                <!-- your mobile rows -->
                <slot></slot>

                <tr v-if="meta.from === null || meta.to === null" class="empty-state-row">
                    <td colspan="100" class="px-4 py-3 text-center text-sm text-gray-500">
                        {{ $trans("general.errors.record_not_found") }}
                    </td>
                </tr>
            </tbody>
        </table>

        <slot name="actions"></slot>

        <Pagination :meta="meta" @refresh="emit('refresh')"></Pagination>
    </BaseCard>

    <!-- Zero state -->
    <BaseCard v-if="meta.total === 0" no-padding no-content-padding>
        <div class="flex flex-col items-center justify-center space-y-4 py-10 text-gray-500">
            <i class="fas fa-check-circle fa-4x text-blue-600"></i>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">You're all set!</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">There's nothing here to view at the moment. Thank you!</p>
            <div>
                <slot name="actionButton"></slot>
            </div>
        </div>
    </BaseCard>
</template>



<script>
export default {
    name: "DataTable",
}
</script>

<script setup>
import { useSlots, provide, computed, watch, onMounted, ref } from "vue"
import { useRoute, useRouter } from "vue-router"
import BaseButton from "@core/components/Forms/BaseButton.vue"

const route = useRoute()
const router = useRouter()
const slots = useSlots()

const emit = defineEmits(["refresh", "toggleSelectAll", "toggleGlobalSelect"])

const props = defineProps({
    header: {
        type: Array,
        default: [],
    },
    footer: {
        type: Array,
        default: [],
    },
    stickyColumns: {
        type: Array,
        default: [],
    },
    meta: {
        type: Object,
        default: {
            hasFooter: false,
            total: 0,
        },
    },
    module: {
        type: String,
        default: "",
    },
    scroll: {
        type: Boolean,
        default: true,
    },
    selected: {
        type: Object,
        default: {
            pageItems: [],
            items: [],
            all: false,
            global: false,
        },
    },
    verticalAlign: {
        type: String,
        default: "top",
        validator(value) {
            return ["top", "bottom", "middle"].includes(value)
        },
    },
})

const sort = ref(null)
const order = ref(null)

const normalizedHeader = computed(() => {
    if (props.header.some((item) => item.key === "action")) {
        return props.header
    }

    return [
        ...props.header,
        {
            key: "action",
            label: "Action",
            visibility: true,
            align: "center",
        },
    ]
})

provide(
    "Header",
    computed(() => normalizedHeader.value)
)
provide("SubHeader", [])
provide(
    "VerticalAlign",
    computed(() => props.verticalAlign)
)

onMounted(() => {
    sort.value = route.query.sort ?? null
    order.value = route.query.order ?? null
})

const toggleSelectAll = ($event) => {
    emit("toggleSelectAll", $event.target.checked)
}

const toggleGlobalSelect = () => {
    emit("toggleGlobalSelect")
}

const getFooterData = (key, value = "label") => {
    let column = props.footer.find((o) => o.key == key)

    if (column === undefined) {
        return
    }

    return column.hasOwnProperty(value) ? column[value] : ""
}

const isVisible = (item) => {
    if (["action", "selectAll"].includes(item.key)) {
        return true
    }

    if (item.hasOwnProperty("printable") && item.printable === true) {
        return false
    }

    return item.visibility
}

const updateSort = (key) => {
    let column = normalizedHeader.value.find((o) => o.key == key)

    if (column === undefined) {
        return
    }

    if (!column.hasOwnProperty("sortable") || column.sortable !== true) {
        return
    }

    sort.value = key

    if (route.query.sort != key) {
        order.value = "asc"
        updateURL({ sort: key, order: "asc" })
    } else {
        if (route.query.order === undefined || route.query.order == "desc") {
            order.value = "asc"
            updateURL({ sort: key, order: "asc" })
        } else {
            order.value = "desc"
            updateURL({ sort: key, order: "desc" })
        }
    }
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

<style>
/* Base styles for all rows */
tbody.striped-rows > tr {
    background-color: white !important;
}

/* Dark mode base */
.dark tbody.striped-rows > tr {
    background-color: rgb(30 41 59) !important;
}

/* Hover glow effect for regular data rows */
tbody.striped-rows > tr:not(.selection-banner):not(.empty-state-row):hover {
    background-color: rgb(219 234 254 / 0.7) !important; /* light primary tint */
    box-shadow:
        0 0 0 1px rgba(59, 130, 246, 0.35),
        0 10px 18px -6px rgba(77, 130, 216, 0.28);
    transform: translateY(-1px);
    transition:
        background-color 150ms ease,
        box-shadow 150ms ease,
        transform 150ms ease;
}

.dark tbody.striped-rows > tr:not(.selection-banner):not(.empty-state-row):hover {
    background-color: rgb(30 64 175 / 0.55) !important; /* darkened primary tint */
    box-shadow:
        0 0 0 1px rgba(191, 219, 254, 0.35),
        0 12px 18px -6px rgba(59, 130, 246, 0.35);
    transform: translateY(-1px);
}

/* Selection banner styling - override alternating pattern */
tbody.striped-rows > tr.selection-banner {
    background-color: rgb(219 234 254) !important;
}

.dark tbody.striped-rows > tr.selection-banner {
    background-color: rgb(30 58 138 / 0.3) !important;
}

/* Empty state row styling - override alternating pattern */
tbody.striped-rows > tr.empty-state-row {
    background-color: white !important;
}

.dark tbody.striped-rows > tr.empty-state-row {
    background-color: rgb(30 41 59) !important;
}

/* Remove right border from last column in header and footer */
thead tr th:last-child,
tfoot tr td:last-child {
    border-right: none !important;
}

/* Remove right border from last column in body cells */
tbody tr td:last-child {
    border-right: none !important;
}
</style>
