<template>
  <div class="space-y-4">
    <div class="rounded-xl border bg-white p-6 shadow-sm">
      <h1 class="text-2xl font-semibold text-slate-900">Family Payments</h1>
      <p class="mt-2 max-w-3xl text-sm text-slate-600">
        Review all payable invoices for your family in one place. The amount shown above is combined for convenience,
        while each payment is still recorded against its original invoice behind the scenes.
      </p>
    </div>

    <InvoicesTab
      :allow-payments="true"
      :show-aggregate-summary="true"
    />
  </div>
</template>

<script setup>
import InvoicesTab from "@views/Pages/Finance/Accountant/Tab/InvoicesTab.vue"
</script>
