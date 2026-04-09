<template>
    <!-- Actions bar with subtle gradient and rounded border -->
    <div
        class="flex flex-wrap items-center gap-2 rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white px-2 py-2">
      
        <DropdownMenu double-top-margin direction="left" :right-margin="false" no-width v-if="$slots.moduleOption">
            <template #clickable>
                <BaseButton design="white" class="shadow-sm">
                    <span class="flex items-center text-blue-800">
                        <slot name="moduleOptionLabel">
                            {{ $trans("general.more") }}
                        </slot>
                        <ChevronDownIcon :class="['ml-1 h-5 w-5']" aria-hidden="true" />
                    </span>
                </BaseButton>
            </template>
            <slot name="moduleOption"></slot>
        </DropdownMenu>

        <!-- Create -->
        <BaseButton
            v-if="hasCreateAction"
            design="primary"
            class="shadow-sm"
            @click="router.push({ name: CreateAction })">
            <i class="fas fa-plus mr-2"></i>
            {{ $trans("global.add", { attribute: title }) }}
        </BaseButton>

        <!-- List -->
        <BaseButton design="primary" v-if="hasListAction" class="shadow-sm" @click="router.push({ name: ListAction })">
            {{ $trans("global.list_all", { attribute: title }) }}
        </BaseButton>

          <!-- Leading slot for high-priority custom actions before other icons, after the Create and list -->
          <slot name="leading"></slot>

        <!-- Bulk Actions (Invite Parent/Guardian) - Before Filter -->
        <BulkActionPopover v-if="showBulkAction && bulkActions.length" :bulk-actions="bulkActions" :label="bulkActionLabel || $trans('general.bulk_action')" @action="$emit('onBulkAction', $event)" />

        <!-- Filter -->
        <BaseButton v-if="hasFilterAction" design="white" class="shadow-sm" @click="$emit('toggleFilter')">
            <i class="fas fa-filter"></i>
        </BaseButton>

         <!-- Coming from the Parent Component -->
         <slot></slot>
       

        <BaseButton v-for="action in additionalActions" :key="action.label" design="white" class="shadow-sm"
            @click="router.push(action.path)">
            {{ action.label }}
        </BaseButton>

        <BaseButton
            v-if="hasNewAction"
            design="primary"
            class="shadow-sm"
            @click="router.push({ name: NewAction })">
            {{ $trans("global.new", { attribute: title }) }}
        </BaseButton>

         <!-- View toggles -->
         <BaseButton design="white" v-tooltip="$trans('global._view', { attribute: $trans('general.list') })"
            v-if="hasViewAction && route.query.view != 'list'" class="shadow-sm" @click="updateURL({ view: 'list' })">
            <i class="fas fa-list"></i>
        </BaseButton>

        <BaseButton design="white" v-tooltip="$trans('global._view', { attribute: $trans('general.card') })"
            v-if="hasViewAction && route.query.view == 'list'" class="shadow-sm" @click="updateURL({ view: 'card' })">
            <i class="fas fa-th-large"></i>
        </BaseButton>

        <!-- Column selector -->
        <DropdownMenu double-top-margin :right-margin="false" no-width
            v-if="route.query.view != 'board' && columnsWithLabel.length"
            v-tooltip="$trans('global.select', { attribute: $trans('general.column') })">
            <template #clickable>
                <BaseButton design="white" class="shadow-sm">
                    <i class="fas fa-table-columns flex h-5 w-5 items-center justify-center"></i>
                </BaseButton>
            </template>

            <div v-for="(column, index) in columnsWithLabel" :key="column.key" class="flex px-4 py-2 text-sm">
                <BaseCheckbox v-model="column.visibility" :disabled="index === 0 ? true : false" :label="column.label"
                    @update:modelValue="onColumnVisibilityChange(columnsWithLabel)" />
            </div>
        </DropdownMenu>

        <!-- Print -->
        <BaseButton v-if="hasPrintAction" design="white" class="shadow-sm" @click="generateOutput('print')">
            <i class="fa-solid fa-print"></i>
        </BaseButton>


        <slot name="after"></slot>

        

        <!-- Export dropdown -->
        <DropdownMenu double-top-margin :right-margin="false" no-width v-if="dropdownActions.length || $slots.dropdown">
            <template #clickable>
                <BaseButton design="white" class="shadow-sm">
                    <i class="fa-solid fa-download"></i>
                </BaseButton>
            </template>

            <DropdownItem icon="fas fa-file-import" v-if="hasImportAction" @click="$emit('toggleImport')">
                {{ $trans("general.import") }}
            </DropdownItem>

            <DropdownItem icon="fas fa-file-pdf" v-if="hasPdfAction" @click="generateOutput('pdf')">
                {{ $trans("general.pdf") }}
            </DropdownItem>
            <DropdownItem icon="fas fa-file-excel" v-if="hasExcelAction" @click="generateOutput('excel')">
                {{ $trans("general.excel") }}
            </DropdownItem>

            <slot name="dropdown"></slot>
        </DropdownMenu>

           <!-- Config -->
           <BaseButton v-if="hasConfigAction" design="white" class="shadow-sm"
            @click="router.push({ name: ConfigAction })">
            <i class="fas fa-cog"></i>
        </BaseButton>
    </div>
