<template>
    <BaseModal :show="modalVisibility" @close="handleClose" size="sm">
        <template #title>
            {{ $trans("auth.password.force_change_password") }}
        </template>

        <FormAction
            no-card
            :init-url="initUrl"
            :init-form="initForm"
            :form="form"
            action="changePassword"
            :keep-adding="false"
            :after-submit="handlePasswordChange"
        >
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <BaseInput
                        type="text"
                        leading-icon="fas fa-user"
                        v-model="form.username"
                        name="username"
                        :label="$trans('auth.register.props.username')"
                        v-model:error="formErrors.username"
                        :placeholder="currentUsername"
                    />
                    <small class="text-gray-500">Optional: Change your username</small>
                </div>

                <div>
                    <BaseInput
                        type="password"
                        leading-icon="fas fa-key"
                        v-model="form.newPassword"
                        name="newPassword"
                        :label="$trans('auth.password.props.new_password')"
                        v-model:error="formErrors.newPassword"
                        autofocus
                    />
                    <small class="text-gray-500">Minimum 8 characters with uppercase, lowercase, number, and special character</small>

                    <!-- Password validation feedback -->
                    <div v-if="form.newPassword" class="mt-2 text-sm">
                        <div class="space-y-1">
                            <div :class="form.newPassword.length >= 8 ? 'text-green-600' : 'text-red-600'">
                                ✓ At least 8 characters
                            </div>
                            <div :class="/[a-z]/.test(form.newPassword) ? 'text-green-600' : 'text-red-600'">
                                ✓ Contains lowercase letter
                            </div>
                            <div :class="/[A-Z]/.test(form.newPassword) ? 'text-green-600' : 'text-red-600'">
                                ✓ Contains uppercase letter
                            </div>
                            <div :class="/\d/.test(form.newPassword) ? 'text-green-600' : 'text-red-600'">
                                ✓ Contains number
                            </div>
                            <div :class="/[@$!%*?&]/.test(form.newPassword) ? 'text-green-600' : 'text-red-600'">
                                ✓ Contains special character (@$!%*?&)
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <BaseInput
                        type="password"
                        leading-icon="fas fa-key"
                        v-model="form.newPasswordConfirmation"
                        name="newPasswordConfirmation"
                        :label="$trans('auth.password.props.new_password_confirmation')"
                        v-model:error="formErrors.newPasswordConfirmation"
                    />
                    
                    <!-- Password confirmation feedback -->
                    <div v-if="form.newPasswordConfirmation" class="mt-2 text-sm">
                        <div :class="form.newPassword === form.newPasswordConfirmation ? 'text-green-600' : 'text-red-600'">
                            ✓ Passwords match
                        </div>
                    </div>
                </div>
            </div>
        </FormAction>
    </BaseModal>
</template>

<script>
export default {
    name: "ForcePasswordChangeModal",
}
</script>

<script setup>
import { ref, reactive, computed, inject, onMounted, onBeforeUnmount, watch } from "vue"
import { useStore } from "vuex"
import { useRouter } from "vue-router"
import { getFormErrors } from "@core/helpers/action"

const props = defineProps({
    // If true, modal will be controlled externally (e.g., from Login page)
    // If false, modal will auto-show based on forceChangePassword flag
    controlled: {
        type: Boolean,
        default: false
    },
    // External visibility control (when controlled=true)
    show: {
        type: Boolean,
        default: false
    }
})

const store = useStore()
const router = useRouter()
const emitter = inject("emitter")

const initUrl = "user/profile/"
const formErrors = getFormErrors(initUrl)

const initForm = {
    username: "",
    newPassword: "",
    newPasswordConfirmation: "",
}

const form = reactive({ ...initForm })
const visibility = ref(false)

// Computed visibility - use external control if controlled, otherwise use internal ref
const modalVisibility = computed(() => {
    return props.controlled ? props.show : visibility.value
})

const currentUsername = computed(() => {
    const user = store.getters['auth/user/getUser']
    return user?.username || ""
})

const handleClose = () => {
    // Don't allow closing the modal - user must change password
    // visibility.value = false
}

const emit = defineEmits(['password-changed'])

const handlePasswordChange = async (response) => {
    // Refresh user data to get updated username if changed
    try {
        await store.dispatch("auth/user/fetch")
    } catch (error) {
        console.warn('Failed to refresh user data:', error)
    }
    
    // Password changed successfully, close modal
    visibility.value = false
    
    // Emit event for parent component to handle (e.g., Login page)
    emit('password-changed')
    
    // If no parent is listening (e.g., in layout), handle redirect ourselves
    // This maintains backward compatibility with layout usage
    const user = store.getters['auth/user/getUser']
    const roles = user?.roles || []
    
    let targetRoute
    if (roles.includes('student-profile')) {
        targetRoute = { name: 'StudentDashboard' }
    } else if (roles.includes('parent-profile')) {
        targetRoute = { name: 'ParentDashboard' }
    } else {
        targetRoute = { name: 'Dashboard' }
    }
    
    // Only redirect if we're not in login page (check current route)
    if (router.currentRoute.value.name !== 'Login') {
        router.push(targetRoute).catch(err => {
            console.error('Navigation failed:', err)
        })
    }
}

const checkAndShowModal = () => {
    const user = store.getters['auth/user/getUser']
    // Check for forceChangePassword (camelCase) or force_change_password (snake_case) or in meta
    const forceChangePassword = user?.forceChangePassword ?? 
                                user?.force_change_password ?? 
                                user?.meta?.forceChangePassword ?? 
                                user?.meta?.force_change_password ?? 
                                false
    
    if (forceChangePassword) {
        visibility.value = true
        // Reset form
        Object.assign(form, initForm)
    }
}

onMounted(async () => {
    // If controlled externally, don't auto-show based on store changes
    if (props.controlled) {
        return
    }
    
    // Listen for force password change event (fallback for programmatic triggers)
    emitter.on("forcePasswordChange", () => {
        visibility.value = true
        // Reset form
        Object.assign(form, initForm)
    })
    
    // Check immediately if user has force_change_password flag set
    checkAndShowModal()
    
    // Watch for forceChangePassword changes in store - this is the primary way to detect password change requirement
    watch(
        () => {
            const user = store.getters['auth/user/getUser']
            return user?.forceChangePassword ?? 
                   user?.force_change_password ?? 
                   user?.meta?.forceChangePassword ?? 
                   user?.meta?.force_change_password ?? 
                   false
        },
        (newValue) => {
            if (newValue) {
                checkAndShowModal()
            } else {
                // If flag is cleared, hide modal
                visibility.value = false
            }
        },
        { immediate: true, deep: true }
    )
    
    // Also watch for user object changes (in case user is loaded after modal mounts)
    watch(
        () => store.getters['auth/user/getUser'],
        (user) => {
            if (user) {
                checkAndShowModal()
            }
        },
        { immediate: true, deep: true }
    )
})

onBeforeUnmount(() => {
    emitter.all.delete("forcePasswordChange")
})
</script>

