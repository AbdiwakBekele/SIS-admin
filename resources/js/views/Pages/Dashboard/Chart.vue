<template>
    <BaseFlexCard class="md:w-1/3" :is-loading="isLoading">
        <template #title>{{ $trans("dashboard.status_wise_tenant") }}</template>
        <div>
            <Doughnut
                :data="state.statusWiseData"
                :options="options"
                v-if="state.statusWiseData?.labels"
            />
        </div>
    </BaseFlexCard>

    <BaseFlexCard class="md:w-1/3" :is-loading="isLoading">
        <template #title>{{ $trans("dashboard.plan_wise_tenant") }}</template>
        <div>
            <Doughnut
                :data="state.planWiseData"
                :options="options"
                v-if="state.planWiseData?.labels"
            />
        </div>
    </BaseFlexCard>
</template>

<script>
export default {
    name: "DashboardChart",
}
</script>

<script setup>
import { onMounted, reactive, ref } from "vue"
import { useStore } from "vuex"
import { Doughnut } from "vue-chartjs"
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js"

ChartJS.register(ArcElement, Tooltip, Legend)

const store = useStore()

const options = ref({
    responsive: true,
})

const isLoading = ref(false)
const state = reactive({})

onMounted(async () => {
    isLoading.value = true

    await store
        .dispatch("dashboard/getChart")
        .then((response) => {
            isLoading.value = false
            Object.assign(state, response)
        })
        .catch((e) => {
            isLoading.value = false
        })
})
</script>
