<template>
    <template v-for="navGroup in navigations">
        <div class="space-y-0">
            <h3 v-if="navGroup.meta.label" v-show="showMenuName" class="flex px-5 py-3 uppercase text-white">
                <span>{{ $trans(navGroup.meta.label) }}</span>
            </h3>

            <ul class="text-sm">
                <li class="text-black hover:text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 dark:hover:bg-dark-body mx-3 rounded-xl"
                    v-for="(navigation, index) in navGroup.children" :key="navigation.name"
                    :class="{ 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md': isParentActive(navigation) }">
                    <!-- group divider - only show if this item has a group and it's different from previous item -->
                    <div v-if="visibleMenu(navigation) && shouldShowGroupDivider(navGroup.children, index) && showMenuName" class="uppercase"
                        style="display:flex;align-items:center;width:100%;padding-right:10px;padding-left:10px;">
                        <span class="pr-2 text-[12px] text-gray-400 font-bold"
                            style="white-space:nowrap;">
                            {{ navigation.meta.group }}
                        </span>
                        <hr style="flex:1;border:none;height:1px;background-color:#cacccf;">
                    </div>

                    <template v-if="visibleMenu(navigation)">
                        <component :is="isLink(navigation) ? 'router-link' : 'div'"
                            :to="isLink(navigation) ? { name: navigation.name } : ''" class="cursor-pointer"
                            @click="menuClick(navigation)">
                            <div class="flex items-center py-3 pl-3 pr-1 rounded-lg"
                                :class="{
                                    'justify-between': hasChild(navigation),
                                    'bg-gradient-to-r from-blue-500 to-blue-600 text-white': isParentActive(navigation),
                                }">
                                <div class="flex items-center">
                                    <span>
                                        <i class="mr-3 w-6 shrink-0"
                                            :class="[navigation.meta.icon, isParentActive(navigation) ? 'text-white' : 'text-gray-700 dark:text-gray-300']"></i>
                                    </span>
                                    <span v-show="showMenuName">
                                        {{ $trans(navigation.meta.label) }}
                                    </span>
                                </div>

                                <div v-if="hasChild(navigation) && showMenuName" class="flex items-center">
                                    <!-- Badge before chevron icon -->
                                    <span v-if="treeCount(navigation) > 0"
                                        class="inline-flex items-center justify-center min-w-5 h-5 px-1 rounded-full text-[10px] font-semibold bg-red-600 text-white mr-3 leading-none">
                                        {{ treeCount(navigation) }}
                                    </span>
                                    <i :class="['fas fa-chevron-right w-4 shrink-0', isParentActive(navigation) ? 'text-white' : 'text-gray-700 dark:text-gray-300']"
                                        v-if="state.openedMenu != navigation.name"></i>
                                    <i :class="['fas fa-chevron-down w-4 shrink-0', isParentActive(navigation) ? 'text-white' : 'text-gray-700 dark:text-gray-300']" v-else></i>
                                </div>
                                <!-- Badge for items without children -->
                                <span v-else-if="treeCount(navigation) > 0 && showMenuName"
                                    class="inline-flex items-center justify-center min-w-5 h-5 px-1 rounded-full text-[10px] font-semibold bg-red-600 text-white leading-none ml-2">
                                    {{ treeCount(navigation) }}
                                </span>
                            </div>
                        </component>

                        <MenuParentTransition direction="down"
                            :show="hasChild(navigation) && state.openedMenu == navigation.name">
                            <ul v-show="showMenuName">
                                <li class="text-gray-700 hover:text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 cursor-pointer dark:hover:bg-black rounded-lg"
                                    :class="{ 'bg-gradient-to-r from-blue-500 to-blue-600 text-white': isActive(children) }"
                                    :key="children.name" v-for="children in navigation.children">
                                    <router-link :to="{ name: children.name }">
                                        <div class="flex items-center justify-between px-8 py-2 rounded-lg relative"
                                            :class="{ 'bg-gradient-to-r from-blue-500 to-blue-600 text-white': isActive(children) }">
                                            <div class="flex items-center">
                                                <span>
                                                    <i
                                                        :class="['fa-regular fa-circle-dot text-[7px] ml-5 mr-2 mb-2', isActive(children) ? 'text-white' : 'text-blue-300']"></i>
                                                </span>
                                                <span>
                                                    {{ $trans(children.meta.label) }}
                                                </span>
                                            </div>
                                            <!-- Child-level badge: red dot when there is any count in this node or its children -->
                                            <span
                                                v-if="treeCount(children) > 0"
                                                class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-red-600 ml-2"
                                            ></span>
                                        </div>

                                    </router-link>
                                </li>
                            </ul>
                        </MenuParentTransition>
                    </template>
                </li>
            </ul>
        </div>
    </template>
</template>

<script>
export default {
    name: "AppSidebar",
}
</script>

<script setup>
import { computed, reactive, watch } from "vue"
import { useStore } from "vuex"
import { useRoute, useRouter } from "vue-router"

const store = useStore()
const route = useRoute()
const router = useRouter()

const props = defineProps({
    mobile: {
        type: Boolean,
        deafult: false,
    },
})

const state = reactive({
    openedMenu: "",
    activeMenu: route.name,
    activeSubMenu: "",
})

const sidebarType = computed(() => store.getters["layout/getSidebarType"])
const navigations = computed(() => store.getters["navigation/navigations"])

