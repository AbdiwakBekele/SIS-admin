<template>
  <PageHeader :title="`Your Profile`" :navs="[]">
    <PageHeaderAction name="MyParentProfile" :title="$trans('admission_inquiry.my_profile')">
      <!-- Edit functinality disabled for now -->
      <!-- <BaseButton design="white"
        v-tooltip="$trans('general.edit')"
        @click="router.push({ name: 'MyParentProfileEdit' })">
        <i class="fa-solid fa-pen-to-square"></i>
      </BaseButton> -->
    </PageHeaderAction>
  </PageHeader>

  <ParentTransition appear :visibility="true">
    <div class="grid grid-cols-11 gap-2 p-3 shadow-md">
      <!-- Left Section -->
      <div class="col-span-3">
        <div class="bg-white rounded-lg border-r pr-6 py-5 text-center">
          <div class="grid grid-cols-2 gap-1">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg mx-auto">
              <i class="fas fa-user text-white text-2xl"></i>
            </div>
            <div class="text-left">
              <p class="mt-2 text-base font-bold">{{ guardian.firstName }} {{ guardian.middleName }}
                {{ guardian.lastName }}
              </p>
            </div>
          </div>

          <p class="text-sm text-left mx-4 mt-4"><strong>BASIC INFORMATION</strong></p>
          <hr class="mx-4">
          <div class="grid grid-cols-2 pl-4" style="text-align: left;">
            <div>
              <p class="text-sm my-2 mx-1"><strong>Gender</strong></p>
              <p class="text-sm my-2 mx-1"><strong>Date of Birth</strong></p>
              <p class="text-sm my-2 mx-1"><strong>Added On</strong></p>
            </div>
            <div>
              <p class="text-sm my-2 mx-1">{{ guardian.gender?.toUpperCase() || '-' }}</p>
              <p class="text-sm my-2 mx-1">{{ formatDate(guardian.birthDate?.value) || '-' }}</p>
              <p class="text-sm my-2 mx-1">{{ guardian.createdAt?.formatted || '-' }}</p>
            </div>
          </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
          <p class="text-sm text-left mx-4"><strong>CONTACT INFO</strong></p>
          <hr class="mx-4">
          <!-- Phone -->
          <div class="grid grid-cols-6 text-left">
            <span class="col-span-1 mt-3 ml-4">
              <i class="fa-solid fa-phone"></i>
            </span>
            <div class="col-span-5 mt-2">
              <p class="text-sm"><strong>Phone Number</strong></p>
              <span class="text-gray-600 text-sm">{{ guardian.phoneNumber || '-' }}</span>
            </div>
          </div>
          <!-- Email -->
          <div class="grid grid-cols-6 text-left">
            <span class="col-span-1 mt-3 ml-4">
              <i class="fa-solid fa-envelope"></i>
            </span>
            <div class="col-span-5 mt-2">
              <p class="text-sm"><strong>Email</strong></p>
              <span class="text-gray-600 text-sm">{{ guardian.email || '-' }}</span>
            </div>
          </div>
          <!-- Current Address -->
          <div class="grid grid-cols-6 text-left">
            <span class="col-span-1 mt-3 ml-4">
              <i class="fa-solid fa-location-dot"></i>
            </span>
            <div class="col-span-5 mt-2">
              <p class="text-sm"><strong>Current Address</strong></p>
              <span class="text-gray-600 text-sm">
                {{
                  guardian.currentAddress?.present
                    ? `${guardian.currentAddress.present.addressLine1}${guardian.currentAddress.present.addressLine2
                      ? ', ' + guardian.currentAddress.present.addressLine2 : ''},
                ${guardian.currentAddress.present.city}, ${guardian.currentAddress.present.state}
                ${guardian.currentAddress.present.zipcode}, ${guardian.currentAddress.present.country}`
                    : '-'
                }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Section with Tabs -->
      <div class="col-span-8 rounded-lg">
        <!-- Tab Menus -->
        <div class="border-b-2 border-gray-200">
          <div class="w-full flex gap-1">
            <div v-for="(tab, index) in tabs" :key="`r1-${index}`" @click="activeTab = tab" :class="[
              'flex-1 min-w-max whitespace-nowrap cursor-pointer px-3 pt-2.5 pb-1.5 text-sm text-center transition-all duration-150 flex items-center justify-center gap-2',
              activeTab === tab
                ? 'bg-blue-200 shadow-inner border border-blue-400 border-b-0 rounded-t-lg text-blue-900 font-medium'
                : 'bg-white border border-gray-300 border-b-0 rounded-t-lg -mb-px z-20 shadow-lg relative -top-1 text-gray-700 hover:text-blue-600 hover:bg-gray-50'
            ]">
              <i :class="[
                getTabIcon(tab),
                activeTab === tab ? 'text-blue-700' : 'text-gray-500',
                'text-sm'
              ]"></i>
              <span>{{ tab }}</span>
            </div>
          </div>
        </div>

        <!-- Tab Content -->
        <div>
          <!-- Parent/Guardian Detail -->
          <div v-if="activeTab === 'Parent/Guardian Details'">
            <!-- Personal Info -->
            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
              <p class="text-sm text-left mx-4"><strong>Personal Info</strong></p>
              <hr class="mx-4">
              <div class="grid grid-cols-12 text-left">
                <!-- Full name -->
                <span class="col-span-1 mt-3 ml-4">
                  <i class="fa-solid fa-user"></i>
                </span>
                <div class="col-span-2 mt-2">
                  <p class="text-sm"><strong>Full name</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.firstName }} {{ guardian.middleName }}
                    {{ guardian.lastName }}</span>
                </div>
                <!-- Personal Email -->
                <span class="col-span-1 mt-3 ml-4">
                  <i class="fa-solid fa-envelope"></i>
                </span>
                <div class="col-span-2 mt-2">
                  <p class="text-sm"><strong>Email</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.email || '-' }}</span>
                </div>
                <!-- Nationality -->
                <span class="col-span-1 mt-3 ml-4">
                  <i class="fa-solid fa-globe"></i>
                </span>
                <div class="col-span-2 mt-2">
                  <p class="text-sm"><strong>Nationality</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.nationality || '-' }}</span>
                </div>
                <!-- Username -->
                <span class="col-span-1 mt-3 ml-4">
                  <i class="fa-solid fa-address-card"></i>
                </span>
                <div class="col-span-2 mt-2">
                  <p class="text-sm"><strong>Username</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.username || '-' }}</span>
                </div>
              </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
              <p class="text-sm text-left mx-4">
                <strong>Documents</strong>
              </p>
              <hr class="mx-4 my-3">
              <div class="grid grid-cols-12">
                <div
                  class="grid grid-cols-6 col-span-6 text-left bg-gray-100 border border-gray-300 rounded-lg p-3 mx-4 mt-2"
                  v-for="document in guardian.documents" :key="document.id">
                  <span class="col-span-1 mt-2 ml-4">
                    <i class="fa-solid fa-file-pdf"></i>
                  </span>
                  <div class="col-span-4 mt-2">
                    <p class="text-sm">{{ document.name }}</p>
                  </div>
                  <button class="col-span-1 mt-2 mr-4" @click="downloadDocument(document)">
                    <i class="fa-solid fa-download"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Others -->
            <div v-if="false" class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
              <p class="text-sm text-left mx-4"><strong>Others</strong></p>
              <hr class="mx-4 my-3">
              <div class="grid grid-cols-3 text-left px-4">
                <div class="col-span-1 mt-2">
                  <p class="text-sm"><strong>Religious Affiliation</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.religiousAffiliation || '-' }}</span>
                </div>
                <div class="col-span-1 mt-2">
                  <p class="text-sm"><strong>Family Status</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.familyStatus || '-' }}</span>
                </div>
                <div class="col-span-1 mt-2">
                  <p class="text-sm"><strong>Time Zone</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.timeZone || '-' }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Demographics Info -->
          <div v-if="activeTab === 'Demographics'">
            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
              <p class="text-sm text-left mx-4"><strong>Demographic Info</strong></p>
              <hr class="mx-4 my-3">
              <div class="grid grid-cols-12 text-left">
                <!-- Citizenship -->
                <span class="col-span-1 mt-3 ml-4">
                  <i class="fa-solid fa-globe"></i>
                </span>
                <div class="col-span-3 mt-2">
                  <p class="text-sm"><strong>Citizenship</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.nationality || '-' }}</span>
                </div>
                <!-- Race -->
                <span class="col-span-1 mt-3 ml-4">
                  <i class="fa-solid fa-person"></i>
                </span>
                <div class="col-span-3 mt-2">
                  <p class="text-sm"><strong>Race</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.race || '-' }}</span>
                </div>
                <!-- Gender -->
                <span class="col-span-1 mt-3 ml-4">
                  <i class="fa-solid fa-venus-mars"></i>
                </span>
                <div class="col-span-3 mt-2">
                  <p class="text-sm"><strong>Gender</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.gender || '-' }}</span>
                </div>
                <!-- Language -->
                <span class="col-span-1 mt-3 ml-4">
                  <i class="fa-solid fa-language"></i>
                </span>
                <div class="col-span-3 mt-2">
                  <p class="text-sm"><strong>Language</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.nativeLanguage || '-' }}</span>
                </div>
              </div>
            </div>

            <!-- Previous Address -->
            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
              <p class="text-sm text-left mx-4">
                <strong>Previous Address</strong>
              </p>
              <hr class="mx-4 my-3">
              <div class="grid grid-cols-12 text-left m-2 bg-gray-100 border border-gray-300 rounded"
                v-for="address in guardian.previousAddresses" :key="`${address.street}-${address.zip}`">
                <div class="col-span-4 m-2">
                  <p class="text-sm"><strong>Street</strong></p>
                  <span class="text-gray-600 text-sm">{{ address.street || '-' }}</span>
                </div>
                <div class="col-span-4 m-2">
                  <p class="text-sm"><strong>City</strong></p>
                  <span class="text-gray-600 text-sm">{{ address.city || '-' }}</span>
                </div>
                <div class="col-span-4 m-2">
                  <p class="text-sm"><strong>State</strong></p>
                  <span class="text-gray-600 text-sm">{{ address.state || '-' }}</span>
                </div>
                <div class="col-span-4 m-2">
                  <p class="text-sm"><strong>ZIP</strong></p>
                  <span class="text-gray-600 text-sm">{{ address.zip || '-' }}</span>
                </div>
                <div class="col-span-4 m-2">
                  <p class="text-sm"><strong>Country</strong></p>
                  <span class="text-gray-600 text-sm">{{ address.country || '-' }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Children -->
          <div v-if="activeTab === 'Children'">
            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
              <p class="text-sm text-left mx-4">
                <strong>Children</strong>
              </p>
              <hr class="mx-4 my-3">
              <div class="grid grid-cols-12 text-left m-2 bg-gray-100 border border-gray-300 rounded"
                v-for="child in guardian.children" :key="child.uuid">
                <div class="col-span-3 m-2">
                  <img
                    :src="child.profileImage || 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTgZep-EXpmd-GkvIOhZ3BpgfPRoY1jANIWIA&s'"
                    alt="Child Image" class="w-24 h-24 rounded-full border mr-4" />
                </div>
                <div class="grid grid-cols-12 col-span-9">
                  <!-- Full name -->
                  <div class="col-span-4 m-2">
                    <p class="text-sm"><strong>Full name</strong></p>
                    <span class="text-gray-600 text-sm">{{ child.name }}</span>
                  </div>
                  <!-- Student ID -->
                  <div class="col-span-4 m-2">
                    <p class="text-sm"><strong>Student ID</strong></p>
                    <span class="text-gray-600 text-sm">{{ child.studentIdNumber || '-' }}</span>
                  </div>
                  <!-- Gender -->
                  <div class="col-span-4 m-2">
                    <p class="text-sm"><strong>Gender</strong></p>
                    <span class="text-gray-600 text-sm">{{ child.gender || '-' }}</span>
                  </div>
                  <!-- Grade Level -->
                  <div class="col-span-4 m-2">
                    <p class="text-sm"><strong>Grade Level</strong></p>
                    <span class="text-gray-600 text-sm">{{ child.gradeLevel || '-' }}</span>
                  </div>
                  <!-- Enrollment Date -->
                  <div class="col-span-4 m-2">
                    <p class="text-sm"><strong>Enrollment Date</strong></p>
                    <span class="text-gray-600 text-sm">{{ formatDate(child.enrollmentDate) || '-' }}</span>
                  </div>
                  <!-- Phone -->
                  <div class="col-span-4 m-2">
                    <p class="text-sm"><strong>Phone</strong></p>
                    <span class="text-gray-600 text-sm">{{ child.contactNumber || '-' }}</span>
                  </div>
                </div>
                <div class="grid grid-cols-3 col-span-12 px-4 border-t-2 border-gray-300">
                  <!-- Relation -->
                  <div class="col-span-1 m-2">
                    <p class="text-sm"><strong>Relation</strong></p>
                    <span class="text-gray-600 text-sm">{{ child.relation || '-' }}</span>
                  </div>
                  <!-- Is Legal Guardian -->
                  <div class="col-span-1 m-2">
                    <p class="text-sm"><strong>Is Legal Guardian</strong></p>
                    <span class="text-gray-600 text-sm">{{ child.isLegalGuardian ? 'Yes' : 'No' }}</span>
                  </div>
                  <!-- Authority Type -->
                  <div class="col-span-1 m-2">
                    <p class="text-sm"><strong>Authority Type</strong></p>
                    <span class="text-gray-600 text-sm">{{ child.authorityType || '-' }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Financial -->
          <div v-if="activeTab === 'Financial'">
<div class="bg-white rounded-lg border-r px-4 py-5 text-center mt-2" v-if="true">
    <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
        <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
        <p class="text-sm text-gray-600 font-medium">No Financial Status</p>
        <p class="text-xs text-gray-500 mt-1">Financial status information is not available at this time, coming soon!</p>
    </div>
</div>

          <!-- currently unavailable
            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
              <p class="text-sm text-left mx-4">
                <strong>Bank Details</strong>
              </p>
              <hr class="mx-4 my-3">
              <div class="grid grid-cols-12 text-left">
                <div class="col-span-4 mt-2 mx-6">
                  <p class="text-sm"><strong>Bank Name</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.bankName || '-' }}</span>
                </div>
                <div class="col-span-4 mt-2">
                  <p class="text-sm"><strong>Branch</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.branch || '-' }}</span>
                </div>
                <div class="col-span-4 mt-2">
                  <p class="text-sm"><strong>IFSC</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.ifsc || '-' }}</span>
                </div>
              </div>
            </div>

            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
              <p class="text-sm text-left mx-4">
                <strong>Insurance Providers</strong>
              </p>
              <hr class="mx-4 my-3">
              <div v-if="guardian.meta?.insuranceProviders?.length" class="grid grid-cols-12">
                <div v-for="(provider, index) in guardian.meta.insuranceProviders" :key="index"
                  class="grid grid-cols-6 col-span-6 text-left border border-gray-300 bg-gray-100 px-3 py-1 m-2 rounded-lg">
                  <div class="col-span-3 mt-2 mx-6">
                    <p class="text-sm"><strong>Provider Name</strong></p>
                    <span class="text-gray-600 text-sm">{{ provider.name || '-' }}</span>
                  </div>
                  <div class="col-span-3 mt-2">
                    <p class="text-sm"><strong>Policy Number</strong></p>
                    <span class="text-gray-600 text-sm">{{ provider.policyNumber || provider.policy_number || '-' }}</span>
                  </div>
                </div>
              </div>
              <div v-else class="text-sm text-gray-600 mx-4">No insurance providers available</div>
            </div>

            <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
              <p class="text-sm text-left mx-4"><strong>Others</strong></p>
              <hr class="mx-4 my-3">
              <div class="grid grid-cols-12 text-left">
                <div class="col-span-4 mt-2 mx-6">
                  <p class="text-sm"><strong>Payment Preference</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.paymentPreferences || '-' }}</span>
                </div>
                <div class="col-span-4 mt-2">
                  <p class="text-sm"><strong>Payment Plan Preference</strong></p>
                  <span class="text-gray-600 text-sm">{{ guardian.paymentPlanPreference || '-' }}</span>
                </div>
              </div>
            </div>
          -->
          </div>
        </div>
      </div>
    </div>
  </ParentTransition>
