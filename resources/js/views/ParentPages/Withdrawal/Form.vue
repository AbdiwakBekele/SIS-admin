<template>
    <FormAction 
        :has-setup-wizard="false" 
        :pre-requisites="false" 
        @setPreRequisites="setPreRequisites"
        :init-url="initUrl" 
        :init-form="initForm" 
        :form="form" 
        :set-form="setForm" 
        redirect="ParentWithdrawalList"
        :uuid="route.params.uuid" 
        @submit="validateForm"
        :keepAdding="false"
        submitText="Save"
    >
        <div class="grid grid-cols-3 gap-6">
            <!-- Withdrawal Date -->
            <div class="col-span-3 sm:col-span-1">
                <DatePicker as="date" v-model="form.withdrawal_date" name="withdrawal_date"
                    :label="$trans('student.withdrawal.props.withdrawal_effective_date')" no-clear
                    v-model:error="formErrors.withdrawal_date" />
            </div>

            <!-- School Transferring To -->
            <div class="col-span-3 sm:col-span-1">
                <BaseInput type="text" v-model="form.transfering_to" name="transfering_to"
                    :label="$trans('student.withdrawal.props.school_home_transfering_to')" autofocus
                    v-model:error="formErrors.transfering_to" />
            </div>

            <!-- Address -->
            <div class="col-span-3 sm:col-span-1">
                <BaseInput type="text" v-model="form.address" name="address"
                    :label="$trans('student.withdrawal.props.address')" autofocus v-model:error="formErrors.address" />
            </div>

            <!-- Phone -->
            <div class="col-span-3 sm:col-span-1">
                <PhoneInput v-model="form.phone" name="phone"
                    :label="$trans('student.withdrawal.props.phone')" v-model:error="formErrors.phone" />
            </div>

            <!-- Email -->
            <div class="col-span-3 sm:col-span-1">
                <BaseInput type="text" v-model="form.email" name="email"
                    :label="$trans('student.withdrawal.props.email')" autofocus v-model:error="formErrors.email" />
            </div>

            <!-- Withdrawn By -->
            <div class="col-span-3 sm:col-span-1">
                <BaseInput type="text" v-model="form.withdrawn_by" name="withdrawn_by"
                    :label="$trans('student.withdrawal.props.withdrawal_completed_by')" autofocus
                    v-model:error="formErrors.withdrawn_by" />
            </div>

            <!-- Relationship -->
            <div class="col-span-3 sm:col-span-1">
                <BaseInput type="text" v-model="form.relationship" name="relationship"
                    :label="$trans('student.withdrawal.props.your_relation_to_the_student')" autofocus
                    v-model:error="formErrors.relationship" />
            </div>

            <!-- Reason -->
            <div class="col-span-3">
                <BaseEditor v-model="form.reason" name="reason"
                    :label="$trans('student.withdrawal.props.reason_for_withdrawal')"
                    v-model:error="formErrors.reason" />
            </div>

            <!-- Checkbox Confirmation -->
            <div class="col-span-3">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" id="confirm_withdrawal" v-model="form.confirm_withdrawal"
                        class="mt-1 h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        :disabled="route.name === 'ParentWithdrawalEdit'" />
                    <label for="confirm_withdrawal" class="text-sm text-gray-700">
                        {{ $trans('student.withdrawal.props.parental_consent') }}
                    </label>
                </div>
                <div v-if="formErrors.confirm_withdrawal" class="text-red-600 text-sm mt-1">
                    {{ formErrors.confirm_withdrawal }}
                </div>
            </div>

            <!-- Signature Field -->
            <div class="mt-2 col-span-1">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{
                        $trans('student.withdrawal.props.signature') }}</label>
                    <div class="border border-gray-300 rounded-md overflow-hidden">
                        <canvas ref="canvas" class="bg-gray-100 w-full h-40" @mousedown="startDrawing" @mousemove="draw"
                            @mouseup="stopDrawing" @mouseleave="stopDrawing" @touchstart="startDrawing"
                            @touchmove="draw" @touchend="stopDrawing"></canvas>
                    </div>
                </div>
                <div class="mt-2 flex gap-3">
                    <button @click="clearCanvas" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </FormAction>
</template>

<script>
export default {
    name: "ParentWithdrawalForm",
}
</script>

<script setup>
import { onMounted, ref, reactive, nextTick } from 'vue';
import { cloneDeep } from '@core/utils';
import { getFormErrors } from '@core/helpers/action';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const route = useRoute();
const router = useRouter();
const store = useStore();

const initForm = {
    withdrawal_date: '',
    transfering_to: '',
    address: '',
    phone: '',
    email: '',
    withdrawn_by: '',
    relationship: '',
    reason: '',
    confirm_withdrawal: false,
    signature: '',
};

const initUrl = 'student/withdrawal/';
const formErrors = reactive({
    withdrawal_date: '',
    transfering_to: '',
    address: '',
    phone: '',
    email: '',
    withdrawn_by: '',
    relationship: '',
    reason: '',
    confirm_withdrawal: '',
    signature: '',
});

const preRequisites = reactive({});
const form = reactive(cloneDeep(initForm));
const canvas = ref(null);
let ctx;
let drawing = false;

