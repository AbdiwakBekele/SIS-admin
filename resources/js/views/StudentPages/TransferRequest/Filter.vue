<template>
    <FilterForm
        :init-form="initForm"
        :form="form"
        :multiple="['students']"
        @hide="emit('hide')"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseSelectSearch
                    multiple
                    v-if="fetchData.isLoaded"
                    name="students"
                    :label="
                        $trans('global.select', {
                            attribute: $trans('student.student'),
                        })
                    "
                    v-model="form.students"
                    value-prop="uuid"
                    :init-search="fetchData.students"
                    search-key="name"
                    search-action="student/summary"
                >
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }} ({{
                            slotProps.value.codeNumber
                        }})
                    </template>

                    <template #listOption="slotProps">
                        {{ slotProps.option.name }} ({{
                            slotProps.option.codeNumber
                        }})
                    </template>
                </BaseSelectSearch>
            </div>
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
    students: [],
    startDate: "",
    endDate: "",
}

const form = reactive({ ...initForm })
const state = reactive({})

const fetchData = reactive({
    students: [],
    isLoaded: route.query.students ? false : true,
})

onMounted(async () => {
    fetchData.students = route.query.students
        ? route.query.students.split(",")
        : []
    fetchData.isLoaded = true
})
</script>
