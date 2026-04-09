<template>
    <BaseModal :visibility="show" @close="closeModal">
        <template #title>
            <div class="flex items-center">
                <i class="fab fa-google text-blue-500 mr-2"></i>
                Google OAuth Setup Guide
            </div>
        </template>
        
        <div class="space-y-6">
            <!-- Introduction -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium">What you need:</p>
                        <p class="mt-1">To enable Google SSO, you'll need to create OAuth 2.0 credentials in Google Cloud Console and configure them in your application.</p>
                    </div>
                </div>
            </div>

            <!-- Step 1: Google Cloud Console -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-2">1</span>
                    Google Cloud Console Setup
                </h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-start">
                        <i class="fas fa-arrow-right text-gray-400 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">Go to Google Cloud Console:</p>
                            <a href="https://console.cloud.google.com/" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                https://console.cloud.google.com/
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-arrow-right text-gray-400 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">Create or select a project</p>
                            <p class="text-gray-600">If you don't have a project, create one first</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-arrow-right text-gray-400 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">Enable Google+ API</p>
                            <p class="text-gray-600">Go to "APIs & Services" > "Library" > Search for "Google+ API" > Click "Enable"</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Create OAuth Credentials -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-2">2</span>
                    Create OAuth 2.0 Credentials
                </h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-start">
                        <i class="fas fa-arrow-right text-gray-400 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">Go to "APIs & Services" > "Credentials"</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-arrow-right text-gray-400 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">Click "Create Credentials" > "OAuth 2.0 Client IDs"</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-arrow-right text-gray-400 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">Configure OAuth consent screen (if prompted):</p>
                            <ul class="list-disc list-inside ml-4 mt-1 text-gray-600">
                                <li>Choose "External" user type</li>
                                <li>Fill in App name, User support email, Developer contact</li>
                                <li>Add your domain to authorized domains</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-arrow-right text-gray-400 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">Configure OAuth 2.0 Client ID:</p>
                            <ul class="list-disc list-inside ml-4 mt-1 text-gray-600">
                                <li>Application type: "Web application"</li>
                                <li>Name: "Your App Name - Web Client"</li>
                                <li>Authorized redirect URIs: <code class="bg-gray-100 px-1 rounded">{{ callbackUrl }}</code></li>
                            </ul>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-2"></i>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-medium">Critical for Redirect URI:</p>
                                        <p class="mt-1">Make sure to add <strong>exactly</strong> this URL to "Authorized redirect URIs":</p>
                                        <code class="block bg-white px-2 py-1 rounded text-xs mt-1 break-all border">{{ callbackUrl }}</code>
                                        <p class="mt-1 text-xs">• Include the full URL with https://</p>
                                        <p class="text-xs">• Don't add a trailing slash</p>
                                        <p class="text-xs">• If you have multiple domains, add each callback URL</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Get Credentials -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-2">3</span>
                    Copy Your Credentials
                </h3>
                
                <div class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-2"></i>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium">Important:</p>
                                <p>Copy these credentials immediately after creation. You won't be able to see the Client Secret again!</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-lg p-3">
                            <h4 class="font-medium text-gray-900 mb-2">Google Client ID</h4>
                            <p class="text-sm text-gray-600">A long string ending with <code class="bg-gray-100 px-1 rounded">.apps.googleusercontent.com</code></p>
                            <p class="text-xs text-gray-500 mt-1">Example: 123456789-abcdefghijklmnop.apps.googleusercontent.com</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-3">
                            <h4 class="font-medium text-gray-900 mb-2">Google Client Secret</h4>
                            <p class="text-sm text-gray-600">A long string of random characters</p>
                            <p class="text-xs text-gray-500 mt-1">Example: GOCSPX-abcdefghijklmnopqrstuvwxyz</p>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg p-3">
                        <h4 class="font-medium text-gray-900 mb-2">Google Callback URL</h4>
                        <p class="text-sm text-gray-600">This is the URL where Google will redirect users after authentication:</p>
                        <code class="block bg-gray-100 px-2 py-1 rounded text-sm mt-1 break-all">{{ callbackUrl }}</code>
                    </div>
                </div>
            </div>

            <!-- Step 4: Configure Application -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-2">4</span>
                    Configure Your Application
                </h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-start">
                        <i class="fas fa-arrow-right text-gray-400 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">Add to your .env file:</p>
                            <div class="bg-gray-100 p-2 rounded mt-1 font-mono text-xs">
                                <div>GOOGLE_CLIENT_ID=your_client_id_here</div>
                                <div>GOOGLE_CLIENT_SECRET=your_client_secret_here</div>
                                <div>GOOGLE_REDIRECT_URL={{ callbackUrl }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-arrow-right text-gray-400 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">In your application settings:</p>
                            <ul class="list-disc list-inside ml-4 mt-1 text-gray-600">
                                <li>Enable "OAuth Login"</li>
                                <li>Enable "Google OAuth Login"</li>
                                <li>Enter your Google Client ID and Client Secret</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting -->
            <div class="border border-orange-200 rounded-lg p-4 bg-orange-50">
                <h3 class="text-lg font-semibold text-orange-900 mb-3 flex items-center">
                    <i class="fas fa-tools text-orange-500 mr-2"></i>
                    Troubleshooting
                </h3>
                
                <div class="space-y-4 text-sm text-orange-800">
                    <div class="flex items-start">
                        <i class="fas fa-check text-orange-500 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">"Invalid provider" error:</p>
                            <p>Check if Google OAuth is enabled in application settings</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-check text-orange-500 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">"Client ID not found" error:</p>
                            <p>Verify environment variables are set correctly</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium text-red-700">"redirect_uri_mismatch" error (Most Common):</p>
                            <div class="mt-2 space-y-2">
                                <p>This error occurs when the callback URL in your Google Cloud Console doesn't match your application's callback URL.</p>
                                <div class="bg-white p-3 rounded border border-red-200">
                                    <p class="font-medium text-red-700 mb-2">To fix this:</p>
                                    <ol class="list-decimal list-inside space-y-1 text-sm">
                                        <li>Go to <a href="https://console.cloud.google.com/" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Google Cloud Console</a></li>
                                        <li>Navigate to "APIs & Services" > "Credentials"</li>
                                        <li>Find your OAuth 2.0 Client ID and click on it</li>
                                        <li>In "Authorized redirect URIs" section, add this exact URL:</li>
                                        <li class="ml-4"><code class="bg-gray-100 px-2 py-1 rounded text-xs break-all">{{ callbackUrl }}</code></li>
                                        <li>Click "Save"</li>
                                        <li>Wait 5-10 minutes for changes to propagate</li>
                                    </ol>
                                </div>
                                <div class="bg-blue-50 p-3 rounded border border-blue-200">
                                    <p class="font-medium text-blue-700 mb-1">Important Notes:</p>
                                    <ul class="list-disc list-inside space-y-1 text-sm">
                                        <li>The URL must match exactly (including https:// and no trailing slash)</li>
                                        <li>If you have multiple domains/subdomains, add each callback URL</li>
                                        <li>For development, also add: <code class="bg-gray-100 px-1 rounded">http://localhost:8000/auth/google/callback</code></li>
                                        <li>Changes can take up to 10 minutes to take effect</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-check text-orange-500 mt-1 mr-2"></i>
                        <div>
                            <p class="font-medium">"Access blocked" error:</p>
                            <p>Check OAuth consent screen configuration and domain verification</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 flex justify-end space-x-3">
            <BaseButton @click="closeModal" design="secondary">
                Close
            </BaseButton>
            <BaseButton as="a" href="https://console.cloud.google.com/" target="_blank" design="primary">
                <i class="fas fa-external-link-alt mr-2"></i>
                Open Google Console
            </BaseButton>
        </div>
    </BaseModal>
</template>

<script>
export default {
    name: "GoogleOAuthHelpModal",
}
</script>

<script setup>
import { computed } from "vue"

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['close'])

const callbackUrl = computed(() => {
    const baseUrl = window.location.origin
    return `${baseUrl}/auth/google/callback`
})

const closeModal = () => {
    emit('close')
}
</script> 