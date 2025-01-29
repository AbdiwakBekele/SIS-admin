<template>
    <div v-if="customFields.length > 0" class="mt-4 grid grid-cols-3 gap-6">
        <div
            class="col-span-3 sm:col-span-1"
            v-for="(customField, index) in customFields"
        >
            <BaseInput
                v-if="
                    customField.type.value == 'text_input' ||
                    customField.type.value == 'email_input' ||
                    customField.type.value == 'currency_input'
                "
                type="text"
                :currency="
                    customField.type.value == 'currency_input' ? true : false
                "
                v-model="customField.value"
                :name="`customFields.${customField.name}`"
                :label="customField.label"
                v-model:error="
                    formErrors[`customFields.${index}.${customField.name}`]
                "
            />
            <BaseInput
                v-if="customField.type.value == 'number_input'"
                type="number"
                v-model="customField.value"
                :name="`customFields.${customField.name}`"
                :label="customField.label"
                v-model:error="
                    formErrors[`customFields.${index}.${customField.name}`]
                "
            />
            <DatePicker
                v-if="
                    customField.type.value == 'date_picker' ||
                    customField.type.value == 'time_picker' ||
                    customField.type.value == 'date_time_picker'
                "
                :as="
                    customField.type.value == 'date_picker'
                        ? 'date'
                        : customField.type.value == 'time_picker'
                          ? 'time'
                          : 'datetime'
                "
                v-model="customField.value"
                :name="`customFields.${customField.name}`"
                :label="customField.label"
                :no-clear="customField.isRequired ? true : false"
                v-model:error="
                    formErrors[`customFields.${index}.${customField.name}`]
                "
            />
            <BaseTextarea
                v-if="customField.type.value == 'multi_line_text_input'"
                v-model="customField.value"
                :name="`customFields.${customField.name}`"
                :label="customField.label"
                v-model:error="
                    formErrors[`customFields.${index}.${customField.name}`]
                "
            />
            <BaseSelect
                v-if="
                    customField.type.value == 'select_input' ||
                    customField.type.value == 'multi_select_input'
                "
                :multiple="
                    customField.type.value == 'multi_select_input'
                        ? true
                        : false
                "
                v-model="customField.value"
                :options="customField.optionArray"
                :name="`customFields.${customField.name}`"
                :label="customField.label"
                v-model:error="
                    formErrors[`customFields.${index}.${customField.name}`]
                "
            />
            <BaseMultiCheckbox
                horizontal
                v-if="customField.type.value == 'checkbox_input'"
                :options="customField.optionArray"
                v-model="customField.value"
                :name="`customFields.${customField.name}`"
                :label="customField.label"
                v-model:error="
                    formErrors[`customFields.${index}.${customField.name}`]
                "
            />
            <BaseRadioGroup
                v-if="customField.type.value == 'radio_input'"
                top-margin
                :options="customField.optionArray"
                v-model="customField.value"
                :name="`customFields.${customField.name}`"
                :label="customField.label"
                v-model:error="
                    formErrors[`customFields.${index}.${customField.name}`]
                "
                horizontal
            />
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    customFields: {
        type: Array,
        required: true,
    },
    formErrors: {
        type: Object,
        required: true,
    },
})
</script>
