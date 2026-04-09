<template>
    <ListItem :init-url="initUrl" :additional-query="{}" @setItems="setItems">
        <template #header>
            <PageHeader :title="`Progress Report`" :navs="[
                {
                    label: `Students`,
                    path: '',
                },
            ]">
                <PageHeaderAction url="communication/announcements/" name="NewStudentApplication"
                    :title="`Enrollment Form`" :actions="userActions" :dropdown-actions="dropdownActions"
                    @toggleFilter="showFilter = !showFilter" />
            </PageHeader>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="announcements.headers" :meta="announcements.meta"
                module="admission_inquiry.new_student_application" @refresh="emitter.emit('listItems')">
                <DataRow v-for="announcement in announcements.data" :key="announcement.uuid" @double-click="
                    router.push({
                        name: 'CommunicationAnnouncementShow',
                        params: { uuid: announcement.uuid },
                    })
                    ">
                    <DataCell name="codeNumber">
                        {{ announcement.codeNumber }}
                    </DataCell>
                    <DataCell name="title">
                        {{ announcement.titleExcerpt }}
                    </DataCell>
                    <DataCell name="type">
                        {{ announcement.type?.name || "-" }}
                    </DataCell>
                    <DataCell name="employee">
                        {{ announcement.employee?.name || "-" }}
                        <TextMuted block>{{
                            announcement.employee?.codeNumber
                        }}</TextMuted>
                    </DataCell>
                    <DataCell name="audience">
                        <div v-for="audienceType in announcement.audienceTypes">
                            {{ audienceType }}
                        </div>
                    </DataCell>
                    <!-- <DataCell name="publishedAt">
                        {{ announcement.publishedAt.formatted }}
                    </DataCell> -->
                    <DataCell name="createdAt">
                        {{ announcement.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'CommunicationAnnouncementShow',
                                    params: { uuid: announcement.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('announcement:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'CommunicationAnnouncementEdit',
                                    params: { uuid: announcement.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('announcement:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'CommunicationAnnouncementDuplicate',
                                    params: { uuid: announcement.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('announcement:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: announcement.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <!-- <BaseButton v-if="perform('announcement:create')" @click="
                        router.push({
                            name: 'CommunicationAnnouncementCreate',
                        })
                        ">
                        {{
                            $trans("global.add", {
                                attribute: $trans(
                                    "communication.announcement.announcement"
                                ),
                            })
                        }}
                    </BaseButton> -->
                </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "CommunicationAnnouncementList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform, actingAs } from "@core/helpers/action"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]

if (perform("announcement:create")) {
    // userActions.unshift("create")
}

let dropdownActions = []
if (perform("announcement:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "communication/announcement/"
const showFilter = ref(false)

const announcements = reactive({})

const setItems = (data) => {
    Object.assign(announcements, [])
}
</script>
