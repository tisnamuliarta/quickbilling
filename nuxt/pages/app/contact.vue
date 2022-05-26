<template>
  <div>
    <NuxtChild
      keep-alive
      :keep-alive-props="{ exclude: ['pages/app/contact/form-customer.vue', 'pages/app/contact/form-vendor.vue'] }"
    />
  </div>
</template>

<script>
export default {
  name: 'Contacts',

  head() {
    return {
      title: 'Contacts',
    }
  },

  activated() {
    this.$nuxt.$emit('extensionSetting', {
      tabs: [
        {
          title: 'Customer',
          route: '/app/contact/customer',
        },
        {
          title: 'Vendor',
          route: '/app/contact/vendor',
        },
      ],
      item: [
        {text: 'Customer', type: '', route: '/app/contact/form-customer'},
        {text: 'Vendor', type: '', route: '/app/contact/form-vendor'},
      ],
      show: true,
      showBtn: true
    })
  },
}
</script>
