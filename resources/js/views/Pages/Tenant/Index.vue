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
                <DataRow v-for="tenant in tenants.data" :key="tenant.uuid">
                    <DataCell name="name">
                        {{ tenant.name }}
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
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                v-if="perform('tenant:login')"
                                icon="fas fa-arrow-circle-right"
                                as="link"
                                :href="tenant.loginUrl"
                                target="_blank"
                                >{{
                                    $trans("auth.login.login")
                                }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                icon="fas fa-arrow-circle-right"
                                @click="
                                    router.push({
                                        name: 'TenantShow',
                                        params: { uuid: tenant.uuid },
                                    })
                                "
                                >{{ $trans("general.show") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('tenant:edit')"
                                icon="fas fa-edit"
                                @click="
                                    router.push({
                                        name: 'TenantEdit',
                                        params: { uuid: tenant.uuid },
                                    })
                                "
                                >{{ $trans("general.edit") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('tenant:create')"
                                icon="fas fa-copy"
                                @click="
                                    router.push({
                                        name: 'TenantDuplicate',
                                        params: { uuid: tenant.uuid },
                                    })
                                "
                                >{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('tenant:delete')"
                                icon="fas fa-trash"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: tenant.uuid,
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
