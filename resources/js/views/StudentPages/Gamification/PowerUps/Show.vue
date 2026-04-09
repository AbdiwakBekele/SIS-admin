<template>
  <PageHeader :title="$trans(route.meta.trans, { attribute: $trans('gamification.powerup.powerup') })" :navs="[
    { label: $trans('gamification.gamification'), path: 'MyStudentGamification' },
    { label: $trans('gamification.powerup.powerup'), path: 'MyStudentPowerUpsList' },
  ]">
  </PageHeader>
  <ParentTransition appear :visibility="true">
    <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem">
      <div class="bg-white rounded-lg p-6 shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <img v-if="powerup.icon || (powerup.media && powerup.media.length)"
              :src="powerup.icon || powerup.media[0]?.url" alt="Powerup Icon" class="w-24 h-24 mb-4 object-cover" />
            <span v-else class="text-sm text-gray-600">No Icon</span>
            <h2 class="text-xl font-semibold">{{ powerup.name || '-' }}</h2>
            <p class="text-sm text-gray-600">{{ $trans('gamification.powerup.props.points_required') }}:
              {{ powerup.points || powerup.pointsRequired || '-' }}</p>
            <p class="text-sm text-gray-600">Created: {{ powerup.createdAt?.formatted || powerup.createdAt || '-' }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">Description</h3>
            <p class="text-sm text-gray-600" v-html="powerup.description || '-'"></p>
          </div>
        </div>
      </div>
    </ShowItem>
  </ParentTransition>
</template>

<script>
export default {
  name: 'MyStudentPowerUpsShow',
}
</script>

<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import { perform } from '@core/helpers/action'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const initUrl = "gamification/powerup/"
const powerup = ref({})

const setItem = (data) => {
  powerup.value = data
  console.log('Powerup data loaded:', data)
}
</script>
