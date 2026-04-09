<template>
    <ListItem :init-url="initUrl" :additional-query="{ details: true }" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('new_enrollment.missing_document_type.props.document_types')" :navs="[
                { label: $trans('new_enrollment.new_enrollment'), path: '' },
            ]">
                <PageHeaderAction url="gamification/redemptionRequest/" name="StudentRedemptionRequests"
                    :title="$trans('new_enrollment.missing_document_type.props.document_type')" :actions="userActions"
                    :dropdown-actions="dropdownActions" :additional-dropdown-actions-query="{ details: true }"
                    >
                </PageHeaderAction>
            </PageHeader>
        </template>

        <!-- <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template> -->

        <ParentTransition appear :visibility="true">
            <DataTable :header="documentTypes.headers" :meta="documentTypes.meta"
                module="withdrawals.exit_interviews" @refresh="emitter.emit('listItems')">
                <DataRow v-for="interview in documentTypes.data" :key="interview.uuid" @double-click="
                    router.push({
                        name: 'StudentRedemptionRequestsShow',
                        params: { uuid: interview.uuid },
                    })
                    ">
                    <DataCell name="student">
                        {{ interview.documentType || 'N/A' }}
                    </DataCell>
                    <DataCell name="createdAt">
                        {{ interview.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="actions">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'StudentRedemptionRequestsShow',
                                    params: { uuid: interview.uuid },
                                })
                                ">{{ $trans('general.view') }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('studentRedemptionRequests:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'documentTypesEdit',
                                    params: { uuid: interview.uuid },
                                })
                                ">{{ $trans('general.edit') }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('studentRedemptionRequests:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'StudentRedemptionRequestsDuplicate',
                                    params: { uuid: interview.uuid },
                                })
                                ">{{ $trans('general.duplicate') }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('studentRedemptionRequests:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', { uuid: interview.uuid })
                                ">{{ $trans('general.delete') }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton v-if="perform('studentRedemptionRequests:create')"
                        @click="router.push({ name: 'documentTypesCreate' })">
                        {{ $trans('global.add', { attribute: $trans('withdrawals.exit_interviews.exit_interviews') }) }}
                    </BaseButton>
                </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "StudentRedemptionRequestsList",
}
</script>

<script setup>
import { ref, reactive, inject, watch } from "vue";
import { useRouter } from "vue-router";
import { perform } from "@core/helpers/action";
// import FilterForm from "./Filter.vue";

const router = useRouter();
const emitter = inject("emitter");

let userActions = []; // ["filter"] - removed since Filter.vue is missing
if (perform("studentRedemptionRequests:create")) {
    userActions.unshift("create");
}

let dropdownActions = [];
if (perform("studentRedemptionRequests:export")) {
    dropdownActions = ["print", "pdf", "excel"];
}

const initUrl = "gamification/redemptionRequest/";
const showFilter = ref(false);
const documentTypes = reactive({});

const setItems = (data) => {
    Object.assign(documentTypes, data);
};

watch(
    () => documentTypes.data,
    (newData) => {
        if (Array.isArray(newData) && newData.length === 0) {
            router.push({ name: "StudentRedemptionRequestsCreate" })
        }
    }
)

</script>