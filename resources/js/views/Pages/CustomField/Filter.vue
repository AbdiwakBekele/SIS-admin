<template>
    <FilterForm
        :init-form="initForm"
        :form="form"
        :multiple="[]"
        @hide="emit('hide')"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.label"
                    name="label"
                    :label="$trans('custom_field.props.label')"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    v-model="form.form"
                    name="form"
                    :label="$trans('custom_field.props.form')"
                    :options="state.forms"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    v-model="form.type"
                    name="type"
                    :label="$trans('custom_field.props.type')"
                    :options="state.types"
                />
            </div>
        </div>
    </FilterForm>
</template>

<script setup>
import { reactive, onMounted } from "vue"
import { useRoute } from "vue-router"

const route = useRoute()

const emit = defineEmits(["hide"])

const props = defineProps({
    preRequisites: {
        type: Object,
        default() {
            return {}
        },
    },
})

const initForm = {
    label: "",
    form: "",
    type: "",
}

const form = reactive({ ...initForm })
const state = reactive({
    forms: props.preRequisites.forms,
    types: props.preRequisites.types,
})

const fetchData = reactive({
    isLoaded: true,
})

onMounted(async () => {
    fetchData.isLoaded = true
})
</script>
