<template>
  <div>
    <NuxtChild
      keep-alive
      :keep-alive-props="{ exclude: ['pages/item/form/fitem.vue'] }"
    />
  </div>
</template>

<script>
export default {
  activated() {
    this.$nuxt.$emit('extensionSetting', {
      tabs: [
        {
          title: 'Chart Of Account',
          route: '/app/accounting/account',
        },
        {
          title: 'Reconcile',
          route: '/app/accounting/reconcile',
        },
      ],
      item: [
        { text: 'Chart Of Account', type: 'SQ', route: '/app/form/accounting/account' },
        { text: 'Reconcile', type: 'SO', route: '/app/form/accounting/reconcile' },
      ],
      show: true,
      showBtn: false
    })
  },
}
</script>
