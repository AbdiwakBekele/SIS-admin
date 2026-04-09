<template>
  <PageHeader :title="challengeTitle" :navs="[
    { label: $trans('gamification.gamification'), path: { name: 'MyStudentGamificationDetail' } },
    { label: $trans('gamification.quest.quests'), path: { name: 'MyStudentGamificationDetail', query: { tab: 'challenges' } } },
  ]">
  </PageHeader>

  <div class="p-6 bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <ParentTransition appear :visibility="true">
      <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
        @redirectTo="router.push({ name: 'MyStudentChallengeList' })">
        <div v-if="challenge.uuid" class="space-y-6">
          <!-- Challenge Details Card -->
          <BaseCard no-padding no-content-padding bottom-content-padding
            class="rounded-xl border border-blue-200 bg-white shadow-lg overflow-hidden">
            <template #title>
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold flex items-center text-gray-900">
                  <i class="fa-solid fa-trophy mr-3 text-yellow-500"></i>
                  Challenge Details
                </h2>
              </div>
            </template>
            <div class="p-6 space-y-4">
              <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div>
                  <p class="text-sm text-gray-500 mb-1">{{ $trans('gamification.quest.props.name') }}</p>
                  <p class="font-semibold text-gray-900">{{ challenge.name || '-' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500 mb-1">{{ $trans('gamification.quest.props.reward_type') }}</p>
                  <p class="font-medium text-blue-600 capitalize">{{ challenge.rewardType || '-' }}</p>
                </div>
                <div v-if="challenge.rewardType === 'points'">
                  <p class="text-sm text-gray-500 mb-1">{{ $trans('gamification.quest.props.reward_value') }}</p>
                  <p class="font-bold text-yellow-600 flex items-center">
                    <i class="fa-solid fa-star mr-1"></i>
                    {{ challenge.rewardValue || '-' }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-500 mb-1">Start Date</p>
                  <p class="text-gray-700">{{ challenge.startDate?.formatted || '-' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500 mb-1">End Date</p>
                  <p class="text-gray-700">{{ challenge.endDate?.formatted || '-' }}</p>
                </div>
              </div>
              
              <div v-if="challenge.description" class="pt-4 border-t">
                <p class="text-sm text-gray-500 mb-2">{{ $trans('gamification.quest.props.description') }}</p>
                <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ challenge.description }}</p>
              </div>

              <!-- Action Buttons -->
              <div class="pt-4 border-t flex items-center justify-end gap-3">
                <!-- Status Alerts -->
                <div v-if="challenge.status === 'completed'"
                  class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-center">
                  <i class="fa-solid fa-check-circle text-green-600 mr-2"></i>
                  <span class="text-green-800 text-sm font-medium">Challenge completed successfully!</span>
                </div>

                <div v-if="challenge.status === 'withdrew' && !challenge.joined"
                  class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-center">
                  <i class="fa-solid fa-info-circle text-yellow-600 mr-2"></i>
                  <span class="text-yellow-800 text-sm font-medium">You have withdrawn from this challenge.</span>
                </div>

                <BaseButton v-if="!challenge.joined" design="primary" @click="handleAccept"
                  class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200">
                  <i class="fa-solid fa-check mr-2" />
                  Accept Challenge
                </BaseButton>

                <BaseButton v-if="challenge.joined && challenge.status === 'accepted'" design="danger"
                  @click="handleWithdraw"
                  class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200">
                  <i class="fa-solid fa-times mr-2" />
                  Withdraw
                </BaseButton>

                <BaseButton v-if="challenge.status === 'withdrew'" design="primary"
                  @click="handleAccept"
                  class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200">
                  <i class="fa-solid fa-user-plus mr-2" />
                  Re-accept Challenge
                </BaseButton>
              </div>
            </div>
          </BaseCard>

          <!-- Submit Completion (Only when accepted) -->
          <BaseCard v-if="challenge.joined && challenge.status === 'accepted'" no-padding no-content-padding bottom-content-padding
            class="rounded-xl border border-blue-200 bg-white shadow-lg overflow-hidden">
            <template #title>
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold flex items-center text-gray-900">
                  <i class="fa-solid fa-paper-plane mr-3 text-blue-600"></i>
                  Submit Completion
                </h2>
              </div>
            </template>
            <div class="p-6">
              <p class="text-sm text-gray-600 mb-4">Describe how you completed this challenge</p>
              <div class="space-y-4">
                <textarea v-model="submission.description" placeholder="Write how you completed the challenge..."
                  class="w-full border border-gray-300 px-4 py-3 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all duration-200"
                  rows="4"></textarea>
                <div class="text-right">
                  <BaseButton design="primary" @click="handleSubmit"
                    :disabled="!submission.description.trim() || submission.description.trim().length < 2"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-paper-plane mr-2"></i>
                    Submit Challenge
                  </BaseButton>
                </div>
              </div>
            </div>
          </BaseCard>
        </div>
      </ShowItem>
    </ParentTransition>
  </div>
</template>

<script>
export default {
  name: 'MyStudentChallengeShow',
}
</script>

<script setup>
import { reactive, computed, inject } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { confirmAction } from '@core/helpers/alert';

const route = useRoute();
const router = useRouter();
const store = useStore();
const emitter = inject('emitter');
const $trans = inject('$trans');

const initialChallenge = {
  uuid: null,
  name: '',
  code: '',
  rewardType: '',
  rewardValue: '',
  rewardTitle: '',
  startDate: null,
  endDate: null,
  description: '',
  joined: false,
  status: null,
  studentChallenges: [],
  createdAt: null,
  updatedAt: null,
};
const initUrl = 'gamification/challenge/';
const challenge = reactive({ ...initialChallenge });

const submission = reactive({
  description: '',
});

const handleAccept = async () => {
  if (!(await confirmAction('Are you sure you want to accept this challenge?'))) {
    return;
  }
  try {
    await store.dispatch('gamification/challenge/acceptChallenge', route.params.uuid);
    // Refresh the challenge data to get updated status (backend will filter to current student)
    emitter.emit('refreshItem');
    // Refresh dashboard gamification card
    emitter.emit('refreshGamificationDashboard');
  } catch (error) {
    console.error('Accept error:', error);
    // Error handled in challenge.js
  }
};

const handleWithdraw = async () => {
  if (!(await confirmAction('Are you sure you want to withdraw from this challenge?'))) {
    return;
  }
  try {
    await store.dispatch('gamification/challenge/withdrawChallenge', route.params.uuid);
    // Refresh the challenge data to get updated status
    emitter.emit('refreshItem');
    // Refresh dashboard gamification card
    emitter.emit('refreshGamificationDashboard');
  } catch (error) {
    console.error('Withdraw error:', error);
    // Error handled in challenge.js
  }
};

const handleSubmit = async () => {
  const description = submission.description.trim();
  if (!description || description.length < 2) {
    store.dispatch('global/addToast', {
      type: 'error',
      message: $trans('gamification.challenge.description_required'),
    });
    return;
  }
  if (!(await confirmAction('Are you sure you want to submit this challenge?'))) {
    return;
  }
  try {
    console.log('Submitting description:', description);
    await store.dispatch('gamification/challenge/submitChallenge', {
      uuid: route.params.uuid,
      description,
    });
    Object.assign(challenge, {
      joined: false,
      status: 'completed',
      studentChallenges: [],
    });
    submission.description = '';
    console.log('After submit:', challenge);
    router.push({ name: 'MyStudentGamification' });
  } catch (error) {
    console.error('Submit error:', error);
    // Error handled in challenge.js
  }
};


const challengeTitle = computed(() => {
  if (challenge.name) {
    return challenge.name;
  }
  try {
    if ($trans && route.meta?.trans) {
      return $trans(route.meta.trans, { attribute: $trans('gamification.quest.quest') });
    }
  } catch (e) {
    console.warn('Translation error:', e);
  }
  return 'Challenge';
});

const challengeStatus = computed(() => {
  const now = new Date();
  const start = challenge.startDate ? new Date(challenge.startDate.date) : null;
  const end = challenge.endDate ? new Date(challenge.endDate.date) : null;
  
  if (!start || !end) {
    return { label: 'Unknown', class: 'bg-gray-100 text-gray-700', icon: 'fa-solid fa-question-circle' };
  }
  
  if (now < start) {
    return { label: 'Upcoming', class: 'bg-blue-100 text-blue-700', icon: 'fa-solid fa-clock' };
  } else if (now >= start && now <= end) {
    return { label: 'Active', class: 'bg-green-100 text-green-700', icon: 'fa-solid fa-check-circle' };
  } else {
    return { label: 'Ended', class: 'bg-gray-100 text-gray-700', icon: 'fa-solid fa-flag-checkered' };
  }
});

const rewardTypeIcon = computed(() => {
  const icons = {
    points: 'fa-solid fa-star',
    badge: 'fa-solid fa-medal',
    powerup: 'fa-solid fa-bolt',
    streakStar: 'fa-solid fa-fire',
  };
  return icons[challenge.rewardType] || 'fa-solid fa-gift';
});

const rewardItem = computed(() => {
  if (challenge.rewardType === 'badge' && challenge.badge) return challenge.badge;
  if (challenge.rewardType === 'powerup' && challenge.powerup) return challenge.powerup;
  if (challenge.rewardType === 'streakStar' && challenge.streakStar) return challenge.streakStar;
  return null;
});

const rewardItemIcon = computed(() => {
  if (challenge.rewardType === 'badge') return 'fa-solid fa-medal';
  if (challenge.rewardType === 'powerup') return 'fa-solid fa-bolt';
  if (challenge.rewardType === 'streakStar') return 'fa-solid fa-fire';
  return 'fa-solid fa-gift';
});

const rewardItemClass = computed(() => {
  if (challenge.rewardType === 'badge') return 'bg-gradient-to-br from-blue-500 to-blue-600';
  if (challenge.rewardType === 'powerup') return 'bg-gradient-to-br from-green-500 to-green-600';
  if (challenge.rewardType === 'streakStar') return 'bg-gradient-to-br from-orange-500 to-orange-600';
  return 'bg-gradient-to-br from-gray-500 to-gray-600';
});

const rewardItemTextClass = computed(() => {
  if (challenge.rewardType === 'badge') return 'text-blue-600';
  if (challenge.rewardType === 'powerup') return 'text-green-600';
  if (challenge.rewardType === 'streakStar') return 'text-orange-600';
  return 'text-gray-600';
});

const setItem = (data) => {
  // Backend now filters studentChallenges to only include current student's status
  const studentChallenges = data.studentChallenges || [];
  
  // Since backend filters, we can just get the first (and only) one
  const userChallenge = studentChallenges.length > 0 ? studentChallenges[0] : {};
  
  Object.assign(challenge, {
    ...data,
    joined: !!userChallenge.uuid,
    status: userChallenge.status || null,
    studentChallenges: studentChallenges, // Already filtered by backend
  });
  console.log('setItem challenge:', { ...challenge, studentChallenges: challenge.studentChallenges.length, status: challenge.status });
};
</script>
