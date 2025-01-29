<template>
    <FilterForm :init-form="initForm" :form="form" @hide="emit('hide')">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.codeNumber"
                    name="codeNumber"
                    :label="$trans('subscription.props.code_number')"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseInput
                    type="text"
                    v-model="form.domain"
                    name="domain"
                    :label="$trans('tenant.props.domain')"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <DatePicker
                    v-model:start="form.startDate"
                    v-model:end="form.endDate"
                    name="dateBetween"
                    as="range"
                    :label="$trans('general.date_between')"
                />
            </div>
            <div class="col-span-3 sm:col-span-1">
                <BaseSwitch
                    vertical
                    v-model="form.showFailed"
                    name="showFailed"
                    :label="$trans('subscription.props.show_failed')"
                />
            </div>
        </div>
    </FilterForm>
</template>

<script setup>
import { reactive } from "vue"

const emit = defineEmits(["hide"])

const initForm = {
    codeNumber: "",
    domain: "",
    startDate: "",
    endDate: "",
    showFailed: false,
}

const form = reactive({ ...initForm })
</script>
