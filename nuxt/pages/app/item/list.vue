<template>
  <div>
    <InventoryTableItem></InventoryTableItem>
  </div>
</template>

<script>
export default {
  name: 'ItemMaster',
  activated() {
    this.$nuxt.$emit('extensionSetting', {
      show: false,
      showBtn: false,
    })
  },
}
</script>
