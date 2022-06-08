<template>
  <div>
    <InventoryTableContact></InventoryTableContact>
  </div>
</template>

<script>
export default {
  name: "customer-detail",

  activated() {
    this.$nuxt.$emit('extensionSetting', {
      show: false,
      showBtn: false,
    })
  },
}
</script>
