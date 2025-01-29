<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            { label: $trans('custom_field.custom_field'), path: 'CustomField' },
        ]"
    >
        <PageHeaderAction
            name="CustomField"
            :title="$trans('custom_field.custom_field')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'CustomField' })"
        >
            <BaseCard v-if="customField.uuid">
                <template #title>
                    {{ customField.form.label }}
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('custom_field.props.label')">
                        {{ customField.label }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('custom_field.props.type')">
                        {{ customField.type.label }}
                        <BaseBadge
                            v-if="customField.isRequired"
                            design="primary"
                        >
                            {{ $trans("custom_field.props.is_required") }}
                        </BaseBadge>
                    </BaseDataView>

                    <BaseDataView
                        :label="$trans('custom_field.props.position')"
                    >
                        {{ customField.position }}
                    </BaseDataView>

                    <template
                        v-if="
                            customField.type.value == 'text_input' ||
                            customField.type.value == 'multi_line_text_input'
                        "
                    >
                        <BaseDataView
                            :label="$trans('custom_field.props.min_length')"
                        >
                            {{ customField.minLength }}
                        </BaseDataView>

                        <BaseDataView
                            :label="$trans('custom_field.props.max_length')"
                        >
                            {{ customField.maxLength }}
                        </BaseDataView>
                    </template>

                    <template
                        v-if="
                            customField.type.value == 'number_input' ||
                            customField.type.value == 'currency_input'
                        "
                    >
                        <BaseDataView
                            :label="$trans('custom_field.props.min_value')"
                        >
                            {{ customField.minValue }}
                        </BaseDataView>

                        <BaseDataView
                            :label="$trans('custom_field.props.max_value')"
                        >
                            {{ customField.maxValue }}
                        </BaseDataView>
                    </template>

                    <template
                        v-if="
                            customField.type.value == 'select_input' ||
                            customField.type.value == 'multi_select_input' ||
                            customField.type.value == 'radio_input' ||
                            customField.type.value == 'checkbox_input'
                        "
                    >
                        <BaseDataView
                            :label="$trans('custom_field.props.options')"
                        >
                            {{ customField.options }}
                        </BaseDataView>
                    </template>

                    <BaseDataView :label="$trans('general.created_at')">
                        {{ customField.createdAt.formatted }}
                    </BaseDataView>

                    <BaseDataView :label="$trans('general.updated_at')">
                        {{ customField.updatedAt.formatted }}
                    </BaseDataView>
                </dl>
                <template #footer>
                    <ShowButton>
                        <BaseButton
                            design="primary"
                            @click="
                                router.push({
                                    name: 'CustomFieldEdit',
                                    params: { uuid: customField.uuid },
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
    name: "CustomFieldShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialCustomField = {}

const initUrl = "customField/"

const customField = reactive({ ...initialCustomField })

const setItem = (data) => {
    Object.assign(customField, data)
}
</script>
