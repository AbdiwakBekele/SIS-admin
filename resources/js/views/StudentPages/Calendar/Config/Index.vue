<template>
    <ModuleConfig :navigations="navigations">
        <router-view />
    </ModuleConfig>
</template>

<script>
export default {
    name: "CalendarConfig",
}
</script>

<script setup>
const navigations = [
    {
        name: "CalendarConfigGeneral",
        icon: "fas fa-chevron-right",
        label: "config.config",
    },
    {
        name: "CalendarConfigEventType",
        icon: "fas fa-chevron-right",
        label: "calendar.event.type.type",
    },
]
</script>
