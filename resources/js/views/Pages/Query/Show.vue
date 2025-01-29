<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[{ label: $trans('query.query'), path: 'QueryList' }]"
    >
        <PageHeaderAction
            name="Query"
            :title="$trans('query.query')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'Query' })"
        >
            <BaseCard v-if="query.uuid">
                <template #name>
                    {{ query.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('query.props.name')">
                        {{ query.name }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('query.props.email')">
                        {{ query.email }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('query.props.contact_number')">
                        {{ query.contactNumber }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('query.props.subject')">
                        {{ query.subject }}
                    </BaseDataView>

                    <BaseDataView
                        class="col-span-1 sm:col-span-2"
                        :label="$trans('query.props.message')"
                    >
                        {{ query.message }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.created_at')">
                        {{ query.createdAt.formatted }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ query.updatedAt.formatted }}
                    </BaseDataView>
                </dl>
                <template #footer> </template>
            </BaseCard>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "QueryShow",
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

const initialQuery = {}

const initUrl = "query/"

const query = reactive({ ...initialQuery })

const setItem = (data) => {
    Object.assign(query, data)
}
</script>
