<template>
    <ModuleConfig :navigations="navigations">
        <router-view />
    </ModuleConfig>
</template>

<script>
export default {
    name: "SubscriptionConfig",
}
</script>

<script setup>
const navigations = [
    {
        name: "SubscriptionConfigGeneral",
        icon: "fas fa-cogs",
        label: "config.config",
    },
]
</script>
