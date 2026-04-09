<template>
    <ParentTransition appear :visibility="true">
        <form @submit.prevent="customLogin" class="space-y-6">
            <!-- OAuth Error Message (moved to top) -->
            <div v-if="oauthError" class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-2"></i>
                    <div class="text-sm text-red-800">
                        <p class="font-medium">OAuth Authentication Error</p>
                        <p class="mt-1">{{ oauthError }}</p>
                    </div>
                </div>
            </div>

            <!-- Google OAuth Button - Google Branding Guidelines -->
            <div v-if="hasOAuthLoginEnabled && hasGoogleOauthLogin" class="flex justify-center">
                <a href="/auth/google/redirect"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                    v-tooltip="$trans('config.auth.props.login_with_client', { attribute: 'Google' })">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853"
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05"
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335"
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    <span class="text-gray-700 font-medium">Sign in with Google</span>
                </a>
            </div>

            <!-- Separator -->
            <div v-if="hasOAuthLoginEnabled && hasGoogleOauthLogin" class="flex items-center my-2">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="mx-3 text-gray-400 text-xs">or</span>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>

            <BaseInput type="text" leading-icon="fas fa-user" v-model="form.email" name="email"
                :label="$trans('auth.login.props.email_or_username')" v-model:error="formErrors.email" autofocus />

            <BaseInput type="password" leading-icon="fas fa-key" v-model="form.password" name="password"
                :label="$trans('auth.login.props.password')" v-model:error="formErrors.password" />

            <div class="flex items-center justify-between">
                <BaseCheckbox v-model="form.rememberMe" name="rememberMe"
                    :label="$trans('auth.login.props.remember_me')"></BaseCheckbox>

                <BaseLink to="Password" v-if="hasResetPasswordEnabled">{{
                    $trans("auth.password.forgot_password")
                }}</BaseLink>
            </div>

            <div class="flex justify-center space-x-2" v-if="hasOAuthLoginEnabled">
                <BaseButton as="a" href="/auth/facebook/redirect" design="facebook" size="xs" v-tooltip="$trans('config.auth.props.login_with_client', {
                    attribute: 'Facebook',
                })
                    " v-if="hasFacebookOauthLogin"><i class="fab fa-facebook"></i></BaseButton>

                <BaseButton as="a" href="/auth/twitter/redirect" design="info" size="xs" v-tooltip="$trans('config.auth.props.login_with_client', {
                    attribute: 'Twitter',
                })
                    " v-if="hasTwitterOauthLogin"><i class="fab fa-twitter"></i></BaseButton>

                <BaseButton as="a" href="/auth/github/redirect" size="xs" v-tooltip="$trans('config.auth.props.login_with_client', {
                    attribute: 'Github',
                })
                    " v-if="hasGithubOauthLogin"><i class="fab fa-github"></i></BaseButton>

                <BaseButton as="a" href="/auth/microsoft/redirect" size="xs" v-tooltip="$trans('config.auth.props.login_with_client', {
                    attribute: 'Microsoft',
                })
                    " v-if="hasMicrosoftOauthLogin"><i class="fab fa-microsoft"></i></BaseButton>
            </div>

            <BaseButton
                type="submit"
                design="white"
                block
                class="!rounded-md !border-0 !text-blue-800 before:!bg-slate-300 after:!bg-slate-200 hover:!text-blue-800 focus:!ring-slate-300"
            >{{
                $trans("auth.login.login")
            }}</BaseButton>
        </form>
        
        <!-- Force Password Change Modal for Parents (shown on login page) -->
        <ForcePasswordChangeModal 
            :controlled="true"
            :show="showPasswordChangeModal"
            @password-changed="handlePasswordChanged"
        />
    </ParentTransition>
</template>

<script setup>
import { reactive, onMounted, ref, computed, inject } from "vue"
import { useStore } from "vuex"
import { useRoute, useRouter } from "vue-router"
import { getFormErrors, getConfig } from "@core/helpers/action"
import { useToast } from "vue-toastification"
import ForcePasswordChangeModal from "@js/views/Layouts/Partials/ForcePasswordChangeModal.vue"

const route = useRoute()
const router = useRouter()
const store = useStore()
const toast = useToast()

