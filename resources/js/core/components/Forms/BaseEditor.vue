<template>
    <div>
        <BaseLabel :for="state.uniqueId">
            {{ label }}
            <span v-if="labelHint" class="ml-2" v-tooltip="labelHint"
                ><i class="fas fa-question-circle"></i
            ></span>
        </BaseLabel>
        <div class="mt-1">
            <QuillEditor
                ref="quillEditor"
                theme="snow"
                :toolbar="toolbar"
                v-model:content="state.input"
                content-type="html"
                :modules="modules"
                @update:content="updateInput"
                :style="{
                    height:
                        typeof props.height === 'number'
                            ? `${props.height}px`
                            : props.height,
                }"
            />
        </div>
        <FormError :error="error" />
    </div>
</template>

<script>
export default {
    name: "BaseEditor",
    inheritAttrs: false,
}
</script>

<script setup>
import { onMounted, reactive, ref, watch } from "vue"
import { useStore } from "vuex"
import { QuillEditor, Delta } from "@vueup/vue-quill"
import ImageUploader from "quill-image-uploader"

const store = useStore()

const emit = defineEmits(["update:modelValue", "update:error"])

const props = defineProps({
    label: {
        type: String,
        default: "",
    },
    labelHint: {
        type: String,
        default: "",
    },
    placeholder: {
        type: String,
        default: "",
    },
    modelValue: {
        type: [String, Number],
        default: "",
    },
    toolbar: {
        type: [String, Array],
        default() {
            // https://github.com/vueup/vue-quill/blob/master/packages/vue-quill/src/components/options.ts
            return [
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }, { align: [] }],
                ["blockquote", "code-block", "link", "image"],
                [{ color: [] }, "clean"],
            ]
        },
    },
    error: {
        type: String,
        default: "",
    },
    edit: {
        type: Boolean,
        default: false,
    },
    height: {
        type: [String, Number],
        default: "200px",
    },
})

const modules = {
    name: "imageUploader",
    module: ImageUploader,
    options: {
        upload: async (file) => {
            let formData = new FormData()
            formData.append("image", file)

            return await store
                .dispatch("image/upload", {
                    path: "images/upload",
                    data: formData,
                })
                .then((response) => {
                    return response.url
                })
                .catch((e) => {
                    //
                })
        },
    },
    clipboard: {
        matchVisual: false,
        matchers: [
            ["img", () => new Delta()],
            ["IMG", () => new Delta()],
        ],
    },
}

const quillEditor = ref()

const state = reactive({
    uniqueId: "",
    input: props.modelValue,
    set: false,
})

const updateInput = ($event) => {
    // cursor issue fix
    // emit("update:modelValue", quillEditor.value.getHTML())
    emit("update:modelValue", state.input)
    emit("update:error", "")
}

watch(
    () => props.modelValue,
    (value) => {
        // cursor issue fix
        // if (props.edit && state.set === false && value) {
        //     state.input = value
        //     state.set = true
        //     quillEditor.value.setHTML(value)
        // } else if (!props.edit && state.set === false && !value) {
        //     state.input = value
        //     quillEditor.value.setHTML(value)
        // }
        if (value !== state.input) {
            if (props.edit && state.set === false && value) {
                state.input = value
                state.set = true
            } else if (!props.edit && state.set === false && !value) {
                state.input = value
            }
        }
    }
)

onMounted(async () => {
    state.uniqueId = props.id || Math.random().toString(16).slice(2)

    if (quillEditor.value) {
        const quill = quillEditor.value.getQuill()

        // Add paste event listener
        quill.container.addEventListener(
            "paste",
            (e) => {
                e.preventDefault()
                e.stopPropagation()

                // Get text from clipboard
                let text =
                    e.clipboardData.getData("text/html") ||
                    e.clipboardData.getData("text")

                // If clipboard is empty, try to get the current selection
                if (!text.trim()) {
                    const selection = window.getSelection()
                    text = selection.toString()
                }

                // Convert to plain text if needed
                const cleanText = text
                    .replace(/<img[^>]*>/g, "")
                    .replace(/src="[^"]*"/g, "")
                    .replace(/data-src="[^"]*"/g, "")

                // Only clear and paste if we have content
                if (cleanText.trim()) {
                    quill.deleteText(0, quill.getLength())
                    // Use insertText or clipboard.paste for safe insertion
                    const delta = quill.clipboard.convert(cleanText)
                    quill.setContents(delta)
                }

                return false
            },
            true
        )
    }
})
</script>
