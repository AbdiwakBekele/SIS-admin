<template>
    <div>
        <div class="bg-black px-4 pb-16 pt-6">
            <DashboardStat />
        </div>
        <BaseFlexContainer class="-mt-10 px-4">
            <BaseFlexCard class="md:w-1/3">
                <DashboardAgenda />
            </BaseFlexCard>
            <DashboardChart />
        </BaseFlexContainer>

        <DashboardRecord />
    </div>
</template>

<script setup>
import DashboardStat from "./Stat.vue"
import DashboardAgenda from "./Agenda.vue"
import DashboardChart from "./Chart.vue"
import DashboardRecord from "./Record.vue"
</script>