const initUrl = "auth/user/"
const formErrors = getFormErrors(initUrl)
const hasOAuthLoginEnabled = getConfig("auth.enableOauthLogin")
const hasRegistrationEnabled = getConfig("auth.enableRegistration")
const hasResetPasswordEnabled = getConfig("auth.enableResetPassword")
const hasGoogleOauthLogin = getConfig("auth.enableGoogleOauthLogin")
const hasFacebookOauthLogin = getConfig("auth.enableFacebookOauthLogin")
const hasTwitterOauthLogin = getConfig("auth.enableTwitterOauthLogin")
const hasGithubOauthLogin = getConfig("auth.enableGithubOauthLogin")
const hasMicrosoftOauthLogin = getConfig("auth.enableMicrosoftOauthLogin")

const initForm = {
    email: "",
    password: "",
    rememberMe: false,
}

const form = reactive({ ...initForm })
const oauthError = ref("")
const showPasswordChangeModal = ref(false)
const pendingRedirect = ref(null)

// Function to detect if input is email or username
const isEmail = (input) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(input)
}

// Custom login method that transforms the form data
const customLogin = async () => {
    const input = form.email.trim()
    let loginData = { ...form }
    
    if (isEmail(input)) {
        // If it's an email, keep as email field
        loginData.email = input
        delete loginData.username
    } else {
        // If it's not an email, convert to username field
        loginData.username = input
        delete loginData.email
    }
    
    // Add other required fields
    loginData.password = form.password
    loginData.rememberMe = form.rememberMe
    
    try {
        const response = await store.dispatch('auth/user/login', { form: loginData })
        // Wait a bit to ensure all store updates are committed and reactive
        await new Promise(resolve => setTimeout(resolve, 150))
        afterSubmit(response)
    } catch (error) {
        console.error('Login failed:', error)
    }
}

onMounted(async () => {
    await store.dispatch("auth/user/setCSRF")

    // Check for OAuth error in URL query parameter
    if (route.query.oauth_error) {
        // Decode the error message and replace + with spaces
        oauthError.value = decodeURIComponent(route.query.oauth_error).replace(/\+/g, ' ')

        // Show as toast error
        toast.error(oauthError.value)

        // Remove the error from URL to prevent it from showing again on refresh
        const newQuery = { ...route.query }
        delete newQuery.oauth_error
        router.replace({ query: newQuery })
    }
})

const afterSubmit = (response) => {
    Object.assign(form, initForm)

    // Debug: Log the response to see what we're getting
    console.log('Login response:', response);

    // Get user data directly from the response instead of relying on store
    let user = response?.user;

    // Fallback: if no user in response, try to get from store
    if (!user) {
        user = store.getters['auth/user/getUser'];
        console.log('User from store:', user);
    }

    if (!user) {
        console.error('No user data found in response or store');
        toast.error('Login failed: No user data received');
        return;
    }

    // Check for forceChangePassword (camelCase from API)
    if (user?.forceChangePassword || user?.force_change_password) {
        const roles = user?.roles || [];
        
        // Determine target route
        let targetRoute;
        if (roles.includes('student-profile')) {
            targetRoute = { name: 'StudentDashboard' };
        } else if (roles.includes('parent-profile')) {
            targetRoute = { name: 'ParentDashboard' };
        } else {
            targetRoute = route.query.ref ? route.query.ref : { name: "Dashboard" };
        }
        
        // For parent users, show modal on login page instead of redirecting
        if (roles.includes('parent-profile')) {
            // Store the target route for after password change
            pendingRedirect.value = targetRoute;
            // Show modal on login page
            showPasswordChangeModal.value = true;
            return;
        }
        
        // For non-parent users, navigate to dashboard - modal will show automatically in layout
        router.push(targetRoute).catch(err => {
            toast.error('Navigation failed');
        });
        return
    }

    const roles = user?.roles || [];

    let targetRoute;
    if (roles.includes('student-profile')) {
        targetRoute = { name: 'StudentDashboard' };
    } else if (roles.includes('parent-profile')) {
        targetRoute = { name: 'ParentDashboard' };
    } else {
        targetRoute = route.query.ref ? route.query.ref : { name: "Dashboard" };
    }

    // Navigate immediately since login action now waits for config to load
    if (targetRoute) {
        router.push(targetRoute).catch(err => {
            toast.error('Navigation failed after login');
        });
    }
}

// Handle password change completion for parent users
const handlePasswordChanged = () => {
    // Hide modal
    showPasswordChangeModal.value = false;
    
    // Redirect to the pending route (ParentDashboard)
    if (pendingRedirect.value) {
        router.push(pendingRedirect.value).catch(err => {
            toast.error('Navigation failed after password change');
        });
        pendingRedirect.value = null;
    }
}
</script>
