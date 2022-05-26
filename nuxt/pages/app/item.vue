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
          title: 'Item Master Data',
          route: '/app/item/list',
        },
        {
          title: 'Price List',
          route: '/app/item/price-list',
        },
      ],
      item: [
        { text: 'Item Master Data', type: 'SQ', route: '/app/item/form/fitem' },
        { text: 'Price List', type: 'SO', route: '/app/item/form/fprice-list' },
      ],
      show: true,
      showBtn: true
    })
  },
}
</script>
