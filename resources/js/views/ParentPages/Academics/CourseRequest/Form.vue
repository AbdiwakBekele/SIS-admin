<template>
    <FormAction :has-setup-wizard="true" :pre-requisites="false" @setPreRequisites="setPreRequisites"
        :init-url="initUrl" :init-form="initForm" :form="form" :set-form="setForm" redirect="CourseRequest">
        <div class="grid grid-cols-3 gap-6">

            <!-- SChool Year -->
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect v-model="form.name" name="school_year" :label="`School Year`" :options="[
                    { value: '2025', label: '2024 - 2025' },
                    { value: '2026', label: '2025 - 2026' },

                ]" />
            </div>

            <!-- Student Name -->
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect name="student_name" :label="`Academic Designation`" :options="[
                    { value: 'honored', label: 'John Smith' },
                    { value: 'gifted', label: 'Joe Doe' },
                ]" />
            </div>

            <!-- Grade Level -->
            <div class="col-span-3 sm:col-span-1">
                <BaseSelect v-model="form.grade" name="grade" :label="`Grade Level`" :options="[
                    { value: 'honored', label: '9th Grade' },
                    { value: 'gifted', label: '10th Grade' },
                    { value: 'gifted', label: '11th Grade' },
                    { value: 'gifted', label: '12th Grade' },
                ]" />
            </div>
        </div>

        <br>
        <h2> <strong>Requested Courses List</strong> </h2>
        <BaseFieldset class="mt-4" v-for="(session, index) in form.sessions" :key="session.uuid">
            <template #legend>
                Semester {{ index + 1 }}.
                <span class="text-danger ml-2 cursor-pointer" @click=""><i class="fas fa-times-circle"></i></span>
            </template>

            <div class="mt-1 grid grid-cols-3 gap-6">
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect name="course" :label="`Select Courses`" :options="[
                        { label: 'Biology', value: 'Biology' },
                        { label: 'Chemistry', value: 'Chemistry' },
                        { label: 'Mathematics', value: 'Mathematics' },
                    ]">
                    </BaseSelect>
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <BaseInput type="text" v-model="session.name" :name="`sessions.${index}.name`"
                        :label="`Reason for Request`" />
                </div>
            </div>
        </BaseFieldset>
    </FormAction>
</template>

<script>
export default {
    name: "AcademicDivisionForm",
}
</script>

<script setup>
import { reactive } from "vue"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"

const initForm = {
    name: "",
    code: "",
    shortcode: "",
    program: "",
    position: "",
    pgAccount: "",
    description: "",
    sessions: ['1', '2'],
}

const initUrl = "academic/division/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    programs: [],
})

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
        program: data.program?.uuid || "",
    })
    Object.assign(form, cloneDeep(initForm))
}
</script>
