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
          title: 'Purchase Overview',
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
        { text: 'Purchase Order', type: 'PO', route: '/app/form/purchase/order' },
        { text: 'A/P Invoice', type: 'PI', route: '/app/form/purchase/invoice' },
        { text: 'Outgoing Payment', type: 'PP', route: '/app/form/purchase/payment' },
        { text: 'A/P Credit Memo', type: 'APCM', route: '/app/form/purchase/creditmemo' },
        { text: 'Goods Return', type: 'GR', route: '/app/form/purchase/return' },
      ],
      show: true,
      showBtn: true
    })
  },
}
</script>
