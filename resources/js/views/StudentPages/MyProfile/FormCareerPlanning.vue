<template>
    <PageHeader :title="`Career Planning`" :navs="[
        { label: `My Profile`, path: 'MyProfile' },

    ]">
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <FormAction :has-setup-wizard="true" :pre-requisites="false" @setPreRequisites="setPreRequisites"
            :init-url="initUrl" :init-form="initForm" :form="form" :set-form="setForm" redirect="Quest">
            <div class="grid grid-cols-3 gap-6">

                <!-- Career Pathway-->
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect name="rewardType" :label="`Career Pathway`" :options="[
                        { value: 'health', label: `Health Science/Medical` },
                        { value: 'computer', label: `Computer Science/Coding` },
                        { value: 'health', label: `Engineering` },
                        { value: 'health', label: `Business/Economics` },
                        { value: 'health', label: `Education` },
                        { value: 'health', label: `STEM` },
                        { value: 'engineering', label: `Sociology/Psychology` },
                        { value: 'engineering', label: `Languages/International Affairs` },
                        { value: 'engineering', label: `Fine/Performing Arts` },
                        { value: 'engineering', label: `Trades` },
                        { value: 'engineering', label: `General Studies` },
                        { value: 'engineering', label: `Other` },

                    ]" v-model:error="formErrors.rewardType" />
                </div>

                <!-- Quest Reward Type -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseSelect v-model="form.rewardType" name="rewardType" :label="`Postsecondary Plan`" :options="[
                        { value: 'university', label: `University/College/Career School` },
                        { value: 'community', label: `Community/Tech College` },
                        { value: 'workforce', label: `Workforce` },
                        { value: 'military', label: `Military` },
                        { value: 'other', label: `Other` },

                    ]" v-model:error="formErrors.rewardType" />
                </div>

                <!-- University Choices -->
                <div v-if="form.rewardType == 'university'" class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" :label="`University Choice 1`" v-model:error="formErrors.rewardValue" />
                </div>

                <div v-if="form.rewardType == 'university'" class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" :label="`University Choice 2`" v-model:error="formErrors.rewardValue" />
                </div>

                <div v-if="form.rewardType == 'university'" class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" :label="`University Choice 3`" v-model:error="formErrors.rewardValue" />
                </div>

            </div>



        </FormAction>
    </ParentTransition>
</template>

<script>
export default {
    name: "QuestForm",
}
</script>

<script setup>
import { onMounted, reactive } from "vue"
import { useRoute } from "vue-router"
import { cloneDeep } from "@core/utils"
import { v4 as uuidv4 } from "uuid"
import { getFormErrors } from "@core/helpers/action"
import { confirmAction } from "@core/helpers/alert"


const route = useRoute()

const initForm = {
    name: "",
    code: "",
    rewardType: "",
    rewardValue: "",
    description: "",

}


const initUrl = "gamification/quest/"
const formErrors = getFormErrors(initUrl)
const preRequisites = reactive({
    // programs: [],
})

const form = reactive({ ...initForm })
const fetchData = reactive({
    isLoaded: route.params.uuid ? false : true,
})

// const setPreRequisites = (data) => {
//     Object.assign(preRequisites, data)
// }

const setForm = (data) => {
    Object.assign(initForm, {
        ...data,
    })
    Object.assign(form, cloneDeep(initForm))
}

</script>
