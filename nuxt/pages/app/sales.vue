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
          title: 'Overview',
          route: '/app/sales/overview',
        },
        {
          title: 'All Sales',
          route: '/app/sales/list',
        },
        {
          title: 'Invoice',
          route: '/app/sales/invoice',
        }
      ],
      item: [
        { text: 'Sales Quotations', type: 'SQ', route: '/app/sales/form/quote' },
        { text: 'Sales Order', type: 'SO', route: '/app/sales/form/order' },
        { text: 'Sales Delivery', type: 'SD', route: '/app/sales/form/delivery' },
        { text: 'A/R Invoice', type: 'SI', route: '/app/sales/form/invoice' },
        { text: 'Incoming Payment', type: 'SP', route: '/app/sales/form/payment' },
        { text: 'A/R Credit Memo', type: 'ARCM', route: '/app/sales/form/creditmemo' },
        { text: 'Sales Return', type: 'SR', route: '/app/sales/form/return' },
      ],
      show: true,
      showBtn: true
    })
  },
}
</script>
