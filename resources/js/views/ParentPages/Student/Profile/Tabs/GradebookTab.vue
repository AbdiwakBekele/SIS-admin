<template>
    <div class="bg-white rounded-lg border-r px-4 py-5 text-center mt-2">
        <!-- Semester I Grades -->
        <div v-if="false" class="mb-5">
            <p class="text-left"> <strong>Semester I</strong>
            </p>
            <div class="rounded-xl overflow-hidden shadow-lg ring-1 ring-blue-300/50 dark:ring-white/10 mt-2">
                <table class="min-w-full divide-y-2 divide-blue-400 dark:divide-blue-300 bg-white dark:bg-neutral-900">
                    <thead class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white shadow-md">
                        <tr>
                            <th scope="col" class="sm:px-2 xl:px-4 py-4 text-left text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                Course Title
                            </th>
                            <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                HW1
                            </th>
                            <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                HW2
                            </th>
                            <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                HW3
                            </th>
                            <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                Q1
                            </th>
                            <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                Q2
                            </th>
                            <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                Q3
                            </th>
                            <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                MTE
                            </th>
                            <th scope="col" class="sm:px-2 xl:px-4 py-4 text-center text-[13px] font-semibold uppercase tracking-wider border-b border-r border-blue-700 dark:border-blue-500 last:border-r-0">
                                Total
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y-2 divide-blue-400 dark:divide-blue-300 striped-rows">
                        <tr class="data-row">
                            <td class="py-2 sm:px-2 xl:px-4 text-sm border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">Mathematics</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">40</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center last:border-r-0 text-gray-900 dark:text-gray-400">100</td>
                        </tr>

                        <tr class="data-row">
                            <td class="py-2 sm:px-2 xl:px-4 text-sm border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">Chemistry</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">40</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center last:border-r-0 text-gray-900 dark:text-gray-400">100</td>
                        </tr>

                        <tr class="data-row">
                            <td class="py-2 sm:px-2 xl:px-4 text-sm border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">Biology</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">40</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center last:border-r-0 text-gray-900 dark:text-gray-400">100</td>
                        </tr>

                        <tr class="data-row">
                            <td class="py-2 sm:px-2 xl:px-4 text-sm border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">Physics</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">10</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center border-r-2 border-blue-400 dark:border-blue-300 text-gray-900 dark:text-gray-400">40</td>
                            <td class="py-2 sm:px-2 xl:px-4 text-sm text-center last:border-r-0 text-gray-900 dark:text-gray-400">100</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- No Progress Report Message -->
        <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Progress Reports</p>
            <p class="text-xs text-gray-500 mt-1">Progress reports are not available at this time.</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    student: Object
})
</script>

<style scoped>
/* Alternating stripes (light mode) using nth-child(of ...) so only data rows are counted */
tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        odd of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(252 253 255) !important; /* custom very light */
}

tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        even of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(222 235 255) !important; /* custom softer blue */
}

/* Alternating stripes (dark mode) */
.dark
    tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        odd of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(18 24 37) !important; /* slightly lighter dark */
}

.dark
    tbody.striped-rows
    > tr.data-row:not(.selection-banner):not(.empty-state-row):nth-child(
        even of tr.data-row:not(.selection-banner):not(.empty-state-row)
    ) {
    background-color: rgb(59 130 246 / 0.25) !important; /* blue-500 @ 25% */
}

/* Remove right border from last column in header */
thead tr th:last-child {
    border-right: none !important;
}

/* Remove right border from last column in body cells */
tbody tr td:last-child {
    border-right: none !important;
}
</style>
