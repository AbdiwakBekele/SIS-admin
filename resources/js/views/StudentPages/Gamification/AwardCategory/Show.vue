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
                label: $trans('academic.division.division'),
                path: 'AcademicDivisionList',
            },
        ]"
    >
        <PageHeaderAction
            name="AcademicDivision"
            :title="$trans('academic.division.division')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'AcademicDivision' })"
        >
            <DetailLayoutVertical v-if="division.uuid">
                <template #detail>
                    <div class="space-y-2">
                        <BaseCard no-padding no-content-padding>
                            <template #title>
                                {{ $trans("academic.division.division") }}
                            </template>

                            <template #action> </template>
                            <ListContainerVertical>
                                <ListItemView
                                    :label="
                                        $trans('academic.division.props.name')
                                    "
                                >
                                    {{ division.name }}
                                    <TextMuted
                                        block
                                        v-if="division.pgAccount"
                                        >{{ division.pgAccount }}</TextMuted
                                    >
                                </ListItemView>

                                <ListItemView
                                    :label="
                                        $trans('academic.division.props.code')
                                    "
                                >
                                    {{ division.code }}
                                    <TextMuted block>{{
                                        division.shortcode
                                    }}</TextMuted>
                                </ListItemView>

                                <ListItemView
                                    :label="$trans('academic.program.program')"
                                >
                                    {{ division?.program?.name || "-" }}
                                </ListItemView>

                                <ListItemView
                                    :label="$trans('general.created_at')"
                                >
                                    {{ division.createdAt.formatted }}
                                </ListItemView>

                                <ListItemView
                                    :label="$trans('general.updated_at')"
                                >
                                    {{ division.updatedAt.formatted }}
                                </ListItemView>
                            </ListContainerVertical>
                        </BaseCard>
                    </div>
                </template>

                <BaseCard no-padding no-content-padding bottom-content-padding>
                    <template #title>
                        {{
                            $trans("global.detail", {
                                attribute: $trans("academic.division.division"),
                            })
                        }}
                    </template>

                    <SimpleTable
                        :header="header"
                        v-if="division.courses.length > 0"
                    >
                        <DataRow
                            v-for="course in division.courses"
                            :key="course.uuid"
                        >
                            <DataCell name="course">
                                {{ course.name }}
                            </DataCell>
                            <DataCell name="action"> </DataCell>
                        </DataRow>
                    </SimpleTable>

                    <dl
                        class="grid grid-cols-1 gap-x-4 gap-y-8 px-4 pt-4 sm:grid-cols-2"
                    >
                        <BaseDataView
                            class="col-span-1 sm:col-span-2"
                            :label="
                                $trans('academic.division.props.description')
                            "
                        >
                            {{ division.description }}
                        </BaseDataView>
                    </dl>
                </BaseCard>

                <!-- <BaseCard
                    no-padding
                    no-content-padding
                    v-if="perform('division:edit')"
                >
                    <template #title>
                        {{ $trans("academic.config.config") }}
                    </template>

                    <ConfigForm :division="division" />
                </BaseCard> -->
            </DetailLayoutVertical>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "AcademicDivisionShow",
}
</script>

<script setup>
import { reactive, inject, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { perform } from "@core/helpers/action"
// import ConfigForm from "./ConfigForm.vue"

const store = useStore()
const route = useRoute()
const router = useRouter()

const $trans = inject("$trans")

const initialDivision = {}

const initUrl = "academic/division/"

const header = [
    {
        key: "course",
        label: $trans("academic.course.course"),
        visibility: true,
    },
    { key: "action", label: "", visibility: true },
]

const division = reactive({ ...initialDivision })

const setItem = (data) => {
    Object.assign(division, data)
}
</script>
