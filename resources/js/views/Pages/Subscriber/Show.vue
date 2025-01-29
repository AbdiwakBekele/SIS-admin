<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('subscriber.subscriber'), path: 'SubscriberList' },
        ]"
    >
        <PageHeaderAction
            name="Subscriber"
            :title="$trans('subscriber.subscriber')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'Subscriber' })"
        >
            <BaseCard v-if="subscriber.uuid">
                <template #name>
                    {{ subscriber.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('subscriber.props.name')">
                        {{ subscriber.name }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('subscriber.props.email')">
                        {{ subscriber.email }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('subscriber.props.unsubscribed_at')"
                    >
                        {{ subscriber.unsubscribedAt.formatted }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.created_at')">
                        {{ subscriber.createdAt.formatted }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ subscriber.updatedAt.formatted }}
                    </BaseDataView>
                </dl>
                <template #footer>
                    <ShowButton>
                        <BaseButton
                            v-if="perform('subscriber:edit')"
                            design="primary"
                            @click="
                                router.push({
                                    name: 'SubscriberEdit',
                                    params: { uuid: subscriber.uuid },
                                })
                            "
                        >
                            {{ $trans("general.edit") }}
                        </BaseButton>
                    </ShowButton>
                </template>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "SubscriberShow",
}
</script>

<script setup>
import { reactive, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform } from "@core/helpers/action"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialSubscriber = {}

const initUrl = "subscriber/"

const subscriber = reactive({ ...initialSubscriber })

const setItem = (data) => {
    Object.assign(subscriber, data)
}
</script>
