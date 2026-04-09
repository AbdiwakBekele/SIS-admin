<!-- <template>
    <MobileSidebar></MobileSidebar>

    <div class="hidden lg:flex lg:shrink-0">
        <div @mouseover="sidebarMouseOver" @mouseleave="sidebarMouseLeave"
            class="border-gray-300 bg-white dark:bg-dark-header flex flex-col border-r pb-4 pt-5 transition-all duration-200 ease-out dark:border-gray-700"
            :class="{
                'absolute left-0 top-0 z-40 h-full': !isPinnedSidebar,
                'w-16': isMiniSidebar,
                'w-64': isFullSidebar || isPinnedSidebar,
            }">
            <div class="scroller-thin-y scroller-hidden flex h-0 flex-1 flex-col overflow-x-hidden">
                <nav class="px-0">
                    <div class="flex items-left justify-left space-x-2 ml-5">
                        <img src="/images/favicon.ico" alt="App Icon" class="w-8 h-8" />
                        <span class="text-lg font-semibold text-gray-700 mt-1">Simple SIS</span>
                    </div>
                    <AppNavigation />
                </nav>
            </div>
        </div>
    </div>
</template> -->


<template>
    <MobileSidebar></MobileSidebar>

    <div class="hidden lg:flex lg:shrink-0">
        <div @mouseover="sidebarMouseOver" @mouseleave="sidebarMouseLeave"
            class="border-gray-300 bg-white dark:bg-dark-header flex flex-col border-r pb-4 pt-5 transition-all duration-200 ease-out dark:border-gray-700"
            :class="{
                'absolute left-0 top-0 z-40 h-full': !isPinnedSidebar,
                'w-16': isMiniSidebar,
                'w-64': isFullSidebar || isPinnedSidebar,
            }">
            <!-- Wrapper for scrolling area -->
            <div class="flex h-0 flex-1 flex-col overflow-hidden">

                <!-- Sticky logo header -->
                <div class="sticky top-0 z-10 bg-white dark:bg-dark-header px-4">
                    <div class="flex items-center justify-between ml-2 mb-2">
                        <!-- Left: Logo and Text -->
                        <div class="flex items-center space-x-2 transition-all duration-300" :class="{
                            'justify-center': !showMenuName,
                            'justify-start px-2': showMenuName
                        }">
                            <!-- Render logo when config is loaded and logo URL is available -->
                            <!-- Image will be preloaded during login, so it should render immediately -->
                            <template v-if="isConfigLoaded">
                                <img v-if="getLightLogo" 
                                    :src="getLightLogo" 
                                    alt="App Icon"
                                    class="w-10 h-10 object-contain transition-all duration-300"
                                    :class="{ 'w-8 h-8': showMenuName, 'w-10 h-10': !showMenuName }"
                                    @load="imageLoaded = true"
                                    @error="imageError = true" />
                            </template>
                            <span v-if="showMenuName && isConfigLoaded && getAppName"
                                class="text-center text-lg font-semibold text-gray-700 dark:text-white">
                                {{ getAppName }}
                            </span>
                        </div>

                        <!-- Right: Bars Icon -->
                        <i v-if="showMenuName"
                            class="fa-solid fa-bars-staggered text-gray-500 dark:text-white cursor-pointer"
                            @click="setUserSidebar"></i>
                    </div>

                </div>
                <hr>

                <!-- Scrollable navigation -->
                <div class="flex-1 overflow-y-auto scroller-thin-y px-0">
                    <AppNavigation />
                </div>

            </div>
        </div>
    </div>
</template>


<script setup>
import { computed, ref, watch, onMounted } from "vue"
import { useStore } from "vuex"
import {
    getConfig,
} from "@core/helpers/action"

const store = useStore()

// Track if config is loaded
const isConfigLoaded = computed(() => store.getters["config/isLoaded"])

// Track if image is loaded
const imageLoaded = ref(false)
const imageError = ref(false)

// Get logo/app name from config
const getLightLogo = computed(() => {
    // Wait for config to be loaded AND have actual data
    if (!isConfigLoaded.value) {
        return null
    }
    
    // Get full config and verify it has data
    const fullConfig = store.getters["config/configs"];
    if (!fullConfig || !fullConfig.assets) {
        return null
    }
    
    const assets = fullConfig.assets;
    
    // Try ALL possible key variations (camelCase, snake_case, bracket notation)
    let logo = assets.logoLight || 
               assets.logo_light || 
               assets['logoLight'] || 
               assets['logo_light'];
    
    // If not found in direct access, try via getter with both formats
    if (!logo) {
        logo = getConfig("assets.logoLight").value
    }
    if (!logo) {
        logo = getConfig("assets.logo_light").value
    }
    
    // Only return if it's a valid string (not null/undefined/empty)
    const result = logo && typeof logo === 'string' && logo.trim() !== '' ? logo : null;
    
    // Debug logging (remove in production if needed)
    if (!result && isConfigLoaded.value) {
        console.log('AppSidebar - logo not found. Assets:', assets, 'Keys:', Object.keys(assets || {}));
    }
    
    return result
})

const getAppName = computed(() => {
    if (!isConfigLoaded.value) {
        return null
    }
    const appName = getConfig("general.appName").value
    // Only return if it's a valid string (not null/undefined/empty)
    return appName && typeof appName === 'string' && appName.trim() !== '' ? appName : null
})

// Preload the logo image when config is loaded
const preloadImage = (src) => {
    return new Promise((resolve, reject) => {
        if (!src) {
            resolve()
            return
        }
        const img = new Image()
        img.onload = () => {
            imageLoaded.value = true
            imageError.value = false
            resolve()
        }
        img.onerror = () => {
            imageError.value = true
            imageLoaded.value = false
            resolve() // Resolve anyway to not block rendering
        }
        img.src = src
    })
}

// Watch for config loading and logo availability
watch([isConfigLoaded, getLightLogo], async ([configLoaded, logo]) => {
    // Wait a bit for Vue reactivity to update
    if (configLoaded && logo) {
        // Small delay to ensure store is fully reactive
        await new Promise(resolve => setTimeout(resolve, 50))
        if (!imageLoaded.value && !imageError.value) {
            await preloadImage(logo)
        }
    }
}, { immediate: true, deep: true })

// Also preload on mount if config is already loaded
onMounted(async () => {
    // Wait a bit for component to fully mount and store to be ready
    await new Promise(resolve => setTimeout(resolve, 100))
    if (isConfigLoaded.value && getLightLogo.value) {
        await preloadImage(getLightLogo.value)
    }
})
const sidebarType = computed(() => store.getters["layout/getSidebarType"])
const isMiniSidebar = computed(() => sidebarType.value === "mini")
const isFullSidebar = computed(() => sidebarType.value === "full")
const isPinnedSidebar = computed(() => sidebarType.value === "pinned")
const showMenuName = computed(
    () => isFullSidebar.value || isPinnedSidebar.value
)

const sidebarMouseOver = () => {
    if (!isPinnedSidebar.value) {
        store.dispatch("layout/setSidebarType", "full")
    }
}

const sidebarMouseLeave = () => {
    if (!isPinnedSidebar.value) {
        store.dispatch("layout/setSidebarType", "mini")
    }
}

const setUserSidebar = () => {
    let value = isPinnedSidebar.value ? "mini" : "pinned"
    setUserLayout({ sidebar: value })
}

const setUserLayout = (payload) => {
    store.dispatch("layout/setUserLayout", payload)
}

const setMobileSidebar = (value) => {
    store.dispatch("layout/setMobileSidebar", value)
}
</script>
