<template>
    <div class="mt-4 grid grid-cols-4 gap-6">
        <!-- Employee Audience -->
        <div class="col-span-4 sm:col-span-1" v-if="preRequisites.employeeAudienceTypes?.length > 0">
            <BaseSelect v-model="form.employeeAudienceType" name="employeeAudienceType"
                :label="$trans('employee.employee')" :options="preRequisites.employeeAudienceTypes"
                v-model:error="formErrors.employeeAudienceType" @change="onEmployeeAudienceTypeChange" />
        </div>

        <!-- Department Wise -->
        <template v-if="form.employeeAudienceType == 'department_wise'">
            <div class="col-span-4 sm:col-span-1">
                <BaseSelectSearch multiple name="employeeAudiences" :label="$trans('employee.department.department')"
                    v-model="form.employeeAudiences" v-model:error="formErrors.employeeAudiences" value-prop="uuid"
                    label-prop="name" :options="preRequisites.departments" :init-search="form.employeeAudiences"
                    search-action="employee/department/list" @change="onEmployeeAudiencesChange">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }}
                    </template>
                    <template #listOption="slotProps">
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>
        </template>

        <!-- Designation Or Role -->
        <template v-if="form.employeeAudienceType == 'designation_wise'">
            <div class="col-span-4 sm:col-span-1">
                <BaseSelectSearch multiple name="employeeAudiences" :label="$trans('employee.role.role')"
                    v-model="form.employeeAudiences" v-model:error="formErrors.employeeAudiences" value-prop="uuid"
                    :init-search="form.employeeAudiences" :options="preRequisites.roles" search-action="employee/designation/list"
                    @change="onEmployeeAudiencesChange">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }}
                    </template>
                    <template #listOption="slotProps">
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>
        </template>

         <!-- Employee Wise -->
        <template v-if="form.employeeAudienceType == 'employee_wise'">
            <div class="col-span-4 sm:col-span-1">
                <BaseSelectSearch multiple name="employeeAudiences" :label="$trans('employee.employee')"
                    v-model="form.employeeAudiences" v-model:error="formErrors.employeeAudiences" value-prop="uuid"
                    :init-search="form.employeeAudiences" :options="preRequisites.employees" search-action="employee/list"
                    @change="onEmployeeAudiencesChange">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }}
                    </template>
                    <template #listOption="slotProps">
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>
        </template>

        <!-- Student Audience -->
        <div class="col-span-4 sm:col-span-1" v-if="preRequisites.studentAudienceTypes?.length > 0">
            <BaseSelect v-model="form.studentAudienceType" name="studentAudienceType"
                :label="$trans('student.students')" :options="preRequisites.studentAudienceTypes"
                v-model:error="formErrors.studentAudienceType" @change="onStudentAudienceTypeChange" />
        </div>
        <template v-if="form.studentAudienceType == 'division_wise'">
            <div class="col-span-4 sm:col-span-1">
                <BaseSelectSearch multiple name="studentAudiences" :label="$trans('academic.division.division')"
                    v-model="form.studentAudiences" v-model:error="formErrors.studentAudiences" value-prop="uuid"
                    label-prop="name" :options="preRequisites.division" :init-search="form.studentAudiences"
                    search-action="academic/division/list" @change="onStudentAudiencesChange">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }}
                    </template>
                    <template #listOption="slotProps">
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>
        </template>
        <template v-if="form.studentAudienceType == 'course_wise'">
            <div class="col-span-4 sm:col-span-1">
                <BaseSelectSearch multiple name="studentAudiences" :label="$trans('academic.course.course')"
                    :options="preRequisites.courses" v-model="form.studentAudiences"
                    v-model:error="formErrors.studentAudiences" value-prop="uuid" :init-search="form.studentAudiences"
                    search-action="academic/course/list" @change="onStudentAudiencesChange">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }}
                    </template>
                    <template #listOption="slotProps">
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>
        </template>
        <template v-if="form.studentAudienceType == 'batch_wise'">
            <div class="col-span-4 sm:col-span-1">
                <BaseSelectSearch multiple name="studentAudiences" :label="$trans('academic.batch.batch')"
                    :options="preRequisites.batches" v-model="form.studentAudiences"
                    v-model:error="formErrors.studentAudiences" value-prop="uuid" :init-search="form.studentAudiences"
                    search-action="academic/batch/list" @change="onStudentAudiencesChange">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }}
                    </template>
                    <template #listOption="slotProps">
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>
        </template>
        <template v-if="form.studentAudienceType == 'student_wise'">
            <div class="col-span-4 sm:col-span-1">
                <BaseSelectSearch multiple name="studentAudiences" :label="$trans('student.student')"
                    :options="preRequisites.students" v-model="form.studentAudiences"
                    v-model:error="formErrors.studentAudiences" value-prop="uuid" label-prop="name"
                    :init-search="form.studentAudiences"
                    search-action="student/list"
                    @change="onStudentAudiencesChange">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.name }}
                    </template>
                    <template #listOption="slotProps">
                        {{ slotProps.option.name }}
                    </template>
                </BaseSelectSearch>
            </div>
        </template>

        <!-- Guardian Audience Type -->
        <div class="col-span-4 sm:col-span-1" v-if="preRequisites.guardianAudienceTypes?.length > 0">
            <BaseSelect v-model="form.guardianAudienceType" name="guardianAudienceType"
                :label="$trans('guardian.guardians')" :options="preRequisites.guardianAudienceTypes"
                v-model:error="formErrors.guardianAudienceType" 
                :key="`guardian-type-${form.guardianAudienceType || 'empty'}`"
                @change="onGuardianAudienceTypeChange" />
        </div>

        <!-- Guardian Selection Menu (only for individual_wise) -->
        <template v-if="form.guardianAudienceType == 'individual_wise'">
            <div class="col-span-4 sm:col-span-1">
                <BaseSelectSearch multiple v-model="form.guardianAudiences" name="guardianAudiences"
                    :label="$trans('guardian.guardians')" :options="preRequisites.guardian || []" value-prop="uuid"
                    label-prop="firstName" v-model:error="formErrors.guardianAudiences"
                    :init-search="form.guardianAudiences" search-action="guardian/list"
                    :key="`guardian-${form.guardianAudienceType}`"
                    @change="onGuardianAudiencesChange">
                    <template #selectedOption="slotProps">
                        {{ slotProps.value.firstName }}
                    </template>
                    <template #listOption="slotProps">
                        {{ slotProps.option.firstName }}
                    </template>
                </BaseSelectSearch>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    name: "AudienceInput",
}
</script>

