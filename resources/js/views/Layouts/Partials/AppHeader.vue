<template>
    <div
        class="dark:bg-dark-header relative z-10 flex h-16 shrink-0 justify-between bg-white dark:border-b dark:border-gray-700">
        <div class="flex">
            <div class="flex items-center">
                <div class="mx-5">
                    <DatePill :date="new Date()" />
                </div>

                <!-- Logo -->
                <!-- <img
                    :src="getLightLogo"
                    class="ml-4 mr-2 hidden h-12 sm:block"
                />
                <img :src="getIcon" class="mx-2 h-12 sm:hidden" /> -->



                <!-- Toggle Button -->
                <!-- <button type="button" class="hidden h-full w-full px-4 text-gray-500 focus:outline-none lg:block"
                    v-tooltip="$trans('global.toggle', {
                        attribute: $trans('general.sidebar'),
                    })
                        " @click="setUserSidebar">
                    <span class="sr-only">Pinned Sidebar</span>
                    <i class="far fa-circle h-6 w-6" v-if="!isPinnedSidebar" aria-hidden="true"></i>
                    <i class="fas fa-dot-circle h-6 w-6" v-else aria-hidden="true"></i>
                </button> -->

                <!-- <BaseBadge class="hidden sm:block" v-if="!mode" design="danger" :label="$trans('general.demo')" />
                <button type="button" class="h-full w-full px-2 text-gray-500 focus:outline-none sm:px-4 lg:hidden"
                    @click="setMobileSidebar(true)">
                    <span class="sr-only">Open Mobile Sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button> -->

                <!-- QR Code Attendance -->
                <!-- <span v-if="
                    enableQrCodeAttendance &&
                    actingAs(['admin', 'attendance-assistant'], 'any')
                " @click="router.push({ name: 'AttendanceAssistant' })" class="cursor-pointer">
                    <i class="far fa-clock text-white" @click="router.push({ name: 'AttendanceAssistant' })"></i>
                </span> -->

            </div>
        </div>

        <!-- Left Side Bar -->
        <div class="flex items-center justify-between space-x-4 px-4">
            <!-- <TimesheetClock></TimesheetClock> -->
            <PeriodSelection v-if="
                getConfig('academic.periodSelection').value == 'period_wise'
            "></PeriodSelection>
            <SessionWisePeriodSelection v-if="
                getConfig('academic.periodSelection').value ==
                'session_wise'
            "></SessionWisePeriodSelection>
            <!-- TeamSelection disabled - not using teams for this tenant -->
            <!-- <TeamSelection></TeamSelection> -->
           


            <div
                v-if="!actingAs(['student-profile', 'parent-profile'], 'any') && hasAnyQuickAdd"
                ref="wrapNewOption"
                class="relative"
            >
                <!-- Add Icon -->
                <i
                    class="fa-solid fa-square-plus bg-gradient-to-r from-green-500 to-green-700 bg-clip-text text-transparent cursor-pointer text-xl"
                    @click="toggleNewOption"
                ></i>

                <!-- Add Dropdown -->
                <transition name="fade">
                    <div v-if="openNewOption"
                        class="absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-md overflow-hidden z-50 p-4">
                        <h3 class="text-sm font-semibold text-gray-600 mb-3">Add New</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Quick Add: Attendance -->
                            <div
                                v-if="perform('student_attendance:create')"
                                class="flex flex-col items-center justify-center p-3 rounded-lg bg-blue-50 cursor-pointer hover:bg-blue-100"
                                @click="router.push({ name: 'StudentAttendance' })"
                            >
                                <div class="bg-blue-600 text-white p-3 rounded-lg mb-2">
                                    <i class="fa-solid fa-file-invoice text-lg"></i>
                                </div>
                                <span class="text-sm text-gray-800">Attendance</span>
                            </div>

                            <!-- Quick Add: Events -->
                            <div
                                v-if="perform('events:create')"
                                class="flex flex-col items-center justify-center p-3 rounded-lg bg-green-50 cursor-pointer hover:bg-green-100"
                                @click="router.push({ name: 'CalendarEventCreate' })"
                            >
                                <div class="bg-green-500 text-white p-3 rounded-lg mb-2">
                                    <i class="fa-solid fa-calendar text-lg"></i>
                                </div>
                                <span class="text-sm text-gray-800">Events</span>
                            </div>

                            <!-- Quick Add: Incidents -->
                            <div
                                v-if="perform('incidents:access:create')"
                                class="flex flex-col items-center justify-center p-3 rounded-lg bg-yellow-50 cursor-pointer hover:bg-yellow-100"
                                @click="router.push({ name: 'DisciplineIncidentCreate' })"
                            >
                                <div class="bg-yellow-500 text-white p-3 rounded-lg mb-2">
                                    <i class="fa-solid fa-clipboard-list text-lg"></i>
                                </div>
                                <span class="text-sm text-gray-800">Incidents</span>
                            </div>

                            <!-- Quick Add: Students -->
                            <div
                                v-if="perform('all_students:create')"
                                class="flex flex-col items-center justify-center p-3 rounded-lg bg-blue-50 cursor-pointer hover:bg-blue-100"
                                @click="router.push({ name: 'StudentProfileCreate' })"
                            >
                                <div class="bg-blue-600 text-white p-3 rounded-lg mb-2">
                                    <i class="fa-solid fa-user-graduate text-lg"></i>
                                </div>
                                <span class="text-sm text-gray-800">Students</span>
                            </div>


                        </div>
                    </div>

                </transition>
            </div>

            <!-- Language (Globe) -->
            <div ref="wrapLang" class="relative">
                <i class="fa-solid fa-earth-americas bg-gradient-to-r from-blue-400 to-blue-700 bg-clip-text text-transparent cursor-pointer text-xl" @click="toggleLang"></i>

                <transition name="fade">
                    <div v-if="openLang"
                        class="absolute right-0 mt-2 w-44 bg-white shadow-lg rounded-md overflow-hidden z-50">
                        <ul class="py-1 text-sm">
                            <li v-for="lng in languages" :key="lng.code" @click="selectLocale(lng.code)"
                                class="px-3 py-2 hover:bg-gray-100 flex items-center justify-between cursor-pointer">
                                <span class="flex items-center gap-2">
                                    <span aria-hidden="true">{{ lng.flag }}</span>
                                    <span>{{ lng.label }}</span>
                                </span>
                                <i v-if="currentLocale === lng.code" class="fas fa-check text-green-600 text-xs"></i>
                            </li>
                            <li v-if="!languages.length" class="px-3 py-2 text-gray-500">
                                No languages
                            </li>
                        </ul>
                    </div>
                </transition>
            </div>



            <!-- Chat -->
            <span
                v-if="enableChat && perform('communication:chat:read') && !actingAs('parent-profile')"
                class="cursor-pointer"
                @click="
                    router.push({
                        name: 'Chat',
                    })
                "
            >
                <i class="fa-solid fa-message bg-gradient-to-r from-orange-300 to-orange-600 bg-clip-text text-transparent"></i>
            </span>

            <!-- Notification -->
            <div ref="wrap" class="relative">
                <!-- Bell + Badge -->
                <i class="fa-solid fa-bell bg-gradient-to-r from-red-300 to-red-600 bg-clip-text text-transparent cursor-pointer text-xl" @click="toggle"></i>
                <span v-if="notifications.length"
                    class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                    {{ notifications.length }}
                </span>

                <!-- Dropdown -->
                <transition name="fade">
                    <div v-if="open"
                        class="absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-md overflow-hidden z-50">
                        <ul>
                            <li v-for="(n, i) in notifications" :key="i"
                                class="px-4 py-2 hover:bg-gray-100 border-b last:border-none">
                                {{ n.message }}
                            </li>
                            <li v-if="!notifications.length" class="px-4 py-2 text-gray-500">
                                No new notifications
                            </li>
                        </ul>
                    </div>
                </transition>
            </div>

            <!-- Config -->
            <!-- <div v-if="canStoreConfig">
                <router-link :to="{ name: 'Config' }">
                    <i class="fas fa-cog text-gray-600"></i>
                </router-link>
            </div> -->

            <!-- User Profile -->
            <div class="hidden sm:block" v-if="userIsImpersonating">
                <i class="fas fa-circle text-warning cursor-pointer h-4 w-4" @click="unimpersonate"
                    v-tooltip="$trans('user.unimpersonate')"></i>
            </div>
            <div class="hidden sm:block" v-if="maintenanceMode" v-tooltip="$trans('general.under_maintenance')">
                <i class="fas fa-circle text-danger h-4 w-4"></i>
            </div>

            <!-- Open Search -->
            <!-- <button @click="openSearch" class="text-white sm:hidden">
                <i class="fas fa-search"></i>
            </button> -->
            <!-- <button @click="openSearch" class="text-gray-600">
                <i class="fas fa-search"></i>
            </button> -->

            <!-- User Profile -->
            <div class="flex items-center">
                <ProfileDropdown />
                <span class="mx-2"> {{ getName }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, inject } from "vue"
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import { Bars3Icon } from "@heroicons/vue/20/solid"
import { ref, onMounted, onBeforeUnmount } from 'vue'
import DatePill from '../../Pages/Dashboard/Cards/DatePill.vue'

