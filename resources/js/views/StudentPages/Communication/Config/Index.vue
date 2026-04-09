<template>
    <ModuleConfig :navigations="navigations">
        <router-view />
    </ModuleConfig>
</template>

<script>
export default {
    name: "CommunicationConfig",
}
</script>

<script setup>
const navigations = [
    {
        name: "CommunicationConfigGeneral",
        icon: "fas fa-chevron-right",
        label: "config.config",
    },
    {
        name: "CommunicationConfigAnnouncementType",
        icon: "fas fa-chevron-right",
        label: "communication.announcement.type.type",
    },
]
</script>
