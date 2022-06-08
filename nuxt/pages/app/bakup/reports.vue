<template>
  <div>
    <NuxtChild
      keep-alive
      :keep-alive-props="{ exclude: ['pages/sales/form/quote.vue'] }"
    />
  </div>
</template>

<script>
export default {
  activated() {
    this.$nuxt.$emit('extensionSetting', {
      tabs: [
        {
          title: 'Reports',
          route: '/app/reports/list',
        },
        {
          title: 'Performance Center',
          route: '/app/reports/performance',
        },
      ],
      item: [
        { text: 'Sales Quotations', type: 'SQ', route: '/app/form/sales/quote' },
        { text: 'Sales Order', type: 'SO', route: '/app/form/sales/order' },
        { text: 'Sales Delivery', type: 'SD', route: '/app/form/sales/delivery' },
        { text: 'A/R Invoice', type: 'SI', route: '/app/form/sales/invoice' },
        { text: 'Incoming Payment', type: 'SP', route: '/app/form/sales/payment' },
        { text: 'A/R Credit Memo', type: 'ARCM', route: '/app/form/sales/creditmemo' },
        { text: 'Sales Return', type: 'SR', route: '/app/form/sales/return' },
      ],
      show: true,
      showBtn: false
    })
  },
}
</script>
