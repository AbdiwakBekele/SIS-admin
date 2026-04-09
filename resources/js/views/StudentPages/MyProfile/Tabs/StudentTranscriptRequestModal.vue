<template>
    <BaseModal :visibility="visibility" size="xl" @close="handleClose">
        <template #title>
            {{ editingRequest ? 'Edit Official Transcript Request' : 'Request Official Transcript' }}
        </template>

        <div v-if="loading" class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
        </div>

        <div v-else>
            <!-- Recipient Details -->
            <BaseFieldset class="mt-4" v-for="(recipient, index) in form.recipients" :key="recipient.uuid">
                <template #legend>
                    {{ index + 1 }}. Recipient Detail
                    <span class="text-danger ml-2 cursor-pointer" @click="removeRow(index)">
                        <i class="fas fa-times-circle"></i>
                    </span>
                </template>
                <div class="grid grid-cols-3 gap-6">
                    <!-- Recipient Name -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput type="text" v-model="recipient.recipientName" :name="`recipient.${index}.recipientName`"
                            :label="`Recipient Name`" v-model:error="formErrors[`recipient.${index}.recipientName`]" />
                    </div>

                    <!-- Department / Organization -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput type="text" v-model="recipient.organization"
                            :name="`recipient.${index}.organization`" :label="`Organization / Department`"
                            v-model:error="formErrors[`recipient.${index}.organization`]" />
                    </div>

                    <!-- No of Copies -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput type="number" v-model="recipient.copies" :name="`recipient.${index}.copies`"
                            :label="`Number of copies`" v-model:error="formErrors[`recipient.${index}.copies`]" />
                    </div>

                    <!-- Delivery Method -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSelect v-model="recipient.deliveryMethod" :name="`recipient.${index}.deliveryMethod`"
                            :label="`Delivery Method`" :options="[
                                { value: 'Mail', label: `Mail` },
                                { value: 'Email', label: `Email` },
                                { value: 'In-Person', label: `In-Person` },
                            ]" v-model:error="formErrors[`recipient.${index}.deliveryMethod`]" />
                    </div>

                    <!-- Delivery Type -->
                    <div class="col-span-3 sm:col-span-1" v-if="recipient.deliveryMethod == 'Mail'">
                        <BaseSelect v-model="recipient.deliveryType" :name="`recipient.${index}.deliveryType`" :label="`Delivery Type`"
                            :options="[
                                { value: 'Standard', label: `Standard` },
                                { value: 'Express', label: `Express` },
                            ]" v-model:error="formErrors[`recipient.${index}.type`]" />
                    </div>

                    <div class="col-span-3 sm:col-span-1" v-if="recipient.deliveryMethod == 'Email'">
                        <BaseInput type="email" v-model="recipient.email" :name="`recipient.${index}.email`"
                            :label="`Email`" v-model:error="formErrors[`recipient.${index}.email`]" placeholder="Recipient email address" />
                    </div>
                </div>

                <!-- Recipient Address Information -->
                <BaseFieldset class="mt-5" v-if="recipient.deliveryMethod == 'Mail'">
                    <template #legend>{{ `Recipient Address Information` }}</template>
                    <div class="grid grid-cols-4 gap-6">
                        <!-- Line 1 -->
                        <div class="col-span-3 sm:col-span-1">
                            <BaseInput type="text" v-model="recipient.line1" :name="`recipient.${index}.line1`"
                                :label="`Address Line 1`" v-model:error="formErrors[`recipient.${index}.line1`]" />
                        </div>

                        <!-- Line 2 -->
                        <div class="col-span-3 sm:col-span-1">
                            <BaseInput type="text" v-model="recipient.line2" :name="`recipient.${index}.line2`"
                                :label="`Address Line 2 (Optional)`" v-model:error="formErrors[`recipient.${index}.line2`]" />
                        </div>

                        <!-- City -->
                        <div class="col-span-3 sm:col-span-1">
                            <BaseInput type="text" v-model="recipient.city" :name="`recipient.${index}.city`"
                                :label="`City`" v-model:error="formErrors[`recipient.${index}.city`]" />
                        </div>

                        <!-- State -->
                        <div class="col-span-3 sm:col-span-1">
                            <BaseInput type="text" v-model="recipient.state" :name="`recipient.${index}.state`"
                                :label="`State`" v-model:error="formErrors[`recipient.${index}.state`]" />
                        </div>

                        <!-- Postal/ZipCode -->
                        <div class="col-span-3 sm:col-span-1">
                            <BaseInput type="text" v-model="recipient.postalCode"
                                :name="`recipient.${index}.postalCode`" :label="`Postal/Zip Code`"
                                v-model:error="formErrors[`recipient.${index}.postalCode`]" />
                        </div>

                        <!-- Country -->
                        <div class="col-span-3 sm:col-span-1">
                            <BaseInput type="text" v-model="recipient.country" :name="`recipient.${index}.country`"
                                :label="`Country`" v-model:error="formErrors[`recipient.${index}.country`]" />
                        </div>

                        <!-- Phone -->
                        <div class="col-span-3 sm:col-span-1">
                            <BaseInput type="text" v-model="recipient.phone" :name="`recipient.${index}.phone`"
                                :label="`Phone Number`" v-model:error="formErrors[`recipient.${index}.phone`]" />
                        </div>
                    </div>
                </BaseFieldset>
            </BaseFieldset>

            <!-- Add New Row -->
            <div class="mt-4">
                <BaseBadge design="primary" @click="addRow" class="cursor-pointer">
                    <span class="my-2">Add Additional Recipient</span>
                </BaseBadge>
            </div>
        </div>

        <template #footer>
            <div class="flex justify-end gap-2">
                <BaseButton design="secondary" @click="handleClose">Cancel</BaseButton>
                <BaseButton design="primary" @click="handleSubmit" :loading="submitting">
                    {{ editingRequest ? 'Update' : 'Submit' }} Request
                </BaseButton>
            </div>
        </template>
    </BaseModal>
