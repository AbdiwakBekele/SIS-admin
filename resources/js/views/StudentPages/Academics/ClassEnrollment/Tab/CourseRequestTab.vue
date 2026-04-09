<template>
    <ListItem :init-url="initUrl" :additional-query="{ details: true }" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('academic_course_planner.course_planner.course_planner')" :navs="[
                { label: $trans('academic_course_planner.academic_course_planner'), path: 'CourseRequest' },
            ]">
                <PageHeaderAction url="academicCoursePlanner/coursePlanner/" name="coursePlanner"
                    :title="$trans('academic_course_planner.course_planner.course_planner')" :actions="userActions"
                    :dropdown-actions="dropdownActions" :additional-dropdown-actions-query="{
                        details: true,
                    }">

                </PageHeaderAction>
            </PageHeader>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="batches.headers" :meta="batches.meta" module="academicCoursePlanner.coursePlanner"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="batch in batches.data" :key="batch.uuid" @double-click="
                    router.push({
                        name: 'AcademicBatchShow',
                        params: { uuid: batch.uuid },
                    })
                    ">
                    <DataCell name="name">
                        {{ batch.name }}
                        <TextMuted block v-if="batch.pgAccount">{{
                            batch.pgAccount
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="course">
                        {{ batch.course.nameWithTerm }}
                    </DataCell>
                    <DataCell name="maxStrength">
                        {{ batch.currentStrength }}/{{ batch.maxStrength }}
                    </DataCell>
                    <DataCell name="incharge">
                        <div v-for="incharge in batch.incharges">
                            {{ incharge?.employee?.name || "-" }}
                            <TextMuted>{{ incharge?.period }}</TextMuted>
                        </div>
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ batch.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'AcademicBatchShow',
                                    params: { uuid: batch.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('batch:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'AcademicBatchEdit',
                                    params: { uuid: batch.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('batch:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'AcademicBatchDuplicate',
                                    params: { uuid: batch.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('batch:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: batch.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('batch:create')" @click="router.push({ name: 'CoursePlannerCreate' })">
                        {{
                            $trans("global.add", {
                                attribute: $trans("academicCoursePlanner.coursePlanner.coursePlanner"),
                            })
                        }}
                    </BaseButton>
                </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "AcademicCoursePlannerList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"

const router = useRouter()

const emitter = inject("emitter")

let userActions = []
if (perform("batch:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("batch:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "academicCoursePlanner/coursePlanner/"

const batches = reactive({})

const setItems = (data) => {
    Object.assign(batches, data)
}
</script>
