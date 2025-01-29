import { ref, reactive } from "vue"

export function useCustomFields() {
    const customFields = ref([])

    const initCustomFields = (preRequisiteFields, existingData = null) => {
        return preRequisiteFields.map((customField) => {
            const storedCustomField = existingData
                ? existingData.find((o) => o.uuid === customField.uuid)
                : null

            let defaultValue = ""

            if (
                customField.type.value == "multi_select_input" ||
                customField.type.value == "checkbox_input"
            ) {
                defaultValue = []
            }

            return {
                ...customField,
                value: storedCustomField?.value || defaultValue,
            }
        })
    }

    const setCustomFields = (preRequisiteFields, existingData = null) => {
        customFields.value = initCustomFields(preRequisiteFields, existingData)
    }

    return {
        customFields,
        setCustomFields,
    }
}
