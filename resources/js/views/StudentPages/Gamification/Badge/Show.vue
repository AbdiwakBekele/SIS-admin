<template>
    <PageHeader :title="$trans(route.meta.trans, { attribute: $trans('gamification.badge.badges') })" :navs="[
        { label: $trans('gamification.gamification'), path: 'MyStudentGamification' },
        { label: $trans('gamification.badge.badges'), path: 'MyStudentBadgeList' },
    ]">
    </PageHeader>

    <!-- Action Buttons -->
    <ParentTransition appear :visibility="true">
        <ShowItem :init-url="initUrl" :uuid="route.params.uuid" @setItem="setItem"
            @redirectTo="router.push({ name: 'MyStudentBadgeList' })">
            <DetailLayoutVertical v-if="badge.uuid">
                <template #detail>
                    <div class="space-y-2">
                        <BaseCard no-padding no-content-padding bottom-content-padding>
                            <template #title>
                                {{ $trans('gamification.badge.badge') }}
                            </template>
                            <ListContainerVertical>
                                <ListItemView :label="$trans('gamification.badge.props.name')">
                                    {{ badge.name }}
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.badge.props.code')">
                                    {{ badge.code || '-' }}
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.badge.props.level')">
                                    {{ badge.level || '-' }}
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.badge.props.points_required')">
                                    {{ badge.points_required || '-' }}
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.badge.props.media')">
                                    <img v-if="badge.media?.length > 0" :src="badge.media[0].url" alt="Badge Media"
                                        class="w-20 h-20" />
                                    <span v-else>-</span>
                                </ListItemView>
                                <ListItemView :label="$trans('gamification.badge.props.description')">
                                    {{ badge.description || '-' }}
                                </ListItemView>
                                <ListItemView :label="$trans('general.created_at')">
                                    {{ badge.createdAt.formatted }}
                                </ListItemView>
                                <ListItemView :label="$trans('general.updated_at')">
                                    {{ badge.updatedAt.formatted }}
                                </ListItemView>
                            </ListContainerVertical>
                        </BaseCard>
                    </div>
                </template>
            </DetailLayoutVertical>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: 'MyStudentBadgeShow',
}
</script>

<script setup>
import { reactive, inject } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const $trans = inject('$trans');

const initialBadge = {};
const initUrl = 'gamification/badge/';
const badge = reactive({ ...initialBadge });

const setItem = (data) => {
    Object.assign(badge, data);
};

const handleAccept = (uuid) => {
    console.log(`Accept clicked for badge UUID: ${uuid}`);
    // Placeholder for future backend integration
};

const handleDecline = (uuid) => {
    console.log(`Decline clicked for badge UUID: ${uuid}`);
    // Placeholder for future backend integration
};
</script>