<script setup>
import { reactive, onMounted, watch } from "vue"

const emit = defineEmits([
    "update:studentAudienceType",
    "update:employeeAudienceType",
    "update:guardianAudienceType",
    "update:studentAudiences",
    "update:employeeAudiences",
    "update:guardianAudiences",
])

const props = defineProps({
    preRequisites: {
        type: Object,
        default() {
            return {
                studentAudienceTypes: [],
                employeeAudienceTypes: [],
                guardianAudienceTypes: [],
                courses: [],
                divisions: [],
                guardian: [],
                departments: [],
                roles: [],
                batches: [],
                students: [],
            }
        },
    },
    studentAudienceType: {
        type: String,
        default: "",
    },
    employeeAudienceType: {
        type: String,
        default: "",
    },
    guardianAudienceType: {
        type: String,
        default: "",
    },
    studentAudiences: {
        type: Array,
        default: [],
    },
    employeeAudiences: {
        type: Array,
        default: [],
    },
    guardianAudiences: {
        type: Array,
        default: () => [],
    },
    formErrors: {
        type: Object,
        default() {
            return {}
        },
    },
})

const form = reactive({
    studentAudienceType: props.studentAudienceType,
    employeeAudienceType: props.employeeAudienceType,
    guardianAudienceType: props.guardianAudienceType,
    studentAudiences: props.studentAudiences,
    employeeAudiences: props.employeeAudiences,
    guardianAudiences: props.guardianAudiences,
})

const onStudentAudienceTypeChange = (value) => {
    form.studentAudiences = []
    emit("update:studentAudienceType", value)
}
const onEmployeeAudienceTypeChange = (value) => {
    form.employeeAudiences = []
    emit("update:employeeAudienceType", value)
}
const onGuardianAudienceTypeChange = (value) => {
    form.guardianAudiences = []
    emit("update:guardianAudienceType", value)
}
const onStudentAudiencesChange = (value) => emit("update:studentAudiences", value)
const onEmployeeAudiencesChange = (value) => {
    emit("update:employeeAudiences", value)
}
const onGuardianAudiencesChange = (value) => {
    emit("update:guardianAudiences", value)
}

onMounted(() => {
    form.studentAudienceType = props.studentAudienceType
    form.employeeAudienceType = props.employeeAudienceType
    form.guardianAudienceType = props.guardianAudienceType
    form.studentAudiences = props.studentAudiences
    form.employeeAudiences = props.employeeAudiences
    form.guardianAudiences = props.guardianAudiences
})

watch(
    () => [
        props.studentAudienceType,
        props.employeeAudienceType,
        props.guardianAudienceType,
        props.studentAudiences,
        props.employeeAudiences,
        props.guardianAudiences,
    ],
    (value) => {
        form.studentAudienceType = value[0]
        form.employeeAudienceType = value[1]
        form.guardianAudienceType = value[2]
        form.studentAudiences = value[3]
        form.employeeAudiences = value[4]
        form.guardianAudiences = value[5]
    },
    { immediate: true }
)
</script>