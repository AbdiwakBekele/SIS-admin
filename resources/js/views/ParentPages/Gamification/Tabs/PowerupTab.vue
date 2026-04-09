<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong class="text-2xl font-extrabold">{{ $trans('gamification.powerup.powerups') }}</strong>
        </p>
        <hr class="mx-4 mt-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4 px-4">

            <div v-for="power in powerups" :key="power.uuid || power.id" @click="handleView(power)"
                class="flex flex-col items-center bg-gray-50 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 relative cursor-pointer">
                <i :class="'fa-solid fa-star text-3xl text-indigo-500 mb-2'"></i>

                <h4 class="font-semibold text-sm">{{ power.name }}</h4>
                <p class="text-xs text-gray-600 mt-1">{{ power.pointsRequired }} pts</p>
                <!-- Floating Menu -->
                <div v-if="power.uuid" class="absolute top-2 right-2">
                    <FloatingMenu>
                        <FloatingMenuItem icon="fas fa-eye"
                            @click.stop="handleView(power)">
                            {{ $trans("general.view") }}
                        </FloatingMenuItem>
                    </FloatingMenu>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

defineProps({
    powerups: {
        type: Array,
        required: true,
    },
});

const handleView = (power) => {
    if (!power || !power.uuid) {
        console.error('PowerUp missing uuid:', power);
        return;
    }
    try {
        router.push({
            name: 'ParentPowerUpsShow',
            params: { uuid: power.uuid },
        });
    } catch (error) {
        console.error('Navigation error:', error);
    }
};
</script>

