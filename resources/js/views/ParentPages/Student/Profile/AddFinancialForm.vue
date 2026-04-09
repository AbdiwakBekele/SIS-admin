<template>
    <div class="space-y-4">
        <div>
            <label class="block text-gray-700 text-sm mb-2">Bank Name</label>
            <input v-model="form.bankName" type="text"
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                placeholder="Enter bank name" />
        </div>
        <div>
            <label class="block text-gray-700 text-sm mb-2">Branch</label>
            <input v-model="form.branch" type="text"
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                placeholder="Enter branch" />
        </div>
        <div>
            <label class="block text-gray-700 text-sm mb-2">IFSC</label>
            <input v-model="form.ifsc" type="text"
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                placeholder="Enter IFSC code" />
        </div>
    </div>
</template>

<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    financial: Object
});

const form = reactive({
    bankName: props.financial?.bankName || '',
    branch: props.financial?.branch || '',
    ifsc: props.financial?.ifsc || ''
});

watch(() => props.financial, (newVal) => {
    form.bankName = newVal?.bankName || '';
    form.branch = newVal?.branch || '';
    form.ifsc = newVal?.ifsc || '';
}, { deep: true });

watch(form, (newVal) => {
    $emit('update:financial', { ...newVal });
}, { deep: true });

defineEmits(['update:financial']);
</script>