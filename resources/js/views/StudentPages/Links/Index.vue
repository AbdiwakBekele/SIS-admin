<template>
    <ListItem :init-url="initUrl" @setItems="setItems">
        <template #header>
            <PageHeader :title="$trans('link.link')" :navs="[]">
                <PageHeaderAction url="link/links/" name="Links"
                    :title="$trans('link.links')" :actions="userActions" :dropdown-actions="dropdownActions" />
            </PageHeader>
        </template>

        <ParentTransition appear :visibility="true">
            <DataTable :header="links.headers" :meta="links.meta" module="link"
                @refresh="emitter.emit('listItems')">
                <DataRow v-for="link in links.data" :key="link.uuid" @double-click="
                    router.push({
                        name: 'StudentLinksShow',
                        params: { uuid: link.uuid },
                    })
                    ">
                    <DataCell name="title">
                        {{ link.titleExcerpt || '—' }}
                    </DataCell>
                    <DataCell name="resourceType">
                        {{ link.resourceType?.resourceType || '—' }}
                    </DataCell>
                    <DataCell name="link">
                        <a v-if="link.link" :href="link.link" target="_blank" class="text-blue-600 hover:underline">
                            {{ link.link }}
                        </a>
                        <span v-else>—</span>
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
                                    name: 'StudentLinksShow',
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
    name: "StudentLinksList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"
import { useStore } from "vuex"
import { showError } from "@core/helpers/alert"

const router = useRouter()
const emitter = inject("emitter")
const store = useStore()

let userActions = []
let dropdownActions = []

const initUrl = "links/"
const links = reactive({})

const setItems = (data) => {
    Object.assign(links, data)
}

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
