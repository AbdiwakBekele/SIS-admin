<template>
    <div class="space-y-4 text-gray-800 dark:text-gray-400">
        <div v-for="(mediaSection, section) in mediaSections">
            <h4 class="font-semibold" v-if="mediaSectionCount > 1">
                {{ section }}
            </h4>
            <div
                class="mt-2 grid gap-2"
                :class="{
                    'grid-cols-4': grid === 4,
                    'grid-cols-3': grid === 3,
                    'grid-cols-2': grid === 2,
                    'grid-cols-1': grid === 1,
                }"
            >
                <div
                    v-for="item in mediaSection"
                    @click="action(item)"
                    class="sm:col-span-1"
                    :class="{
                        'col-span-4': grid === 4,
                        'col-span-3': grid === 3,
                        'col-span-2': grid === 2,
                        'col-span-1': grid === 1,
                    }"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex cursor-pointer">
                            <span class="mr-4"
                                ><i class="fas text-4xl" :class="item.icon"></i>
                            </span>
                            <div class="flex flex-col">
                                <span>{{ item.name }}</span>
                                <span>{{ item.size }}</span>
                            </div>
                        </div>
                        <div>
                            <slot></slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <BaseAlert
        v-if="media.length === 0 && showNoMediaAlert"
        design="info"
        size="xs"
        >{{ $trans("general.errors.attachment_not_found") }}</BaseAlert
    >

    <BaseModal :show="state.showPreview" @close="state.showPreview = false">
        <template #title
            >{{ state.selectedItem.name }}
            <BaseButton size="xs" @click="download(state.selectedItem.uuid)"
                >{{ $trans("general.download") }}
                <i class="ml-2 fas fa-download"></i>
            </BaseButton>
        </template>
        <div
            class="flex items-center justify-center"
            v-if="state.selectedItem.mime == 'application/pdf'"
        >
            <div>
                <div
                    class="flex justify-center space-x-2"
                    v-if="!state.isLoading"
                >
                    <button @click="page = page > 1 ? page - 1 : page">
                        {{ $trans("pagination.previous") }}
                    </button>
                    <span>{{ page }} / {{ pages }}</span>
                    <button @click="page = page < pages ? page + 1 : page">
                        {{ $trans("pagination.next") }}
                    </button>
                </div>
                <div class="scroller-thin-x scroller-hidden">
                    <VuePDF
                        :pdf="pdf"
                        :page="page"
                        @loaded="state.isLoading = false"
                        :style="{ overflow: 'visible' }"
                    />
                </div>
                <div
                    v-if="state.isLoading"
                    class="absolute inset-0 flex items-center justify-center"
                >
                    {{ $trans("general.loading") }}
                </div>
            </div>
        </div>
        <div
            class="flex items-center justify-center"
            v-if="state.selectedItem.isImage"
        >
            <img :src="url" alt="" />
        </div>
    </BaseModal>
</template>

<script>
export default {
    name: "ListMedia",
}
</script>

<script setup>
import { ref, reactive, computed } from "vue"
import { useStore } from "vuex"
import { VuePDF, usePDF } from "@tato30/vue-pdf"

const store = useStore()

const props = defineProps({
    media: {
        type: Array,
        default: [],
    },
    section: {
        type: String,
        default: "",
    },
    url: {
        type: String,
        default: "/",
    },
    grid: {
        type: Number,
        default: 4,
    },
    showNoMediaAlert: {
        type: Boolean,
        default: true,
    },
})

const appUrl = computed(() => store.getters["config/config"]("system.url"))

const mediaSectionCount = computed(() => {
    return Object.keys(mediaSections.value).length
})

const mediaSections = computed(() => {
    let allMedias = props.media

    if (props.section) {
        allMedias = allMedias.filter((item) => item.section == props.section)
    }

    return allMedias.reduce((data, item) => {
        if (item.sectionName) {
            if (!data[item.sectionName]) {
                data[item.sectionName] = []
            }
            data[item.sectionName].push(item)
        } else {
            if (!data["default"]) {
                data["default"] = []
            }
            data["default"].push(item)
        }
        return data
    }, {})
})

const page = ref(1)
const url = ref("")
const state = reactive({
    selectedItem: {},
    showPreview: false,
    isLoading: false,
})
const { pdf, pages } = usePDF(url)

const action = (item) => {
    if (item.isPreviewable) {
        state.selectedItem = item
        state.isLoading = true
        if (item.mime == "application/pdf") {
            page.value = 1
        }
        url.value = appUrl.value + props.url + "media/" + item.uuid
        state.showPreview = true
    } else {
        state.selectedItem = {}
        download(item.uuid)
    }
}

const download = (uuid) => {
    window.open(appUrl.value + props.url + "media/" + uuid + "?action=download")
}
</script>
