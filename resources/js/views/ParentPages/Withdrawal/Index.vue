<template>
    <ListItem :init-url="initUrl" :additional-query="{ details: true }" @setItems="setItems">
        <template #header>
            <PageHeader :title="`Withdrawal Form Lists`" >
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="withdrawals.headers" :meta="withdrawals.meta" module="student.withdrawal"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="withdrawal in withdrawals.data" :key="withdrawal.uuid" @double-click="
                    router.push({
                        name: 'ParentWithdrawalEdit',
                        params: { uuid: withdrawal.uuid },
                    })
                    ">
                    <DataCell name="student">
                        {{ withdrawal.student || "-" }}
                    </DataCell>
                    <DataCell name="Parentwithdrawal">
                        {{ withdrawal.withdrawnBy || "-" }}
                    </DataCell>

                    <DataCell name="transferingTo">
                        {{ withdrawal.transferingTo || "-" }}
                    </DataCell>

                    <DataCell name="withdrawalDate">
                        {{ withdrawal.withdrawalDate.formatted || "-" }}
                    </DataCell>
                    <DataCell name="status">
                        <span v-if="(withdrawal.meta?.withdrawalStatus || withdrawal.meta?.withdrawal_status) === 'pending'"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                        <span v-else-if="(withdrawal.meta?.withdrawalStatus || withdrawal.meta?.withdrawal_status) === 'submitted'"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Submitted
                        </span>
                        <span v-else-if="(withdrawal.meta?.withdrawalStatus || withdrawal.meta?.withdrawal_status) === 'processed'"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Processed
                        </span>
                        <span v-else class="text-gray-500 text-xs">Unknown</span>
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentWithdrawalEdit',
                                    params: { uuid: withdrawal.uuid },
                                })
                                ">{{ $trans("general.create") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('batch:edit')" icon="fas fa-edit" @click="
                                router.push({
                                    name: 'ParentWithdrawalEdit',
                                    params: { uuid: withdrawal.uuid },
                                })
                                ">{{ $trans("general.edit") }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('batch:create')" icon="fas fa-copy" @click="
                                router.push({
                                    name: 'ParentWithdrawalDuplicate',
                                    params: { uuid: withdrawal.uuid },
                                })
                                ">{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem>
                            <FloatingMenuItem v-if="perform('batch:delete')" icon="fas fa-trash" @click="
                                emitter.emit('deleteItem', {
                                    uuid: withdrawal.uuid,
                                })
                                ">{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "ParentWithdrawalList",
}
</script>

<script setup>
import { ref, reactive, inject, onMounted } from "vue"
import { useRouter } from "vue-router"
import { useStore } from 'vuex'
import { perform } from "@core/helpers/action"
import FilterForm from "./Filter.vue"

console.log('index opened');

const router = useRouter()
const store = useStore()
const emitter = inject("emitter")

let userActions = ["filter"]
if (perform("batch:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("batch:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "student/withdrawal/"
const showFilter = ref(false)
const withdrawals = reactive({
    data: [],
    headers: [],
    meta: {},
    links: {}
})

onMounted(async () => {
    console.log('Loading parent withdrawals...');
    await loadParentWithdrawals();
});

const loadParentWithdrawals = async () => {
    try {
        console.log('Dispatching parentIndex...');
        const response = await store.dispatch('student/withdrawal/parentIndex');

        console.log('Full dispatch response:', response);
        console.log('Response type:', typeof response);
        console.log('Response data:', response.data);

        let withdrawalData = [];
        let headers = [];
        let meta = {};
        let links = {};

        if (response.data && Array.isArray(response.data.data)) {
            withdrawalData = response.data.data;
            headers = response.data.headers || [];
            meta = response.data.meta || {};
            links = response.data.links || {};
        }
        // Check if response.data is the paginated collection
        else if (response.data && response.data.data && Array.isArray(response.data.data)) {
            // Response structure: { data: { data: [...], ... } }
            withdrawalData = response.data.data;
            headers = response.data.headers || [];
            meta = response.data.meta || {};
            links = response.data.links || {};
        }
        // Check if response is the paginated collection directly
        else if (response.data && Array.isArray(response.data)) {
            // Response structure: { data: [...] }
            withdrawalData = response.data;
            headers = response.headers || [];
            meta = response.meta || {};
            links = response.links || {};
        }
        // Check if response is an array directly (all records)
        else if (Array.isArray(response)) {
            withdrawalData = response;
        }
        // Fallback: try to extract from response.data
        else if (response.data) {
            withdrawalData = Array.isArray(response.data) ? response.data : [];
        }

        console.log('Extracted withdrawal data:', withdrawalData);
        console.log('Extracted headers:', headers);
        console.log('Data length:', withdrawalData.length);

        // Update the reactive withdrawals object
        Object.assign(withdrawals, {
            data: withdrawalData,
            headers: headers,
            meta: meta,
            links: links
        });

        console.log('Updated withdrawals object:', withdrawals);
        console.log('Final data length:', withdrawals.data.length);

    } catch (error) {
        console.error('Failed to load parent withdrawals:', error);
        console.error('Error response:', error.response?.data);

        // Set empty data on error
        Object.assign(withdrawals, {
            data: [],
            headers: [],
            meta: {},
            links: {}
        });
    }
};

const parentIndex = async () => {
    console.log('parentIndex triggered');
    await loadParentWithdrawals();

    emitter.emit('listItems'); // Trigger any list refresh events
    console.log('Parent index loaded successfully via dispatch');
    return true;
};

const setItems = (data) => {
    console.log('setItems called (should not happen for parent view):', data);
    Object.assign(withdrawals, data)
}
</script>