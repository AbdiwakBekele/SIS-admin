<template>
    <BaseModal :show="show" @close="closeModal">
        <template #title>
            <div class="flex items-center">
                <i class="fas fa-key text-blue-500 mr-2"></i>
                Reset Password for {{ studentName }}
            </div>
        </template>
        
        <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium">Security Notice:</p>
                        <ul class="mt-1 space-y-1">
                            <li>• The student will be forced to change their password on next login</li>
                            <li>• Use a strong temporary password (minimum 8 characters)</li>
                            <li>• The password will not be visible after reset</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <BaseInput
                        type="password"
                        v-model="form.password"
                        name="password"
                        label="Temporary Password"
                        placeholder="Enter temporary password"
                        v-model:error="form.errors.password"
                        :required="true"
                    />
                    <small class="text-gray-500">Minimum 8 characters with uppercase, lowercase, number, and special character</small>

                    <!-- Password validation feedback -->
                    <div v-if="form.password" class="mt-2 text-sm">
                        <div class="space-y-1">
                            <div :class="form.password.length >= 8 ? 'text-green-600' : 'text-red-600'">
                                ✓ At least 8 characters
                            </div>
                            <div :class="/[a-z]/.test(form.password) ? 'text-green-600' : 'text-red-600'">
                                ✓ Contains lowercase letter
                            </div>
                            <div :class="/[A-Z]/.test(form.password) ? 'text-green-600' : 'text-red-600'">
                                ✓ Contains uppercase letter
                            </div>
                            <div :class="/\d/.test(form.password) ? 'text-green-600' : 'text-red-600'">
                                ✓ Contains number
                            </div>
                            <div :class="/[@$!%*?&]/.test(form.password) ? 'text-green-600' : 'text-red-600'">
                                ✓ Contains special character (@$!%*?&)
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <BaseInput
                        type="password"
                        v-model="form.password_confirmation"
                        name="password_confirmation"
                        label="Confirm Temporary Password"
                        placeholder="Confirm temporary password"
                        v-model:error="form.errors.password_confirmation"
                        :required="true"
                    />
                    
                    <!-- Password confirmation feedback -->
                    <div v-if="form.password_confirmation" class="mt-2 text-sm">
                        <div :class="form.password === form.password_confirmation ? 'text-green-600' : 'text-red-600'">
                            ✓ Passwords match
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <BaseButton
                    design="secondary"
                    @click="closeModal"
                    :disabled="isLoading"
                >
                    Cancel
                </BaseButton>
                <BaseButton
                    design="primary"
                    @click="resetPassword"
                    :loading="isLoading"
                    :disabled="!canResetPassword"
                >
                    <i class="fas fa-key mr-2"></i>
                    Reset Password
                </BaseButton>
            </div>
        </div>
    </BaseModal>
</template>

<script>
export default {
    name: "PasswordResetModal",
}
</script>

<script setup>
import { reactive, computed, watch, ref } from "vue"
import { perform } from "@core/helpers/action"

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    studentUuid: {
        type: String,
        required: true
    },
    studentName: {
        type: String,
        required: true
    },
    studentRoles: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['close', 'success'])

const isLoading = ref(false)

const form = reactive({
    password: '',
    password_confirmation: '',
    errors: {}
})

const canResetPassword = computed(() => {
    return form.password && 
           form.password_confirmation && 
           form.password === form.password_confirmation &&
           form.password.length >= 8 &&
           /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/.test(form.password)
})

const closeModal = () => {
    form.password = ''
    form.password_confirmation = ''
    form.errors = {}
    emit('close')
}

const resetPassword = async () => {
    console.log('resetPassword function called')
    console.log('canResetPassword:', canResetPassword.value)
    console.log('form data:', form)
    console.log('studentUuid:', props.studentUuid)
    console.log('studentRoles:', props.studentRoles)

    if (!canResetPassword.value) {
        console.log('Password validation failed')
        return
    }

    isLoading.value = true
    form.errors = {}

    try {
        const requestData = {
            password: form.password,
            password_confirmation: form.password_confirmation,
            force_change_password: true,
            roles: props.studentRoles.map(role => role.uuid || role)
        }
        
        console.log('Request data:', requestData)
        console.log('Making API call to:', `/api/v1/app/students/${props.studentUuid}/user`)
        
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        console.log('CSRF token found:', !!csrfToken)
        
        const response = await fetch(`/api/v1/app/students/${props.studentUuid}/user`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                // Add Authorization header here if needed
            },
            body: JSON.stringify(requestData)
        })
        const data = await response.json()
        console.log('API response:', data)

        if (response.ok) {
            console.log('Password reset successful')
            emit('success', 'Password reset successfully. Student will be forced to change password on next login.')
            closeModal()
        } else {
            console.log('API returned error')
            form.errors = data.errors || { password: [data.message || 'Password reset failed. Please try again.'] }
        }
    } catch (error) {
        console.error('API error:', error)
        form.errors = { password: ['An error occurred while resetting the password.'] }
    } finally {
        isLoading.value = false
    }
}

// Reset form when modal is closed
watch(() => props.show, (newValue) => {
    if (!newValue) {
        form.password = ''
        form.password_confirmation = ''
        form.errors = {}
    }
})
</script> 