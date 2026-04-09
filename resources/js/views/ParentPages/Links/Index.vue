<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('link.link')" :navs="[]">
                <PageHeaderAction url="link/links/" name="Links"
                    :title="$trans('link.links')" :actions="userActions" :dropdown-actions="dropdownActions"
                    />
            </PageHeader>
        </template>

        <!-- <template #filter>
                <FilterForm v-if="showFilter" @refresh="emitter.emit('listItems')" @hide="showFilter = false"></FilterForm>
        </template> -->

        <ParentTransition appear :visibility="true">
            <DataTable :header="filteredHeaders" :meta="links.meta"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="link in links.data" :key="link.uuid" @double-click="
                    router.push({
                        name: 'ParentLinksShow',
                        params: { uuid: link.uuid },
                    })
                    ">
                    <DataCell name="title">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span>{{ link.titleExcerpt }}</span>
                            <template v-for="child in (getMatchingChildren(link) || [])" :key="child.uuid || child.id || Math.random()">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-blue-400 text-white rounded-full"
                                >
                                    {{ child.name || child.firstName || 'Child' }}
                                </span>
                            </template>
                        </div>
                    </DataCell>
                    <DataCell name="resourceType">
                        {{ link.resourceType?.resourceType || "-" }}
                    </DataCell>
                    <DataCell name="link">
                        <a :href="link.link" target="_blank" class="text-blue-600 hover:underline">
                            {{ link.link }}
                        </a>
                    </DataCell>
                    <!-- <DataCell name="specialPopulation">
                        <div v-if="link.specialPopulation" class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                {{ link.specialPopulation.name }}<template v-if="link.specialPopulationItems && link.specialPopulationItems.length > 0">: {{ link.specialPopulationItems.map(item => item.name).join(', ') }}</template>
                            </span>
                        </div>
                        <div v-else class="text-sm text-gray-400">—</div>
                    </DataCell> -->
                    <!-- <DataCell name="createdAt">
                        {{ link.createdAt.formatted }}
                    </DataCell> -->
                    <DataCell name="action">
                        <FloatingMenu>
                            <FloatingMenuItem icon="fas fa-eye" @click="
                                router.push({
                                    name: 'ParentLinksShow',
                                    params: { uuid: link.uuid },
                                })
                                ">{{ $trans("general.view") }}</FloatingMenuItem>
                            <FloatingMenuItem 
                                v-if="link.media && link.media.length > 0" 
                                icon="fas fa-download" 
                                @click="downloadMedia(link)"
                                >{{ $trans("general.download") }}</FloatingMenuItem>
                        </FloatingMenu>
                    </DataCell>
                </DataRow>
            </DataTable>
        </ParentTransition>
    </ListItem>
</template>

<script>
export default {
    name: "ParentLinksList",
}
</script>

<script setup>
import { ref, reactive, inject, computed } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
// import FilterForm from "./Filter.vue"
import { useStore } from "vuex"
import { showError } from "@core/helpers/alert"

const router = useRouter()
const emitter = inject("emitter")
const store = useStore()

let userActions = [] // ["filter"] - removed since Filter.vue is missing
let dropdownActions = []

const initUrl = "links/"
const showFilter = ref(false)
const links = reactive({})

const getMatchingChildren = (link) => {
    if (!link) return [];
    const children = link.matchingChildren || link.matching_children || [];
    if (!Array.isArray(children)) return [];
    return children.filter(child => child && (child.uuid || child.id));
};

const setItems = (data) => {
    if (data?.data) {
        data.data.forEach((link) => {
            const children = link.matching_children || link.matchingChildren;
            if (children && Array.isArray(children) && children.length > 0) {
                link.matchingChildren = [...children];
            }
        });
    }
    
    Object.assign(links, data)
}

const filteredHeaders = computed(() => {
    if (!links.headers) return []
    return links.headers.map(header => {
        if (header.key === 'audience') {
            return { ...header, visibility: false }
        }
        return header
    })
})

const downloadMedia = async (link) => {
    if (!link.media || link.media.length === 0) {
        showError("No file available for download")
        return
    }
    
    try {
        for (const media of link.media) {
            const downloadUrl = `/api/v1/app/links/${link.uuid}/media/${media.uuid}?action=download`
            
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
            }
            
            const authToken = localStorage.getItem('auth_token')
            if (authToken) {
                headers['Authorization'] = `Bearer ${authToken}`
            }
            
            const response = await fetch(downloadUrl, {
                method: 'GET',
                credentials: 'include',
                headers: headers,
            })

            if (!response.ok) {
                throw new Error(`Download failed: ${response.status} ${response.statusText}`)
            }

            const blob = await response.blob()
            const url = window.URL.createObjectURL(blob)
            const linkElement = document.createElement('a')
            linkElement.href = url
            
            const contentDisposition = response.headers.get('Content-Disposition')
            let fileName = 'download'
            if (contentDisposition) {
                const fileNameMatch = contentDisposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/)
                if (fileNameMatch && fileNameMatch[1]) {
                    fileName = fileNameMatch[1].replace(/['"]/g, '')
                }
            } else {
                fileName = media.fileName || media.name || media.file?.name || 'download'
            }
            
            linkElement.setAttribute('download', fileName)
            document.body.appendChild(linkElement)
            linkElement.click()
            linkElement.remove()
            window.URL.revokeObjectURL(url)
        }
    } catch (error) {
        console.error('Download failed:', error)
        showError("Failed to download file")
    }
}
</script>
