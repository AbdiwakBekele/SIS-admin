<template>
    <FilterForm
        :init-form="initForm"
        :form="form"
        :multiple="['courses']"
        @hide="emit('hide')"
    >
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.name"
                    name="name"
                    :label="$trans('academic.batch.props.name')"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSelectSearch
                    multiple
                    v-if="fetchData.isLoaded"
                    name="courses"
                    :label="
                        $trans('global.select', {
                            attribute: $trans('academic.course.course'),
                        })
                    "
                    v-model="form.courses"
                    value-prop="uuid"
                    :init-search="fetchData.courses"
                    search-action="academic/course/list"
                >
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.nameWithTerm }}
                    </template>

                    <template #listOption="slotProps">
                        {{ slotProps.option.nameWithTerm }}
                    </template>
                </BaseSelectSearch>
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
    name: "",
    courses: [],
}

const form = reactive({ ...initForm })

const fetchData = reactive({
    courses: [],
    isLoaded: route.query.courses ? false : true,
})

onMounted(async () => {
    fetchData.courses = route.query.courses
        ? route.query.courses.split(",")
        : []
    fetchData.isLoaded = true
})
</script>
