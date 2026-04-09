<template>
    <NotificationBar type="app" />
    <div class="dark:bg-dark-body relative flex h-screen overflow-hidden bg-gray-200">
        <AppSidebar></AppSidebar>

        <div class="flex w-0 flex-1 flex-col overflow-hidden" :class="{
            'lg:ml-16': !isPinnedSidebar,
        }">
            <AppHeader></AppHeader>

            <main class="scroller-thin-y scroller-hidden relative z-0 flex-1 focus:outline-none" :class="{
                'overflow-y-auto': route.query.view != 'board',
                'overflow-y-hidden': route.query.view == 'board',
            }">
                <div class="space-y-4" :class="{
                    'py-4 lg:px-4': noPadding,
                }">
                    <router-view />
                </div>

                <FooterCredit />
            </main>
        </div>

        <div class="h-screen flex-0 w-1/3 bg-white dark:bg-dark-header"
            v-if="!screenSize.small && showSetupWizard && actingAs('admin')">
            <div class="scroller-thin-y scroller-hidden h-screen overflow-x-hidden">
                <SetupWizard />
            </div>
        </div>
    </div>

    <ReLogin />
    <ForcePasswordChangeModal />
    <KeySearch ref="keySearchRef" />
</template>

<script setup>
import { ref, computed, provide, onMounted, onUnmounted } from "vue"
import { useStore } from "vuex"
import { useRoute } from "vue-router"
import { actingAs, getConfig, getAuthUser } from "@core/helpers/action"
import { listenPrivate } from "@core/helpers/notification"
import { useScreenSize } from "@core/composables/useScreenSize"

import SetupWizard from "@views/Layouts/Partials/SetupWizard.vue"
import AppSidebar from "@views/Layouts/Partials/AppSidebar.vue"
import AppHeader from "@views/Layouts/Partials/AppHeader.vue"
import ForcePasswordChangeModal from "@views/Layouts/Partials/ForcePasswordChangeModal.vue"

// import ChatBox from "@views/Pages/Chat/ChatBox.vue"

const store = useStore()
const route = useRoute()

const keySearchRef = ref(null)

provide("keySearch", {
    openSearch: () => keySearchRef.value?.openSearch(),
    closeSearch: () => keySearchRef.value?.closeSearch(),
})

const showSetupWizard = getConfig("system.showSetupWizard")

const isPinnedSidebar = computed(() =>
    store.getters["layout/getSidebarType"] === "pinned" ? true : false
)
const noPadding = computed(() => {
    if (route.meta.noPadding) {
        return false
    }

    return true
})

const getAuthUuid = getAuthUser("uuid")
const channelName = `users.${getAuthUuid.value}`

let intervalId = null

onMounted(async () => {
    try {
        await store.dispatch("auth/user/fetch")
    } catch (error) {
        console.warn('Failed to fetch user data:', error)
    }

    if (store._actions["badges/fetch"]) {
        store.dispatch("badges/fetch")
    }

    if (store._actions["badges/fetch"]) {
        intervalId = setInterval(() => {
            store.dispatch("badges/fetch")
        }, 30000)
    }

    listenPrivate(channelName, "test.event", (e) => {
        var audio = new Audio("/notification.mp3")
        audio.play()
    })
})

onUnmounted(() => {
    clearInterval(intervalId)
    window.Echo.private(channelName).stopListening("test.event")
})

const { screenSize } = useScreenSize()
</script>
