<template>
  <div>
    <NuxtChild
      keep-alive
      :keep-alive-props="{ exclude: ['pages/app/form/contact/customer.vue', 'pages/app/form/contact/vendor.vue'] }"
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
        {text: 'Customer', type: '', route: '/app/form/contact/customer'},
        {text: 'Vendor', type: '', route: '/app/form/contact/vendor'},
      ],
      show: true,
      showBtn: true
    })
  },
}
</script>
