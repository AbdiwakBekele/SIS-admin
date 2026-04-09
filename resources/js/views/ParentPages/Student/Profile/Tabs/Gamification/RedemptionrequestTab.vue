<template>
    <div class="flex flex-col lg:flex-row gap-8 p-8 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-3xl shadow-2xl">
        <!-- Redemption Options Section -->
        <div class="w-full lg:w-1/2 bg-white rounded-2xl shadow-xl p-8">
            <div class="flex justify-between items-center mb-8 sticky top-0 bg-white z-10 pt-4">
                <h3 class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ $trans('gamification.redemption_option.redemption_options') }}
                </h3>
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
                        <tr v-for="option in redemptionOptions" :key="option.uuid"
                            class="border-b border-gray-100 hover:bg-indigo-50/50 transition duration-200">
                            <td class="px-6 py-5 text-gray-800 font-medium">{{ option.redemptionOption || 'N/A' }}</td>
                            <td class="px-6 py-5 text-gray-600">{{ option.points || 'N/A' }}</td>
                        </tr>
                        <tr v-if="!redemptionOptions.length" class="text-center">
                            <td colspan="2" class="px-6 py-5 text-gray-500 font-medium">
                                {{ $trans('general.no_records') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Redemption Requests Section -->
        <div class="w-full lg:w-1/2 bg-white rounded-2xl shadow-xl p-8">
            <div class="flex justify-between items-center mb-8 sticky top-0 bg-white z-10 pt-4">
                <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Redemption Requests</h3>
            </div>
            <hr class="mb-8 border-gray-200 opacity-50">
            <div class="overflow-y-auto max-h-[80vh] scrollbar-custom">
                <table class="w-full table-auto text-sm text-left">
                    <thead>
                        <tr class="bg-indigo-50 text-gray-700 text-xs uppercase font-semibold tracking-wide">
                            <th class="px-6 py-4">Reward</th>
                            <th class="px-6 py-4">Points</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="req in redemptionRequests" :key="req.uuid"
                            class="border-b border-gray-100 hover:bg-indigo-50/50 transition duration-200">
                            <td class="px-6 py-5 text-gray-800 font-medium">{{ req.redemption_option || req.redemptionOption || 'N/A' }}</td>
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
                        </tr>
                        <tr v-if="!redemptionRequests.length" class="text-center">
                            <td colspan="3" class="px-6 py-5 text-gray-500 font-medium">
                                {{ $trans('general.no_records') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { inject, ref, onMounted } from "vue";
import { useStore } from "vuex";

const store = useStore();
const emitter = inject("emitter");
const $trans = inject("$trans");

const props = defineProps({
    redemptionRequests: {
        type: Array,
        required: true,
    },
});

const redemptionOptions = ref([]);

const fetchRedemptionOptions = async () => {
    try {
        const response = await store.dispatch("gamification/redemptionRequest/preRequisite");
        redemptionOptions.value = response.redemptionOptions || [];
    } catch (error) {
        console.error('Failed to fetch redemption options:', error);
    }
};

onMounted(() => {
    fetchRedemptionOptions();
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

