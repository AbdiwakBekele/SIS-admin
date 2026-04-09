<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Challenge Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-1">{{ challenge.title }}</h1>
            <p class="text-sm text-gray-600">
                {{ challenge.startDate }} — {{ challenge.endDate }}
            </p>
            <p class="mt-4 text-gray-800">{{ challenge.description }}</p>
        </div>

        <!-- Single toggle button -->
        <BaseButton @click="toggleStatus">
            {{ status === 'activated' ? 'Cancel Challenge' : 'Accept Challenge' }}
        </BaseButton>


        <div v-if="status == 'activated'" class="my-2">
            <div class="col-span-3">
                <BaseEditor name="description" :edit="route.params.uuid ? true : false" :label="$trans('gamification.quest.props.description')
                    " />
            </div>

            <div class="col">
                <MediaUpload multiple :label="$trans('general.file')" module="enquiry" :media="form.media"
                    :media-token="form.mediaToken" @isUpdated="form.mediaUpdated = true"
                    @setHash="(hash) => form.mediaHash.push(hash)" />
            </div>

            <div class="text-right">
                <BaseButton :design="primary">Submit</BaseButton>
            </div>

        </div>





    </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { v4 as uuidv4 } from "uuid"



const initForm = {

    media: [],
    mediaUpdated: false,
    mediaToken: uuidv4(),
    mediaHash: [],
}


const form = reactive({ ...initForm })

const status = ref('deactivated')

function toggleStatus() {
    console.log('asdf');
    status.value = status.value === 'activated' ? 'deactivated' : 'activated'
    console.log(status.value);
}

// Dummy challenges (could come from a store or API)
const challenges = ref([
    {
        id: 1,
        title: 'Walk 10,000 Steps',
        description: `This challenge encourages participants to prioritize their physical well-being by completing 10,000 steps in a single day. Walking regularly improves cardiovascular health, boosts energy levels, and helps maintain a healthy weight. Participants can use step counters or fitness tracking apps to log their steps throughout the day. The goal is to stay active, build consistency, and develop a habit of daily movement.`,
        startDate: '2025-05-20',
        endDate: '2025-05-20',
    },

    {
        id: 2,
        title: 'Read a Chapter',
        description: 'Read at least one chapter of any book.',
        startDate: '2025-05-21',
        endDate: '2025-05-21',
    },
    {
        id: 3,
        title: 'Drink 2L Water',
        description: 'Stay hydrated by drinking 2 liters of water.',
        startDate: '2025-05-22',
        endDate: '2025-05-22',
    },
    {
        id: 4,
        title: 'Meditate for 15 Minutes',
        description: 'Take a mindful break and meditate.',
        startDate: '2025-05-23',
        endDate: '2025-05-23',
    },
    {
        id: 5,
        title: 'Write a Journal Entry',
        description: 'Reflect on your day by writing at least 200 words.',
        startDate: '2025-05-24',
        endDate: '2025-05-24',
    },
])


// Get challenge ID from route params (fallback to 1)
const route = useRoute()
const challengeId = Number(route.params.id) || 1

// Find current challenge
const challenge = computed(
    () => challenges.value.find((c) => c.id === challengeId) || challenges.value[0]
)

// Sort participants by points desc
const sortedParticipants = computed(() =>
    [...participants.value].sort((a, b) => b.points - a.points)
)

// Top 3 for highlighted leaderboard
const topThree = computed(() => sortedParticipants.value.slice(0, 3))
</script>

<style scoped>
/* You can tweak spacing or card styles here if needed */
</style>
