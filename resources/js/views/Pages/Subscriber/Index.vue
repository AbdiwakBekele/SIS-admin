<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('subscriber.subscriber')">
                <PageHeaderAction
                    url="subscribers/"
                    name="Subscriber"
                    :title="$trans('subscriber.subscriber')"
                    :actions="userActions"
                    :dropdown-actions="dropdownActions"
                    @toggleFilter="showFilter = !showFilter"
                />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm
                    @refresh="emitter.emit('listItems')"
                    @hide="showFilter = false"
                ></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable
                :header="subscribers.headers"
                :meta="subscribers.meta"
                module="subscriber"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="subscriber in subscribers.data"
                    :key="subscriber.uuid"
                >
                    <DataCell name="name">
                        {{ subscriber.name }}
                    </DataCell>
                    <DataCell name="email">
                        {{ subscriber.email }}
                    </DataCell>
                    <DataCell name="unsubscribedAt">
                        {{ subscriber.unsubscribedAt.formatted }}
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ subscriber.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                icon="fas fa-arrow-circle-right"
                                @click="
                                    router.push({
                                        name: 'SubscriberShow',
                                        params: { uuid: subscriber.uuid },
                                    })
                                "
                                >{{ $trans("general.show") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('subscriber:edit')"
                                icon="fas fa-edit"
                                @click="
                                    router.push({
                                        name: 'SubscriberEdit',
                                        params: { uuid: subscriber.uuid },
                                    })
                                "
                                >{{ $trans("general.edit") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('subscriber:delete')"
                                icon="fas fa-trash"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: subscriber.uuid,
                                    })
                                "
                                >{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem
                            >
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton
                        v-if="perform('subscriber:create')"
                        @click="router.push({ name: 'SubscriberCreate' })"
                    >
                        {{
                            $trans("global.add", {
                                attribute: $trans("subscriber.subscriber"),
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
    name: "SubscriberList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
import FilterForm from "./Filter.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]
if (perform("subscriber:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("subscriber:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "subscriber/"
const showFilter = ref(false)

const subscribers = reactive({})

const setItems = (data) => {
    Object.assign(subscribers, data)
}
</script>
