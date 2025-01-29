<template>
    <ModuleConfig :navigations="navigations">
        <router-view />
    </ModuleConfig>
</template>

<script>
export default {
    name: "TenantConfig",
}
</script>

<script setup>
const navigations = [
    {
        name: "TenantConfigGeneral",
        icon: "fas fa-cogs",
        label: "config.config",
    },
]
</script>
