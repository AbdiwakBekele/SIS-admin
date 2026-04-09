<template>
    <PageHeader
        :title="$trans(route.meta.label)"
        :navs="[
            {
                label: $trans('communication.communication'),
                path: 'Communication',
            },
        ]"
    >
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <FormAction
            :pre-requisites="{ data: ['datePlaceholders'] }"
            @setPreRequisites="setPreRequisites"
            :init-url="initUrl"
            data-fetch="communication"
            :init-form="initForm"
            :form="form"
            action="store"
            stay-on
            cancel-action
            @cancelled="router.back()"
        >
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.announcementNumberPrefix"
                        name="announcementNumberPrefix"
                        :label="
                            $trans(
                                'communication.announcement.config.props.number_prefix'
                            )
                        "
                        v-model:error="formErrors.announcementNumberPrefix"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="number"
                        v-model="form.announcementNumberDigit"
                        name="announcementNumberDigit"
                        :label="
                            $trans(
                                'communication.announcement.config.props.number_digit'
                            )
                        "
                        v-model:error="formErrors.announcementNumberDigit"
                    />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput
                        type="text"
                        v-model="form.announcementNumberSuffix"
                        name="announcementNumberSuffix"
                        :label="
                            $trans(
                                'communication.announcement.config.props.number_suffix'
                            )
                        "
                        v-model:error="formErrors.announcementNumberSuffix"
                    />
                </div>
                <div class="col-span-3">
                    <BaseAlert size="xs" design="info">{{
                        datePlaceholderInfo
                    }}</BaseAlert>
                </div>
            </div>
        </FormAction>
    </ParentTransition>
</template>

<script>
export default {
    name: "CommunicationConfigGeneral",
}
</script>

<script setup>
import { inject, reactive, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { getFormErrors } from "@core/helpers/action"

const route = useRoute()
const router = useRouter()

const $trans = inject("$trans")

const initUrl = "config/"
const formErrors = getFormErrors(initUrl)
const datePlaceholderInfo = computed(() =>
    $trans("global.placeholder_info", {
        attribute: preRequisites.datePlaceholders,
    })
)

const preRequisites = reactive({
    datePlaceholders: "",
})

const initForm = {
    announcementNumberPrefix: "",
    announcementNumberSuffix: "",
    announcementNumberDigit: 0,
    type: "communication",
}

const form = reactive({ ...initForm })

const setPreRequisites = (data) => {
    Object.assign(preRequisites, {
        datePlaceholders: data.datePlaceholders
            .map((item) => item.value)
            .join(", "),
    })
}
</script>
