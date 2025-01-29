<template>
    <div class="flex flex-col h-full">
        <div
            class="bg-white dark:bg-dark-header p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center"
        >
            <div class="flex items-center">
                <img
                    :src="chat.avatar"
                    :alt="chat.name"
                    class="w-10 h-10 rounded-full mr-3"
                />
                <h2 class="text-xl font-semibold dark:text-white">
                    {{ chat.name }}
                </h2>
            </div>
            <div @click="closeChat">
                <i class="fas fa-lg fa-times cursor-pointer text-gray-500"></i>
            </div>
        </div>
        <div
            class="flex-1 p-4 bg-gray-100 dark:bg-dark-body flex flex-col-reverse scroller-thin-y scroller-hidden h-0"
        >
            <div class="space-y-4">
                <BaseLoader v-if="state.isLoading">
                    <div class="flex justify-center items-center">
                        <span
                            class="text-center text-xs border border-gray-300 dark:border-gray-600 rounded-full px-4 py-2 cursor-pointer"
                        >
                            {{ $trans("chat.loading") }}
                        </span>
                    </div>
                </BaseLoader>
                <!-- <div v-if="state.isLoading" class="text-center">Loading...</div> -->
                <div
                    v-if="state.nextCursor && !state.isLoading"
                    class="text-center"
                >
                    <span
                        class="text-xs border border-gray-300 dark:border-gray-600 rounded-full px-4 py-2 cursor-pointer"
                        @click="getMessages(state.nextCursor)"
                    >
                        {{ $trans("chat.load_more") }}
                    </span>
                </div>
                <div
                    v-for="message in [...state.messages].reverse()"
                    :key="message.uuid"
                    :class="[
                        'flex',
                        message.isSent ? 'justify-end' : 'justify-start',
                    ]"
                >
                    <div class="mr-2" v-if="message.user && !message.isSent">
                        <img
                            v-tooltip="message.user.name"
                            :src="message.user.avatar"
                            :alt="message.user.name"
                            class="w-8 h-8 rounded-full"
                        />
                    </div>
                    <div>
                        <div
                            :class="[
                                'max-w-xs rounded-lg py-1 px-3 text-sm',
                                message.isSent
                                    ? 'bg-primary text-white'
                                    : 'bg-gray-200 dark:bg-gray-700 dark:text-white',
                            ]"
                        >
                            {{ message.content }}
                        </div>
                        <span
                            class="text-xs text-gray-500"
                            v-tooltip="message.createdAt.formatted"
                            >{{ message.createdAt.diffForHuman }}</span
                        >
                    </div>
                    <div class="ml-2" v-if="message.user && message.isSent">
                        <img
                            v-tooltip="message.user.name"
                            :src="message.user.avatar"
                            :alt="message.user.name"
                            class="w-8 h-8 rounded-full"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div
            class="bg-white dark:bg-dark-header p-4 border-t border-gray-200 dark:border-gray-700"
        >
            <div
                v-if="state.isTyping"
                class="text-xs text-gray-500 italic mb-2"
            >
                {{ $trans("chat.is_typing", { attribute: state.typingUser }) }}
            </div>
            <input
                v-model="state.newMessage"
                @keyup.enter="sendMessage"
                @keyup="emitTyping"
                type="text"
                :placeholder="$trans('chat.type_message')"
                class="w-full border border-gray-300 dark:border-gray-600 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-dark-body dark:text-white"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "ChatMessage",
}
</script>

<script setup>
import { reactive, watch, onMounted, ref } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { getAuthUser } from "@core/helpers/action"
import { keysToCamel } from "@core/utils/helper"
import { debounce } from "lodash"

const route = useRoute()
const router = useRouter()
const store = useStore()

const emit = defineEmits(["onCloseChat"])

const props = defineProps({
    chat: {
        type: Object,
        required: true,
    },
})

const getAuthUuid = getAuthUser("uuid")

const state = reactive({
    messages: [],
    isTyping: false,
    typingUser: null,
    newMessage: "",
    nextCursor: null,
    isLoading: false,
})

const typingTimeout = ref(null)

const getMessages = async (cursor = null) => {
    if (state.isLoading) return

    state.isLoading = true

    await store
        .dispatch("chat/getMessages", {
            uuid: props.chat.uuid,
            cursor: cursor,
        })
        .then((response) => {
            state.messages = [...state.messages, ...response.data]
            state.nextCursor = response.meta.nextCursor
        })
        .catch((error) => {
            console.error("Error loading messages:", error)
        })
        .finally(() => {
            state.isLoading = false
        })
}

const handleScroll = (event) => {
    const { scrollTop } = event.target
    if (scrollTop === 0 && state.nextCursor) {
        getMessages(state.nextCursor)
    }
}

const sendMessage = async () => {
    if (state.newMessage.trim()) {
        await store
            .dispatch("chat/sendMessage", {
                uuid: props.chat.uuid,
                message: {
                    content: state.newMessage,
                    type: "text",
                },
            })
            .then((response) => {
                state.messages.unshift(response)
            })
            .catch((error) => {
                console.error("Failed to send message:", error)
            })
        state.newMessage = ""
    }
}

const markMessagesAsRead = async () => {
    if (props.chat) {
        await store.dispatch("chat/markAsRead", props.chat.uuid)
    }
}

const closeChat = () => {
    emit("onCloseChat")
}

const emitTyping = debounce(() => {
    if (!window.Echo) return

    window.Echo.private(`chats.${props.chat.uuid}`).whisper("typing", {
        name: getAuthUser("profile.name").value,
    })

    if (typingTimeout.value) {
        clearTimeout(typingTimeout.value)
    }

    typingTimeout.value = setTimeout(() => {
        window.Echo.private(`chats.${props.chat.uuid}`).whisper(
            "stopTyping",
            {}
        )
    }, 2000)
}, 300)

onMounted(async () => {
    await getMessages()
    // await markMessagesAsRead()

    if (!window.Echo) return

    window.Echo.private(`chats.${props.chat.uuid}`)
        .listen(".message.sent", (e) => {
            // console.log(e)
            let message = keysToCamel(e.message)
            message.isSent =
                message.user.uuid == getAuthUuid.value ? true : false
            if (message.user.uuid != getAuthUuid.value) {
                var audio = new Audio("/notification.mp3")
                audio.play()
                state.messages.unshift(message)
            }
        })
        .listenForWhisper("typing", (e) => {
            state.isTyping = true
            state.typingUser = e.name
        })
        .listenForWhisper("stopTyping", () => {
            state.isTyping = false
            state.typingUser = null
        })
})

watch(
    () => props.chat.uuid,
    () => {
        state.messages = []
        getMessages()
        // markMessagesAsRead()
    }
)
</script>
