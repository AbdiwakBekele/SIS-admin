<template>
    <div v-if="showDebugPanel" class="fixed bottom-4 right-4 z-50 bg-black bg-opacity-90 text-white p-4 rounded-lg shadow-lg max-w-md">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-sm font-bold">ðŸ”§ Debug Panel</h3>
            <button @click="showDebugPanel = false" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="space-y-2 text-xs">
            <div>
                <strong>Environment:</strong> {{ appEnv }}
            </div>
            <div>
                <strong>Debug Mode:</strong> {{ appDebug ? 'âœ… Enabled' : 'âŒ Disabled' }}
            </div>
            <div>
                <strong>Vue Version:</strong> {{ vueVersion }}
            </div>
            <div>
                <strong>Current Route:</strong> {{ currentRoute }}
            </div>
            <div>
                <strong>Store Modules:</strong> {{ storeModules.length }}
            </div>
            
            <div class="mt-3">
                <button @click="logStoreState" class="bg-blue-600 hover:bg-blue-700 px-2 py-1 rounded text-xs">
                    Log Store State
                </button>
                <button @click="logRouteInfo" class="bg-green-600 hover:bg-green-700 px-2 py-1 rounded text-xs ml-1">
                    Log Route Info
                </button>
            </div>
            
            <div class="mt-3">
                <button @click="togglePerformanceMode" class="bg-blue-600 hover:bg-blue-700 px-2 py-1 rounded text-xs">
                    {{ performanceMode ? 'Disable' : 'Enable' }} Performance Mode
                </button>
            </div>
        </div>
    </div>
    
    <!-- Debug Toggle Button -->
    <button 
        v-if="!showDebugPanel" 
        @click="showDebugPanel = true"
        class="fixed bottom-4 right-4 z-50 bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-lg"
        title="Show Debug Panel"
    >
        <i class="fas fa-bug"></i>
    </button>
</template>

<script>
export default {
    name: "DebugPanel",
}
</script>

<script setup>
import { ref, computed, onMounted } from "vue"
import { useRoute } from "vue-router"
import { useStore } from "vuex"

const route = useRoute()
const store = useStore()

const showDebugPanel = ref(false)
const performanceMode = ref(false)

const appEnv = computed(() => import.meta.env.VITE_APP_ENV || 'unknown')
const appDebug = computed(() => import.meta.env.VITE_APP_DEBUG === 'true')
const vueVersion = computed(() => import.meta.env.VITE_VUE_VERSION || 'unknown')
const currentRoute = computed(() => route.name || route.path)
const storeModules = computed(() => Object.keys(store.state))
const isDevelopment = computed(() => import.meta.env.DEV)

const logStoreState = () => {
    console.group('ðŸ“Š Store State')
    console.log('Full Store:', store.state)
    console.log('Modules:', storeModules.value)
    console.groupEnd()
}

const logRouteInfo = () => {
    console.group('ðŸ›£ï¸ Route Information')
    console.log('Current Route:', route)
    console.log('Route Name:', route.name)
    console.log('Route Path:', route.path)
    console.log('Route Params:', route.params)
    console.log('Route Query:', route.query)
    console.groupEnd()
}

const togglePerformanceMode = () => {
    performanceMode.value = !performanceMode.value
    if (performanceMode.value) {
        console.log('ðŸš€ Performance mode enabled - reducing debug output')
    } else {
        console.log('ðŸ”§ Performance mode disabled - full debug output')
    }
}

onMounted(() => {
    // Only show in development
    if (isDevelopment.value) {
        console.log('ðŸ”§ Debug Panel loaded')
        console.log('ðŸ“± Environment:', appEnv.value)
        console.log('ðŸ› Debug Mode:', appDebug.value)
    }
})
</script> 