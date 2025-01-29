<template>
    <DropdownMenu double-top-margin :right-margin="false">
        <template #clickable>
            <img class="h-8 w-8 rounded-full" :src="getAvatar" alt="" />
        </template>
        <div class="py-1">
            <div class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                <span class="font-medium">{{
                    $trans("general.greeting_message", { name: getName })
                }}</span>
                <span class="block text-xs">{{ getEmail }}</span>
            </div>
        </div>
        <div class="py-1 max-h-64 scroller-thin-y scroller-hidden">
            <DropdownItem @click="setUserDisplay()">
                <div class="flex justify-between" v-if="getDisplay == 'dark'">
                    <span>{{
                        $trans("global.choose", {
                            attribute: $trans("general.light_mode"),
                        })
                    }}</span>
                    <span class="ml-4"><i class="fas fa-sun"></i></span>
                </div>
                <div class="flex justify-between" v-if="getDisplay == 'light'">
                    <span>{{
                        $trans("global.choose", {
                            attribute: $trans("general.dark_mode"),
                        })
                    }}</span>
                    <span class="ml-4"><i class="fas fa-moon"></i></span>
                </div>
            </DropdownItem>
            <DropdownItem
                v-if="perform('config:store')"
                :disabled="route.name === 'ConfigRole'"
                @click="
                    router.push({
                        name: 'ConfigRole',
                    })
                "
            >
                {{ $trans("config.role_and_permission") }}
            </DropdownItem>
            <DropdownItem
                :disabled="route.name === 'UserProfile'"
                @click="router.push({ name: 'UserProfile' })"
            >
                {{ $trans("user.profile.profile") }}
            </DropdownItem>
            <DropdownItem
                :disabled="route.name === 'UserPreference'"
                @click="router.push({ name: 'UserPreference' })"
            >
                {{ $trans("user.preference.preference") }}
            </DropdownItem>
            <DropdownItem
                :disabled="route.name === 'UserPassword'"
                @click="router.push({ name: 'UserPassword' })"
            >
                {{ $trans("auth.password.change_password") }}
            </DropdownItem>
        </div>
        <div class="py-1">
            <DropdownItem
                v-if="actingAs('admin')"
                :disabled="route.name === 'FailedLoginAttempt'"
                @click="router.push({ name: 'FailedLoginAttempt' })"
            >
                {{ $trans("auth.failed_login_attempt") }}
            </DropdownItem>
            <DropdownItem
                v-if="actingAs('admin')"
                as="link"
                target="_blank"
                href="/cmd/clear-cache"
            >
                Clear Cache
            </DropdownItem>
            <DropdownItem
                v-if="actingAs('admin')"
                as="link"
                href="/download/formats"
            >
                {{ $trans("general.download_format") }}
            </DropdownItem>
            <DropdownItem
                :disabled="route.name === 'UserPassword'"
                @click="logout"
            >
                {{ $trans("auth.login.logout") }}
            </DropdownItem>
        </div>
    </DropdownMenu>
</template>

<script>
export default {
    name: "ProfileDropdown",
}
</script>

<script setup>
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { useLoading } from "vue-loading-overlay"
import { getAuthUser, actingAs, perform } from "@core/helpers/action"

const store = useStore()
const route = useRoute()
const router = useRouter()
const $loading = useLoading({})

const getAvatar = getAuthUser("avatar")
const getName = getAuthUser("profile.name")
const getEmail = getAuthUser("email")
const getDisplay = getAuthUser("preference.layout.display")

const logout = async () => {
    let loader = $loading.show()
    await store
        .dispatch("auth/user/logout", {})
        .then(() => {
            loader.hide()
            router.push({ name: "Login" })
        })
        .catch((e) => {
            loader.hide()
        })
}

const setUserLayout = (payload) => {
    store.dispatch("layout/setUserLayout", payload)
}

const setUserDisplay = () => {
    let display = getDisplay.value == "light" ? "dark" : "light"
    setUserLayout({ display })
}
</script>
