<template>
    <PageHeader :title="$trans('school_info.school_info')" :navs="[]" />

    <ParentTransition appear :visibility="true">
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="text-center">
                <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
                <p class="text-gray-600">{{ $trans('general.loading') }}</p>
            </div>
        </div>

        <div v-else class="space-y-6">
            <!-- School Logo and Name Card -->
            <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-lg border border-blue-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-6">
                        <div v-if="getSchoolLogo" class="flex-shrink-0">
                            <img :src="getSchoolLogo" :alt="schoolInfo.name" class="w-24 h-24 object-contain rounded-lg border border-blue-200 bg-white p-2" />
                        </div>
                        <div v-else class="flex-shrink-0 w-24 h-24 rounded-lg border border-blue-200 bg-white flex items-center justify-center">
                            <i class="fas fa-school text-4xl text-blue-400"></i>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-blue-900 mb-2">{{ schoolInfo.name || '-' }}</h1>
                            <p class="text-gray-600">{{ schoolInfo.description || '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Cards -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Address Card -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-blue-900">{{ $trans('school_info.address') }}</h2>
                    </div>
                    <div class="text-gray-700 space-y-1">
                        <p>{{ schoolInfo.address_line1 || '-' }}</p>
                        <p>{{ schoolInfo.address_line2 || '-' }}</p>
                        <p>{{ [schoolInfo.city, schoolInfo.state, schoolInfo.zipcode].filter(Boolean).join(', ') || '-' }}</p>
                        <p>{{ schoolInfo.country || '-' }}</p>
                    </div>
                </div>

                <!-- Contact Details Card -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-phone text-blue-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-blue-900">{{ $trans('school_info.contact_info') }}</h2>
                    </div>
                    <div class="space-y-3 text-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope text-blue-500 w-5"></i>
                            <a v-if="schoolInfo.email" :href="`mailto:${schoolInfo.email}`" class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ schoolInfo.email }}
                            </a>
                            <span v-else class="text-gray-500">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-phone text-blue-500 w-5"></i>
                            <a v-if="schoolInfo.phone" :href="`tel:${schoolInfo.phone}`" class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ schoolInfo.phone }}
                            </a>
                            <span v-else class="text-gray-500">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-fax text-blue-500 w-5"></i>
                            <span>{{ schoolInfo.fax || '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-globe text-blue-500 w-5"></i>
                            <a v-if="schoolInfo.website" :href="schoolInfo.website" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ schoolInfo.website }}
                            </a>
                            <span v-else class="text-gray-500">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Cards -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Collegeboard Code Card -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-blue-900">{{ $trans('school_info.collegeboard_code') }}</h2>
                    </div>
                    <p class="text-gray-700 text-lg font-medium">{{ schoolInfo.collegeboard_code || '-' }}</p>
                </div>

                <!-- Accreditation Card -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-certificate text-blue-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-blue-900">{{ $trans('school_info.accreditation') }}</h2>
                    </div>
                    <p class="text-gray-700">{{ schoolInfo.accreditation || '-' }}</p>
                </div>
            </div>

            <!-- Social Media Links Card -->
            <div class="bg-white rounded-lg p-6 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-share-alt text-blue-600 text-xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-blue-900">{{ $trans('school_info.social_media') }}</h2>
                </div>
                <div v-if="schoolInfo.social_media && schoolInfo.social_media.length > 0" class="flex flex-wrap gap-4">
                    <a
                        v-for="social in schoolInfo.social_media"
                        :key="social.platform"
                        :href="social.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 hover:text-blue-900 transition-colors"
                    >
                        <i :class="[social.icon, 'text-lg']"></i>
                        <span class="font-medium">{{ social.name }}</span>
                    </a>
                </div>
                <p v-else class="text-gray-500">-</p>
            </div>
        </div>
    </ParentTransition>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import * as Api from '@core/apis'
import { getConfig } from '@core/helpers/action'

const store = useStore()
const loading = ref(true)
const schoolInfo = ref({
    name: '',
    description: '',
    logo: null,
    address: '',
    address_line1: '',
    address_line2: '',
    city: '',
    state: '',
    zipcode: '',
    country: '',
    email: '',
    phone: '',
    fax: '',
    website: '',
    collegeboard_code: '',
    accreditation: '',
    social_media: [],
})

// Use the same icon as AppHeader (assets.logoLight)
const getSchoolLogo = computed(() => {
    const logoRef = getConfig('assets.logoLight')

    if (logoRef && typeof logoRef.value === 'string' && logoRef.value.trim() !== '') {
        return logoRef.value
    }

    return schoolInfo.value.logo || null
})

async function fetchSchoolInfo() {
    loading.value = true
    try {
        const response = await Api.custom({
            url: '/app/config/school-info',
            method: 'GET',
        })
        Object.assign(schoolInfo.value, response)
    } catch (error) {
        console.error('Failed to fetch school info:', error)
        store.dispatch('toast/error', {
            message: 'Failed to load school information',
        })
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchSchoolInfo()
})
</script>
