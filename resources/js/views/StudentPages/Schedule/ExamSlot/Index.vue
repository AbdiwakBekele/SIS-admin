<template>
    <div class="p-6 bg-gray-100">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Reserve Exam Slot</h2>

        <!-- Filter Form -->
        <div class="bg-white grid grid-cols-1 md:grid-cols-3 gap-4 mb-2 p-4 rounded-lg">
            <!-- AcademicCourse -->
            <div>
                <BaseSelect v-model="form.academic_course" :label="`Select Course`" name="academic_course"
                    label-prop="name" value-prop="uuid" :options="testing" />
            </div>

            <!-- Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Date</label>
                <DatePicker v-model="form.date"
                    class="w-full border rounded-lg px-3 py-2 focus-inclusive focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Search Button -->
            <div class="flex items-end">
                <button @click="loadSlots" class="w-1/2 bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold">
                    Search
                </button>
            </div>
        </div>

        <!-- Available Slots -->
        <div>
            <h3 class="text-lg font-semibold mb-2 text-gray-800">Available Slots</h3>
            <div v-if="loading" class="text-center py-10 border rounded-lg bg-gray-50">
                <p class="text-gray-500">Loading slots...</p>
            </div>

            <div v-else-if="slots.length === 0" class="text-center py-10 border rounded-lg bg-gray-50">
                <p class="text-gray-500">No slots found for the selected date and course.</p>
            </div>

            <div v-else class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div v-for="slot in slots" :key="slot.uuid"
                    class="bg-white border rounded-lg p-4 shadow-md cursor-pointer" :class="{
                        'opacity-50 pointer-events-none': slot.remaining <= 0,
                        'ring-2 ring-blue-500': selectedSlotUuid === slot.uuid
                    }" @click="selectSlot(slot)">
                    <div class="flex justify-between mb-2">
                        <div>
                            <div class="text-lg font-semibold">{{ slot.title }}</div>



                        </div>
                        <div>
                            <span class="px-2 py-1 text-xs rounded-full" :class="{
                                'bg-white': slot.remaining > 10,
                                'bg-green-100 text-green-800': slot.remaining > 5,
                                'bg-yellow-100 text-yellow-800': slot.remaining > 0 && slot.remaining <= 5,
                                'bg-red-100 text-red-800': slot.remaining <= 0
                            }">
                                {{ slot.remaining <= 10 ? 'Remaining: ' + slot.remaining : '' }}
                            </span>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600">
                        {{ slot.startTime.formatted }} - {{ slot.endTime.formatted }}
                    </div>
                    <div class="text-sm text-gray-600">Location: {{ slot.location }}</div>
                    <div class="text-sm text-gray-600">Exam Proctor: {{ slot.exam_invigilator }}</div>
                    <div class="mt-3">
                        <input type="radio" :value="slot.uuid" v-model="selectedSlotUuid"
                            :disabled="slot.remaining <= 0" class="form-radio text-blue-500" />
                        <span class="ml-2 text-sm">Choose this slot</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 flex justify-end gap-4">
            <button @click="reserve" :disabled="!canReserve"
                class="bg-green-500 text-white px-6 py-2 rounded-lg font-semibold disabled:opacity-50">
                Reserve Slot
            </button>
        </div>
    </div>
    <div class="py-3 px-6 bg-gray-100">
        <!-- Reserved Slots Section <-- Added this section for showing reserved slots -->
        <div class="mt-2">
            <h3 class="text-lg font-semibold mb-2 text-gray-800">My Reserved Slots</h3>
            <div v-if="myReservationsLoading" class="text-center py-10 border rounded-lg bg-gray-50">
                <p class="text-gray-500">Loading reservations...</p>
            </div>
            <div v-else-if="myReservations.length === 0" class="text-center py-10 border rounded-lg bg-gray-50">
                <p class="text-gray-500">No reservations yet.</p>
            </div>
            <div v-else class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div v-for="res in myReservations" :key="res.uuid" class="bg-white border rounded-lg p-4 shadow-sm">
                    <div class="flex justify-between mb-2">
                        <div>
                            <div class="text-lg font-semibold">{{ res.examSlot.title }}</div>

                        </div>
                        <div>
                            <button @click="handleDelete(res.uuid)"
                                class="bg-red-500 text-white px-4 py-1 rounded-lg text-sm">
                                Unreserve
                            </button>
                        </div>
                    </div>

                    <div class="text-sm text-gray-500">{{ res.date.formatted }}</div>
                    <div class="text-sm text-gray-600">{{ res.examSlot.startTime.formatted }} - {{
                        res.examSlot.endTime.formatted }}</div>
                    <div class="text-sm text-gray-600">Location: {{ res.examSlot.location }}</div>
                    <div class="text-sm text-gray-600">Exam Proctor: {{ res.examSlot.examProctor }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue"
import { useToast } from "vue-toastification"
import { useStore } from "vuex"
import { getFormErrors } from "@core/helpers/action"
import { confirmDelete } from "@core/helpers/alert";

const toast = useToast()
const store = useStore()

const initUrl = "/app/student/examSlot/"
const formErrors = reactive(getFormErrors(initUrl))
const preRequisitesLoading = ref(true)
const myReservationsLoading = ref(true)

const form = reactive({
    academic_course: "",
    date: "",
})

const preRequisites = computed(() => {
    test.value = acadCourses
    return {
        acadCourses: test.value,
    }
})

const slots = ref([])
const selectedSlotUuid = ref(null)
const loading = ref(false)

const canReserve = computed(() => {
    const selectedSlot = slots.value.find(slot => slot.uuid === selectedSlotUuid.value)
    return form.academic_course && form.date && selectedSlotUuid.value && selectedSlot?.remaining > 0
})



const qwerty = ref([])
const test = ref([])
const fetch = async () => {
}

const loadSlots = async () => {
    const test = await store.dispatch("student/examSlot/preRequisite");
    qwerty.value = store.state.student.examSlot.preRequisites.academicCourses;
    if (!form.date || !form.academic_course) {
        formErrors.date = !form.date ? "Date is required" : "";
        formErrors.academic_course = !form.academic_course ? "Course is required" : "";
        return;
    }

    formErrors.date = "";
    formErrors.academic_course = "";
    loading.value = true;

    try {
        if (!exSlots.value) {
            throw new Error("Exam slots not loaded");
        }
        slots.value = exSlots.value
            .filter(slot => slot.date === form.date)
            .map(slot => ({
                id: slot.id,
                uuid: slot.uuid,
                title: slot.title,
                date: slot.date,
                startTime: slot.startTime,
                endTime: slot.endTime,
                max_students: slot.max_students,
                location: slot.location,
                exam_proctor: slot.examProctor,
                student_count: slot.student_count || 0,
                remaining: slot.maxStudents - (slot.student_count || 0)
            }));
    } catch (error) {
        toast.error("Failed to load slots: " + (error.message || "Unknown error"));
        slots.value = [];
    } finally {
        loading.value = false;
    }
};
const selectSlot = (slot) => {
    if (slot.remaining > 0) {
        selectedSlotUuid.value = slot.uuid
    }
}

const reserve = async () => {
    if (!canReserve.value) return
    const selectedSlot = slots.value.find(slot => slot.uuid === selectedSlotUuid.value)
    if (!selectedSlot) {
        toast.error("Selected slot not found")
        return
    }

    try {
        // store call (already working on your side)
        await store.dispatch("student/examSlot/create", {
            academic_course: form.academic_course,
            exam_slot: selectedSlotUuid.value,
            date: form.date,
            meta: {},
        })

        // optimistic update in Available Slots
        slots.value = slots.value.map(slot =>
            slot.uuid === selectedSlotUuid.value
                ? { ...slot, remaining: slot.remaining - 1, student_count: (slot.student_count || 0) + 1 }
                : slot
        )

        // Refresh my reservations after successful reserve

        selectedSlotUuid.value = null
        await loadMyReservations()
    } catch (error) {
        console.log(error)
        // toast.error(error.response?.data?.message || "Failed to reserve slot")
    }
}

const myReservations = ref([]);
const reservations = ref([]);

const loadMyReservations = async () => {
    myReservationsLoading.value = true;
    try {
        const response = await store.dispatch("student/examSlot/myReservations");
        myReservations.value = response || [];
    } catch (error) {
        toast.error("Failed to load reservations: " + (error.response?.data?.message || error.message));
        myReservations.value = [];
    } finally {
        myReservationsLoading.value = false;
    }
};

const unreserveSlot = async (uuid) => {
    try {
        const slot = myReservations.value.find(res => res.uuid === uuid);
        if (!slot) {
            toast.error("Reservation not found");
            return;
        }
        await store.dispatch("student/examSlot/unreserve", uuid);
        slots.value = slots.value.map(s =>
            s.uuid === slot.examSlot.uuid
                ? { ...s, student_count: (s.student_count || 0) - 1, remaining: s.max_students - ((s.student_count || 0) - 1) }
                : s
        );
        await loadMyReservations();
    } catch (error) {
        toast.error("Failed to unreserve slot: " + (error.response?.data?.message || error.message));
    }
};

const testing = ref([]);
const exSlots = ref([]);

onMounted(async () => {
    try {
        const response = await store.dispatch("student/examSlot/preRequisite");
        testing.value = response.academicCourses || [];
        exSlots.value = response.examSlots || [];
        const reservations = await store.dispatch("student/examSlot/myReservations")
        myReservations.value = reservations || []
        preRequisitesLoading.value = false;
        await loadMyReservations();
    } catch (error) {
        toast.error("Failed to load pre-requisites: " + (error.response?.data?.message || error.message));
        preRequisitesLoading.value = false;
    }
});

const handleDelete = async (uuid) => {
    if (!(await confirmDelete())) {
        return;
    }
    await unreserveSlot(uuid);
};
</script>