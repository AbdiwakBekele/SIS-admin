<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('tenant.tenant')">
                <PageHeaderAction
                    url="tenants/"
                    name="Tenant"
                    :title="$trans('tenant.tenant')"
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
                :header="tenants.headers"
                :meta="tenants.meta"
                module="tenant"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="tenant in tenants.data"
                    :key="tenant.uuid"
                    @doubleClick="
                        router.push({
                            name: 'TenantShow',
                            params: { uuid: tenant.uuid },
                        })
                    "
                >
                    <DataCell
                        name="name"
                        clickable
                        @click="
                            router.push({
                                name: 'TenantShow',
                                params: { uuid: tenant.uuid },
                            })
                        "
                    >
                        <span class="font-medium text-blue-700 hover:underline">
                            {{ tenant.name }}
                        </span>
                        <BaseBadge
                            v-if="tenant.isTrial"
                            :label="$trans('tenant.props.trial')"
                            design="danger"
                        />
                    </DataCell>
                    <DataCell name="email">
                        {{ tenant.email }}
                    </DataCell>
                    <DataCell name="contactNumber">
                        {{ tenant.contactNumber }}
                    </DataCell>
                    <DataCell name="domain">
                        {{ tenant.domain }}
                    </DataCell>
                    <DataCell name="status">
                        <BaseBadge
                            :label="tenant.status.label"
                            :design="tenant.status.color"
                        />
                    </DataCell>
                    <DataCell name="plan">
                        {{ tenant.plan.name }}
                    </DataCell>
                    <DataCell name="expiryDate">
                        {{ tenant.expiryDate.formatted }}
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ tenant.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action" align="center">
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <a
                                v-if="perform('tenant:login')"
                                :href="tenant.loginUrl"
                                target="_blank"
                                class="text-slate-600 hover:text-slate-700"
                                v-tooltip="$trans('auth.login.login')"
                            >
                                <i class="fas fa-arrow-circle-right text-[12px]"></i>
                            </a>
                            <button
                                type="button"
                                class="text-blue-500 hover:text-blue-600"
                                v-tooltip="$trans('general.show')"
                                @click="
                                    router.push({
                                        name: 'TenantShow',
                                        params: { uuid: tenant.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-eye text-[12px]"></i>
                            </button>
                            <button
                                v-if="perform('tenant:edit')"
                                type="button"
                                class="text-slate-600 hover:text-slate-700"
                                v-tooltip="$trans('general.edit')"
                                @click="
                                    router.push({
                                        name: 'TenantEdit',
                                        params: { uuid: tenant.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-edit text-[12px]"></i>
                            </button>
                            <button
                                v-if="perform('tenant:create')"
                                type="button"
                                class="text-green-500 hover:text-green-600"
                                v-tooltip="$trans('general.duplicate')"
                                @click="
                                    router.push({
                                        name: 'TenantDuplicate',
                                        params: { uuid: tenant.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-copy text-[12px]"></i>
                            </button>
                            <button
                                v-if="perform('tenant:delete')"
                                type="button"
                                class="text-red-500 hover:text-red-600"
                                v-tooltip="$trans('general.delete')"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: tenant.uuid,
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
                        v-if="perform('tenant:create')"
                        @click="router.push({ name: 'TenantCreate' })"
                    >
                        {{
                            $trans("global.add", {
                                attribute: $trans("tenant.tenant"),
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
    name: "TenantList",
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
if (perform("tenant:create")) {
    userActions.unshift("create")
}
if (perform("tenant:config")) {
    userActions.unshift("config")
}

let dropdownActions = []
if (perform("tenant:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "tenant/"
const showFilter = ref(false)

const tenants = reactive({})

const setItems = (data) => {
    Object.assign(tenants, data)
}
</script>
