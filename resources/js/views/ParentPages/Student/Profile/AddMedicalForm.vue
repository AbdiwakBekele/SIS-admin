<template>
  <div class="space-y-4">
    <div>
      <label class="block text-gray-700 text-sm mb-2">Allergies</label>
      <textarea
        v-model="form.allergies"
        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
        placeholder="Enter allergies"
        rows="4"
      ></textarea>
    </div>
    <div>
      <label class="block text-gray-700 text-sm mb-2">Medical/Mental Health Conditions</label>
      <textarea
        v-model="form.conditions"
        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
        placeholder="Enter conditions"
        rows="4"
      ></textarea>
    </div>
    <div>
      <label class="block text-gray-700 text-sm mb-2">Medications</label>
      <textarea
        v-model="form.medications"
        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
        placeholder="Enter medications"
        rows="4"
      ></textarea>
    </div>
    <div>
      <label class="block text-gray-700 text-sm mb-2">Physicians/Providers</label>
      <textarea
        v-model="form.physicians"
        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
        placeholder="Enter physicians"
        rows="4"
      ></textarea>
    </div>
    <div>
      <label class="block text-gray-700 text-sm mb-2">Insurance</label>
      <textarea
        v-model="form.insurance"
        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
        placeholder="Enter insurance details"
      ></textarea>
    </div>
    <div>
      <label class="block text-gray-700 text-sm mb-2">Blood Type</label>
      <input
        v-model="form.bloodType"
        type="text"
        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
        placeholder="Enter blood type"
      />
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
  medical: Object
});

const form = reactive({
  allergies: props.medical?.allergies || '',
  conditions: props.medical?.conditions || '',
  medications: props.medical?.medications || '',
  physicians: props.medical?.physicians || '',
  insurance: props.medical?.insurance || '',
  bloodType: props.medical?.bloodType || ''
});

watch(() => props.medical, (newVal) => {
  form.allergies = newVal?.allergies || '';
  form.conditions = newVal?.conditions || '';
  form.medications = newVal?.medications || '';
  form.physicians = newVal?.physicians || '';
  form.insurance = newVal?.insurance || '';
  form.bloodType = newVal?.bloodType || '';
}, { deep: true });

watch(form, (newVal) => {
  $emit('update:medical', { ...newVal });
}, { deep: true });

defineEmits(['update:medical']);
</script>
