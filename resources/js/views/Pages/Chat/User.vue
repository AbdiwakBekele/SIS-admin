<template>
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <div class="text-xl font-semibold dark:text-white">
                {{ $trans("chat.chats") }}
            </div>
            <button
                v-if="!state.showUserSearch"
                @click="toggleUserSearch"
                class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100"
            >
                <i class="fas fa-pen-to-square fa-lg"></i>
            </button>
            <button
                v-if="state.showUserSearch"
                @click="toggleUserSearch"
                class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100"
            >
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>
        <div v-if="!state.showUserSearch" class="relative mt-4">
            <input
                v-model="state.chatSearchQuery"
                type="text"
                :placeholder="$trans('chat.search_chats')"
                class="w-full border border-gray-300 dark:border-gray-600 rounded-full px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-dark-body dark:text-white"
                @input="debouncedChatSearch"
                @keydown.esc="closeChatSearch"
            />
            <button
                v-if="state.chatSearchQuery"
                @click="clearChatSearch"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                type="button"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div v-else class="relative mt-4">
            <input
                v-model="state.userSearchQuery"
                type="text"
                :placeholder="$trans('chat.search_users')"
                class="w-full border border-gray-300 dark:border-gray-600 rounded-full px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-dark-body dark:text-white"
                @input="debouncedUserSearch"
                @keydown.esc="closeUserSearch"
            />
            <button
                v-if="state.userSearchQuery"
                @click="clearUserSearch"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                type="button"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <ul
        class="flex-1 scroller-thin-y scroller-hidden h-0"
        ref="chatList"
        @scroll="handleScroll"
    >
        <template v-if="!state.showUserSearch">
            <li
                v-for="chat in state.chats"
                :key="chat.uuid"
                @click="onChatSelect(chat)"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
            >
                <div
                    class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700"
                >
                    <div class="flex items-center">
                        <img
                            :src="chat.avatar"
                            :alt="chat.name"
                            class="w-10 h-10 rounded-full mr-3"
                        />
                        <div class="font-medium dark:text-white">
                            {{ chat.name }}
                            <TextMuted block v-if="chat.latestMessage">{{
                                chat.latestMessage.content
                            }}</TextMuted>
                        </div>
                    </div>
                    <div>
                        <span
                            class="px-2 py-1 bg-primary text-white text-xs rounded-full"
                            v-if="chat.unreadCount > 0"
                        >
                            {{ chat.unreadCount }}
                        </span>
                    </div>
                </div>
            </li>
            <li v-if="state.chats.length === 0 && state.chatSearchQuery">
                <div class="p-4 text-sm text-gray-500 dark:text-gray-400">
                    {{ $trans("chat.no_chats_found") }}
                </div>
            </li>
            <li v-if="state.isLoading" class="p-4 text-center">
                <span class="text-gray-500 dark:text-gray-400">{{
                    $trans("chat.loading")
                }}</span>
            </li>
        </template>
        <template v-else>
            <li
                v-for="user in state.searchedUsers"
                :key="user.id"
                @click="startChatWithUser(user)"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
            >
                <div
                    class="flex items-center p-4 border-b border-gray-200 dark:border-gray-700"
                >
                    <img
                        :src="user.avatar"
                        :alt="user.name"
                        class="w-10 h-10 rounded-full mr-3"
                    />
                    <div class="font-medium dark:text-white">
                        {{ user.name }}
                    </div>
                </div>
            </li>
            <li
                v-if="state.searchedUsers.length === 0 && state.userSearchQuery"
            >
                <div class="p-4 text-sm text-gray-500 dark:text-gray-400">
                    {{ $trans("chat.no_users_found") }}
                </div>
            </li>
        </template>
    </ul>
</template>

<script>
export default {
    name: "ChatUser",
}
</script>

<script setup>
import { reactive, onMounted, ref } from "vue"
import { useRoute } from "vue-router"
import { useStore } from "vuex"
import _ from "lodash"

const emit = defineEmits(["onSelectChat"])

const route = useRoute()
const store = useStore()

const chatList = ref(null)

