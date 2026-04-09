<template>
  <PageHeader :title="challengeTitle" :navs="[
    { label: $trans('gamification.gamification'), path: { name: 'MyParentStudentGamificationDetail' } },
    { label: $trans('gamification.quest.quests'), path: { name: 'MyParentStudentGamificationDetail', query: { tab: 'challenges' } } },
  ]">
  </PageHeader>

  <div class="p-6 bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
  <ParentTransition appear :visibility="true">
    <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
        @redirectTo="router.push({ name: 'ParentChallengeList' })">
        <div v-if="challenge.uuid" class="space-y-6">
          <!-- Challenge Details Card -->
          <BaseCard no-padding no-content-padding bottom-content-padding
            class="rounded-xl shadow-lg border border-blue-200 overflow-hidden">
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
              
              <!-- Status Information -->
              <div class="pt-4 border-t">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-gray-500 mb-2">Status</p>
                    <span :class="[
                      'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold',
                      challenge.status === 'completed' || challenge.status === 'approved' ? 'bg-green-100 text-green-700 border border-green-200' :
                      challenge.status === 'accepted' ? 'bg-blue-100 text-blue-700 border border-blue-200' :
                      challenge.status === 'pending' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' :
                      challenge.status === 'rejected' ? 'bg-red-100 text-red-700 border border-red-200' :
                      challenge.status === 'withdrew' ? 'bg-gray-100 text-gray-600 border border-gray-300' :
                      'bg-gray-100 text-gray-700 border border-gray-200'
                    ]">
                      <i :class="
                        challenge.status === 'completed' || challenge.status === 'approved' ? 'fa-solid fa-check-circle' :
                        challenge.status === 'accepted' ? 'fa-solid fa-hourglass-half' :
                        challenge.status === 'pending' ? 'fa-solid fa-clock' :
                        challenge.status === 'rejected' ? 'fa-solid fa-times-circle' :
                        challenge.status === 'withdrew' ? 'fa-solid fa-user-minus' :
                        'fa-solid fa-question-circle'
                      " class="mr-1.5"></i>
                      {{ challenge.status ? challenge.status.charAt(0).toUpperCase() + challenge.status.slice(1) : 'Not Joined' }}
                    </span>
                  </div>
                  
                  <div v-if="challenge.status === 'completed' || challenge.status === 'approved'"
                    class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-center">
                    <i class="fa-solid fa-check-circle text-green-600 mr-2"></i>
                    <span class="text-green-800 text-sm font-medium">Challenge completed successfully!</span>
                  </div>
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
  name: 'ParentChallengeShow',
}
</script>

<script setup>
import { reactive, computed, inject } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const $trans = inject('$trans');

const initialChallenge = {
  uuid: null,
  name: '',
  code: '',
  rewardType: '',
  rewardValue: '',
  rewardTitle: '',
  badge: '',
  powerup: '',
  streakStar: '',
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
  const studentChallenges = data.studentChallenges || [];
  const userChallenge = studentChallenges.length > 0 ? studentChallenges[0] : {};
  Object.assign(challenge, {
    ...initialChallenge,
    ...data,
    joined: !!userChallenge.uuid,
    status: userChallenge.status || challenge.status,
    studentChallenges,
  });
};
</script>
