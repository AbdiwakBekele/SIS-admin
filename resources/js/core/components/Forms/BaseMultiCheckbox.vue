<template>
    <div>
        <BaseLabel v-if="label">{{ label }}</BaseLabel>
        <div
            class="flex"
            :class="{
                'space-x-2': horizontal,
                'flex-col space-y-1': !horizontal,
                'mt-4': label,
            }"
        >
            <div v-for="option in options" :key="option.value">
                <div class="flex items-center">
                    <input
                        :id="getOptionId(option.value)"
                        v-bind="$attrs"
                        :checked="isSelected(option.value)"
                        @change="updateInput(option.value)"
                        type="checkbox"
                        :disabled="disabled"
                        class="text-primary dark:text-dark-body border-primary h-4 w-4 rounded focus:ring-0 dark:border-gray-700"
                        :class="{
                            'cursor-pointer': !disabled,
                            'text-gray-400 dark:text-gray-500': disabled,
                        }"
                    />
                    <label
                        :for="getOptionId(option.value)"
                        class="ml-2 block text-sm text-gray-900 dark:text-gray-400"
                        :class="{
                            'cursor-pointer': !disabled,
                        }"
                    >
                        <div class="flex justify-between">
                            {{ option.label }}
                            <span
                                v-if="option.labelHint"
                                class="ml-2"
                                v-tooltip="option.labelHint"
                            >
                                <i class="fas fa-question-circle"></i>
                            </span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        <FormError :error="error" />
    </div>
</template>

<script setup>
import { onMounted, reactive } from "vue"

const emit = defineEmits(["update:modelValue", "update:error"])

const props = defineProps({
    label: {
        type: String,
        default: "",
    },
    options: {
        type: Array,
        default: () => [],
    },
    modelValue: {
        type: Array,
        default: () => [],
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: "",
    },
    horizontal: {
        type: Boolean,
        default: false,
    },
})

const state = reactive({
    uniqueIds: [],
})

const getOptionId = (value) => {
    return state.uniqueIds[value] || ""
}

const isSelected = (value) => {
    return props.modelValue.includes(value)
}

const getOptionError = (value) => {
    const errorIndex = props.options.findIndex(
        (option) => option.value === value
    )
    return errorIndex !== -1 ? props.modelValue[errorIndex] : ""
}

const updateInput = (value) => {
    const updatedModelValue = isSelected(value)
        ? props.modelValue.filter((selectedValue) => selectedValue !== value)
        : [...props.modelValue, value]

    emit("update:modelValue", updatedModelValue)
    emit("update:error", "")
}

onMounted(() => {
    state.uniqueIds = Object.fromEntries(
        props.options.map((option) => [
            option.value,
            option.id || Math.random().toString(16).slice(2),
        ])
    )
})
</script>