// Load withdrawal data for edit mode using Vuex store
const loadWithdrawalData = async () => {
    if (!route.params.uuid) {
        console.log('Create mode - no data to load');
        return;
    }

    try {
        console.log('Loading withdrawal data for edit:', route.params.uuid);
        const response = await store.dispatch('student/withdrawal/get', {
            uuid: route.params.uuid,
        });

        console.log('Loaded withdrawal data:', response);
        setForm(response.data || response);

    } catch (error) {
        console.error('Error loading withdrawal data:', error);
        router.push({ name: 'ParentWithdrawalList' });
    }
};

onMounted(async () => {
    console.log('Route Name:', route.name, 'UUID:', route.params.uuid);
    if (route.params.uuid) {
        await loadWithdrawalData();
    }

    nextTick(() => {
        const c = canvas.value;
        if (c) {
            ctx = c.getContext('2d');
            ctx.strokeStyle = 'black';
            ctx.lineWidth = 2;
            resizeCanvas();
        }
    });
});

const setForm = (data) => {
    console.log('setForm received data:', data);
    Object.assign(initForm, {
        withdrawal_date: data.withdrawalDate || data.withdrawal_date || '',
        transfering_to: data.transferingTo || data.transfering_to || '',
        address: data.address || '',
        phone: data.phone || '',
        email: data.email || '',
        withdrawn_by: data.withdrawnBy || data.withdrawn_by || '',
        relationship: data.relationship || '',
        reason: data.reason || '',
        confirm_withdrawal: route.name === 'ParentWithdrawalEdit' ? true : (data.confirmWithdrawal || data.confirm_withdrawal || false),
        signature: data.signature || data.meta?.signature || '',
    });
    Object.assign(form, cloneDeep(initForm));

    if (initForm.signature) {
        const loadSignatureWithRetry = (attempts = 3, delay = 100) => {
            if (attempts <= 0) {
                console.error('Failed to load signature after retries');
                return;
            }
            if (ctx && canvas.value) {
                clearCanvas();
                loadSignature(initForm.signature);
            } else {
                setTimeout(() => loadSignatureWithRetry(attempts - 1, delay * 2), delay);
            }
        };
        loadSignatureWithRetry();
    }
};

const setPreRequisites = (data) => {
    console.log('setPreRequisites received data:', data);
    Object.assign(preRequisites, data);
};

const validateForm = async () => {
    console.log('validate Form triggered with data:', form);
    formErrors.confirm_withdrawal = form.confirm_withdrawal ? '' : 'You must confirm the withdrawal to submit the form';

    if (!form.confirm_withdrawal) {
        return false;
    }

    try {
        if (route.name === 'ParentWithdrawalEdit' && route.params.uuid) {
            console.log('Parent edit - dispatching parentUpdate');
            const response = await store.dispatch('student/withdrawal/parentUpdate', {
                uuid: route.params.uuid,
                form: form
            });

            console.log('Parent update successful:', response);
            Object.assign(form, response.data || {});
            router.push({ name: 'ParentWithdrawalList' });
            return true;
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        const errors = getFormErrors(error);
        console.log('Validation errors:', errors);
        const nonStudentErrors = Object.keys(errors).filter(key => key !== 'student').reduce((obj, key) => {
            obj[key] = errors[key];
            return obj;
        }, {});
        if (Object.keys(nonStudentErrors).length > 0) {
            Object.keys(nonStudentErrors).forEach(key => {
                formErrors[key] = errors[key][0] || errors[key];
            });
        } else {
            router.push({ name: 'ParentWithdrawalList' });
        }
        return true; 
    }
};

// Canvas functions
function resizeCanvas() {
    const c = canvas.value;
    if (c) {
        c.width = c.offsetWidth;
        c.height = c.offsetHeight;
        if (form.signature) {
            loadSignature(form.signature);
        }
    }
}

function startDrawing(e) {
    if (!ctx) return;
    drawing = true;
    ctx.beginPath();
    const pos = getPos(e);
    ctx.moveTo(pos.x, pos.y);
}

function draw(e) {
    if (!drawing || !ctx) return;
    const pos = getPos(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
}

function stopDrawing() {
    drawing = false;
    if (ctx) {
        saveSignature();
    }
}

function saveSignature() {
    if (!canvas.value) return;
    const dataURL = canvas.value.toDataURL('image/png');
    form.signature = dataURL;
    console.log('Saved signature:', form.signature);
}

function loadSignature(signatureData) {
    if (!ctx || !canvas.value) {
        console.error('Canvas or context not ready for signature loading');
        return;
    }
    console.log('Loading signature:', signatureData);
    if (signatureData) {
        const img = new Image();
        img.src = signatureData;
        img.onload = () => {
            ctx.drawImage(img, 0, 0, canvas.value.width, canvas.value.height);
            console.log('Signature loaded successfully');
        };
        img.onerror = () => {
            console.error('Failed to load signature image:', signatureData);
        };
    }
}

function clearCanvas() {
    if (ctx && canvas.value) {
        ctx.clearRect(0, 0, canvas.value.width, canvas.value.height);
        form.signature = '';
        console.log('Cleared signature');
    }
}

function getPos(e) {
    if (!canvas.value) return { x: 0, y: 0 };
    const rect = canvas.value.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    return {
        x: clientX - rect.left,
        y: clientY - rect.top,
    };
}
</script>

<style scoped>
canvas {
    touch-action: none;
}
</style>
