<template>
    <div>
        <!-- Password Reset Modal -->
        <PasswordResetModal
            :show="showResetPasswordModal"
            :student-uuid="student.uuid"
            :student-name="`${student.firstName} ${student.lastName}`"
            @close="showResetPasswordModal = false"
            @success="onPasswordResetSuccess"
        />

        <!-- Original template content would go here -->
        <!-- For now, just a simple button to test the modal -->
        <div class="p-4">
            <button 
                class="bg-blue-500 px-4 py-2 rounded text-white" 
                @click="showResetPasswordModal = true"
            >
                <i class="fas fa-key mr-2"></i>
                Reset Password for {{ student.firstName }} {{ student.lastName }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "StudentProfileShowWithPasswordReset",
}
</script>

<script setup>
import { reactive, ref, inject, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import PasswordResetModal from "@js/components/Student/PasswordResetModal.vue"

const store = useStore()
const route = useRoute()
const router = useRouter()
const emitter = inject("emitter")

// Mock student data for testing
const student = reactive({
    uuid: "test-student-uuid",
    firstName: "John",
    lastName: "Doe",
    email: "john.doe@example.com"
})

// Password Reset Functionality
const showResetPasswordModal = ref(false)

const onPasswordResetSuccess = (message) => {
    // Show success notification
    emitter.emit('showNotification', {
        type: 'success',
        message: message
    })
}
</script> 