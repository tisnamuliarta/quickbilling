<template>
  <div>
    <DocumentTableDocument type-document="PP"></DocumentTableDocument>
  </div>
</template>

<script>
export default {
  name: 'PurchasePayment',
  layout: 'dashboard',

  head() {
    return {
      title: 'Outgoing Payment',
    }
  },
}
</script>
