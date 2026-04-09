<template>
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <TenantRecord :records="records.tenants || []" />
        <SubscriptionRecord :records="records.subscriptions || []" />
    </div>
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
        .catch(() => {
            isLoading.value = false
        })
})
</script>
