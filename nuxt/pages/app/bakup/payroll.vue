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
          title: 'Payroll Overview',
          route: '/app/payroll/overview',
        },
        {
          title: 'Employee',
          route: '/app/payroll/employee',
        },
        {
          title: 'Contractor',
          route: '/app/payroll/contractor',
        }
      ],
      item: [
        { text: 'Employee', type: '', route: '/app/form/payroll/employee' },
        { text: 'Contractor', type: '', route: '/app/form/payroll/contractor' },
      ],
      show: true,
      showBtn: true
    })
  },
}
</script>