const isFullSidebar = computed(() => sidebarType.value === "full")
const isPinnedSidebar = computed(() => sidebarType.value === "pinned")
const showMenuName = computed(
    () => props.mobile || isFullSidebar.value || isPinnedSidebar.value
)

// Single node count by route name
const nodeCount = (nav) => {
    const badgeGetter = store.getters["badges/countFor"]

    if (typeof badgeGetter !== "function") {
        return 0
    }

    return badgeGetter(nav?.name) || 0
}

// If you want parent items to aggregate all visible children:
const treeCount = (nav) => {
    if (!nav?.children?.length) return nodeCount(nav)
    let sum = nodeCount(nav) || 0
    for (const c of nav.children) sum += treeCount(c)
    return sum
}

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

const visibleMenu = (menu) => {
    if (menu.meta.isHiddenNav) {
        return false
    }

    if (!menu.hasOwnProperty("children")) {
        return true
    }

    if (menu.children.length === 0) {
        return false
    }

    return true
}

const hasChild = (menu) => {
    if (!menu.hasOwnProperty("children")) {
        return false
    }

    return menu.children.length > 0 && !menu.meta.hideChildren
}

const isLink = (navigation) => {
    return hasChild(navigation) ? false : true
}

// Toggle Submenus
const menuClick = (navigation) => {
    if (hasChild(navigation)) {
        if (state.openedMenu == navigation.name) {
            state.openedMenu = ""
        } else {
            state.openedMenu = navigation.name
        }
    } else {
        // router.push({name: navigation.name})
    }
}

const subMenuClick = (children) => {
    // router.push({name: children.name})
}

const isParentActive = (navigation) => {
    // Check if the parent route itself matches exactly
    // Do NOT check redirects - redirects are navigation helpers, not active states
    if (!navigation || !navigation.name) {
        return false
    }

    // Check exact match
    if (state.activeMenu === navigation.name) {
        return true
    }

    // Only check direct children when hideChildren is true
    // This means children are tabs within the page, not separate navigation items
    // (e.g., CourseRequest, SectionTransfer are tabs within ClassEnrollmentWorkspace)
    if (navigation.meta?.hideChildren && navigation.children && Array.isArray(navigation.children)) {
        for (const child of navigation.children) {
            if (child.name && state.activeMenu === child.name) {
                return true
            }
            // Also check redirects in children
            if (child.redirect && child.redirect.name && state.activeMenu === child.redirect.name) {
                return true
            }
        }
    }

    // Check if current route is a child of this parent
    // (e.g., StudentProfileShow, StudentProfileCreate are children of StudentProfile)
    // This works for routes where children follow the pattern-based naming
    if (state.activeMenu && state.activeMenu.startsWith(navigation.name)) {
        const remaining = state.activeMenu.substring(navigation.name.length)
        // Check if remaining part is a known child pattern (Show, Create, Edit, etc.)
        // and starts with uppercase (camelCase pattern)
        if (remaining && remaining[0] === remaining[0].toUpperCase()) {
            const childPatterns = ['Show', 'Create', 'Edit', 'Duplicate', 'Config', 'List', 'Index']
            if (childPatterns.some(pattern => remaining.startsWith(pattern))) {
                return true
            }
        }
    }

    return false
}

const isActive = (navigation) => {
    // For children, check exact route match and redirects
    if (!navigation || !navigation.name) {
        return false
    }

    // Check if the route name matches exactly
    if (state.activeMenu === navigation.name) {
        return true
    }

    // Check redirect if exists (for child routes that redirect)
    let redirectMenu =
        navigation.redirect && navigation.redirect.name
            ? navigation.redirect.name
            : ""

    if (redirectMenu && state.activeMenu === redirectMenu) {
        return true
    }

    // Check if current route is a child of this navigation item
    // (e.g., StudentProfileShow, StudentProfileCreate are children of StudentProfile)
    if (state.activeMenu && state.activeMenu.startsWith(navigation.name)) {
        const remaining = state.activeMenu.substring(navigation.name.length)
        // Check if remaining part is a known child pattern (Show, Create, Edit, etc.)
        // and starts with uppercase (camelCase pattern)
        if (remaining && remaining[0] === remaining[0].toUpperCase()) {
            const childPatterns = ['Show', 'Create', 'Edit', 'Duplicate', 'Config', 'List', 'Index']
            if (childPatterns.some(pattern => remaining.startsWith(pattern))) {
                return true
            }
        }
    }

    return false
}

const shouldShowGroupDivider = (children, currentIndex) => {
    const currentItem = children[currentIndex]
    
    // If current item doesn't have a group, don't show divider
    if (!currentItem?.meta?.group) {
        return false
    }
    
    // If it's the first item, show divider if it has a group
    if (currentIndex === 0) {
        return true
    }
    
    // Get previous visible item's group
    let previousItem = null
    for (let i = currentIndex - 1; i >= 0; i--) {
        if (visibleMenu(children[i])) {
            previousItem = children[i]
            break
        }
    }
    
    // If no previous visible item, show divider
    if (!previousItem) {
        return true
    }
    
    // Show divider if previous item's group is different (or doesn't have a group)
    const previousGroup = previousItem?.meta?.group
    const currentGroup = currentItem.meta.group
    
    return previousGroup !== currentGroup
}

watch(
    () => route.name, //Watching the route name
    (newValue) => { // RUns when route.name change and updates the active menus
        state.activeMenu = newValue
    }
)
</script>
