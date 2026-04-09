<template>
    <div class="fixed bottom-5 right-5 z-50">
        <!-- Minimized chat icon -->
        <transition name="fade">
            <div
                v-if="!isOpen"
                @click="toggleChat"
                class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center cursor-pointer shadow-lg transition-all duration-300 ease-in-out hover:bg-blue-600"
            >
                <i class="fas fa-comment text-white"></i>
            </div>
        </transition>

        <!-- Expanded chat box -->
        <transition name="slide-up">
            <div
                v-if="isOpen"
                class="w-80 h-96 bg-white rounded-lg shadow-lg flex flex-col overflow-hidden"
            >
                <div
                    class="bg-blue-500 text-white p-3 flex items-center justify-between"
                >
                    <button
                        v-if="selectedChat"
                        @click="goBack"
                        class="text-white focus:outline-none"
                    >
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h3 v-if="selectedChat" class="text-lg font-semibold">
                        {{ headerTitle }}
                    </h3>
                    <div
                        v-else
                        class="flex items-center bg-blue-400 rounded-full overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{
                            'w-full': isSearchExpanded,
                            'w-8': !isSearchExpanded,
                        }"
                    >
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search chats..."
                            class="bg-transparent text-white placeholder-blue-200 px-3 py-1 w-full focus:outline-none"
                            :class="{
                                'w-0': !isSearchExpanded,
                                'w-full': isSearchExpanded,
                            }"
                            @blur="collapseSearch"
                        />
                        <button
                            @click="toggleSearch"
                            class="text-white p-1 focus:outline-none"
                        >
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <button
                        @click="toggleChat"
                        class="text-white text-xl focus:outline-none"
                    >
                        &times;
                    </button>
                </div>
                <div v-if="!selectedChat" class="flex-grow overflow-y-auto">
                    <div
                        v-for="chat in filteredChats"
                        :key="chat.id"
                        @click="selectChat(chat)"
                        class="p-3 border-b border-gray-200 cursor-pointer hover:bg-gray-100"
                    >
                        {{ chat.name }}
                    </div>
                </div>
                <div v-else class="flex-grow overflow-y-auto p-3">
                    <div
                        v-for="message in selectedChat.messages"
                        :key="message.id"
                        class="bg-gray-100 rounded-lg p-2 mb-2 max-w-[80%]"
                    >
                        {{ message.text }}
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed } from "vue"

const isOpen = ref(false)
const selectedChat = ref(null)
const searchQuery = ref("")
const isSearchExpanded = ref(false)

const chats = ref([
    { id: 1, name: "Chat 1", messages: [{ id: 1, text: "Hello from Chat 1" }] },
    { id: 2, name: "Chat 2", messages: [{ id: 1, text: "Hello from Chat 2" }] },
    { id: 3, name: "Chat 3", messages: [{ id: 1, text: "Hello from Chat 3" }] },
])

const headerTitle = computed(() => {
    return selectedChat.value ? selectedChat.value.name : "Chats"
})

const filteredChats = computed(() => {
    return chats.value.filter((chat) =>
        chat.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

const toggleChat = () => {
    isOpen.value = !isOpen.value
    if (!isOpen.value) {
        selectedChat.value = null
        searchQuery.value = ""
    }
}

const selectChat = (chat) => {
    selectedChat.value = chat
    searchQuery.value = ""
}

const goBack = () => {
    selectedChat.value = null
}

const toggleSearch = () => {
    isSearchExpanded.value = !isSearchExpanded.value
    if (isSearchExpanded.value) {
        setTimeout(() => {
            document.querySelector('input[type="text"]').focus()
        }, 100)
    }
}

const collapseSearch = () => {
    if (searchQuery.value === "") {
        isSearchExpanded.value = false
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active,
.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scale(0.5);
}

.slide-up-enter-from,
.slide-up-leave-to {
    transform: translateY(100%);
    opacity: 0;
}

@media (max-width: 640px) {
    .w-80 {
        width: 100%;
    }
    .h-96 {
        height: 50vh;
    }
    .fixed {
        bottom: 0;
        right: 0;
    }
}
</style>