</template>

<script setup>
import { ref, reactive, inject, onMounted, computed, onBeforeUnmount } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { PencilSquareIcon } from "@heroicons/vue/20/solid"

const route = useRoute()
const router = useRouter()
const store = useStore()
const emitter = inject("emitter")
const $trans = inject("$trans")

const activeTab = ref("Parent/Guardian Details")
const tabs = ["Parent/Guardian Details", "Demographics", "Children", "Financial"]

// Get icon for each tab
function getTabIcon(tabName) {
  const iconMap = {
    'Parent/Guardian Details': 'fa-solid fa-user',
    'Demographics': 'fa-solid fa-users',
    'Children': 'fa-solid fa-child',
    'Financial': 'fa-solid fa-dollar-sign',
  }
  return iconMap[tabName] || 'fa-solid fa-circle'
}

function formatDate(dateString) {
  if (!dateString) return '-'
  const date = new Date(dateString)
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return date.toLocaleDateString('en-US', options)
}

const isLoading = ref(false)
const guardian = reactive({})

const getGuardian = async () => {
  isLoading.value = true
  await store
    .dispatch("guardian/myProfile")
    .then((response) => {
      console.log(response)
      Object.assign(guardian, response['guardian'])
      isLoading.value = false
    })
    .catch((e) => {
      isLoading.value = false
      router.push({ name: "ParentDashboard" })
    })
}

const profileImage = computed(() => {
  return guardian?.contact?.photoUrl ||
    guardian?.contact?.photo_url ||
    guardian?.contact?.photo ||
    guardian?.photo ||
    null
})

const hasActualPhoto = computed(() => {
  return !!(guardian?.contact?.photoUrl ||
    guardian?.contact?.photo_url ||
    guardian?.contact?.photo ||
    guardian?.photo)
})

const downloadDocument = async (document) => {
  try {
    const response = await axios.get(`/api/documents/${document.id}/download`, {
      responseType: 'blob',
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', document.name)
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)
  } catch (error) {
    console.error('Download failed:', error)
  }
}

onMounted(async () => {
  emitter.on("guardianUpdated", () => {
    getGuardian()
  })
  await getGuardian()
})

onBeforeUnmount(() => {
  emitter.all.delete("guardianUpdated")
})
</script>
