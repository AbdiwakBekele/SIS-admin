<template>
    <FilterForm
        :init-form="initForm"
        :form="form"
        :multiple="['employees', 'batches', 'subjects']"
        @hide="emit('hide')"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseSelectSearch
                    multiple
                    v-if="fetchData.isLoaded"
                    name="employees"
                    :label="
                        $trans('global.select', {
                            attribute: $trans('employee.employee'),
                        })
                    "
                    v-model="form.employees"
                    value-prop="uuid"
                    :init-search="fetchData.employees"
                    search-key="name"
                    search-action="employee/list"
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
                <BaseSelectSearch
                    multiple
                    v-if="fetchData.isLoaded"
                    name="batches"
                    :label="
                        $trans('global.select', {
                            attribute: $trans('academic.batch.batch'),
                        })
                    "
                    v-model="form.batches"
                    value-prop="uuid"
                    :init-search="fetchData.batches"
                    search-key="course_batch"
                    search-action="academic/batch/list"
                >
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.course.name }}
                        {{ slotProps.value.name }}
                    </template>

                    <template #listOption="slotProps">
                        {{ slotProps.option.course.nameWithTerm }}
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect
                    multiple
                    v-model="form.subjects"
                    name="subjects"
                    :label="$trans('academic.subject.subject')"
                    label-prop="name"
                    value-prop="uuid"
                    :options="state.subjects"
                />
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

const props = defineProps({
    preRequisites: {
        type: Object,
        default() {
            return {}
        },
    },
})

const initForm = {
    employees: [],
    batches: [],
    subjects: [],
    startDate: "",
    endDate: "",
}

const form = reactive({ ...initForm })
const state = reactive({
    subjects: props.preRequisites.subjects,
})

const fetchData = reactive({
    employees: [],
    batches: [],
    subjects: [],
    isLoaded:
        route.query.employees || route.query.batches || route.query.subjects
            ? false
            : true,
})

onMounted(async () => {
    fetchData.employees = route.query.employees
        ? route.query.employees.split(",")
        : []
    fetchData.batches = route.query.batches
        ? route.query.batches.split(",")
        : []
    fetchData.subjects = route.query.subjects
        ? route.query.subjects.split(",")
        : []
    fetchData.isLoaded = true
})
</script>