import {
    perform,
    getConfig,
    getAuthUser,
    actingAs,
    isSuperAdmin,
} from "@core/helpers/action"


const router = useRouter()
const store = useStore()


const userIsImpersonating = getConfig("isImpersonating").value

const getName = getAuthUser("profile.name")
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

// At least one quick-add permission must be present to show the "+" menu
const hasAnyQuickAdd = computed(() =>
    perform('student_attendance:create') ||
    perform('events:create') ||
    perform('incidents:access:create') ||
    perform('all_students:create')
)

const enableQrCodeAttendance = getConfig(
    "employee.enableQrCodeAttendance"
).value

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
            window.open("/app", "_self")
        })
        .catch((e) => {
            //
        })
}

const keySearch = inject("keySearch")

const openSearch = () => {
    keySearch.openSearch()
}

const notifications = ref([
])
const open = ref(false)
const openNewOption = ref(false)
const wrap = ref(null)
const wrapNewOption = ref(null)

function toggle() {
    open.value = !open.value
}

function toggleNewOption() {
    openNewOption.value = !openNewOption.value
}

// close when clicking outside
function onClickOutside(e) {
    if (wrap.value && !wrap.value.contains(e.target)) {
        open.value = false
    }

    if (wrapNewOption.value && !wrapNewOption.value.contains(e.target)) {
        openNewOption.value = false
    }
}

const languages = ref([
    { code: 'en', label: 'English', flag: 'ðŸ‡ºðŸ‡¸' },
    { code: 'fr', label: 'FranÃ§ais', flag: 'ðŸ‡«ðŸ‡·' },
    { code: 'es', label: 'EspaÃ±ol', flag: 'ðŸ‡ªðŸ‡¸' },
    { code: 'zh', label: 'ä¸­æ–‡ (ç®€ä½“)', flag: 'ðŸ‡¨ðŸ‡³' },
])

const openLang = ref(false)
const currentLocale = ref('en') // default

function toggleLang() {
    openLang.value = !openLang.value
}

function selectLocale(code) {
    currentLocale.value = code
    openLang.value = false
}

onMounted(() => {
    document.addEventListener('click', onClickOutside)
})
onBeforeUnmount(() => {
    document.removeEventListener('click', onClickOutside)
})

</script>


<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>