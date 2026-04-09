<template>
    <template v-if="teams.length > 1">
        <DropdownMenu double-top-margin no-width :right-margin="false">
            <template #clickable>
                <span class="text-gray-50" v-if="!screenSize.small">{{
                    currentTeam?.name || $trans("team.team")
                }}</span>
                <span v-else>
                    <i class="fas fa-shuffle text-gray-50"></i>
                </span>
            </template>
            <div class="py-1 max-h-96 scroller-thin-y scroller-hidden">
                <template v-for="team in teams">
                    <DropdownItem
                        @click="select(team)"
                        v-if="team.id != currentTeam.id"
                    >
                        {{ team.name }}
                    </DropdownItem>
                    <DropdownItem v-if="team.id == currentTeamId">
                        <div class="w-full flex justify-between items-center">
                            {{ team.name }}
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
    name: "TeamSelectionFlyoutMenu",
}
</script>

<script setup>
import { computed } from "vue"
import { useStore } from "vuex"
import { getConfig } from "@core/helpers/action"
import { useScreenSize } from "@core/composables/useScreenSize"

const store = useStore()

const { screenSize } = useScreenSize()

const teams = getConfig("teams")
const currentTeamId = computed(() => store.getters["auth/user/currentTeamId"])

const currentTeam = computed(() =>
    teams.value.find((team) => team.id == currentTeamId.value)
)

const select = async (team) => {
    if (team.id == currentTeamId.value) {
        return
    }

    await store
        .dispatch("team/select", {
            uuid: team.uuid,
        })
        .then(async (response) => {
            await store.dispatch("auth/user/fetch")

            location.reload()
        })
        .catch((e) => {})
}
</script>
