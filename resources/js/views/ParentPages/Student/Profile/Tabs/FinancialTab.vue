<template>
  <div class="space-y-4 mt-2">
    <div class="rounded-lg border bg-white px-4 py-5">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Financial Status</h2>
          <p class="text-sm text-gray-500">
            Live invoice summary and payment activity for this child.
          </p>
        </div>
        <BaseButton design="primary" :disabled="loading || !studentUuid" @click="loadOverview">
          <i class="fa-solid fa-arrows-rotate mr-2"></i>
          Refresh
        </BaseButton>
      </div>

      <div
        v-if="loadError"
        class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
      >
        {{ loadError }}
      </div>

      <div
        v-else-if="loading"
        class="mt-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-6 text-sm text-gray-500"
      >
        Loading financial data...
      </div>

      <template v-else>
        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-4">
          <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Total Invoiced</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ summaryCard.totalInvoiced }}</p>
            <p class="mt-2 text-xs text-gray-400">Currency: {{ currency }}</p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Total Paid</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ summaryCard.totalPaid }}</p>
            <p class="mt-2 text-xs text-gray-400">Currency: {{ currency }}</p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Outstanding</p>
            <p
              class="mt-1 text-2xl font-semibold"
              :class="summaryCard.statusVariant === 'success' ? 'text-emerald-600' : 'text-rose-600'"
            >
              {{ summaryCard.outstanding }}
            </p>
            <p class="mt-2 text-xs text-gray-400">Currency: {{ currency }}</p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Status</p>
            <div class="mt-1 inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm" :class="statusClasses">
              <span class="h-2 w-2 rounded-full bg-current opacity-70" />
              <span class="font-medium">{{ summaryCard.statusLabel }}</span>
            </div>
            <p v-if="summaryCard.lastPayment" class="mt-2 text-xs text-gray-500">
              Last payment: {{ summaryCard.lastPayment }}
            </p>
          </div>
        </div>

        <div
          v-if="currentAssignment"
          class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4"
        >
          <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500">Current Fee Type</p>
              <h3 class="text-lg font-semibold text-gray-900">
                {{ currentAssignment.feeStructure?.name || "-" }}
              </h3>
            </div>
            <span
              class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
              :class="
                currentAssignment.isActive
                  ? 'bg-emerald-50 text-emerald-700'
                  : 'bg-gray-100 text-gray-500'
              "
            >
              {{ currentAssignment.isActive ? "Active" : "Inactive" }}
            </span>
          </div>
          <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500">Category</p>
              <p class="text-sm font-semibold text-gray-900">
                {{ currentAssignment.feeStructure?.category || "-" }}
              </p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500">Installments</p>
              <p class="text-sm font-semibold text-gray-900">
                {{ currentAssignment.feeStructure?.installments ?? "-" }}
              </p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500">Period</p>
              <p class="text-sm font-semibold text-gray-900">
                {{ displayDate(currentAssignment.feeStructure?.startDate) }}
                <span class="text-gray-400">to</span>
                {{ displayDate(currentAssignment.feeStructure?.endDate) }}
              </p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500">Total Amount</p>
              <p class="text-sm font-semibold text-gray-900">
                {{ displayMoney(currentAssignment.feeStructure?.totalAmount) }}
              </p>
            </div>
          </div>
        </div>
      </template>
    </div>

    <InvoicesTab
      :fixed-student-id="studentUuid || ''"
      :hide-student-filter="true"
      :allow-payments="true"
    />
  </div>
</template>

<script setup>
import { computed, reactive, ref, watch } from "vue"
import { useStore } from "vuex"
import { useToast } from "vue-toastification"
import BaseButton from "@core/components/Forms/BaseButton.vue"
import InvoicesTab from "@views/Pages/Finance/Accountant/Tab/InvoicesTab.vue"

const props = defineProps({
  student: {
    type: Object,
    default: () => ({}),
  },
})

const store = useStore()
const toast = useToast()

const loading = ref(false)
const loadError = ref("")
const overview = reactive({
  summary: null,
  currentAssignment: null,
})

const studentUuid = computed(() => props.student?.uuid || null)
const currency = computed(() => overview.summary?.currency ?? "USD")

function formatCurrency(value, curr = currency.value) {
  try {
    return new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: curr,
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(Number(value) || 0)
  } catch {
    return `$${Number(value || 0).toFixed(2)}`
  }
}

function displayMoney(money) {
  if (!money && money !== 0) {
    return "-"
  }

  if (typeof money === "number") {
    return formatCurrency(money)
  }

  if (typeof money === "object") {
    if (Object.hasOwn(money, "formatted")) {
      return money.formatted
    }
    if (Object.hasOwn(money, "value")) {
      return formatCurrency(money.value)
    }
  }

  return formatCurrency(money)
}

function displayDate(date) {
  if (!date) {
    return "-"
  }

  if (typeof date === "string") {
    return date
  }

  if (typeof date === "object") {
    if (Object.hasOwn(date, "formatted")) {
      return date.formatted || "-"
    }
    if (Object.hasOwn(date, "value")) {
      return date.value
    }
  }

  return "-"
}

function badgeClass(variant) {
  switch (variant) {
    case "success":
      return "bg-emerald-50 text-emerald-700"
    case "warning":
      return "bg-amber-50 text-amber-700"
    case "danger":
      return "bg-rose-50 text-rose-700"
    default:
      return "bg-gray-100 text-gray-600"
  }
}

const summaryCard = computed(() => ({
  totalInvoiced: displayMoney(overview.summary?.totalInvoiced),
  totalPaid: displayMoney(overview.summary?.totalPaid),
  outstanding: displayMoney(overview.summary?.outstanding),
  statusLabel: overview.summary?.status?.label ?? "No data",
  statusVariant: overview.summary?.status?.variant ?? "muted",
  lastPayment: displayDate(overview.summary?.lastPaymentAt),
}))

const statusClasses = computed(() => badgeClass(summaryCard.value.statusVariant))
const currentAssignment = computed(() => overview.currentAssignment ?? null)

function applyOverview(data = {}) {
  overview.summary = data.summary ?? null
  overview.currentAssignment = data.currentAssignment ?? null
}

function resetOverview() {
  applyOverview()
}

async function loadOverview() {
  if (!studentUuid.value) {
    resetOverview()
    return
  }

  loading.value = true
  loadError.value = ""

  try {
    const response = await store.dispatch("student/financial/fetchOverview", {
      uuid: studentUuid.value,
    })
    const data = response?.data || response || {}
    applyOverview(data)
  } catch (error) {
    const message = error?.message || "Unable to load financial overview."
    loadError.value = message
    toast.error(message)
    resetOverview()
  } finally {
    loading.value = false
  }
}

watch(
  () => studentUuid.value,
  (uuid) => {
    if (uuid) {
      loadOverview()
    } else {
      resetOverview()
    }
  },
  { immediate: true }
)
</script>