</template>


<script>
export default {
    name: "PageHeaderAction",
}
</script>

<script setup>
import { computed, inject, useSlots } from "vue"
import { useRoute, useRouter } from "vue-router"
import Cookies from "js-cookie"
import { toQueryString } from "@core/helpers/array"
import { ChevronDownIcon } from "@heroicons/vue/20/solid"
import BulkActionPopover from "@core/components/Ui/BulkActionPopover.vue"

const slots = useSlots()
const route = useRoute()
const router = useRouter()

const emitter = inject("emitter")

const emit = defineEmits([
    "toggleFilter",
    "toggleImport",
    "onBulkAction",
    "refresh",
])

const props = defineProps({
    name: {
        type: String,
        default: "",
    },
    url: {
        type: String,
        default: "",
    },
    title: {
        type: String,
        default: "",
    },
    configPath: {
        type: String,
        default: "",
    },
    actions: {
        type: Array,
        default: [],
    },
    dropdownActions: {
        type: Array,
        default: [],
    },
    additionalDropdownActionsQuery: {
        type: Object,
        default: () => ({}),
    },
    additionalActions: {
        type: Array,
        default: [],
    },
    showBulkAction: {
        type: Boolean,
        default: false,
    },
    bulkActions: {
        type: Array,
        default: [],
    },
    bulkActionLabel: {
        type: String,
        default: '',
    },
    headers: {
        type: Array,
        default: [],
    },
})

const hasViewAction = computed(() => props.actions.includes("view"))
const hasCreateAction = computed(() => props.actions.includes("create"))
const hasNewAction = computed(() => props.actions.includes("new"))
const hasConfigAction = computed(() => props.actions.includes("config"))
const hasFilterAction = computed(() => props.actions.includes("filter"))
const hasListAction = computed(() => props.actions.includes("list"))
const hasImportAction = computed(() => props.actions.includes("import"))
// const hasImportAction = computed(() => props.dropdownActions.includes("import"))
const hasPrintAction = computed(() => props.dropdownActions.includes("print"))
const hasPdfAction = computed(() => props.dropdownActions.includes("pdf"))
const hasExcelAction = computed(() => props.dropdownActions.includes("excel"))

const CreateAction = computed(() => props.name + "Create")
const NewAction = computed(() => props.name + "New")
const ConfigAction = computed(() =>
    props.configPath ? props.configPath : props.name + "Config"
)
const ListAction = computed(() => props.name + "List")

const columnsWithLabel = computed(() =>
    props.headers.filter((header) => header.label)
)

const COOKIE_KEY = `${props.name}_columns`

const generateOutput = (action) => {
    let url = "/app/" + props.url + "export"
    let queries = {
        ...route.query,
        ...props.additionalDropdownActionsQuery,
        columns: columnsWithLabel.value
            .filter((header) => header.visibility)
            .map((header) => header.key)
            .join(","),
        output: action,
    }
    window.open(toQueryString(url, queries), "_blank").focus()
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

const onColumnVisibilityChange = (columns) => {
    const visibleColumns = columns
        .filter((col) => col.visibility)
        .map((col) => col.key)

    Cookies.set(COOKIE_KEY, JSON.stringify(visibleColumns), { expires: 1 })
}
</script>
