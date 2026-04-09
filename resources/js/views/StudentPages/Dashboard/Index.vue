<template>
    <div>
        <div
            class="px-4 pt-6 bg-black cursor-pointer"
            v-if="isSuperAdminUser && expireAlert"
            @click="router.push({ name: 'Subscription' })"
        >
            <BaseAlert design="error">
                {{ expireAlert }}
            </BaseAlert>
        </div>

        <DashboardStat v-if="perform('dashboard:stat')" />

        <div class="mt-4 px-4" v-if="perform('form:submit')">
            <FormList />
        </div>

        <div class="mt-4 px-4" v-if="actingAs(['student', 'guardian'], 'any')">
            <StudentList />
        </div>

        <BaseFlexContainer class="mt-4 px-4">
            <StudentSchedule />
        </BaseFlexContainer>

        <Celebration v-if="showCelebrationInDashboard" />

        <Gallery
            v-if="
                showGalleryInDashboard &&
                actingAs(['student', 'guardian'], 'any')
            "
        />
    </div>
</template>

<script setup>
import { useRouter } from "vue-router"
import {
    getConfig,
    isSuperAdmin,
    perform,
    actingAs,
} from "@core/helpers/action"
import DashboardStat from "./Stat.vue"
import StudentSchedule from "./Schedule.vue"
import StudentList from "./StudentList.vue"
import FormList from "./FormList.vue"
import Celebration from "./Celebration.vue"
import Gallery from "./Gallery.vue"

const router = useRouter()

const isSuperAdminUser = isSuperAdmin()
const expireAlert = getConfig("tenant.expireAlert")
const showCelebrationInDashboard = getConfig(
    "calendar.showCelebrationInDashboard"
)
const showGalleryInDashboard = getConfig("gallery.showGalleryInDashboard")
</script>
