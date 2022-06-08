<template>
  <div>
    <NuxtChild
      keep-alive
      :keep-alive-props="{ exclude: ['pages/form/item/item.vue'] }"
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
        { text: 'Item Master Data', type: '', route: '/app/form/item/item' },
        { text: 'Price List', type: '', route: '/app/form/item/price-list' },
      ],
      show: true,
      showBtn: false
    })
  },
}
</script>
