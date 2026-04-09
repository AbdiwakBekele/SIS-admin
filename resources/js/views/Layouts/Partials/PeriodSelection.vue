<template>
    <span
        v-if="periods.length == 1 || !canChangePeriod"
        class="inline-flex items-center text-base font-medium text-white"
    >
        <span v-if="!screenSize.small"
            >{{ currentPeriod?.name || "" }}
            <span v-if="currentPeriod?.sessionName"
                >({{ currentPeriod?.sessionName }})</span
            >
        </span>
        <span v-else>
            <i class="fas fa-calendar-alt"></i>
        </span>
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
                <template v-for="period in periods">
                    <DropdownItem
                        @click="select(period)"
                        v-if="period.id != currentPeriodId"
                    >
                        {{ period.name }}
                    </DropdownItem>
                    <DropdownItem v-if="period.id == currentPeriodId">
                        <div class="w-full flex justify-between items-center">
                            {{ period.name }}
                            <i
                                class="far fa-xl text-success fa-check-circle"
                            ></i>
                        </div>
                    </DropdownItem>
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
import { computed } from "vue"
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
