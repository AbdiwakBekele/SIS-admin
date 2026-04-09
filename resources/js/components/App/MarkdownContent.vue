<template>
    <div class="md-content" v-html="content"></div>
</template>

<script>
export default {
    name: "MarkdownContent",
}
</script>

<script setup>
import { getCurrentInstance, onMounted } from "vue"
import hljs from "@js/highlight"

const props = defineProps({
    content: {
        type: String,
        default: "",
    },
})

onMounted(() => {
    const parentElement = getCurrentInstance().parent.subTree.el
    parentElement.addEventListener("DOMContentLoaded", hljs.highlightAll())
})
</script>
