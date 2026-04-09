<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong> Library Info </strong> </p>
        <hr class="mx-4">
        <div v-if="student?.library && (student.library.checkedOutBooks?.length > 0 || student.library.fines !== undefined)">
            <div v-if="student.library.checkedOutBooks && student.library.checkedOutBooks.length > 0" class="mt-4">
                <p class="text-sm text-left mx-4 mb-2"><strong>Checked Out Books</strong></p>
                <div class="grid grid-cols-1 gap-2">
                    <div v-for="book in student.library.checkedOutBooks" :key="book.uuid || book.id"
                        class="bg-gray-100 border border-gray-300 rounded-lg p-3 text-left">
                        <p class="text-sm"><strong>{{ book.title }}</strong></p>
                        <p class="text-xs text-gray-600">Due Date: {{ book.dueDate?.formatted || book.dueDate || '-' }}</p>
                    </div>
                </div>
            </div>
            <div v-if="student.library.fines !== undefined" class="mt-4">
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-3">
                    <p class="text-sm"><strong>Fines</strong></p>
                    <p class="text-gray-600 text-sm mt-1">{{ formatCurrency(student.library.fines) }}</p>
                </div>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Library Information</p>
            <p class="text-xs text-gray-500 mt-1">Library information is not available at this time.</p>
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

