<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('query.query')">
                <PageHeaderAction
                    url="queries/"
                    name="Query"
                    :title="$trans('query.query')"
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
                :header="queries.headers"
                :meta="queries.meta"
                module="query"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="query in queries.data"
                    :key="query.uuid"
                    @doubleClick="
                        router.push({
                            name: 'QueryShow',
                            params: { uuid: query.uuid },
                        })
                    "
                >
                    <DataCell
                        name="name"
                        clickable
                        @click="
                            router.push({
                                name: 'QueryShow',
                                params: { uuid: query.uuid },
                            })
                        "
                    >
                        <span class="font-medium text-blue-700 hover:underline">
                            {{ query.name }}
                        </span>
                    </DataCell>
                    <DataCell name="email">
                        {{ query.email }}
                    </DataCell>
                    <DataCell name="subject">
                        {{ query.subject }}
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ query.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                icon="fas fa-arrow-circle-right"
                                @click="
                                    router.push({
                                        name: 'QueryShow',
                                        params: { uuid: query.uuid },
                                    })
                                "
                                >{{ $trans("general.show") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('query:delete')"
                                icon="fas fa-trash"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: query.uuid,
                                    })
                                "
                                >{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem
                            >
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton> </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "QueryList",
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

let dropdownActions = []
if (perform("query:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "query/"
const showFilter = ref(false)

const queries = reactive({})

const setItems = (data) => {
    Object.assign(queries, data)
}
</script>
