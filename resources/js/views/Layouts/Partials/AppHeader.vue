<template>
    <div
        class="dark:bg-dark-header relative z-10 flex h-16 shrink-0 justify-between bg-black dark:border-b dark:border-gray-700"
    >
        <div class="flex">
            <div class="flex items-center">
                <img
                    :src="getLightLogo"
                    class="ml-4 mr-2 hidden h-12 sm:block"
                />
                <img :src="getIcon" class="mx-2 h-12 sm:hidden" />
                <button
                    type="button"
                    class="hidden h-full w-full px-4 text-gray-500 focus:outline-none lg:block"
                    v-tooltip="
                        $trans('global.toggle', {
                            attribute: $trans('general.sidebar'),
                        })
                    "
                    @click="setUserSidebar"
                >
                    <span class="sr-only">Pinned Sidebar</span>
                    <i
                        class="far fa-circle h-6 w-6"
                        v-if="!isPinnedSidebar"
                        aria-hidden="true"
                    ></i>
                    <i
                        class="fas fa-dot-circle h-6 w-6"
                        v-else
                        aria-hidden="true"
                    ></i>
                </button>
                <BaseBadge
                    class="hidden sm:block"
                    v-if="!mode"
                    design="danger"
                    :label="$trans('general.demo')"
                />
                <button
                    type="button"
                    class="h-full w-full px-2 text-gray-500 focus:outline-none sm:px-4 lg:hidden"
                    @click="setMobileSidebar(true)"
                >
                    <span class="sr-only">Open Mobile Sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>
            </div>
        </div>
        <div class="flex items-center justify-between space-x-4 px-4">
            <span
                v-if="enableChat"
                class="cursor-pointer"
                @click="
                    router.push({
                        name: 'Chat',
                    })
                "
            >
                <i class="far fa-message text-white"></i>
            </span>
            <div v-if="canStoreConfig">
                <router-link :to="{ name: 'Config' }">
                    <i class="fas fa-cog text-white"></i>
                </router-link>
            </div>
            <div class="hidden sm:block" v-if="userIsImpersonating">
                <i
                    class="fas fa-circle text-warning cursor-pointer h-4 w-4"
                    @click="unimpersonate"
                    v-tooltip="$trans('user.unimpersonate')"
                ></i>
            </div>
            <div
                class="hidden sm:block"
                v-if="maintenanceMode"
                v-tooltip="$trans('general.under_maintenance')"
            >
                <i class="fas fa-circle text-danger h-4 w-4"></i>
            </div>
            <div class="flex items-center">
                <ProfileDropdown />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import { Bars3Icon } from "@heroicons/vue/20/solid"
import { perform, getConfig, isSuperAdmin } from "@core/helpers/action"

const router = useRouter()
const store = useStore()

const userIsImpersonating = getConfig("isImpersonating").value

const canStoreConfig = perform("config:store")

const getLightLogo = getConfig("assets.logoLight")
const getIcon =
    getConfig("layout.display").value == "dark"
        ? getConfig("assets.iconLight")
        : getConfig("assets.icon")
const mode = getConfig("system.mode")
const maintenanceMode = getConfig("system.enableMaintenanceMode")
const isPinnedSidebar = computed(
    () => store.getters["layout/getSidebarType"] === "pinned"
)
const enableChat = getConfig("chat.enableChat").value

const setMobileSidebar = (value) => {
    store.dispatch("layout/setMobileSidebar", value)
}

const setUserLayout = (payload) => {
    store.dispatch("layout/setUserLayout", payload)
}

const setUserSidebar = () => {
    let value = isPinnedSidebar.value ? "mini" : "pinned"
    setUserLayout({ sidebar: value })
}

const unimpersonate = async (uuid) => {
    await store
        .dispatch("auth/user/unimpersonate")
        .then((response) => {
            location.reload()
        })
        .catch((e) => {
            //
        })
}
</script>
