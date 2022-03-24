<template>
  <div>
    <DocumentTableDocument type-document="PI"></DocumentTableDocument>
  </div>
</template>

<script>
export default {
  name: 'PurchaseInvoice',
  layout: 'dashboard',

  head() {
    return {
      title: 'A/P Invoice',
    }
  },
}
</script>