const state = reactive({
    chats: [],
    selectedChat: null,
    userSearchQuery: "",
    chatSearchQuery: "",
    searchedUsers: [],
    showUserSearch: false,
    isLoading: false,
    nextCursor: null,
    hasMoreChats: true,
})

const getChats = async (cursor = null) => {
    if (!state.hasMoreChats && cursor) return

    state.isLoading = true

    try {
        const response = await store.dispatch("chat/getChats", {
            cursor,
            search: state.chatSearchQuery,
        })

        if (cursor) {
            state.chats.push(...response.data)
        } else {
            state.chats = response.data
        }

        state.nextCursor = response.meta.nextCursor
        state.hasMoreChats = !!response.meta.nextCursor
    } catch (error) {
        console.error("Failed to get chats:", error)
    } finally {
        state.isLoading = false
    }
}

const handleScroll = _.debounce(() => {
    if (!chatList.value) return

    const { scrollTop, scrollHeight, clientHeight } = chatList.value
    if (
        scrollTop + clientHeight >= scrollHeight - 100 &&
        !state.isLoading &&
        state.hasMoreChats
    ) {
        getChats(state.nextCursor)
    }
}, 200)

const selectChat = async (uuid) => {
    await store
        .dispatch("chat/getChat", uuid)
        .then((response) => {
            state.selectedChat = response
            emit("onSelectChat", state.selectedChat)
        })
        .catch((error) => {
            console.error("Failed to get chat:", error)
        })
    state.isChatOpen = true
}

const toggleUserSearch = () => {
    state.showUserSearch = !state.showUserSearch
    state.userSearchQuery = ""
    state.searchedUsers = []
}

const closeUserSearch = () => {
    state.showUserSearch = false
    state.userSearchQuery = ""
    state.searchedUsers = []
}

const closeChatSearch = () => {
    state.chatSearchQuery = ""
    getChats()
}

const onChatSelect = async (chat) => {
    chat.unreadCount = 0
    await selectChat(chat.uuid)
}

const searchUsers = async () => {
    state.chatSearchQuery = ""
    if (state.userSearchQuery.trim()) {
        try {
            const users = await store.dispatch(
                "chat/searchUsers",
                state.userSearchQuery
            )
            state.searchedUsers = users
        } catch (error) {
            console.error("Failed to search users:", error)
        }
    } else {
        state.searchedUsers = []
    }
}

const startChatWithUser = async (user) => {
    try {
        const newChat = await store.dispatch("chat/createChat", {
            name: user.name,
            is_group_chat: false,
            participants: [user.uuid],
        })

        if (state.chats.find((chat) => chat.uuid == newChat.uuid)) {
            await selectChat(newChat.uuid)
            closeUserSearch()
            return
        }

        state.chats.unshift({
            uuid: newChat.uuid,
            name: user.name,
            avatar: user.avatar,
        })

        await selectChat(newChat.uuid)
        closeUserSearch()
    } catch (error) {
        console.error("Failed to start chat with user:", error)
    }
}

// Add this method to delete a chat
const deleteChat = async () => {
    if (state.selectedChat) {
        try {
            await store.dispatch("chat/deleteChat", state.selectedChat.uuid)
            state.isChatOpen = false
        } catch (error) {
            console.error("Failed to delete chat:", error)
        }
    }
}

const clearChatSearch = () => {
    state.chatSearchQuery = ""
    getChats()
}

const clearUserSearch = () => {
    state.userSearchQuery = ""
    state.searchedUsers = []
}

const debouncedChatSearch = _.debounce(() => {
    state.chats = [] // Clear existing chats
    state.nextCursor = null // Reset cursor
    state.hasMoreChats = true // Reset hasMoreChats
    getChats() // Fetch chats with the search query
}, 300)
const debouncedUserSearch = _.debounce(searchUsers, 300)

onMounted(async () => {
    await getChats()

    if (route.params.uuid) {
        await selectChat(route.params.uuid)
    }

    // state.chats.forEach((chat) => {
    //     window.Echo.private(`chat.${chat.uuid}`).listen("MessageSent", (e) => {
    //         console.log(e)
    //     })
    // })
})
</script>
