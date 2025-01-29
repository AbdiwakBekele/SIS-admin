<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('utility.backup.backup')">
                <PageHeaderAction
                    url="utility/backups/"
                    name="UtilityBackup"
                    :title="$trans('utility.backup.backup')"
                    :actions="[]"
                    :dropdown-actions="['print', 'pdf', 'excel']"
                    :headers="backups.headers"
                    @toggleFilter="showFilter = !showFilter"
                >
                    <BaseButton design="white" @click="generateBackup">
                        {{
                            $trans("global.generate", {
                                attribute: $trans("utility.backup.backup"),
                            })
                        }}
                    </BaseButton>
                </PageHeaderAction>
            </PageHeader>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable
                :header="backups.headers"
                :meta="backups.meta"
                module="utility.backup"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow v-for="backup in backups.data" :key="backup.uuid">
                    <DataCell name="name">
                        {{ backup.name }}
                    </DataCell>
                    <DataCell name="size">
                        {{ backup.size }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                icon="fas fa-trash"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: backup.name,
                                    })
                                "
                                >{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem
                            >
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "BackupList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"

const router = useRouter()
const store = useStore()
const emitter = inject("emitter")

const initUrl = "utility/backup/"
const isLoading = ref(false)
const showFilter = ref(false)

const backups = reactive({})

const setItems = (data) => {
    Object.assign(backups, data)
}

const generateBackup = () => {
    isLoading.value = true
    store
        .dispatch("utility/backup/generateBackup")
        .then(() => {
            isLoading.value = false
            emitter.emit("listItems")
        })
        .catch((error) => {
            isLoading.value = false
        })
}
</script>
