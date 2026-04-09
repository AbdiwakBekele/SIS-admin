<template>
    <div>
        <BaseHeading>{{ $trans("general.view_log") }}</BaseHeading>
        <div
            class="flex items-end gap-2"
            v-for="viewLog in viewLogs"
            :key="viewLog.id"
        >
            <div class="text-sm font-medium">{{ viewLog.name }}</div>
            <div class="text-xs text-gray-500">
                {{ viewLog.lastViewedAt.formatted }} -
                {{
                    $trans("general.times", {
                        attribute: viewLog.details.length,
                    })
                }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ViewLog",
}
</script>

<script setup>
const props = defineProps({
    viewLogs: {
        type: Array,
        required: true,
    },
})
</script>
