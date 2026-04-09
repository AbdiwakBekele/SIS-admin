<template>
    <div class="flex flex-col lg:flex-row gap-8 p-8 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-3xl shadow-2xl">
        <!-- Redemption Options Section -->
        <div class="w-full lg:w-1/2 bg-white rounded-2xl shadow-xl p-8">
            <div class="flex justify-between items-center mb-8 sticky top-0 bg-white z-10 pt-4">
                <h3 class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ $trans('gamification.redemption_option.redemption_options') }}
                </h3>
                <BaseButton design="secondary" size="sm" @click="fetchPreRequisites">
                    {{ $trans('general.refresh') }}
                </BaseButton>
            </div>
            <hr class="mb-8 border-gray-200 opacity-50">
            <div class="overflow-y-auto max-h-[80vh] scrollbar-custom">
                <table class="w-full table-auto text-sm text-left">
                    <thead>
                        <tr class="bg-indigo-50 text-gray-700 text-xs uppercase font-semibold tracking-wide">
                            <th class="px-6 py-4">{{ $trans('gamification.redemption_option.redemption_option') }}</th>
                            <th class="px-6 py-4">{{ $trans('gamification.redemption_option.props.points') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="option in preRequisites.redemptionOptions" :key="option.uuid"
                            class="border-b border-gray-100 hover:bg-indigo-50/50 transition duration-200">
                            <td class="px-6 py-5 text-gray-800 font-medium">{{ option.redemptionOption || 'N/A' }}</td>
                            <td class="px-6 py-5 text-gray-600">{{ option.points || 'N/A' }}</td>
                        </tr>
                        <tr v-if="!preRequisites.redemptionOptions.length" class="text-center">
                            <td colspan="2" class="px-6 py-5 text-gray-500 font-medium">
                                {{ $trans('general.no_records') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- My Redemption Requests Section -->
        <div class="w-full lg:w-1/2 bg-white rounded-2xl shadow-xl p-8">
            <div class="flex justify-between items-center mb-8 sticky top-0 bg-white z-10 pt-4">
                <h3 class="text-2xl font-bold text-gray-900 tracking-tight">My Redemption Requests</h3>
                <div class="flex items-center gap-2">
                    <BaseButton design="primary" size="sm" @click="showModal('create')" class="flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        {{ $trans('global.add', { attribute: $trans('gamification.redemption_request.redemption_request') }) }}
                    </BaseButton>
                    <BaseButton design="secondary" size="sm" @click="fetchMyRedemptionRequests">
                        {{ $trans('general.refresh') }}
                    </BaseButton>
                </div>
            </div>
            <hr class="mb-8 border-gray-200 opacity-50">
            <div class="overflow-y-auto max-h-[80vh] scrollbar-custom">
                <table class="w-full table-auto text-sm text-left">
                    <thead>
                        <tr class="bg-indigo-50 text-gray-700 text-xs uppercase font-semibold tracking-wide">
                            <th class="px-6 py-4">Reward</th>
                            <th class="px-6 py-4">Points</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="req in redemptionRequests" :key="req.uuid"
                            class="border-b border-gray-100 hover:bg-indigo-50/50 transition duration-200">
                            <td class="px-6 py-5 text-gray-800 font-medium">{{ req.redemptionOption || 'N/A' }}</td>
                            <td class="px-6 py-5 text-gray-600">{{ req.points || 'N/A' }}</td>
                            <td class="px-6 py-5">
                                <span :class="[
                                    'px-4 py-2 rounded-full text-xs font-semibold',
                                    req.status === 'Approved' ? 'bg-green-100 text-green-800' :
                                        req.status === 'Rejected' ? 'bg-red-100 text-red-800' :
                                            'bg-yellow-100 text-yellow-800'
                                ]">
                                    {{ req.status }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <FloatingMenu>
                                    <FloatingMenuItem v-if="perform('redemptionRequests:edit')" icon="fas fa-edit"
                                        @click="editRequest(req)">
                                        {{ $trans('general.edit') }}
                                    </FloatingMenuItem>
                                    <FloatingMenuItem v-if="perform('redemptionRequests:create')" icon="fas fa-copy"
                                        @click="duplicateRequest(req.uuid)">
                                        {{ $trans('general.duplicate') }}
                                    </FloatingMenuItem>
                                    <FloatingMenuItem v-if="perform('redemptionRequests:delete')" icon="fas fa-trash"
                                        @click="deleteRequest(req.uuid)">
                                        {{ $trans('general.delete') }}
                                    </FloatingMenuItem>
                                </FloatingMenu>
                            </td>
                        </tr>
                        <tr v-if="!redemptionRequests.length" class="text-center">
                            <td colspan="4" class="px-6 py-5 text-gray-500 font-medium">
                                {{ $trans('general.no_records') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Redemption Request Modal -->
    <BaseModal :visibility="modal.visible" :isLoading="isLoading" @close="modal.visible = false" size="sm">
        <template #title>
            {{ $trans('global.add', { attribute: $trans('gamification.redemption_request.redemption_request') }) }}
        </template>
        <div class="mt-4 grid grid-cols-1 gap-6">
            <div>
                <BaseSelect v-model="form.redemption_option_id" name="redemption_option_id"
                    :label="$trans('gamification.redemption_option.redemption_option')"
                    label-prop="redemptionOption" value-prop="uuid" :options="preRequisites.redemptionOptions" />
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <BaseButton design="primary" @click="submit" class="px-4 py-2">
                {{ $trans('general.save') }}
            </BaseButton>
            <BaseButton design="secondary" @click="modal.visible = false" class="px-4 py-2">
                {{ $trans('general.cancel') }}
            </BaseButton>
        </div>
    </BaseModal>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import { useStore } from "vuex";
import { perform } from "@core/helpers/action";
import { cloneDeep } from "@core/utils";

const store = useStore();
const route = useRoute();
const router = useRouter();
const toast = useToast();

const redemptionRequests = ref([]);
const redempOpt = ref([]);
const isLoading = ref(false);

const modal = reactive({
    visible: false,
    mode: "create",
});

const preRequisites = reactive({
    redemptionOptions: [],
});

const initForm = {
    redemption_option_id: "",
    status: "Pending",
};

const form = reactive({ ...initForm });

// const formErrors = computed(() => store.state['gamification/redemptionRequest'].formErrors);

const fetchMyRedemptionRequests = async () => {
    try {
        const response = await store.dispatch("gamification/redemptionRequest/fetchList", {
            studentUuid: store.state.auth.user.student_uuid,
            details: true,
        });
        console.log('Fetched my redemption requests:', response.data);
        redempOpt.value = response.data;
        console.log('Extracted redemption options12221:', redempOpt.value);
        for (const req of response.data) {
            if (req.redemptionRequests.length !== 0) {
                redemptionRequests.value.push(...req.redemptionRequests);
            }
        }
        console.log('Current redemption requests:', redemptionRequests.value);
    } catch (error) {
        console.error('Fetch error:', error.response?.data || error);
        toast.error('Failed to fetch redemption requests: ' + (error.response?.data?.message || error.message));
    }
};

const fetchPreRequisites = async () => {
    try {
        const response = await store.dispatch("gamification/redemptionRequest/preRequisite");
        Object.assign(preRequisites, response);
    } catch (error) {
        toast.error('Failed to fetch pre-requisites: ' + (error.response?.data?.message || error.message));
    }
};

const showModal = (mode) => {
    modal.mode = mode;
    modal.visible = true;
    Object.assign(form, cloneDeep(initForm));
};

const submit = async () => {
    if (!form.redemption_option_id) {
        toast.error("Redemption option is required");
        return;
    }
    try {
        isLoading.value = true;
        await store.dispatch("gamification/redemptionRequest/create", { form });
        modal.visible = false;
        await fetchMyRedemptionRequests();
        toast.success("Redemption request created");
    } catch (error) {
        toast.error("Failed to create redemption request: " + (error.response?.data?.message || error.message));
    } finally {
        isLoading.value = false;
    }
};

const editRequest = (req) => {
    router.push({ name: 'RedemptionRequestEdit', params: { uuid: req.uuid } });
};

const deleteRequest = async (uuid) => {
    if (confirm($trans('general.confirm_delete', { attribute: $trans('gamification.redemption_request.redemption_request') }))) {
        try {
            await store.dispatch('gamification/redemptionRequest/delete', uuid);
            toast.success($trans('global.deleted', { attribute: $trans('gamification.redemption_request.redemption_request') }));
            await fetchMyRedemptionRequests();
        } catch (error) {
            console.error('Delete error:', error.response?.data || error);
            toast.error('Failed to delete redemption request: ' + (error.response?.data?.message || error.message));
        }
    }
};

const duplicateRequest = async (uuid) => {
    try {
        await store.dispatch('gamification/redemptionRequest/duplicate', uuid);
        toast.success($trans('global.duplicated', { attribute: $trans('gamification.redemption_request.redemption_request') }));
        await fetchMyRedemptionRequests();
    } catch (error) {
        console.error('Duplicate error:', error.response?.data || error);
        toast.error('Failed to duplicate redemption request: ' + (error.response?.data?.message || error.message));
    }
};

onMounted(() => {
    fetchMyRedemptionRequests();
    fetchPreRequisites();
});
</script>

<style scoped>
.scrollbar-custom::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-custom::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #a5b4fc, #818cf8);
    border-radius: 12px;
}

.scrollbar-custom::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 12px;
}

.scrollbar-custom {
    scrollbar-width: thin;
    scrollbar-color: #818cf8 #f1f5f9;
}
</style>