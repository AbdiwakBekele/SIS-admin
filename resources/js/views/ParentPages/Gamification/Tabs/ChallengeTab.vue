<template>
    <div class="bg-white rounded-lg p-6 my-2">
        <p class="text-sm text-left mx-4">
            <strong class="text-2xl font-extrabold">{{ $trans('gamification.challenge.challenges') }}</strong>
        </p>
        <hr class="mx-4 mt-4">
        <table class="w-full table-auto text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Challenges</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Max Points</th>
                    <th class="px-4 py-2 text-left">Start Date</th>
                    <th class="px-4 py-2 text-left">End Date</th>
                    <th class="px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="ch in challenges" :key="ch.id" class="border-b" @click="
                    router.push({ name: 'ParentChallengeShow', params: { uuid: ch.uuid } })
                    ">
                    <td class="px-4 py-2">{{ ch.name }}</td>
                    <td class="px-4 py-2">{{ ch.rewardType }}</td>
                    <td class="px-4 py-2">{{ ch.rewardValue }}</td>
                    <td class="px-4 py-2">{{ ch.startDate?.formatted || '-' }}</td>
                    <td class="px-4 py-2">{{ ch.endDate?.formatted || '-' }}</td>
                    <td class="px-4 py-2">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye"
                                @click.stop="router.push({ name: 'ParentChallengeShow', params: { uuid: ch.uuid } })">
                                {{ $trans("general.view") }}
                            </FloatingMenuItem>
                        </FloatingMenu>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

defineProps({
    challenges: {
        type: Array,
        required: true,
    },
});
</script>

