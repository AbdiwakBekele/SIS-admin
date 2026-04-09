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
                    @doubleClick="
                        router.push({
                            name: 'SubscriberShow',
                            params: { uuid: subscriber.uuid },
                        })
                    "
                >
                    <DataCell
                        name="name"
                        clickable
                        @click="
                            router.push({
                                name: 'SubscriberShow',
                                params: { uuid: subscriber.uuid },
                            })
                        "
                    >
                        <span class="font-medium text-blue-700 hover:underline">
                            {{ subscriber.name }}
                        </span>
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
                    <DataCell name="action" align="center">
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <button
                                type="button"
                                class="text-blue-500 hover:text-blue-600"
                                v-tooltip="$trans('general.show')"
                                @click="
                                    router.push({
                                        name: 'SubscriberShow',
                                        params: { uuid: subscriber.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-eye text-[12px]"></i>
                            </button>
                            <button
                                v-if="perform('subscriber:edit')"
                                type="button"
                                class="text-slate-600 hover:text-slate-700"
                                v-tooltip="$trans('general.edit')"
                                @click="
                                    router.push({
                                        name: 'SubscriberEdit',
                                        params: { uuid: subscriber.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-edit text-[12px]"></i>
                            </button>
                            <button
                                v-if="perform('subscriber:delete')"
                                type="button"
                                class="text-red-500 hover:text-red-600"
                                v-tooltip="$trans('general.delete')"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: subscriber.uuid,
                                    })
                                "
                            >
                                <i class="fas fa-trash text-[12px]"></i>
                            </button>
                        </div>
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
