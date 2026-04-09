<template>
    <FilterForm
        :init-form="initForm"
        :form="form"
        :multiple="[]"
        @hide="emit('hide')"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <DatePicker
                    v-model:start="form.startDate"
                    v-model:end="form.endDate"
                    name="dateBetween"
                    as="range"
                    :label="$trans('general.date_between')"
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

const initForm = {
    startDate: "",
    endDate: "",
}

const form = reactive({ ...initForm })

const fetchData = reactive({
    isLoaded: true,
})

onMounted(async () => {
    fetchData.isLoaded = true
})
</script>
