<template>
    <ListItem :init-url="initUrl" :pre-requisites="true" @setPreRequisites="setPreRequisites" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('student.children')" :navs="[]">

                <PageHeaderAction url="student/profiles/" name="MyChildrenProfile" :title="$trans('student.children')"
                    :actions="userActions" :dropdown-actions="dropdownActions">
                </PageHeaderAction>

            </PageHeader>
        </template>

        <!-- <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" :pre-requisites="preRequisites"
                    @hide="showFilter = false" />
            </ParentTransition>
        </template> -->

        <ParentTransition appear :visibility="true">
            <DataTable :header="students.headers" :meta="students.meta" module="student.student"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="student in students.data" :key="student.uuid"
                    @double-click="router.push({ name: 'MyChildrenProfileShow', params: { uuid: student.uuid } })">
                    <DataCell name="first_name">
                        {{ student.firstName }} {{ student.middleName }} {{ student.lastName }}
                    </DataCell>
                    <DataCell name="gender">{{ student.gender }}</DataCell>
                    <DataCell name="gradeLevel">{{ student.gradeLevel || '-' }}</DataCell>
                    <DataCell name="joiningDate">{{ student.joiningDate.formatted || '-' }}</DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye"
                                @click="router.push({ name: 'MyChildrenProfileShow', params: { uuid: student.uuid } })">
                                {{ $trans("general.view") }}
                            </FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "MyChildrenProfileList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
// import FilterForm from "./Filter.vue"

const router = useRouter()
const emitter = inject("emitter")

let userActions = [] // ["filter"] - removed since Filter.vue is missing

let dropdownActions = []
if (perform("registration:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "student/studentProfile/"
const preRequisites = reactive({ statuses: [] })
const showFilter = ref(false)


const students = reactive({})

const setItems = (data) => {
    if (data.headers) {
        data.headers = data.headers.filter(header => header.key !== 'status' && header.key !== 'selectAll')
    }
    Object.assign(students, data)
}

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}
</script>

<style scoped></style>
