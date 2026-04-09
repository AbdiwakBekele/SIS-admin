<template>
    <ListItem
        :init-url="initUrl"
        
        @setItems="setItems"
    >
        <template #header>
            <PageHeader
                :title="$trans('gamification.award_category.award_category')"
                :navs="[
                    { label: $trans('gamification.gamification'), path: 'StudentGamification' },
                ]"
            >
                <PageHeaderAction
                    url="gamification/award-category/"
                    name="AwardCategory"
                    :title="$trans('gamification.award_category.award_category')"
                    :actions="userActions"
                    :dropdown-actions="dropdownActions"
                    :additional-dropdown-actions-query="{
                        details: true,
                    }"
                    @toggleFilter="showFilter = !showFilter"
                >
                    
                </PageHeaderAction>
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
                :header="awardCategories.headers"
                :meta="awardCategories.meta"
                module="gamification.awardCategory"
                @refresh="emitter.emit('listItems')"
            >
                <DataRow
                    v-for="awardCategory in awardCategories.data"
                    :key="awardCategory.uuid"
                    @double-click="
                        router.push({
                            name: 'AwardCategoryShow',
                            params: { uuid: awardCategory.uuid },
                        })
                    "
                >

                <!-- Award Category Name -->
                    <DataCell name="name">
                        {{ awardCategory.name }}
                    </DataCell>

                    <!-- Award Category Code -->
                    <DataCell name="code">
                        {{ awardCategory.code }}
                    </DataCell>
                    
                    <DataCell name="createdAt">
                        {{ awardCategory.createdAt.formatted }}
                    </DataCell>
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem
                                icon="fas fa-eye"
                                @click="
                                    router.push({
                                        name: 'AwardCategoryShow',
                                        params: { uuid: awardCategory.uuid },
                                    })
                                "
                                >{{ $trans("general.view") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('division:edit')"
                                icon="fas fa-edit"
                                @click="
                                    router.push({
                                        name: 'AwardCategoryEdit',
                                        params: { uuid: awardCategory.uuid },
                                    })
                                "
                                >{{ $trans("general.edit") }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('division:create')"
                                icon="fas fa-copy"
                                @click="
                                    router.push({
                                        name: 'AwardCategoryDuplicate',
                                        params: { uuid: awardCategory.uuid },
                                    })
                                "
                                >{{
                                    $trans("general.duplicate")
                                }}</FloatingMenuItem
                            >
                            <FloatingMenuItem
                                v-if="perform('division:delete')"
                                icon="fas fa-trash"
                                @click="
                                    emitter.emit('deleteItem', {
                                        uuid: awardCategory.uuid,
                                    })
                                "
                                >{{
                                    $trans("general.delete")
                                }}</FloatingMenuItem
                            >
                        </FloatingMenu>
                    </DataCell>
                </DataRow>

                <template #actionButton>
                    <BaseButton
                        v-if="perform('division:create')"
                        @click="router.push({ name: 'AwardCategoryCreate' })"
                    >
                        {{
                            $trans("global.add", {
                                attribute: $trans("gamification.award_category.award_category"),
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
    name: "AwardCategoryList",
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
if (perform("division:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("division:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "gamification/awardCategory/"
const showFilter = ref(false)
const showReorderModal = ref(false)

const awardCategories = reactive({})

const setItems = (data) => {
    Object.assign(awardCategories, data)
}
</script>
