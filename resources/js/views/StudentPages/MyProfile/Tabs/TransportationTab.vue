<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong> Transportation Info </strong> </p>
        <hr class="mx-4">
        <div v-if="student?.transportation && (student.transportation.route || student.transportation.stop)">
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-3">
                    <p class="text-sm"><strong>Route</strong></p>
                    <p class="text-gray-600 text-sm mt-1">{{ student.transportation.route || '-' }}</p>
                </div>
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-3">
                    <p class="text-sm"><strong>Stop</strong></p>
                    <p class="text-gray-600 text-sm mt-1">{{ student.transportation.stop || '-' }}</p>
                </div>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Transportation Information</p>
            <p class="text-xs text-gray-500 mt-1">Transportation information is not available at this time.</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    student: Object
})
</script>

