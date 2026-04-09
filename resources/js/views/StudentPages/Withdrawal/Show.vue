<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('academic.academic'), path: 'Academic' },
            {
                label: $trans('academic.batch.batch'),
                path: 'AcademicBatchList',
            },
        ]"
    >
        <PageHeaderAction
            name="AcademicBatch"
            :title="$trans('academic.batch.batch')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'AcademicBatch' })"
        >
            <BaseCard v-if="batch.uuid">
                <template #title>
                    {{ batch.name }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('academic.batch.props.name')">
                        {{ batch.name }}
                        <TextMuted block v-if="batch.pgAccount">{{
                            batch.pgAccount
                        }}</TextMuted>
                    </BaseDataView>

                    <BaseDataView :label="$trans('academic.course.course')">
                        {{ batch.course.name }}
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('academic.batch.props.max_strength')"
                    >
                        {{ batch.maxStrength }}
                    </BaseDataView>

                    <BaseDataView
                        :label="
                            $trans('academic.batch.props.roll_number_prefix')
                        "
                    >
                        {{ batch.rollNumberPrefix }}
                    </BaseDataView>

                    <BaseDataView
                        class="col-span-1 sm:col-span-2"
                        :label="$trans('academic.batch.props.description')"
                    >
                        {{ batch.description }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.created_at')">
                        {{ batch.createdAt.formatted }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ batch.updatedAt.formatted }}
                    </BaseDataView>
                </dl>
                <template #footer>
                    <ShowButton>
                        <BaseButton
                            v-if="perform('batch:edit')"
                            design="primary"
                            @click="
                                router.push({
                                    name: 'AcademicBatchEdit',
                                    params: { uuid: batch.uuid },
                                })
                            "
                        >
                            {{ $trans("general.edit") }}
                        </BaseButton>
                    </ShowButton>
                </template>
            </BaseCard>

            <!-- <BaseCard
                no-padding
                no-content-padding
                v-if="batch.uuid && perform('batch:edit')"
            >
                <template #title>
                    {{ $trans("academic.config.config") }}
                </template>

                <ConfigForm :batch="batch" />
            </BaseCard> -->
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "AcademicBatchShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform } from "@core/helpers/action"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialBatch = {}

const initUrl = "academic/batch/"

const batch = reactive({ ...initialBatch })

const setItem = (data) => {
    Object.assign(batch, data)
}
</script>
