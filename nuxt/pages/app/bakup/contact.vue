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
          title: 'Business Partner Overview',
          route: '/app/contact/overview',
        },
        {
          title: 'Customer',
          route: '/app/contact/customer',
        },
      ],
      item: [
        {text: 'Customer', type: '', route: '/app/form/contact/customer'},
        {text: 'Vendor', type: '', route: '/app/form/contact/vendor'},
      ],
      show: true,
      showBtn: false
    })
  },
}
</script>