</template>

<script setup>
import { reactive, ref, watch } from "vue"
import { useStore } from "vuex"
import { v4 as uuidv4 } from "uuid"
import BaseModal from "@core/components/Ui/BaseModal.vue"
import BaseInput from "@core/components/Forms/BaseInput.vue"
import BaseSelect from "@core/components/Forms/BaseSelect.vue"
import BaseButton from "@core/components/Forms/BaseButton.vue"
import BaseFieldset from "@core/components/Ui/BaseFieldset.vue"
import BaseBadge from "@core/components/Ui/BaseBadge.vue"

const props = defineProps({
    visibility: { type: Boolean, default: false },
    student: { type: Object, required: true },
    existingRequest: { type: Object, default: null },
})

const emit = defineEmits(['close', 'saved'])

const store = useStore()
const loading = ref(false)
const submitting = ref(false)
const editingRequest = ref(false)

const initRecipient = {
    uuid: uuidv4(),
    recipientName: "",
    organization: "",
    deliveryMethod: "",
    deliveryType: "",
    email: "",
    line1: "",
    line2: "",
    city: "",
    state: "",
    postalCode: "",
    country: "",
    phone: "",
    copies: 1,
}

const form = reactive({
    recipients: [],
})

const formErrors = reactive({})

watch(() => props.visibility, (newVal) => {
    if (newVal) {
        loadRequest()
    } else {
        resetForm()
    }
})

watch(() => props.existingRequest, (newVal) => {
    if (newVal && props.visibility) {
        loadRequest()
    }
})

function loadRequest() {
    if (props.existingRequest) {
        editingRequest.value = true
        form.recipients = (props.existingRequest.recipients || []).map(recipient => ({
            uuid: recipient.uuid || uuidv4(),
            recipientName: recipient.recipientName || recipient.recipient_name || "",
            organization: recipient.organization || "",
            deliveryMethod: recipient.deliveryMethod || recipient.delivery_method || "",
            deliveryType: recipient.deliveryType || recipient.delivery_type || "",
            email: recipient.email || "",
            line1: recipient.line1 || "",
            line2: recipient.line2 || "",
            city: recipient.city || "",
            state: recipient.state || "",
            postalCode: recipient.postalCode || recipient.postal_code || "",
            country: recipient.country || "",
            phone: recipient.phone || "",
            copies: recipient.copies || 1,
        }))
    } else {
        editingRequest.value = false
        if (form.recipients.length === 0) {
            addRow()
        }
    }
}

function resetForm() {
    form.recipients = []
    Object.keys(formErrors).forEach(key => delete formErrors[key])
    editingRequest.value = false
}

function addRow() {
    form.recipients.push({
        ...initRecipient,
        uuid: uuidv4(),
    })
}

function removeRow(index) {
    if (form.recipients.length > 1) {
        form.recipients.splice(index, 1)
    } else {
        form.recipients = [{ ...initRecipient, uuid: uuidv4() }]
    }
}

function mapBackendErrorsToForm(errors) {
    const mapped = {}
    for (const [key, messages] of Object.entries(errors)) {
        mapped[key] = Array.isArray(messages) ? messages[0] : messages
        if (key.startsWith('recipients.') && key.includes('.')) {
            const formKey = 'recipient.' + key.replace('recipients.', '')
            mapped[formKey] = Array.isArray(messages) ? messages[0] : messages
        }
    }
    return mapped
}

function validateRecipients() {
    let valid = true
    form.recipients.forEach((recipient, index) => {
        if ((recipient.deliveryMethod || '') === 'Email') {
            if (!(recipient.email || '').trim()) {
                formErrors[`recipient.${index}.email`] = 'The email field is required when Delivery Method is Email.'
                valid = false
            }
        }
    })
    return valid
}

async function handleSubmit() {
    submitting.value = true
    Object.keys(formErrors).forEach(key => delete formErrors[key])

    if (!validateRecipients()) {
        submitting.value = false
        return
    }

    try {
        const payload = {
            student: props.student.uuid,
            recipients: form.recipients.map(recipient => ({
                recipient_name: recipient.recipientName,
                organization: recipient.organization,
                copies: recipient.copies || 1,
                delivery_method: recipient.deliveryMethod,
                delivery_type: recipient.deliveryType,
                email: (recipient.email || '').trim() || null,
                line1: recipient.line1,
                line2: recipient.line2,
                city: recipient.city,
                state: recipient.state,
                postal_code: recipient.postalCode,
                country: recipient.country,
                phone: recipient.phone,
            })),
        }

        if (editingRequest.value && props.existingRequest) {
            await store.dispatch("student/transcriptRequest/update", {
                uuid: props.existingRequest.uuid,
                form: payload,
            })
        } else {
            await store.dispatch("student/transcriptRequest/store", {
                form: payload,
            })
        }

        emit('saved')
        handleClose()
    } catch (error) {
        if (error.response?.data?.errors) {
            Object.assign(formErrors, mapBackendErrorsToForm(error.response.data.errors))
        }
    } finally {
        submitting.value = false
    }
}

function handleClose() {
    emit('close')
}
</script>

