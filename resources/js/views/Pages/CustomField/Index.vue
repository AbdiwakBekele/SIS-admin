<template>
    <ListItem
        :pre-requisites="true"
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl"
        @setItems="setItems"
    >
        <template #header>
            <PageHeader :title="$trans('custom_field.custom_field')" :navs="[]">
                <PageHeaderAction
                    url="custom-fields/"
                    name="CustomField"
                    :title="$trans('custom_field.custom_field')"
                    :actions="userActions"
                    :dropdown-actions="dropdownActions"
                    :headers="customFields.headers"
                    @toggleFilter="showFilter = !showFilter"
                />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm
                    :pre-requisites="preRequisites"
                    @refresh="emitter.emit('listItems')"
                    @hide="showFilter = false"
                ></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable
                :header="customFields.headers"
                :meta="customFields.meta"
                module="custom_field"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="customField in customFields.data"
                    :key="customField.uuid"
                >
                    <DataCell name="form">
                        {{ customField.form.label }}
                    </DataCell>
                    <DataCell name="type">
                        {{ customField.type.label }}
                    </DataCell>
                    <DataCell name="label">
                        {{ customField.label }}
                        <BaseBadge
                            v-if="customField.isRequired"
                            design="primary"
                            >{{
                                $trans("custom_field.props.required")
                            }}</BaseBadge
                        >
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ customField.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                icon="fas fa-arrow-circle-right"
                                @click="
                                    router.push({
                                        name: 'CustomFieldShow',
                                        params: { uuid: customField.uuid },
                                    })
                                "
                                >{{ $trans("general.show") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                icon="fas fa-edit"
                                @click="
                                    router.push({
                                        name: 'CustomFieldEdit',
                                        params: { uuid: customField.uuid },
                                    })
                                "
                                >{{ $trans("general.edit") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                icon="fas fa-copy"
                                @click="
                                    router.push({
                                        name: 'CustomFieldDuplicate',
                                        params: { uuid: customField.uuid },
                                    })
                                "
                                >{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                icon="fas fa-trash"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: customField.uuid,
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
                        @click="router.push({ name: 'CustomFieldCreate' })"
                    >
                        {{
                            $trans("global.add", {
                                attribute: $trans("custom_field.custom_field"),
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
    name: "CustomFieldList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
import FilterForm from "./Filter.vue"
import { useColumnVisibility } from "@core/composables/useColumnVisibility"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["create", "filter"]

let dropdownActions = ["print", "pdf", "excel"]

const initUrl = "customField/"
const showFilter = ref(false)
const preRequisites = reactive({
    forms: [],
    types: [],
})

const customFields = reactive({})

const { applyVisibility } = useColumnVisibility("CustomField")

const setItems = (data) => {
    data.headers = applyVisibility(data.headers)
    Object.assign(customFields, data)
}

const setPreRequisites = (data) => {
    Object.assign(preRequisites, data)
}
</script>
