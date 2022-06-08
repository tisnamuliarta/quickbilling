<template>
  <div>
    <InventoryTableContact contact-type="Vendor"></InventoryTableContact>
  </div>
</template>

<script>
export default {
  name: "vendor",

  activated() {
    this.$nuxt.$emit('extensionSetting', {
      show: false,
      showBtn: false,
    })
  },
}
</script>
