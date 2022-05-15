<template>
  <div>
    <DocumentTableDocument type-document="SP"></DocumentTableDocument>
  </div>
</template>

<script>
export default {
  name: 'SalesPayment',
  layout: 'dashboard',

  head() {
    return {
      title: 'Incoming Payment',
    }
  },
}
</script>
