<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('subscription.subscription')">
                <PageHeaderAction
                    url="subscriptions/"
                    name="Subscription"
                    :title="$trans('subscription.subscription')"
                    :actions="userActions"
                    :dropdown-actions="dropdownActions"
                    @toggleFilter="showFilter = !showFilter"
                />
            </PageHeader>
        </template>

        <template #filter>
            <ParentTransition appear :visibility="showFilter">
                <FilterForm
                    @refresh="emitter.emit('listItems')"
                    @hide="showFilter = false"
                ></FilterForm>
            </ParentTransition>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable
                :header="subscriptions.headers"
                :meta="subscriptions.meta"
                module="subscription"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="subscription in subscriptions.data"
                    :key="subscription.uuid"
                    @doubleClick="
                        router.push({
                            name: 'SubscriptionShow',
                            params: { uuid: subscription.uuid },
                        })
                    "
                >
                    <DataCell
                        name="codeNumber"
                        clickable
                        @click="
                            router.push({
                                name: 'SubscriptionShow',
                                params: { uuid: subscription.uuid },
                            })
                        "
                    >
                        <span class="font-medium text-blue-700 hover:underline">
                            {{ subscription.codeNumber }}
                        </span>
                        <BaseBadge design="error" v-if="!subscription.status">{{
                            subscription.statusDisplay
                        }}</BaseBadge>
                        <BaseBadge v-if="subscription.isOnline">{{
                            $trans("subscription.props.online")
                        }}</BaseBadge>
                    </DataCell>
                    <DataCell name="tenant">
                        {{ subscription.tenant.domain }}
                    </DataCell>
                    <DataCell name="plan">
                        {{ subscription.plan.name }} ({{
                            subscription.plan.code
                        }})
                    </DataCell>
                    <DataCell name="amount">
                        {{ subscription.amount.formatted }}
                    </DataCell>
                    <DataCell name="startDate">
                        {{ subscription.startDate.formatted }}
                    </DataCell>
                    <DataCell name="expiryDate">
                        {{ subscription.expiryDate.formatted }}
                    </DataCell>
                    <DataCell name="date">
                        {{ subscription.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action" align="center">
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <button
                                type="button"
                                class="text-blue-500 hover:text-blue-600"
                                v-tooltip="$trans('general.show')"
                                @click="
                                    router.push({
                                        name: 'SubscriptionShow',
                                        params: { uuid: subscription.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-eye text-[12px]"></i>
                            </button>
                            <a
                                v-if="subscription.status"
                                :href="`/subscriptions/${subscription.uuid}/print`"
                                target="_blank"
                                class="text-slate-600 hover:text-slate-700"
                                v-tooltip="$trans('global.print', { attribute: $trans('subscription.subscription') })"
                            >
                                <i class="fas fa-print text-[12px]"></i>
                            </a>
                            <button
                                v-if="
                                    perform('subscription:edit') &&
                                    !subscription.isOnline
                                "
                                type="button"
                                class="text-blue-500 hover:text-blue-600"
                                v-tooltip="$trans('general.edit')"
                                @click="
                                    router.push({
                                        name: 'SubscriptionEdit',
                                        params: { uuid: subscription.uuid },
                                    })
                                "
                            >
                                <i class="fas fa-edit text-[12px]"></i>
                            </button>
                            <button
                                v-if="
                                    perform('subscription:delete') &&
                                    !subscription.isOnline
                                "
                                type="button"
                                class="text-red-500 hover:text-red-600"
                                v-tooltip="$trans('general.delete')"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: subscription.uuid,
                                    })
                                "
                            >
                                <i class="fas fa-trash text-[12px]"></i>
                            </button>
                        </div>
                    </DataCell>
                </DataRow>
                <template #actionButton>
                    <BaseButton
                        v-if="perform('subscription:create')"
                        @click="router.push({ name: 'SubscriptionCreate' })"
                    >
                        {{
                            $trans("global.add", {
                                attribute: $trans("subscription.subscription"),
                            })
                        }}
                    </BaseButton>
                </template>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "SubscriptionList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
import FilterForm from "./Filter.vue"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]
if (perform("subscription:create")) {
    userActions.unshift("create")
}
if (perform("subscription:config")) {
    userActions.unshift("config")
}

let dropdownActions = []
if (perform("subscription:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "subscription/"
const showFilter = ref(false)

const subscriptions = reactive({})

const setItems = (data) => {
    Object.assign(subscriptions, data)
}
</script>
