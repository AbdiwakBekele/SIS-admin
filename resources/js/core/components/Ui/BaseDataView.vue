<template>
    <div :class="class">
        <BaseDataList :label="label" :revert="revert" :font-size="fontSize">
            <slot name="label">{{ label }}</slot>
        </BaseDataList>
        <BaseDataDef :revert="revert" :font-size="fontSize" v-if="html">
            <div v-html="$slots.default?.()?.[0]?.children"></div>
        </BaseDataDef>
        <BaseDataDef
            :revert="revert"
            :font-size="fontSize"
            v-else-if="paragraph"
        >
            <span
                v-html="nl2br($slots.default?.()?.[0]?.children || '')"
            ></span>
        </BaseDataDef>
        <BaseDataDef :revert="revert" :font-size="fontSize" v-else-if="dynamic">
            <slot></slot>
        </BaseDataDef>
        <BaseDataDef :revert="revert" :font-size="fontSize" v-else>
            <slot></slot>
        </BaseDataDef>
    </div>
</template>

<script>
export default {
    name: "BaseDataView",
}
</script>

<script setup>
import { useSlots } from "vue"

const $slots = useSlots()

const props = defineProps({
    label: {
        type: String,
        default: "",
    },
    class: {
        type: String,
        default: "sm:col-span-1",
    },
    revert: {
        type: Boolean,
        default: false,
    },
    fontSize: {
        type: String,
        validator(value) {
            return ["xs", "sm"].includes(value)
        },
        default: "sm",
    },
    html: {
        type: Boolean,
        default: false,
    },
    paragraph: {
        type: Boolean,
        default: false,
    },
    dynamic: {
        type: Boolean,
        default: false,
    },
})

const nl2br = (str) => {
    if (typeof str !== "string") return ""
    return str.replace(/(\r\n|\n|\r)/gm, "<br>")
}
</script>
