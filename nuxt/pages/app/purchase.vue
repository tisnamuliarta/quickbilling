<template>
  <div>
    <NuxtChild
      keep-alive
      :keep-alive-props="{ exclude: ['pages/purchase/form/quote.vue'] }"
    />
  </div>
</template>

<script>
export default {
  activated() {
    this.$nuxt.$emit('extensionSetting', {
      tabs: [
        {
          title: 'Overview',
          route: '/app/purchase/overview',
        },
        {
          title: 'All purchase',
          route: '/app/purchase/list',
        },
        {
          title: 'Invoice',
          route: '/app/purchase/invoice',
        }
      ],
      item: [
        { text: 'Expense', type: 'EXPENSE', route: '/app/expense' },
        { text: 'Purchase Order', type: 'PO', route: '/app/purchase/form/order' },
        { text: 'A/P Invoice', type: 'PI', route: '/app/purchase/form/invoice' },
        { text: 'Outgoing Payment', type: 'PP', route: '/app/purchase/form/payment' },
        { text: 'A/P Credit Memo', type: 'APCM', route: '/app/purchase/form/creditmemo' },
        { text: 'Goods Return', type: 'GR', route: '/app/purchase/form/return' },
      ],
      show: true,
      showBtn: true
    })
  },
}
</script>
