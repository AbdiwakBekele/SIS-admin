<template>
    <BaseFlexContainer class="mt-4 px-4">
        <BaseFlexCard class="md:w-1/2">
            <TenantRecord :records="records.tenants" />
        </BaseFlexCard>

        <BaseFlexCard class="md:w-1/2">
            <SubscriptionRecord :records="records.subscriptions" />
        </BaseFlexCard>
    </BaseFlexContainer>
</template>

<script>
export default {
    name: "DashboardRecord",
}
</script>

<script setup>
import { onMounted, reactive, ref } from "vue"
import { useStore } from "vuex"
import TenantRecord from "./TenantRecord.vue"
import SubscriptionRecord from "./SubscriptionRecord.vue"

const store = useStore()

const isLoading = ref(false)
const records = reactive({})

onMounted(async () => {
    isLoading.value = true

    await store
        .dispatch("dashboard/getRecord")
        .then((response) => {
            isLoading.value = false
            Object.assign(records, response)
        })
        .catch((e) => {
            isLoading.value = false
        })
})
</script>
