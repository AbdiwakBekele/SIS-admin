<template>
    <div class="bg-white rounded-lg p-6">
        <div class="flex justify-between">
            <h3 class="text-sm font-semibold mb-4">Syllabus</h3>
            <button @click="emit('add-syllabus')" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Add Syllabus</button>
        </div>
        <table class="w-full table-auto text-sm border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Week</th>
                    <th class="px-4 py-2 text-left">Topic</th>
                    <th class="px-4 py-2 text-left">Readings</th>
                    <th class="px-4 py-2 text-left">Resources</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="s in syllabus" :key="s.week" class="border-b">
                    <td class="px-4 py-2">{{ s.week }}</td>
                    <td class="px-4 py-2">{{ s.topic }}</td>
                    <td class="px-4 py-2">{{ s.readings }}</td>
                    <td class="px-4 py-2">
                        <a v-for="(res, i) in s.resources" :key="i" :href="res.link"
                            class="text-blue-600 underline mr-2" target="_blank">
                            {{ res.label }}
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
const props = defineProps({
    syllabus: { type: Array, default: () => [] },
})

const emit = defineEmits(['add-syllabus'])
</script>

