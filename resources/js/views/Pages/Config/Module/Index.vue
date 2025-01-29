<template>
    <ConfigPage>
        <template #action> </template>
        <FormAction
            no-card
            :init-url="initUrl"
            :init-form="initForm"
            :form="form"
            action="storeModule"
            stay-on
            redirect="Config"
        >
            <CardHeader
                first
                :title="$trans('config.module.module_config')"
                :description="$trans('config.module.module_info')"
            ></CardHeader>

            <BaseFieldset
                :class="{
                    'mt-4': index > 0,
                }"
                v-for="(item, index) in form.modules"
                :key="item.name"
            >
                <template #legend>
                    <div class="flex items-center">
                        {{ item.label }}
                        <span class="ml-2">
                            <BaseSwitch
                                reverse
                                v-model="item.visibility"
                                :name="item.name"
                                v-model:error="formErrors[item.name]"
                            />
                        </span>
                    </div>
                </template>

                <div
                    class="grid grid-cols-3 gap-4 pl-4"
                    v-if="item.children.length > 0 && item.visibility"
                >
                    <div
                        class="col-span-3 sm:col-span-1"
                        v-for="subItem in item.children"
                        :key="subItem.name"
                    >
                        <BaseSwitch
                            reverse
                            v-model="subItem.visibility"
                            :name="subItem.name"
                            :label="subItem.label"
                            v-model:error="formErrors[subItem.name]"
                        />
                    </div>
                </div>
            </BaseFieldset>

            <!-- <div class="space-y-4">
                <div v-for="item in form.modules" :key="item.name">
                    <BaseSwitch
                        reverse
                        v-model="item.visibility"
                        :name="item.name"
                        :label="item.label"
                        v-model:error="formErrors[item.name]"
                    />

                    <div
                        class="mt-4 pl-4 space-y-4"
                        v-if="item.children.length > 0"
                    >
                        <div
                            v-for="subItem in item.children"
                            :key="subItem.name"
                        >
                            <BaseSwitch
                                reverse
                                v-model="subItem.visibility"
                                :name="subItem.name"
                                :label="subItem.label"
                                v-model:error="formErrors[subItem.name]"
                            />
                        </div>
                    </div>
                </div>
            </div> -->
        </FormAction>
    </ConfigPage>
</template>

<script>
export default {
    name: "ConfigModule",
}
</script>

<script setup>
import { ref, reactive, onMounted } from "vue"
import { useStore } from "vuex"
import { cloneDeep } from "@core/utils"
import { getFormErrors } from "@core/helpers/action"

const store = useStore()

const initUrl = "config/"
const formErrors = getFormErrors(initUrl)

const isLoading = ref(false)

const initForm = {
    modules: [],
}

const form = reactive({ ...initForm })

const preRequisite = async () => {
    isLoading.value = true
    await store
        .dispatch(initUrl + "modulePreRequisite")
        .then((response) => {
            isLoading.value = false
            Object.assign(initForm, response)

            Object.assign(form, cloneDeep(initForm))
        })
        .catch((e) => {
            isLoading.value = false
        })
}

onMounted(async () => {
    await preRequisite()
})
</script>
