<template>
  <div>
    <DocumentTableDocument type-document="PO"></DocumentTableDocument>
  </div>
</template>

<script>
export default {
  name: 'PurchaseOrder',
  layout: 'dashboard',

  head() {
    return {
      title: 'Purchase Order',
    }
  },
}
</script>
