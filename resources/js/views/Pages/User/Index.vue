<template>
    <ListItem
        :init-url="initUrl"
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        @setItems="setItems"
    >
        <template #header>
            <PageHeader :title="$trans('user.user')">
                <PageHeaderAction
                    url="users/"
                    name="User"
                    :title="$trans('user.user')"
                    :actions="actions"
                    :dropdown-actions="dropdownActions"
                    @toggleFilter="showFilter = !showFilter"
                />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm
                    @refresh="emitter.emit('listItems')"
                    :pre-requisites="preRequisites"
                    @hide="showFilter = false"
                ></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable
                :header="users.headers"
                :meta="users.meta"
                module="user"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="user in users.data"
                    :key="user.uuid"
                    @doubleClick="
                        router.push({
                            name: 'UserShow',
                            params: { uuid: user.uuid },
                        })
                    "
                >
                    <DataCell
                        name="name"
                        clickable
                        @click="
                            router.push({
                                name: 'UserShow',
                                params: { uuid: user.uuid },
                            })
                        "
                    >
                        <span class="font-medium text-blue-700 hover:underline">
                            {{ user.profile.name }}
                        </span>
                    </DataCell>
                    <DataCell name="email">
                        {{ user.email }}
                    </DataCell>
                    <DataCell name="username">
                        {{ user.username }}
                    </DataCell>
                    <DataCell name="roles">
                        <div v-for="role in user.roles">
                            <BaseBadge :label="role.label" />
                        </div>
                    </DataCell>
                    <DataCell name="status">
                        <BaseBadge
                            :label="user.status.label"
                            :design="user.status.color"
                        />
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ user.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action" align="center">
                        <div
                            v-if="
                                user.isEditable ||
                                user.isDeletable ||
                                perform('user:impersonate')
                            "
                            class="flex items-center justify-center gap-3 text-sm"
                        >
                            <button
                                v-if="perform('user:impersonate')"
                                type="button"
                                class="text-slate-600 hover:text-slate-700"
                                v-tooltip="$trans('user.impersonate')"
                                @click="impersonate(user.uuid)"
                            >
                                <i class="fas fa-key text-[12px]"></i>
                            </button>
                            <button
                                type="button"
                                class="text-slate-600 hover:text-slate-700"
                                v-tooltip="$trans('general.show')"
                                @click="
                                    router.push({
                                        name: 'UserShow',
                                        params: { uuid: user.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-eye text-[12px]"></i>
                            </button>
                            <button
                                v-if="user.isEditable"
                                type="button"
                                class="text-blue-500 hover:text-blue-600"
                                v-tooltip="$trans('general.edit')"
                                @click="
                                    router.push({
                                        name: 'UserEdit',
                                        params: { uuid: user.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-edit text-[12px]"></i>
                            </button>
                            <button
                                v-if="user.isDeletable"
                                type="button"
                                class="text-red-500 hover:text-red-600"
                                v-tooltip="$trans('general.delete')"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: user.uuid,
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
                        @click="router.push({ name: 'UserCreate' })"
                        v-if="perform('user:create')"
                    >
                        {{
                            $trans("global.add", {
                                attribute: $trans("user.user"),
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
    name: "UserList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform } from "@core/helpers/action"
import FilterForm from "./Filter.vue"

const router = useRouter()
const store = useStore()

const emitter = inject("emitter")

let actions = ["filter"]
if (perform("user:create")) {
    actions.unshift("create")
}

let dropdownActions = []
if (perform("user:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "user/"
const preRequisites = reactive({})
const showFilter = ref(false)

const users = reactive({})

const setItems = (data) => {
    Object.assign(users, data)
}

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}

const impersonate = async (uuid) => {
    await store
        .dispatch("auth/user/impersonate", {
            uuid,
        })
        .then((response) => {
            window.location.href = "/"
        })
        .catch((e) => {
            //
        })
}
</script>
