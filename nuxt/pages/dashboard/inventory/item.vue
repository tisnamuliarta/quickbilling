<template>
  <div>
    <InventoryTableItem></InventoryTableItem>
  </div>
</template>

<script>
export default {
  name: "Item",
  layout: 'dashboard',

  head() {
    return {
      title: 'Items',
    }
  },
}
</script>
