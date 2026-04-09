<template>
    <ListItem :init-url="initUrl" :additional-query="{}" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('notification.notification')" :navs="[]">
                <PageHeaderAction url="communication/notifications/" name="Notification"
                    :title="$trans('notification.notification')" :actions="userActions"
                    :dropdown-actions="dropdownActions" />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="notifications.headers" :meta="notifications.meta" module="communication.notification"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="notification in notifications.data" :key="notification.uuid" @double-click="
                    router.push({
                        name: 'StudentNotificationShow',
                        params: { uuid: notification.uuid },
                    })
                    ">
                    <DataCell name="title">
                        {{ notification.title }}
                    </DataCell>
                    <DataCell name="audience">
                        <div v-for="audienceType in notification.audience">
                            {{ audienceType }}
                        </div>
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ notification.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'StudentNotificationShow',
                                    params: { uuid: notification.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('notification:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'NotificationEdit',
                                    params: { uuid: notification.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('notification:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'NotificationDuplicate',
                                    params: { uuid: notification.uuid },
                                })
                                ">{{ $trans("general.duplicate") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('notification:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: notification.uuid,
                                })
                                ">{{ $trans("general.delete") }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('notification:create')" @click="
                        router.push({
                            name: 'NotificationCreate',
                        })
                        ">
                        {{ $trans("global.add", {
                            attribute: $trans("notification.notification")
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
    name: "NotificationList",
}
</script>

<script setup>
import { ref, reactive, inject, watch } from "vue"
import { useRouter } from "vue-router"
import { perform, actingAs } from "@core/helpers/action"
// import FilterForm from "./Filter.vue"

const router = useRouter()
const emitter = inject("emitter")

let userActions = [] // ["filter"] - removed since Filter.vue is missing
if (perform("notification:config")) {
    userActions.push("config")
}
if (perform("notification:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("notification:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "communication/notification/"
const showFilter = ref(false)
const notifications = reactive({})

watch(
    () => notifications.data,
    (newData) => {
        console.log('Notifications data:', newData);
        if (Array.isArray(newData) && newData.length === 0) {
            router.push({ name: "NotificationCreate" })
        }
    }
)

const setItems = (data) => {
    console.log('Received data from ListItem:', data);
    Object.assign(notifications, data)
}
</script>