<template>
    <span
        v-if="periods.length == 1 || !canChangePeriod"
        class="inline-flex items-center text-base font-medium text-white"
    >
        {{ currentPeriod?.name || "" }}
        <span v-if="currentPeriod?.sessionName"
            >({{ currentPeriod?.sessionName }})</span
        >
    </span>
    <template v-if="periods.length > 1 && canChangePeriod">
        <DropdownMenu double-top-margin no-width :right-margin="false">
            <template #clickable>
                <span class="text-gray-50" v-if="!screenSize.small"
                    >{{
                        currentPeriod?.name || $trans("academic.period.period")
                    }}
                    <span v-if="currentPeriod?.sessionName"
                        >({{ currentPeriod?.sessionName }})</span
                    >
                </span>
                <span v-else>
                    <i class="fas fa-calendar-alt text-gray-50"></i>
                </span>
            </template>
            <div class="py-1 max-h-96 scroller-thin-y scroller-hidden">
                <template v-for="session in getSessions()">
                    <div
                        @click="selectedSession = session.sessionName"
                        class="cursor-pointer ml-2 group flex items-center py-2 text-sm font-medium text-gray-900 dark:text-gray-400"
                    >
                        {{ session.sessionName }}
                    </div>

                    <template v-if="selectedSession == session.sessionName">
                        <DropdownItem
                            v-for="period in session.periods"
                            class="ml-2"
                            @click="select(period)"
                        >
                            <span
                                class="flex justify-between items-center w-full"
                            >
                                {{ period.name }}
                                <i
                                    v-if="period.id == currentPeriodId"
                                    class="far fa-xl text-success fa-check-circle"
                                ></i>
                            </span>
                        </DropdownItem>
                    </template>
                </template>
            </div>
        </DropdownMenu>
    </template>
</template>

<script>
export default {
    name: "PeriodSelectionFlyoutMenu",
}
</script>

<script setup>
import { ref, computed } from "vue"
import { useStore } from "vuex"
import { perform, getConfig } from "@core/helpers/action"
import { useScreenSize } from "@core/composables/useScreenSize"

const store = useStore()

const { screenSize } = useScreenSize()

const periods = getConfig("periods")
const canChangePeriod = computed(() => perform("period:change"))
const currentPeriodId = computed(
    () => store.getters["auth/user/currentPeriodId"]
)

const currentPeriod = computed(() =>
    periods.value.find((period) => period.id == currentPeriodId.value)
)

const getSessions = () => {
    let sessionNames = []
    periods.value.forEach((period) => {
        if (!sessionNames.includes(period.sessionName)) {
            sessionNames.push(period.sessionName)
        }
    })

    return sessionNames.map((sessionName) => {
        return {
            sessionName,
            periods: periods.value.filter(
                (period) => period.sessionName == sessionName
            ),
        }
    })
}

const selectedSession = ref(null)

const select = async (period) => {
    if (period.id == currentPeriodId.value) {
        return
    }

    await store
        .dispatch("academic/period/select", {
            uuid: period.uuid,
        })
        .then(async (response) => {
            await store.dispatch("auth/user/fetch")

            location.reload()
        })
        .catch((e) => {})
}
</script>
