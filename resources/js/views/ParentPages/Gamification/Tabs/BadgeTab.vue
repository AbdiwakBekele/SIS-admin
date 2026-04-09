<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4">
            <strong class="text-2xl font-extrabold">{{ $trans('gamification.badge.badges') }}</strong>
        </p>
        <hr class="mx-4 mt-4">
        <!-- Badge Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4 px-4">

            <div v-for="badge in badges" :key="badge.id" @click="
                router.push({
                    name: 'ParentBadgeShow',
                    params: { uuid: badge.uuid },
                })
                "
                class="flex flex-col items-center bg-gray-50 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 relative">
                <i :class="'fa-solid fa-bolt text-3xl text-blue-600 mb-2'"></i>

                <h4 class="font-semibold text-sm">{{ badge.name }}</h4>
                <p class="text-xs text-gray-600 text-center mt-1">{{ badge.pointsRequired }}</p>
                <!-- Floating Menu -->
                <div class="absolute top-2 right-2">
                    <FloatingMenu>
                        <FloatingMenuItem icon="fas fa-eye"
                            @click.stop="router.push({ name: 'ParentBadgeShow', params: { uuid: badge.uuid } })">
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
    badges: {
        type: Array,
        required: true,
    },
});
</script>

