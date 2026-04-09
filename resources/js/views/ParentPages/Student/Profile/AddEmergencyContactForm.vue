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
    </div>
</template>

<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    emergencyContact: Object
});

const emit = defineEmits(['update:emergencyContact']);

const form = reactive({
    name: props.emergencyContact?.name || '',
    email: props.emergencyContact?.email || '',
    phone: props.emergencyContact?.phone || ''
});

watch(() => props.emergencyContact, (newVal) => {
    form.name = newVal?.name || '';
    form.email = newVal?.email || '';
    form.phone = newVal?.phone || '';
}, { deep: true });

watch(form, (newVal) => {
    emit('update:emergencyContact', { ...newVal });
}, { deep: true });
</script>