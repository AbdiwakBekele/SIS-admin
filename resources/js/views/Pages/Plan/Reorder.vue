<template>
    <BaseModal :show="visibility" @close="closeModal">
        <template #title>
            {{
                $trans("global.reorder", {
                    attribute: $trans("plan.plan"),
                })
            }}
        </template>

        <div v-if="state.plans.length">
            <draggable class="space-y-2" :list="state.plans" item-key="uuid">
                <template #item="{ element, index }">
                    <div class="flex border rounded-xl px-4 py-2">
                        <i class="fas fa-arrows mr-2 cursor-pointer"></i>
                        <BaseLabel>{{ element.name }}</BaseLabel>
                    </div>
                </template>
            </draggable>
        </div>
        <div v-else>
            <BaseAlert design="info" size="xs">
                {{ $trans("general.errors.record_not_found") }}
            </BaseAlert>
        </div>

        <div class="mt-4 flex justify-end" v-if="state.plans.length">
            <BaseButton @click="reorder">
                {{ $trans("general.reorder") }}
            </BaseButton>
        </div>
    </BaseModal>
</template>

<script>
export default {
    name: "PlanReorder",
}
</script>

<script setup>
import { onMounted, ref, reactive } from "vue"
import { useRoute } from "vue-router"
import { useStore } from "vuex"
import draggable from "vuedraggable"
import { getFormErrors } from "@core/helpers/action"

const route = useRoute()
const store = useStore()

const emit = defineEmits(["close", "refresh"])

const props = defineProps({
    visibility: {
        type: Boolean,
        default: false,
    },
})

const initForm = {
    plans: [],
}

const initUrl = "plan/"
const formErrors = getFormErrors(initUrl)

const isLoading = ref(false)

const state = reactive({
    plans: [],
})
const form = reactive({ ...initForm })

const getPlans = async () => {
    isLoading.value = true
    await store
        .dispatch("plan/list", {
            params: {
                all: true,
            },
        })
        .then((response) => {
            isLoading.value = false
            state.plans = response
        })
        .catch((e) => {
            isLoading.value = false
        })
}

const reorder = async () => {
    isLoading.value = true
    await store
        .dispatch("plan/reorder", {
            data: {
                plans: state.plans,
            },
        })
        .then((response) => {
            isLoading.value = false
            emit("refresh")
            emit("close")
        })
        .catch((e) => {
            isLoading.value = false
        })
}

const closeModal = () => {
    emit("close")
}

onMounted(() => {
    getPlans()
})
</script>
