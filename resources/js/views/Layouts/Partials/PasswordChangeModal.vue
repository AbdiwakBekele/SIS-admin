<template>
    <BaseModal :show="show" @close="handleClose" size="sm">
        <template #title>
            {{ $trans("auth.password.change_password") }}
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
                        v-model="displayUsername"
                        name="username"
                        :label="$trans('auth.register.props.username')"
                        disabled
                        readonly
                    />
                    <small class="text-gray-500">Your current username</small>
                </div>

                <div>
                    <BaseInput
                        type="password"
                        leading-icon="fas fa-key"
                        v-model="form.currentPassword"
                        name="currentPassword"
                        :label="$trans('auth.password.props.current_password')"
                        v-model:error="formErrors.currentPassword"
                        autofocus
                    />
                </div>

                <div>
                    <BaseInput
                        type="password"
                        leading-icon="fas fa-key"
                        v-model="form.newPassword"
                        name="newPassword"
                        :label="$trans('auth.password.props.new_password')"
                        v-model:error="formErrors.newPassword"
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
    name: "PasswordChangeModal",
}
</script>

<script setup>
import { ref, reactive, computed } from "vue"
import { useStore } from "vuex"
import { getFormErrors } from "@core/helpers/action"

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:show', 'password-changed'])

const store = useStore()

const initUrl = "user/profile/"
const formErrors = getFormErrors(initUrl)

const initForm = {
    currentPassword: "",
    newPassword: "",
    newPasswordConfirmation: "",
    username: "",
}

const form = reactive({ ...initForm })

const currentUsername = computed(() => {
    const user = store.getters['auth/user/getUser']
    return user?.username || ""
})

const displayUsername = computed({
    get: () => currentUsername.value,
    set: () => {} // No-op setter for readonly field
})

const handleClose = () => {
    emit('update:show', false)
    // Reset form when closing
    Object.assign(form, initForm)
}

const handlePasswordChange = async (response) => {
    // Password changed successfully, close modal
    emit('update:show', false)
    emit('password-changed')
    
    // Reset form
    Object.assign(form, initForm)
}
</script>
