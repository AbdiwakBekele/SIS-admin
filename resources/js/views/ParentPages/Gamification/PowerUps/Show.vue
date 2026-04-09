<template>
    <PageHeader
        :title="$trans(route.meta.trans, { attribute: $trans(route.meta.label) })"
        :navs="[
            { label: $trans('gamification.gamification'), path: 'MyParentStudentGamification' },
            { label: $trans('gamification.powerup.powerups'), path: 'MyParentStudentGamification', query: { tab: 'powerups' } },
        ]"
    >
    <PageHeaderAction
      name="ParentChallenge"
      :title="$trans('gamification.powerup.powerups')"
      :actions="[]"
    >
    <BaseButton design="primary" class="shadow-sm" @click="router.push({ name: 'MyParentStudentGamification', query: { tab: 'powerups' } })">
        {{ $trans("global.list_all", { attribute: $trans('gamification.powerup.powerups') }) }}
      </BaseButton>
    </PageHeaderAction>
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'ParentPowerUpsList' })"
        >
            <BaseCard v-if="powerUp.uuid">
                <template #title>
                    {{ powerUp.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView v-if="powerUp.icon" :label="$trans('gamification.powerups.props.icon')">
                        <img :src="powerUp.icon" alt="" width="40px" />
                    </BaseDataView>
                    <BaseDataView class="col-span-1 sm:col-span-2" :label="$trans('gamification.powerups.props.description')" html>
                        {{ powerUp.description }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.created_at')">
                        {{ powerUp.createdAt?.formatted }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ powerUp.updatedAt?.formatted }}
                    </BaseDataView>
                </dl>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "ParentPowerUpsShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"

const route = useRoute()
const router = useRouter()

const initialPowerUp = {}

const initUrl = "gamification/powerup/"

const powerUp = reactive({ ...initialPowerUp })

const setItem = (data) => {
    Object.assign(powerUp, data)
}
</script>
