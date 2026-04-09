<template>
    <div class="bg-white rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold">Discussion</h3>
        </div>

        <div v-for="thread in discussions" :key="thread.id"
            class="border border-gray-300 bg-gray-100 p-4 mb-4 rounded-lg">
            <div class="grid grid-cols-5 items-center cursor-pointer" @click="toggleThread(thread.id)">
                <div class="col-span-3">
                    <h4 class="font-medium">{{ thread.title }}</h4>
                    <div class="text-xs text-gray-500">by {{ thread.author.name }} • {{ thread.createdAt }}</div>
                </div>
                <div class="col-span-1 text-sm">
                    <span>Replies: {{ thread.comments.length }}</span>
                    <button class="text-blue-600 underline mx-3">
                        {{ openThreadId === thread.id ? 'Hide Replies' : 'View Replies' }}
                    </button>
                </div>
            </div>

            <div v-if="openThreadId === thread.id" class="mt-4 space-y-3">
                <div v-for="c in thread.comments" :key="c.id" class="bg-white p-3 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">{{ c.author.name }}</span>
                        <span>
                            <span class="text-xs text-gray-400">{{ c.createdAt }}</span>
                            <i class="fa-solid fa-thumbs-up mx-5"></i>
                        </span>
                    </div>
                    <p class="text-sm mt-1">{{ c.body }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
    discussions: { type: Array, default: () => [] },
})

const emit = defineEmits([])

const openThreadId = ref(null)

function toggleThread(id) { 
    openThreadId.value = openThreadId.value === id ? null : id 
}
</script>

