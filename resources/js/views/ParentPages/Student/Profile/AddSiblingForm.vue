<template>
    <div class="space-y-4">
        <div>
            <label class="block text-gray-700 text-sm mb-2">Name</label>
            <input v-model="form.name" type="text"
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                placeholder="Enter full name" />
        </div>
        <div>
            <label class="block text-gray-700 text-sm mb-2">Email</label>
            <input v-model="form.email" type="email"
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                placeholder="Enter email" />
        </div>
        <div>
            <label class="block text-gray-700 text-sm mb-2">Phone</label>
            <input v-model="form.phone" type="tel"
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                placeholder="Enter phone number" />
        </div>
        <div>
            <label class="block text-gray-700 text-sm mb-2">Grade Level</label>
            <input v-model="form.gradeLevel" type="text"
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                placeholder="Enter grade level" />
        </div>
    </div>
</template>

<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    sibling: Object
});

const form = reactive({
    name: props.sibling?.name || '',
    email: props.sibling?.email || '',
    phone: props.sibling?.phone || '',
    gradeLevel: props.sibling?.gradeLevel || ''
});

watch(() => props.sibling, (newVal) => {
    form.name = newVal?.name || '';
    form.email = newVal?.email || '';
    form.phone = newVal?.phone || '';
    form.gradeLevel = newVal?.gradeLevel || '';
}, { deep: true });

watch(form, (newVal) => {
    $emit('update:sibling', { ...newVal });
}, { deep: true });

defineEmits(['update:sibling']);
</script>