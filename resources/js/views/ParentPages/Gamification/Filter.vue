<template>
    <div
        class="absolute right-0 w-96 bg-white border border-gray-400 rounded-lg shadow-xl z-50 p-4 space-y-4 translate-x-[-5rem]">
        <!-- Date Range -->
        <div>
            <label class="text-sm font-medium text-gray-700">Filter</label>
            <div class="flex justify-between">
                <input type="date" v-model="filters.startDate"
                    class="form-input mt-1 mr-2 w-1/2 border border-gray-300 rounded-lg text-sm" />
                <input type="date" v-model="filters.endDate"
                    class="form-input mt-1 w-1/2 border border-gray-300 rounded-lg text-sm" />
            </div>
        </div>

        <div class="flex justify-between">
            <button class="bg-gray-100 text-gray-800 px-4 py-2 border border-gray-300 rounded-lg text-sm"
                @click="reset">Reset</button>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm" @click="apply">Apply</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
const emit = defineEmits(['refresh', 'hide'])

const filters = ref({
    grade: '',
    gender: '',
    status: '',
    ageGroup: '',
    startDate: '',
    endDate: '',
})

const grades = [1, 2, 3, 4, 5, 6, 7, 8]

const apply = () => {
    emit('refresh', filters.value)
    emit('hide')
}

const reset = () => {
    filters.value = {
        grade: '',
        gender: '',
        status: '',
        ageGroup: '',
        startDate: '',
        endDate: '',
    }
}
</script>
