<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong> Cafeteria Info </strong> </p>
        <hr class="mx-4">
        <div v-if="student?.cafeteria && (student.cafeteria.balance !== undefined || student.cafeteria.mealPlan)">
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-3">
                    <p class="text-sm"><strong>Balance</strong></p>
                    <p class="text-gray-600 text-sm mt-1">{{ formatCurrency(student.cafeteria.balance) }}</p>
                </div>
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-3">
                    <p class="text-sm"><strong>Meal Plan</strong></p>
                    <p class="text-gray-600 text-sm mt-1">{{ student.cafeteria.mealPlan || '-' }}</p>
                </div>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Cafeteria Information</p>
            <p class="text-xs text-gray-500 mt-1">Cafeteria information is not available at this time.</p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    student: Object
})

const formatCurrency = (value) => {
    if (value === null || value === undefined) return '-'
    try {
        return new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(Number(value) || 0)
    } catch {
        return `$${Number(value || 0).toFixed(2)}`
    }
}
</script>

