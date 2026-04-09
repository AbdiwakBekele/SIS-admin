<template>
    <div>
        <div class="flex justify-between">
            <BaseLabel :for="state.uniqueId" v-if="(label || labelHtml) && !floatingLabel" :required="required">
                <template v-if="labelHtml">
                    <span v-html="labelHtml"></span>
                    <span v-if="labelHint" class="ml-2" v-tooltip="labelHint"><i class="fas fa-question-circle"></i></span>
                </template>
                <template v-else>
                    {{ label }}
                    <span v-if="labelHint" class="ml-2" v-tooltip="labelHint"><i class="fas fa-question-circle"></i></span>
                </template>
            </BaseLabel>
            <slot name="additional-label"></slot>
        </div>
        <div class="mt-1" :class="{
            'floating-label-wrapper': floatingLabel,
            relative:
                leadingIcon ||
                trailingIcon ||
                trailingText ||
                currencyItem.trailingText ||
                percentage,
            flex: leadingText || currencyItem.leadingText,
            'rounded-md shadow-sm': rounded,
        }">
            <span v-if="leadingText || currencyItem.leadingText"
                class="inline-flex items-center border-gray-300 px-3 text-gray-500 sm:text-sm" :class="{
                    'pl-10': leadingIcon,
                    'border-b-2': !invisible,
                    'rounded-l-md': rounded,
                    'border-r': !invisible && leadingText,
                    'border-primary dark:border-gray-700': state.focused,
                    'border-gray-300 dark:border-gray-700': !state.focused,
                }">
                {{ leadingText || currencyItem.leadingText }}
            </span>
            <div v-if="leadingIcon" class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="text-gray-400" :class="leadingIcon" aria-hidden="true">
                </i>
            </div>

            <input :id="state.uniqueId" v-bind="$attrs" :placeholder="floatingLabel ? ' ' : placeholder || label"
                class="focus:border-primary block w-full border border-gray-300 rounded-lg bg-inherit focus:ring-0 disabled:bg-gray-200 dark:border-gray-700 dark:text-gray-200 dark:focus:border-gray-200 dark:disabled:bg-gray-700"
                :class="{
                    'text-sm': textSize == 'sm',
                    'text-lg': textSize == 'lg',
                    'text-xl': textSize == 'xl',
                    'text-2xl': textSize == '2xl',
                    'text-right': align == 'right',
                    'text-center': align == 'center',
                    'pr-12': align == 'right' && trailingText,
                    'pl-10': leadingIcon,
                    'pr-10':
                        trailingIcon || currencyItem.trailingText || percentage,
                    'rounded-md':
                        rounded &&
                        !leadingText &&
                        !currencyItem.leadingText &&
                        !adjacentLeft &&
                        !adjacentRight &&
                        !adjacentCenter,
                    'rounded-r-md':
                        rounded &&
                        (leadingText ||
                            currencyItem.leadingText ||
                            adjacentRight),
                    'rounded-l-md': rounded && adjacentLeft,
                    'floating-label-input': floatingLabel,
                }" @focus="state.focused = true" @blur="state.focused = false"
                :type="currency || percentage ? 'number' : $attrs.type" :step="currency
                    ? currencyItem.step
                    : percentage
                        ? 0.01
                        : $attrs.step
                    " :value="modelValue" @input="updateInput" />

            <label v-if="floatingLabel" :for="state.uniqueId" class="floating-label" :class="{
                'floating-label-active': state.focused || modelValue,
            }">
                {{ label }}
            </label>
            <div v-if="
                trailingIcon ||
                trailingText ||
                currencyItem.trailingText ||
                percentage
            " class="absolute inset-y-0 right-0 flex cursor-pointer items-center pr-3">
                <i v-if="trailingIcon" class="text-gray-400" :class="trailingIcon" aria-hidden="true">
                </i>
                <span v-if="currencyItem.trailingText" class="border-gray-300 text-gray-500 sm:text-sm">
                    {{ currencyItem.trailingText }}
                </span>
                <span v-if="trailingText" class="border-gray-300 text-gray-500 sm:text-sm">
                    {{ trailingText }}
                </span>
                <span v-if="percentage" class="border-gray-300 text-gray-500 sm:text-sm">
                    %
                </span>
            </div>
        </div>
        <FormError :error="error" />
    </div>
