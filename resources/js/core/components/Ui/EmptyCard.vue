<template>
    <BaseCard v-if="total === 0">
        <div
            class="flex flex-col items-center justify-center space-y-4 py-10 text-gray-500"
        >
            <i class="fas fa-list fa-4x"></i>
            <div class="text-xl font-semibold">
                {{ $trans("dashboard.nothing_to_show") }}
            </div>
        </div>
    </BaseCard>
</template>

<script setup>
const props = defineProps({
    total: {
        type: Number,
        default: 0,
    },
})
</script>
