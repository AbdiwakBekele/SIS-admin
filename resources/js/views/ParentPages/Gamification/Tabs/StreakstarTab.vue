<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong class="text-2xl font-extrabold">{{ $trans('gamification.streakstar.streakstars') }}</strong>
        </p>
        <hr class="mx-4 mt-3">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4 px-4">

            <div v-for="streak in streakStars" :key="streak.uuid || streak.id" @click="handleView(streak)"
                class="flex flex-col items-center bg-gray-50 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 relative cursor-pointer">
                <i :class="'fa-solid fa-people-group text-3xl text-yellow-500 mb-2'"></i>

                <h4 class="font-semibold text-sm">{{ streak.name }}</h4>
                <p class="text-xs text-gray-600 mt-1">{{ $trans('gamification.streakstar.props.points_required') }}: {{ streak.pointsRequired || streak.points_required || 0 }}</p>
                <!-- Floating Menu -->
                <div v-if="streak.uuid" class="absolute top-2 right-2">
                    <FloatingMenu>
                        <FloatingMenuItem icon="fas fa-eye"
                            @click.stop="handleView(streak)">
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
    streakStars: {
        type: Array,
        required: true,
    },
});

const handleView = (streak) => {
    if (!streak || !streak.uuid) {
        console.error('StreakStar missing uuid:', streak);
        return;
    }
    try {
        router.push({
            name: 'ParentStreakStarShow',
            params: { uuid: streak.uuid },
        });
    } catch (error) {
        console.error('Navigation error:', error);
    }
};
</script>