</template>

<script>
export default {
    name: "BaseInput",
    inheritAttrs: false,
    methods: {
        updateInput($event) {
            this.$emit("update:modelValue", $event.target.value)
            this.$emit("change", $event.target.value)
            this.$emit("update:error", "")
        },
    },
}
</script>

<script setup>
import { onMounted, reactive, computed, watch } from "vue"
import { useStore } from "vuex"

const store = useStore()

const props = defineProps({
    label: {
        type: String,
        default: "",
    },
    labelHtml: {
        type: String,
        default: "",
    },
    required: {
        type: Boolean,
        default: false,
    },
    currency: {
        type: Boolean,
        default: false,
    },
    currencyName: {
        type: String,
        default: "",
    },
    labelHint: {
        type: String,
        default: "",
    },
    placeholder: {
        type: String,
        default: " ",
    },
    modelValue: {
        type: [String, Number],
        default: "",
    },
    error: {
        type: String,
        default: "",
    },
    rounded: {
        type: Boolean,
        default: false,
    },
    leadingIcon: {
        type: String,
        default: "",
    },
    trailingIcon: {
        type: String,
        default: "",
    },
    leadingText: {
        type: String,
        default: "",
    },
    trailingText: {
        type: String,
        default: "",
    },
    percentage: {
        type: Boolean,
        default: false,
    },
    adjacentLeft: {
        type: Boolean,
        default: false,
    },
    adjacentCenter: {
        type: Boolean,
        default: false,
    },
    adjacentRight: {
        type: Boolean,
        default: false,
    },
    invisible: {
        type: Boolean,
        default: false,
    },
    align: {
        type: String,
        default: "left",
    },
    textSize: {
        type: String,
        default: "sm",
    },
    floatingLabel: {
        type: Boolean,
        default: false,
    },
})

const defaultCurrencyDetail = computed(() =>
    store.getters["config/config"]("system.currency")
)
const currencyDetail = computed(() => {
    let currencyFound
    if (props.currencyName) {
        currencyFound = store.getters["config/config"](
            "system.currencies"
        ).find((currency) => currency.name == props.currencyName)
    }

    return currencyFound === undefined
        ? defaultCurrencyDetail.value
        : currencyFound
})

const state = reactive({
    uniqueId: "",
    focused: false,
})

const currencyItem = reactive({
    leadingText: "",
    trailingText: "",
    step: 2,
})

const updateCurrency = () => {
    if (props.currency) {
        if (currencyDetail.value.position == "prefix") {
            currencyItem.leadingText = currencyDetail.value.symbol
        } else {
            currencyItem.trailingText = currencyDetail.value.symbol
        }
        currencyItem.step = 1 / Math.pow(10, currencyDetail.value.decimal)
    }
}

watch(
    () => props.currencyName,
    () => {
        updateCurrency()
    }
)

onMounted(() => {
    state.uniqueId = props.id || Math.random().toString(16).slice(2)

    updateCurrency()
})
</script>

<style scoped>
.floating-label-wrapper {
    position: relative;
}

.floating-label-input {
    padding-top: 1.25rem !important;
    padding-bottom: 0.25rem !important;
}

.floating-label {
    position: absolute;
    left: 0.75rem;
    top: 0.75rem;
    font-size: 0.875rem;
    color: #6b7280;
    pointer-events: none;
    transition: all 0.2s ease-out;
}

.floating-label-active {
    transform: translateY(-0.6rem) scale(0.85);
    color: #6b7280;
}

:deep(.dark) .floating-label {
    color: #9ca3af;
}

:deep(.dark) .floating-label-active {
    color: #e5e7eb;
}
</style>
