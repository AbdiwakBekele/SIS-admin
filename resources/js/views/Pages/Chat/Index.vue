<template>
    <div
        class="flex flex-col md:flex-row h-[calc(100vh_-_100px)] bg-gray-100 dark:bg-dark-header"
    >
        <div
            :class="[
                'w-full md:w-1/4 bg-white dark:bg-dark-header border-r border-gray-200 dark:border-gray-700 flex flex-col',
                { 'hidden md:flex': state.selectedChat },
            ]"
        >
            <ChatUser @onSelectChat="selectChat" />
        </div>

        <div
            :class="[
                'flex-1 flex flex-col',
                { 'hidden md:flex': !state.selectedChat },
            ]"
        >
            <div
                v-if="!state.selectedChat"
                class="h-full flex items-center justify-center text-gray-500"
            >
                {{ $trans("chat.select_chat_tip") }}
            </div>
            <router-view
                v-if="state.selectedChat"
                :chat="state.selectedChat"
                @onCloseChat="closeChat"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "Chat",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import ChatUser from "./User.vue"
import ChatMessage from "./Message.vue"

const router = useRouter()
const store = useStore()

const state = reactive({
    selectedChat: null,
})

const selectChat = (chat) => {
    state.selectedChat = chat
    router.push({ name: "ChatMessage", params: { uuid: chat.uuid } })
}

const closeChat = () => {
    state.selectedChat = null
    router.push({ name: "Chat" })
}
</script>
